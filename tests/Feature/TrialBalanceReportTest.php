<?php

namespace Tests\Feature;

use App\Http\Controllers\Jasper\Reports\trial_balance_two_column_FFReportController as TrialBalance;
use App\Models\Account;
use App\Models\AccountTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Trial Balance (Jasper report, docs/HANDOVER.md §11.12). On PostgreSQL it always failed with "Error executing SQL
 * statement": JasperStarter binds the String date parameters as varchar and the query compared them with a date column.
 * Even with dates that worked, an account showed only if it had an entry before the start date. And PHPJasper puts
 * every report parameter into a shell command unescaped, while the controller passed every query-string key and value.
 */
class TrialBalanceReportTest extends TestCase
{
    use RefreshDatabase;

    private const JRXML = 'app/report/source/MyReports/src/trial_balance_two_column_FF.jrxml';

    public function test_report_query_runs_on_postgresql_and_shows_accounts_with_no_earlier_entries(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();
        $this->actingAs($admin);
        $company = $admin->active_company_id;
        $expense = Account::where('company_id', $company)->where('name', 'Maintenance Expense')->firstOrFail();
        $payable = Account::where('company_id', $company)->where('name', 'Accounts Payable')->firstOrFail();

        // Nothing before 2030, so both accounts have no opening balance for the range below.
        AccountTransaction::createTransaction('2030-05-10', $company, $expense->id, $payable->id, 1, 400, 1, 999, null, null, 51);

        $rows = collect($this->runReportQuery([
            'client_id' => $admin->client_id, 'company_id' => $company, 'start_date' => '2030-01-01', 'end_date' => '2030-12-31',
        ]))->keyBy('name');

        $this->assertEquals(400, $rows['Maintenance Expense']->balancedr_cur);
        $this->assertEquals(400, $rows['Maintenance Expense']->balancedr_close);
        $this->assertEquals(400, $rows['Accounts Payable']->balancecr_cur);
        $this->assertEquals(400, $rows['Accounts Payable']->balancecr_close);
    }

    public function test_only_validated_values_reach_the_report_command(): void
    {
        $admin = User::where('email', 'admin@idl.pk')->first();
        $this->actingAs($admin);
        $this->travelTo('2030-06-15 10:00:00');

        $expected = fn (string $start, string $end) => [
            'client_id' => (int) $admin->client_id, 'company_id' => (int) $admin->active_company_id,
            'start_date' => $start, 'end_date' => $end,
        ];

        $this->assertSame($expected('2030-02-01', '2030-02-28'), TrialBalance::params('2030-02-01 - 2030-02-28'));

        // No filter, an impossible date, a reversed range or anything extra: this year.
        foreach ([null, '', '2030-02-30 - 2030-03-01', '2030-12-31 - 2030-01-01', '2030-01-01 - 2030-12-31" & whoami & "'] as $input) {
            $this->assertSame($expected('2030-01-01', '2030-12-31'), TrialBalance::params($input), var_export($input, true));
        }
    }

    /**
     * Runs the report's own SQL from the .jrxml the way JasperStarter does: Integer parameters as integers, String
     * parameters as varchar (PDO would send them untyped and PostgreSQL would infer date, hiding the bug).
     */
    private function runReportQuery(array $params): array
    {
        $xml = file_get_contents(storage_path(self::JRXML));
        $this->assertSame(1, preg_match('/<queryString>\s*<!\[CDATA\[(.*?)\]\]>/s', $xml, $m));

        $bindings = [];
        $sql = preg_replace_callback('/\$P\{(\w+)\}/', function ($p) use ($params, &$bindings) {
            $bindings[] = $params[$p[1]];
            return is_int($params[$p[1]]) ? 'CAST(? AS integer)' : 'CAST(? AS varchar)';
        }, $m[1]);

        return DB::select($sql, $bindings);
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AccountingAudit extends Command
{
    protected $signature = 'accounting:audit {--company=}';

    protected $description = 'Read-only accounting integrity audit.';

    public function handle(): int
    {
        $companyId = $this->option('company');
        $issues = 0;

        $totals = DB::table('account_transactions')
            ->selectRaw('company_id, SUM(debit) AS dr, SUM(credit) AS cr')
            ->when($companyId, function ($q) use ($companyId) {
                return $q->where('company_id', $companyId);
            })
            ->groupBy('company_id')
            ->get();

        foreach ($totals as $row) {
            $dr = (float) $row->dr;
            $cr = (float) $row->cr;
            $balanced = abs($dr - $cr) < 0.01;

            if (!$balanced) {
                $issues++;
            }

            $this->line("Company {$row->company_id}: Dr {$dr} / Cr {$cr} " . ($balanced ? 'BALANCED' : 'UNBALANCED'));
        }

        if ($issues === 0) {
            $this->info('Grand totals balanced.');
        }

        return $issues === 0 ? self::SUCCESS : self::FAILURE;
    }
}

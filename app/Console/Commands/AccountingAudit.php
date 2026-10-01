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

        // 2. Per-document balance: every source document's postings must balance.
        $unbalancedDocs = DB::table('account_transactions')
            ->selectRaw('company_id, table_id, record_id, SUM(debit) AS dr, SUM(credit) AS cr, COUNT(*) AS rows_count')
            ->whereNotNull('table_id')
            ->whereNotNull('record_id')
            ->when($companyId, function ($q) use ($companyId) {
                return $q->where('company_id', $companyId);
            })
            ->groupBy('company_id', 'table_id', 'record_id')
            ->get()
            ->filter(function ($r) {
                return abs((float) $r->dr - (float) $r->cr) >= 0.01;
            });

        $this->line('');
        $this->info('2. Per-document balance:');
        if ($unbalancedDocs->isEmpty()) {
            $this->line('   OK - every source document balances.');
        } else {
            $issues += $unbalancedDocs->count();
            foreach ($unbalancedDocs->take(25) as $r) {
                $this->warn("   company {$r->company_id} table#{$r->table_id} record {$r->record_id}: Dr {$r->dr} / Cr {$r->cr} ({$r->rows_count} rows)");
            }
            if ($unbalancedDocs->count() > 25) {
                $this->line('   ... and ' . ($unbalancedDocs->count() - 25) . ' more.');
            }
        }

        // 3. Traceability: NULL record_id and source rows that no longer exist.
        $knownTables = [
            15 => 'gl_journals',
            21 => 'orders',
            24 => 'payment_headers',
            51 => 'invoices',
            65 => 'material_inouts',
        ];

        $this->line('');
        $this->info('3. Traceability:');
        foreach ($knownTables as $tableId => $tableName) {
            $nullRecord = DB::table('account_transactions')
                ->where('table_id', $tableId)
                ->whereNull('record_id')
                ->when($companyId, function ($q) use ($companyId) {
                    return $q->where('company_id', $companyId);
                })
                ->count();

            $orphaned = DB::table('account_transactions as at')
                ->leftJoin("{$tableName} as src", 'at.record_id', '=', 'src.id')
                ->where('at.table_id', $tableId)
                ->whereNotNull('at.record_id')
                ->when($companyId, function ($q) use ($companyId) {
                    return $q->where('at.company_id', $companyId);
                })
                ->whereNull('src.id')
                ->count();

            $this->line("   table_id {$tableId} ({$tableName}): {$nullRecord} postings with NULL record_id, {$orphaned} postings whose source record is gone");
        }

        // 4. Double postings: more rows than a single Dr/Cr pair for one document.
        //    GL journals (table_id 15) are excluded - their row count is line count.
        $doubles = DB::table('account_transactions')
            ->selectRaw('company_id, table_id, record_id, COUNT(*) AS rows_count')
            ->whereNotNull('table_id')
            ->whereNotNull('record_id')
            ->where('table_id', '!=', 15)
            ->when($companyId, function ($q) use ($companyId) {
                return $q->where('company_id', $companyId);
            })
            ->groupBy('company_id', 'table_id', 'record_id')
            ->get()
            ->filter(function ($r) {
                return $r->rows_count > 2;
            });

        $this->line('');
        $this->info('4. Double-posted documents:');
        if ($doubles->isEmpty()) {
            $this->line('   None detected.');
        } else {
            $issues += $doubles->count();
            foreach ($doubles->take(25) as $r) {
                $this->warn("   company {$r->company_id} table#{$r->table_id} record {$r->record_id}: {$r->rows_count} rows");
            }
            if ($doubles->count() > 25) {
                $this->line('   ... and ' . ($doubles->count() - 25) . ' more.');
            }
        }

        if ($issues === 0) {
            $this->info('Grand totals balanced.');
        }

        return $issues === 0 ? self::SUCCESS : self::FAILURE;
    }
}

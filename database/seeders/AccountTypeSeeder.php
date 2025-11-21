<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountType;

class AccountTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $AccountTypes = [
            [
                'name' => 'Asset',
                'code' => 'AS',
                'child' => [
                    [
                        'name' => 'Current Assets',
                        'code' => 'CA',
                    ],
                    [
                        'name' => 'Fixed Assets',
                        'code' => 'FA',
                    ],
                    [
                        'name' => 'Long Term Assets',
                        'code' => 'LTA',
                    ],
                ]
            ],
            [
                'name' => 'Liability',
                'code' => 'LI',
                'child' => [
                    [
                        'name' => 'Current Liability',
                        'code' => 'CL',
                    ],
                    [
                        'name' => 'Long Term Liability',
                        'code' => 'LTL',
                    ],
                ]
            ],
            [
                'name' => 'Equity',
                'code' => 'EQ',
                'child' => [
                    [
                        'name' => 'Equity',
                        'code' => 'SEQ',
                    ]
                ]
            ],
            [
                'name' => 'Revenue',
                'code' => 'RE',
                'child' => [
                    [
                        'name' => 'Income',
                        'code' => 'IN',
                    ],
                    [
                        'name' => 'Long Term Liability',
                        'code' => 'OIN',
                    ],
                    [
                        'name' => 'Revenue',
                        'code' => 'REV',
                    ],
                    [
                        'name' => 'Other Revenue',
                        'code' => 'OREV',
                    ],
                ]
            ],
            [
                'name' => 'Expense',
                'code' => 'EXP',
                'child' => [
                    [
                        'name' => 'Admin Expense',
                        'code' => 'AE',
                    ],
                    [
                        'name' => 'Sales Expense',
                        'code' => 'SE',
                    ],
                    [
                        'name' => 'Cost',
                        'code' => 'CO',
                    ],
                ]
            ],
        ];
        foreach($AccountTypes as $AccountType){
            $type = AccountType::firstOrCreate([
                'name' => $AccountType['name'],
                'code' => $AccountType['code'],
                'is_active' => 1,
                'is_system' => true,
                // 'created_by' => 1,
            ]);
            foreach ($AccountType['child'] as $key => $AccountSubType) {
                AccountType::firstOrCreate([
                    'name' => $AccountSubType['name'],
                    'code' => $AccountSubType['code'],
                    'parent_id' => $type->id,
                    'is_active' => 1,
                    'is_system' => true,

                    // 'created_by' => 1,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;
use App\Models\InvoiceDocumentType;
use Illuminate\Database\Seeder;

class InvoiceDocumentTypeSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $documentTypes = [
            ['id' => 1, 'name' => 'maintenance', 'code' => 'MNT-', 'description' => 'maintenance desc'],
            ['id' => 2, 'name' => 'toll_tax', 'code' => 'TOLL-', 'description' => 'toll tax desc'],
            ['id' => 3, 'name' => 'fuel', 'code' => 'FUEL-', 'description' => 'fuel desc'],
            ['id' => 4, 'name' => 'entertainment', 'code' => 'ENT-', 'description' => 'entertainment desc'],
            ['id' => 5, 'name' => 'inspection', 'code' => 'Insp-', 'description' => 'inspection desc'],
            ['id' => 6, 'name' => 'purchase_order', 'code' => 'PO-', 'description' => 'purchase order desc'],
            ['id' => 7, 'name' => 'material_inout', 'code' => 'MI-', 'description' => 'material inout desc'],
            ['id' => 8, 'name' => 'inventory_move', 'code' => 'IM-', 'description' => 'Inventory move desc'],
            ['id' => 9, 'name' => 'inventory_consumption', 'code' => 'IC-', 'description' => 'Inventory consumption desc'],
            ['id' => 10, 'name' => 'purchase_invoice', 'code' => 'PI-', 'description' => 'Purchase Invoice desc'],
            ['id' => 11, 'name' => 'physical_inventory', 'code' => 'PINV-', 'description' => 'physical Inventory desc'],
            ['id' => 12, 'name' => 'm_match_po', 'code' => 'MPO-', 'description' => 'Match PO desc'],
        ];

        foreach ($documentTypes as $type) {
            InvoiceDocumentType::updateOrCreate(
                ['id' => $type['id']], // Search by id
                [
                    'name' => $type['name'],
                    'code' => $type['code'],
                    'description' => $type['description'],
                ]
            );
        }

    }
}

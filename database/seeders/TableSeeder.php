<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Table;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $tables = Schema::getAllTables();
        // $db = env('DB_DATABASE');
        // Table::truncate();
        $tables = self::get_tables();
        foreach ($tables as $table) {
            // $table = $table->{'Tables_in_'.$db};
            // Table::firstOrCreate([
            //     'name' => $table,
            //     'model_name' => self::convertTableNameToModelName($table),
            // ]);

            $id = $table['id'];
            unset($table['id']);
            $body = $table;
            $rc = Table::where('id', $id)->first();
            if($rc){
                Table::where('id', $id)->update($body);
            }else{
                Table::firstOrCreate(['id' => $id], $body);
            }
        }
    }
    static function get_tables(){
        // do not change id and new table at the end row dont disturb the sequence
        return [
            ['id' => '1','name' => 'account_transactions','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'AccountTransaction'],
            ['id' => '2','name' => 'account_types','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'AccountType'],
            ['id' => '3','name' => 'accounts','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Account'],
            ['id' => '4','name' => 'actors','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Actor'],
            ['id' => '5','name' => 'blogs','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Blog'],
            ['id' => '6','name' => 'vehicle_companies','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'VehicleCompany'],
            ['id' => '7','name' => 'cities','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'City'],
            ['id' => '8','name' => 'companies','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Company'],
            ['id' => '9','name' => 'country_codes','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'CountryCode'],
            ['id' => '10','name' => 'currencies','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Currency'],
            ['id' => '11','name' => 'customers','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Customer'],
            ['id' => '12','name' => 'events','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Event'],
            // ['id' => '13','name' => 'failed_jobs','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'FailedJobs'],
            ['id' => '14','name' => 'gl_journal_lines','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'GlJournalLine'],
            ['id' => '15','name' => 'gl_journals','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'GlJournal'],
            ['id' => '16','name' => 'locations','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Location'],
            // ['id' => '17','name' => 'migrations','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Migration'],
            ['id' => '18','name' => 'role_permission_type_functions','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RolePermissionTypeFunction'],
            ['id' => '19','name' => 'notifications','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Notification'],
            ['id' => '20','name' => 'order_lines','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'OrderDetail'],
            ['id' => '21','name' => 'orders','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Order'],
            ['id' => '22','name' => 'partners','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Partner'],
            // ['id' => '23','name' => 'password_resets','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'PasswordReset'],
            ['id' => '24','name' => 'payment_headers','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'PaymentHeader'],
            ['id' => '25','name' => 'payment_lines','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'PaymentLine'],
            ['id' => '26','name' => 'personal_access_tokens','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'PersonalAccessToken'],
            ['id' => '27','name' => 'rate_lists','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RateList'],
            ['id' => '28','name' => 'role_module_actors','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RoleModuleActors'],
            ['id' => '29','name' => 'role_permission_types','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RolePermissionType'],
            ['id' => '30','name' => 'role_modules','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RoleModule'],
            ['id' => '31','name' => 'role_permissions','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RolePermission'],
            ['id' => '32','name' => 'roles','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Role'],
            ['id' => '33','name' => 'route_rates','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'RouteRate'],
            ['id' => '34','name' => 'routes','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Routes'],
            ['id' => '35','name' => 'sidebar_groups','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'SidebarGroups'],
            ['id' => '36','name' => 'sidebar_items','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'SidebarItems'],
            ['id' => '37','name' => 'tables','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Table'],
            ['id' => '38','name' => 'user_companies','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'UserCompany'],
            ['id' => '39','name' => 'user_social_profiles','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'UserSocialProfile'],
            ['id' => '40','name' => 'users','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'User'],
            ['id' => '41','name' => 'vehicle_classes','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'VehicleClass'],
            ['id' => '42','name' => 'vehicle_models','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'VehicleModel'],
            ['id' => '43','name' => 'vehicles','company_id' => NULL,'description' => NULL,'created_at' => '2024-10-24 18:06:37','updated_at' => '2024-10-24 18:06:37','model_name' => 'Vehicle'],
            ['id' => '44', 'name' => 'products', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' => 'Product'],
            ['id' => '45', 'name' => 'load_types', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' => 'LoadType'],
            ['id' => '46', 'name' => 'unit_measures', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' => 'UnitMeasure'],
            ['id' => '47', 'name' => 'broadcast_messages', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' => 'BroadcastMessage'],
            ['id' => '48', 'name' => 'time_logs', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'TimeLog'],
            ['id' => '49', 'name' => 'activities', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Activity'],
            ['id' => '50', 'name' => 'activity_lines', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ActivityLine'],
            ['id' => '51', 'name' => 'invoices', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Invoice'],
            ['id' => '52', 'name' => 'invoice_lines', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InvoiceLine'],
            ['id' => '53', 'name' => 'invoice_document_types', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InvoiceDocumentType'],
            ['id' => '54', 'name' => 'order_detail_route_histories', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'OrderDetailRouteHistory'],
            ['id' => '55', 'name' => 'clients', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Client'],
            ['id' => '56', 'name' => 'invoice_line_products', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InvoiceLineProduct'],
            ['id' => '57', 'name' => 'role_has_modules', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'RoleHasModule'],
            ['id' => '58', 'name' => 'tables', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Table'],
            ['id' => '59', 'name' => 'manufacturing_companies', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ManufacturingCompany'],
            ['id' => '60', 'name' => 'brands', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Brand'],
            ['id' => '61', 'name' => 'ware_houses', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'WareHouse'],
            ['id' => '62', 'name' => 'locators', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Locator'],
            ['id' => '63', 'name' => 'product_categories', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductCategory'],
            ['id' => '64', 'name' => 'product_sub_categories', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductSubCategory'],
            ['id' => '65', 'name' => 'material_inouts', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'MaterialInout'],
            ['id' => '66', 'name' => 'material_inout_lines', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'MaterialInoutLine'],
            ['id' => '67', 'name' => 'product_groups_1', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductGroup1'],
            ['id' => '68', 'name' => 'product_groups_2', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductGroup2'],
            ['id' => '69', 'name' => 'product_groups_3', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductGroup3'],
            ['id' => '70', 'name' => 'product_types', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductType'],
            ['id' => '71', 'name' => 'partner_locations', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'PartnerLocation'],
            ['id' => '72', 'name' => 'price_lists', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'PriceList'],
            ['id' => '73', 'name' => 'price_list_versions', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'PriceListVersion'],
            ['id' => '74', 'name' => 'product_prices', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductPrice'],
            ['id' => '75', 'name' => 'inventory_moves', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InventoryMove'],
            ['id' => '76', 'name' => 'movement_lines', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'MovementLine'],
            ['id' => '77', 'name' => 'inventory_consumptions', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InventoryConsumption'],
            ['id' => '78', 'name' => 'internal_uselines', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'InternalUseline'],
            ['id' => '79', 'name' => 'stock_storages', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'StockStorage'],
            ['id' => '80', 'name' => 'physical_inventories', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'PhysicalInventory'],
            ['id' => '81', 'name' => 'product-costings', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'ProductCosting'],
            ['id' => '82', 'name' => 'taxes', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Tax'],
            ['id' => '82', 'name' => 'm_match_po', 'company_id' => NULL, 'description' => NULL, 'created_at' => '2024-10-24 18:06:37', 'updated_at' => '2024-10-24 18:06:37', 'model_name' =>'Tax'],
           

        ];
    }

    static function convertTableNameToModelName($tableName)
    {
        // Split the table name by underscores
        $words = explode('_', $tableName);

        // Capitalize the first letter of each word
        $words = array_map('ucfirst', $words);

        // Join the words back together
        return implode('', $words);
    }
}

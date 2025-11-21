<?php

namespace Database\Seeders;

use App\Models\VehicleCompany;
use App\Models\LoadType;
use App\Models\Location;
use App\Models\Partner;
use App\Models\PartnerLocation;
use App\Models\Product;
use App\Models\RateList;
use App\Models\Route;
use App\Models\UnitMeasure;
use App\Models\User;
use App\Models\UserCompany;
use App\Models\Vehicle;
use App\Models\VehicleClass;
use App\Models\VehicleModel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RandomDataSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // creating locations:
        $locations = [
            ['id' => 1, 'name' => 'makkah'],
            ['id' => 2, 'name' => 'madinah'],
        ];

        foreach ($locations as $location) {
            Location::firstOrCreate(
                ['id' => $location['id']], // Search by id
                [
                    'name' => $location['name'],
                    'company_id' => 1,

                ]
            );
        }
        // -------
        // creating routes
        $routes = [
            ['id' => 1, 'name' => 'makkah route', 'from_loc' => 2, 'to_loc' => 1, 'distance_unit' => 'km', 'distance' => '150'],
            ['id' => 2, 'name' => 'madinah route', 'from_loc' => 1, 'to_loc' => 2, 'distance_unit' => 'km', 'distance' => '200'],
        ];

        foreach ($routes as $route) {
            Route::firstOrCreate(
                ['id' => $route['id']], // Search by id
                [
                    'name' => $route['name'],
                    'from_loc' => $route['from_loc'],
                    'to_loc' => $route['to_loc'],
                    'distance' => $route['distance'],
                    'distance_unit' => $route['distance_unit'],
                    'company_id' => 1,

                ]
            );
        }
        // ----------
        // creating agent:
        $agents = [
            ['id' => 3, 'name' => 'testingagent', 'address1' => 'gulberg H4 block lahore', 'company_name' => 'idlogix', 'email' => 'testingagent@gmail.com', 'phone_no' => '345767612', 'whatsapp_no' => '345767612', 'prefix_whatsapp' => '92', 'prefix_phone' => '92', 'cnic' => '3517753131'],
        ];

        foreach ($agents as $agent) {
            $partners = Partner::firstOrCreate(
                ['id' => $agent['id']], // Search by id
                [
                    'name' => $agent['name'],
                    // 'partner_type' => $partner_data['partner_type'],
                    'company_name' => $agent['company_name'],
                    'email' => $agent['email'],
                    'phone_no' => $agent['phone_no'],
                    'whatsapp_no' => $agent['whatsapp_no'],
                    'prefix_whatsapp' => $agent['prefix_whatsapp'],
                    'prefix_phone' => $agent['prefix_phone'] ?? null,
                    'cnic' => $agent['cnic'],
                    'address1' => $agent['address1'],

                    'created_by' => 2,

                    'actor_id' => 4,
                    'source' => 'manual',
                    'company_id' => 1,
                ]

            );
            $user = User::firstOrCreate(
                ['email' => $agent['email']], // Search by email
                [
                    'name' => $agent['name'],
                    'image' => 'profile_images/default/default.jpeg' ?? null,
                    'partner_id' => $partners->id,
                    'actor_id' => 4,
                    'client_id' => 1,
                    'role_id' => 3,
                    'flag' => 1,
                    'password' => Hash::make('00000000'),
                    'active_company_id' => 1,
                ]
            );
            $partner_location = PartnerLocation::firstOrCreate(
                ['partner_id' => $partners->id],
                [
                    
                    'address1' => $agent['address1'] ?? null,
                    'partner_id' => $partners->id,
                    'phone_no' => $agent['phone_no'],
                    'whatsapp_no' => $agent['whatsapp_no'],
                    'prefix_whatsapp' => $agent['prefix_whatsapp'],
                    'prefix_phone' => $agent['prefix_phone'] ?? null,
                    'client_id' => 1,
                    'is_default'=>1,
                    'company_id' => 1,
                    'created_by'=>1,

                ]
            );
            $partners->update(['partner_loc_id' => $partner_location->id]);


            UserCompany::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'company_id' => 1,
                ]
            );
        }
        // creating driver:
        $drivers = [
            ['id' => 4, 'name' => 'testingdriver', 'address1' => 'johar town j6 block', 'email' => 'testingdriver@gmail.com', 'phone_no' => '34576761222', 'whatsapp_no' => '34576761222', 'prefix_whatsapp' => '92', 'prefix_phone' => '92', 'cnic' => '3517753131', 'passport' => '2221111', 'age' => '24', 'experience' => '6', 'akama' => '2354718'],
        ];

        foreach ($drivers as $driver) {
            $partners = Partner::firstOrCreate(
                ['id' => $driver['id']], // Search by id
                [
                    'name' => $driver['name'],
                    // 'partner_type' => $partner_data['partner_type'],
                    // 'company_name'=>$driver['company_name'],
                    'email' => $driver['email'],
                    'phone_no' => $driver['phone_no'],
                    'whatsapp_no' => $driver['whatsapp_no'],
                    'prefix_whatsapp' => $driver['prefix_whatsapp'],
                    'prefix_phone' => $driver['prefix_phone'] ?? null,
                    'cnic' => $driver['cnic'],
                    'address1' => $driver['address1'],

                    'created_by' => 2,

                    'actor_id' => 5,
                    'company_id' => 1,
                ]

            );
            // Handle User creation or retrieval
            $user = User::firstOrCreate(
                ['email' => $driver['email']], // Search by email
                [
                    'name' => $driver['name'],
                    'image' => 'profile_images/default/default.jpeg' ?? null,
                    'partner_id' => $partners->id,
                    'actor_id' => 5,
                    'client_id' => 1,
                    'role_id' => 4,
                    'flag' => 1,
                    'password' => Hash::make('00000000'),
                    'active_company_id' => 1,
                ]
            );
            $partner_location = PartnerLocation::firstorCreate(
                ['partner_id' => $partners->id],
                [
                    
                    'address1' => $driver['address1'] ?? null,
                    'partner_id' => $partners->id,
                    'phone_no' => $driver['phone_no'],
                    'whatsapp_no' => $driver['whatsapp_no'],
                    'prefix_whatsapp' => $driver['prefix_whatsapp'],
                    'prefix_phone' => $driver['prefix_phone'] ?? null,
                    'client_id' => 1,
                    'is_default'=>1,
                    'company_id' => 1,
                    'created_by'=>1,
                ]
            );
            $partners->update(['partner_loc_id' => $partner_location->id]);

            UserCompany::firstOrCreate(
                [
                    'user_id' => $user->id,
                    'company_id' => 1,
                ]
            );
        }
        // creating vehicle class
        $classses = [
            ['id' => 1, 'name' => 'car', 'seats_allow' => 4, 'bags_allow' => 2],
            ['id' => 2, 'name' => 'bus', 'seats_allow' => 6, 'bags_allow' => 3],
        ];

        foreach ($classses as $class) {
            VehicleClass::firstOrCreate(
                [
                    'id' => $class['id'],
                ], // Search by id
                [
                    'name' => $class['name'],
                    'seats_allow' => $class['seats_allow'],
                    'bags_allow' => $class['bags_allow'],
                    'created_by' => 2,
                    'company_id' => 1,
                ]
            );
        }

        // creating vehicle company
        $car_companies = [
            ['id' => 1, 'name' => 'honda'],
            ['id' => 2, 'name' => 'toyota'],
        ];

        foreach ($car_companies as $car_company) {
            VehicleCompany::firstOrCreate(
                ['id' => $car_company['id']], // Search by id
                [
                    'name' => $car_company['name'],
                    'created_by' => 2,
                    'company_id' => 1,

                ]
            );
        }
        // creating vehicle model
        $models = [
            ['id' => 1, 'name' => 'civic', 'vehicle_company_id' => 1, 'vehicle_class_id' => 1],
            ['id' => 2, 'name' => 'corolla', 'vehicle_company_id' => 2, 'vehicle_class_id' => 1],
        ];

        foreach ($models as $model) {
            VehicleModel::firstOrCreate(
                ['id' => $model['id']], // Search by id
                [
                    'name' => $model['name'],
                    'vehicle_company_id' => $model['vehicle_company_id'],
                    'vehicle_class_id' => $model['vehicle_class_id'],
                    'created_by' => 2,
                    'company_id' => 1,

                ]
            );
        }
        // creating ratelist
        $ratelists = [
            ['id' => 1, 'name' => 'makkah route-car', 'vehicle_class_id' => 1, 'route_id' => 1, 'estimated_time' => 120, 'price' => 200],
            ['id' => 2, 'name' => 'maddinah route-car', 'vehicle_class_id' => 1, 'route_id' => 2, 'estimated_time' => 120, 'price' => 210],
        ];

        foreach ($ratelists as $ratelist) {
            RateList::firstOrCreate(
                ['id' => $ratelist['id']], // Search by id
                [
                    'name' => $ratelist['name'],
                    'vehicle_class_id' => $ratelist['vehicle_class_id'],
                    'route_id' => $ratelist['route_id'],
                    'estimated_time' => $ratelist['estimated_time'],
                    'price' => $ratelist['price'],
                    'company_id' => 1,

                ]
            );
        }

        // creating units
        $units = [
            ['id' => 1, 'name' => 'kg'],
            ['id' => 2, 'name' => 'pound'],
        ];

        foreach ($units as $unit) {
            UnitMeasure::firstOrCreate(
                ['id' => $unit['id']], // Search by id
                [
                    'name' => $unit['name'],
                    'company_id' => 1,
                    'client_id' => 1,
                    'created_by'=>2

                ]
            );
        }
        // creating loadstype
        $loads = [
            ['id' => 1, 'name' => 'bags'],
            ['id' => 2, 'name' => 'stationary'],
        ];

        foreach ($loads as $load) {
            LoadType::firstOrCreate(
                ['id' => $load['id']], // Search by id
                [
                    'name' => $load['name'],
                    'company_id' => 1,

                ]
            );
        }

        // creating vehicles
        $vehicles = [
            ['id' => 1, 'vehicle_identification_number' => '50778', 'driver_id' => 4,

                // 'vehicle_company_id' => $data['vehicle_company_id'],
                'driver_id' => 4,
                'year' => '2021',

                'vehicle_no' => '5077',
                'registration_no' => '5077',

                // 'vehicle_class_id' => $data['vehicle_class_id'],

                'milage' => '10000',
                'maintenance_interval_days' => '30',
                'maintenance_oilchange_interval_km' => '150',

                'car_condition' => 'used',

                'is_status' => 'active',
            ],
        ];

        foreach ($vehicles as $vehicle) {
            Vehicle::firstOrCreate(
                ['id' => $vehicle['id']], // Search by id
                [
                    'vehicle_identification_number' => $vehicle['vehicle_identification_number'],
                    'vehicle_model_id' => 1,
                    'ownership' => 'owned',
                    // 'vehicle_company_id' => $data['vehicle_company_id'],
                    'driver_id' => $vehicle['driver_id'],
                    'year' => $vehicle['year'],
                    'vehicle_no' => $vehicle['vehicle_no'],
                    'registration_no' => $vehicle['registration_no'],
                    // 'vehicle_class_id' => $data['vehicle_class_id'],
                    'milage' => $vehicle['milage'],
                    'maintenance_interval_days' => $vehicle['maintenance_interval_days'],
                    'maintenance_oilchange_interval_km' => $vehicle['maintenance_oilchange_interval_km'],
                    'image' => null,
                    'car_condition' => $vehicle['car_condition'],
                    'is_status' => 'active',
                    'reason' => null,
                    'is_ac' => 1,
                    'created_by' => 2,
                    'company_id' => 1,
                    'fuel_type' => 'petrol',
                    'transmission_type'=> 'manual',

                ]
            );
        }
        // // creating loadstype
        // $products = [
        //     ['id' => 1, 'name' => 'play station',
        //         'cost_price' => '13000',
        //         'sale_price' => '13000',
        //         'unit_measure_id' => 1,
        //         'sku' => '112',
        //     ],
        // ];

        // foreach ($products as $product) {
        //     Product::firstOrCreate(
        //         ['id' => $product['id']], // Search by id
        //         [
        //             'name' => $product['name'],
        //             'cost_price' => $product['cost_price'],
        //             'sale_price' => $product['sale_price'],
        //             'sku' => $product['sku'],
        //             'unit_measure_id' => $product['unit_measure_id'],
        //             'company_id' => 1,
        //             'created_by' => 2,

        //         ]
        //     );
        // }

    }
}

<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Actor;
use App\Models\Company;
use App\Models\Role;
use App\Models\RoleHasModule;
use App\Models\RoleModule;
use App\Models\RoleModuleActors;
use App\Models\RolePermission;
use App\Models\RolePermissionType;
use App\Models\RolePermissionTypeFunction;
use App\Models\SidebarGroups;
use App\Models\SidebarItems;
use App\Models\User;
use App\Models\Client;
use App\Models\UserCompany;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $adminClient = Client::updateOrCreate(['id' => 1],[
            'name' => 'admin',
            // 'user_id' => $adminUser->id,
        ]);
        $superAdminActor = Actor::updateOrCreate(['id' => 1], ['name' => 'super_admin', 'description' => 'this is super admin']);
        $adminActor = Actor::updateOrCreate(['id' => 2], ['name' => 'admin', 'description' => 'this is admin']);
        $businessActor = Actor::firstOrCreate(['id' => 3],['name' => 'business', 'description' => 'this is business']);
        $agentActor = Actor::firstOrCreate(['id' => 4],['name' => 'agent', 'description' => 'this is agent']);
        $driverActor = Actor::firstOrCreate(['id' => 5],['name' => 'driver', 'description' => 'this is driver']);
        $customerActor = Actor::firstOrCreate(['id' => 6],['name' => 'business_customer', 'description' => 'this is customer']);
        $employeeActor = Actor::firstOrCreate(['id' => 7],['name' => 'employee', 'description' => 'this is employee']);
        $walkincustomerActor = Actor::firstOrCreate(['id' => 8],['name' => 'walkincustomer', 'description' => 'this is walkincustomer']);
        $vehicleManagerActor = Actor::firstOrCreate(['id' => 9],['name' => 'vehicle_manager', 'description' => 'this is vehicle_manager']);
        $vendorActor = Actor::firstOrCreate(['id' => 10],['name' => 'vendor', 'description' => 'this is vendor']);

        $superAdminRole = Role::updateOrCreate([
            'id' => 1,
        ], [
            'name' => 'Supper Admin',
            'actor_id' => $superAdminActor->id,
            'is_system' => 1,
            'home' => '/',
        ]);

        $adminRole = Role::updateOrCreate([
            'id' => 2,
        ], [
            'client_id' => $adminClient->id,
            'name' => 'admin',
            'actor_id' => $adminActor->id,
            'is_system' => 1,
            'home' => '/',
        ]);

        $agentRole = Role::updateOrCreate([
            'id' => 3,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'agent',
            'actor_id' => $agentActor->id,
            'is_system' => 1,
            'home' => '/',

        ]);

        $driverRole = Role::updateOrCreate([
            'id' => 4,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'driver',
            'actor_id' => $driverActor->id,
            'is_system' => 1,
            'home' => '/',

        ]);

        $managmentRole = Role::updateOrCreate([
            'id' => 5,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'management',
            'actor_id' => $employeeActor->id,
            'is_system' => 1,
            'home' => '/',

        ]);

        $staffRole = Role::updateOrCreate([
            'id' => 6,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'office_staff',
            'actor_id' => $employeeActor->id,
            'is_system' => 1,
            'home' => '/',
        ]);


        $vehicleManagerRole = Role::updateOrCreate([
            'id' => 7,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'vehicle_manager',
            'actor_id' => $vehicleManagerActor->id,
            'is_system' => 1,
            'home' => '/',
        ]);
        $vendorRole = Role::updateOrCreate([
            'id' => 8,
        ],[
            'client_id' => $adminClient->id,
            'name' => 'vendor',
            'actor_id' => $vendorActor->id,
            'is_system' => 1,
            'home' => '/',
        ]);

        // $walkincustomerRole=Role::forceCreate([
        //     'name'=>'walkincustomer',
        //     'actor_id'=>$walkincustomerActor->id,
        //     'is_system'=> 1,
        //     'home'=>'/',

        // ]);

        $superAdminUser = User::updateOrCreate(
            [
                'id' => 1,
            ],
            [
                'name' => 'super_admin',
                'email' => 'super_admin@idl.pk',
                'role_id' => $superAdminRole->id,
                'actor_id' => $superAdminActor->id,
                'is_super_admin' => 1,
                'password' => Hash::make('00000000'),
            ]
        );

        $Company = Company::updateOrCreate(['id'=>1],[
            'name' => 'Admin Company',
            'description' => 'desc',
            'client_id' => $adminClient->id,
        ]);
        $adminUser = User::updateOrCreate(
            [
                'id' => 2,
            ],
            [
                'name' => 'admin',
                'email' => 'admin@idl.pk',
                'role_id' => $adminRole->id,
                'actor_id' => $adminActor->id,
                'is_company_admin' => 1,
                'active_company_id' => $Company->id,
                'client_id' => $adminClient->id,
                'password' => Hash::make('00000000'),
            ]
        );
        Client::where('id', $adminClient->id )->update([
            'user_id' => $adminUser->id,
        ]);
        Account::defaultAccounts($Company->id, $adminUser->id);
        $UserCompany = UserCompany::firstOrCreate([
            'company_id' => $Company->id,
            'user_id' => $adminUser->id,
        ]);

        // add company in role
        // make role add modules

        $created_by = $superAdminUser->id;

        $actors = [
            $superAdminActor->id,
            $adminActor->id,
            $businessActor->id,
            $agentActor->id,
            $driverActor->id,
            $customerActor->id,
            $employeeActor->id,
            $walkincustomerActor->id,
            // $vehicleManagerActor->id,
        ];

        $actors_without_superadmin = [
            $adminActor->id,
            $businessActor->id,
            $agentActor->id,
            $driverActor->id,
            $customerActor->id,
            $employeeActor->id,
            $walkincustomerActor->id,
            // $vehicleManagerRole->id,
        ];

        $roles_without_superadmin = [
            $adminRole->id,
            // $businessRole->id,
            $agentRole->id,
            $driverRole->id,
            // $customerRole->id,
            $managmentRole->id,
            // $walkincustomerRole->id,
            $staffRole->id,
            $agentRole->id,
        ];

        // $adminagentActor = [$adminActor->id, $agentActor->id];

        $roles = [
            [$superAdminRole->id, $superAdminActor->id],
            [$adminRole->id, $adminActor->id],
            [$agentRole->id, $agentActor->id],
            [$driverRole->id, $driverActor->id],
            [$managmentRole->id, $employeeActor->id],
            [$staffRole->id, $employeeActor->id],
            [$vehicleManagerRole->id, $vehicleManagerActor->id],
            [$vendorRole->id, $vendorActor->id],
        ];

        $role_modules = [
            [
                'module_id' => 1,
                'module_name' => 'Users',
                'name' => 'Users',
                'link' => 'users',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                'position' => null,
                'group_id' => '1',
                'actor_id' => [$superAdminActor->id, $adminActor->id],
                'role_id' => [$superAdminRole->id, $adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 2,
                'module_name' => 'Roles',
                'name' => 'Roles',
                'link' => 'roles',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info link-icon"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
                'position' => null,
                'group_id' => '1',
                'actor_id' => [$superAdminActor->id, $adminActor->id],
                'role_id' => [$superAdminRole->id, $adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 3,
                'module_name' => 'RoleModules',
                'name' => 'Role Modules',
                'link' => 'role_modules',
                'icon' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info link-icon"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
                'position' => '20',
                'group_id' => null,
                'actor_id' => [$superAdminActor->id],
                'role_id' => [$superAdminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'delete' => [
                        'is_read' => 0,
                        'methods' => [
                            'delete_row' => 'json',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 4,
                'module_name' => 'Actors',
                'name' => 'Actors',
                'link' => 'actors',
                'icon' => '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
                'position' => '19',
                'group_id' => null,
                'actor_id' => [$superAdminActor->id],
                'role_id' => [$superAdminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 5,
                'module_name' => 'Vehicle',
                'name' => 'Vehicles',
                'link' => 'vehicles',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                </svg>',
                'position' => null,
                'group_id' => '3',
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'getVehicleLocation' => 'view',
                            'getVehicleClassDetails' => 'json',
                        ],
                    ],
                    'update' => [
                        'is_read' => 0,
                        'methods' => [
                            'changeStatus' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 6,
                'module_name' => 'Employee',
                'name' => 'Employees',
                'link' => 'employees',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>',
                'position' => '4',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 7,
                'module_name' => 'Routes',
                'name' => 'Routes',
                'link' => 'routes',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="21" height="21" fill="currentColor" class="bi bi-signpost" viewBox="0 0 16 16">
                <path d="M7 1.414V4H2a1 1 0 0 0-1 1v4a1 1 0 0 0 1 1h5v6h2v-6h3.532a1 1 0 0 0 .768-.36l1.933-2.32a.5.5 0 0 0 0-.64L13.3 4.36a1 1 0 0 0-.768-.36H9V1.414a1 1 0 0 0-2 0M12.532 5l1.666 2-1.666 2H2V5z"/>
                </svg>',
                'position' => null,
                'group_id' => '4',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 8,
                'module_name' => 'RateList',
                'name' => 'Rate List',
                'link' => 'ratelists',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-dollar-sign"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>',
                'position' => null,
                'group_id' => '4',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 9,
                'module_name' => 'Orders',
                'name' => 'Admin Rides',
                'link' => 'orders',
                'icon' => '<svg width="22" height="22" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>',
                'position' => '2',
                'group_id' => 10,
                // 'actor_id' => $actors_without_superadmin,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read_receipt' => [
                        'is_read' => 0,
                        'methods' => [
                            'receipt' => 'view',
                            'receiptDetails' => 'view',
                        ],
                    ],
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'login_partner_orders' => 'view',
                            'api_login_partner_orders' => 'json',
                            'latestPendingOrders' => 'json',
                            'getStatusCount' => 'json',
                            'saveDraft' => 'json',
                            'updateOrderStatus' => 'json',
                            'api_today_total_rides'=>'json',
                            'api_today_active_rides'=>'json',
                            'api_today_pending_rides'=>'json',
                            'api_monthly_pending_rides'=>'json',
                            // 'api_built_to'=>'json',
                        ],
                    ],
                    'update' => [
                        'is_read' => 0,
                        'methods' => [
                            'changePassword' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 10,
                'module_name' => 'PendingOrders',
                'name' => 'Pending Rides',
                'link' => 'pending_orders',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => '0',
                'group_id' => 10,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 11,
                'module_name' => 'Drivers',
                'name' => 'Drivers',
                'link' => 'drivers',
                'icon' => '<?xml version="1.0" encoding="iso-8859-1"?>
                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
                    <svg fill="currentColor" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        width="21" height="21" viewBox="0 0 124.64 124.64"
                        xml:space="preserve">
                    <g><g><path d="M38.912,16.332l-0.457,1.518c-4.296,0.746-5.938,1.77-5.938,3.477c0,1.514,1.298,2.482,4.562,3.209
                            c-0.485,0.793-0.748,1.619-0.748,2.471c0,1.691,1.021,3.289,2.822,4.701c-0.563,1.951-0.005,4.719,0.457,6.4
                            c0.326,1.813,0.902,3.096,1.777,4.002c0.068,0.078,0.104,0.146,0.117,0.236c1.799,11.902,11.607,22.742,20.578,22.742
                            c10.603,0,20.036-11.975,21.869-22.713c0.016-0.09,0.081-0.201,0.035-0.152c0.908-0.893,1.532-2.188,1.811-3.75
                            c0.608-2.281,1.114-5.129,0.537-6.955c4.278-3.445,2.611-6.229,2.192-6.811c3.89-0.742,5.384-1.752,5.384-3.381
                            c0-1.799-1.811-2.836-6.628-3.596l-0.428-1.398c6.241-1.732,10.11-4.125,10.11-6.783C96.964,4.274,81.707,0,62.88,0
                            C44.062,0,28.8,4.273,28.8,9.549C28.8,12.207,32.67,14.599,38.912,16.332z M70.107,15.029c0.189,0.043,0.376,0.145,0.542,0.293
                            l0.032,0.035c0.148,0.164,0.25,0.352,0.293,0.541c0.129,0.541-0.301,1.357-0.795,1.883l-2.323,2.457l1.751,4.504
                            c0.1,0.271,0.049,0.611-0.121,0.83l-0.693,0.895c-0.256,0.324-0.76,0.287-0.967-0.061l-2.281-3.852l-2.328,2.184
                            c-0.004,0.002-0.008,0.006-0.012,0.01l0.141,1.076l-1.145,1.48l-0.336-0.412c-0.027-0.033-0.054-0.068-0.074-0.109l-0.492-0.918
                            c-0.146,0.051-0.402,0.125-0.624,0.125c-0.202,0-0.364-0.061-0.481-0.178c-0.22-0.223-0.235-0.592-0.053-1.105l-1.004-0.547
                            l-0.451-0.332l1.494-1.166l1.078,0.137c0.004-0.004,0.008-0.008,0.012-0.012l0.029-0.039l2.149-2.285l-3.85-2.283
                            c-0.179-0.107-0.286-0.279-0.296-0.473c-0.012-0.191,0.077-0.373,0.24-0.498l0.887-0.691c0.216-0.168,0.567-0.219,0.827-0.121
                            l4.509,1.75l2.455-2.32C68.822,15.259,69.613,14.914,70.107,15.029z M58.961,25.359l0.17,0.078v0.002
                            c0.11,0.307-0.039,0.477-0.346,0.357c-2.104-0.816-3.781-2.492-4.598-4.6c-0.119-0.305,0.049-0.455,0.357-0.344
                            c0.918,0.328,2,0.582,3.161,0.756c0.323,0.051,0.618,0.346,0.667,0.67c0.037,0.244,0.08,0.484,0.124,0.721l-1.388,1.063
                            L58.961,25.359z M59.687,20.939c-0.328-0.025-0.618-0.314-0.643-0.641c-0.031-0.406-0.049-0.826-0.061-1.256
                            c0.092,0.092,0.196,0.172,0.311,0.24l2.721,1.615l-0.105,0.111c-0.068,0.002-0.137,0.004-0.207,0.004
                            C61.005,21.013,60.331,20.988,59.687,20.939z M60.002,21.847l-0.709,0.543c-0.002-0.006-0.002-0.01-0.002-0.016
                            c-0.055-0.322,0.174-0.561,0.501-0.539C59.861,21.841,59.932,21.843,60.002,21.847z M65.257,16.371
                            c-0.022-0.326,0.217-0.555,0.539-0.502c0.375,0.061,0.732,0.133,1.073,0.207l-1.11,1.051l-0.474-0.186
                            C65.278,16.752,65.27,16.56,65.257,16.371z M64.619,10.765c1.807,0.703,3.297,2.039,4.199,3.734
                            c-0.421,0.211-0.8,0.492-1.085,0.762l-0.089,0.084c-0.611-0.156-1.262-0.289-1.945-0.393c-0.322-0.049-0.617-0.346-0.667-0.668
                            c-0.176-1.162-0.429-2.244-0.757-3.16C64.164,10.816,64.313,10.646,64.619,10.765z M64.36,16.265
                            c0.008,0.105,0.012,0.215,0.019,0.324l-2.518-0.979c-0.068-0.025-0.139-0.045-0.211-0.063c0.018,0,0.035,0,0.053,0
                            c0.699,0,1.372,0.027,2.016,0.074C64.044,15.648,64.337,15.939,64.36,16.265z M60.203,10.847c0.13-0.297,0.521-0.574,0.848-0.6
                            c0.215-0.018,0.434-0.027,0.652-0.027c0.22,0,0.438,0.01,0.652,0.027c0.326,0.025,0.717,0.303,0.848,0.6
                            c0.369,0.85,0.691,1.99,0.913,3.34c0.054,0.322-0.176,0.563-0.503,0.539c-0.633-0.045-1.274-0.066-1.91-0.066
                            c-0.636,0-1.277,0.021-1.91,0.066c-0.326,0.023-0.556-0.217-0.502-0.539C59.511,12.838,59.834,11.697,60.203,10.847z
                            M59.687,15.623c0.32-0.022,0.647-0.041,0.982-0.055c-0.238,0.064-0.463,0.172-0.652,0.32l-0.879,0.686
                            c-0.041,0.031-0.08,0.064-0.117,0.1c0.008-0.137,0.013-0.275,0.023-0.408C59.068,15.939,59.359,15.648,59.687,15.623z
                            M54.187,15.365c0.816-2.104,2.493-3.781,4.598-4.6c0.307-0.119,0.456,0.051,0.346,0.359c-0.329,0.916-0.582,1.998-0.758,3.16
                            c-0.049,0.322-0.344,0.619-0.667,0.668c-1.161,0.176-2.243,0.428-3.161,0.758C54.236,15.82,54.068,15.672,54.187,15.365z
                            M53.668,17.627c0.026-0.324,0.303-0.715,0.603-0.846c0.849-0.369,1.989-0.691,3.337-0.912c0.324-0.053,0.563,0.176,0.54,0.502
                            c-0.043,0.635-0.066,1.275-0.066,1.91c0,0.637,0.023,1.277,0.066,1.91c0.022,0.326-0.216,0.557-0.54,0.502
                            c-1.348-0.221-2.488-0.543-3.337-0.912c-0.3-0.131-0.576-0.521-0.603-0.848c-0.018-0.215-0.025-0.432-0.025-0.652
                            C53.642,18.062,53.65,17.843,53.668,17.627z M62.633,37.443c7.882,0,14.947-1.377,19.769-3.557
                            c0.165-0.055,0.753-0.355,0.753-0.355c0.097-0.045,0.179-0.098,0.271-0.145c0.006,0.797-0.121,2.15-0.709,4.33l-0.033,0.148
                            c-0.156,0.936-0.475,1.66-0.985,2.166c-0.44,0.492-0.748,1.127-0.868,1.801C79.244,51.12,70.71,61.917,62.084,61.917
                            c-7.274,0-15.916-9.932-17.444-20.051c-0.11-0.713-0.414-1.348-0.938-1.922c-0.459-0.479-0.771-1.26-0.981-2.463l-0.034-0.15
                            c-0.479-1.723-0.62-2.998-0.614-3.822C46.888,35.906,54.306,37.443,62.633,37.443z"/>
                            <path d="M101.729,75.925c-0.354-0.453-2.33-2.752-6.282-4.258c-3.473-1.076-12.057-3.921-16.757-7.322
                            c-1.358-0.979-2.95-2.879-2.95-2.879l-0.466,0.441c-0.228,0.214-0.461,0.402-0.69,0.607c-0.906,2.842-3.726,11.07-8.398,19.729
                            l-0.342-6.678l-2.643-1.889l2.643-1.887l-1.604-5.537H60.4l-1.604,5.537l2.642,1.887l-2.642,1.889l-0.341,6.678
                            c-4.673-8.657-7.493-16.887-8.398-19.729c-0.229-0.205-0.464-0.393-0.691-0.607L48.9,61.466c0,0-1.591,1.899-2.951,2.879
                            c-4.699,3.401-13.285,6.246-16.755,7.322c-3.953,1.506-5.927,3.805-6.282,4.258c-7.271,10.795-8.096,35.013-8.102,35.252
                            c0.064,3.224,1.023,4.324,1.352,4.459c16.026,7.132,36.191,8.968,46.01,9.002v0.002c0.049,0,0.101,0,0.148,0s0.1,0,0.148,0v-0.002
                            c9.818-0.034,29.983-1.87,46.01-9.002c0.328-0.135,1.285-1.235,1.352-4.459C109.823,110.938,109,86.72,101.729,75.925z"/>
                        </g>
                    </g></svg>',
                'position' => '6',
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'driver_search' => 'view',
                        ],
                    ],
                ],
            ],

            [
                'module_id' => 12,
                'module_name' => 'Customers',
                'name' => 'Customers',
                'link' => 'customers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                        <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>',
                'position' => '5',
                'group_id' => 10,
                'actor_id' => $actors_without_superadmin,
                'role_id' => $roles_without_superadmin,
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'create_customer_from_order' => 'view',
                        ],
                    ],
                ],
            ],

            [
                'module_id' => 13,
                'module_name' => 'BusinessAgent',
                'name' => 'Agent',
                'link' => 'business_agents',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                </svg>',
                'position' => null,
                'group_id' => '2',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'partner_dropdown' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 14,
                'module_name' => 'DriverAssignment',
                'name' => 'Driver Assignment',
                'link' => 'driver_assignments',
                'icon' => '<?xml version="1.0" encoding="iso-8859-1"?>
                <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                    <svg fill="currentColor" height="20" width="20" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 512 512" xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <path d="M512,341.333c0-8.917-6.635-15.147-16.128-15.147c-5.355,0-14.528,2.752-22.421,7.296
                                c-10.453-22.016-27.52-41.536-39.723-47.616c-8.085-4.032-35.627-8.533-60.395-8.533c-24.768,0-52.309,4.501-60.416,8.533
                                c-12.181,6.08-29.248,25.6-39.701,47.616c-7.915-4.544-17.088-7.296-22.421-7.296c-4.053,0-7.552,1.067-10.325,3.157
                                c-3.733,2.795-5.803,7.061-5.803,11.989c0,16.64,12.907,28.181,14.4,29.44c1.195,1.024,2.56,1.643,3.968,2.048
                                c-1.344,3.84-2.539,7.744-3.499,11.627C280.789,386.069,320,392.704,320,416c0,5.888-4.779,10.667-10.667,10.667
                                s-10.667-4.779-10.667-10.667c-3.2-4.203-26.987-8.981-52.757-10.304c-0.32,3.605-0.576,7.211-0.576,10.304
                                c0,15.936,1.472,43.115,10.667,57.003v17.664c0,11.968,9.365,21.333,21.333,21.333h21.333c11.968,0,21.333-9.365,21.333-21.333
                                v-7.232c12.885,3.221,32.981,7.232,53.333,7.232c20.352,0,40.448-4.011,53.333-7.232v7.232c0,11.968,9.365,21.333,21.333,21.333
                                h21.333c11.968,0,21.333-9.365,21.333-21.333v-17.664c9.195-13.888,10.667-41.067,10.667-57.003
                                c0-3.115-0.256-6.699-0.576-10.304c-25.792,1.344-49.6,6.272-53.035,11.371c0,5.888-4.651,10.155-10.517,10.155
                                c-5.888,0-10.539-5.312-10.539-11.2c0-23.296,39.211-29.931,70.464-31.552c-0.96-3.904-2.176-7.787-3.499-11.627
                                c1.408-0.405,2.773-1.024,3.968-2.048C499.093,369.515,512,357.973,512,341.333z M394.667,426.667H352
                                c-5.888,0-10.667-4.779-10.667-10.667c0-5.888,4.779-10.667,10.667-10.667h42.667c5.888,0,10.667,4.779,10.667,10.667
                                C405.333,421.888,400.555,426.667,394.667,426.667z M451.264,356.373c-0.043,0-0.085,0-0.107,0.021
                                c-4.288,0.469-8.725,0.917-13.184,1.387c-0.555,0.064-1.088,0.107-1.643,0.171c-4.565,0.469-9.173,0.917-13.717,1.344
                                c-1.173,0.107-2.304,0.213-3.456,0.32c-3.349,0.32-6.656,0.619-9.899,0.896c-1.472,0.128-2.88,0.235-4.309,0.363
                                c-2.859,0.235-5.611,0.448-8.299,0.661c-1.408,0.107-2.795,0.213-4.139,0.299c-2.603,0.171-5.013,0.32-7.36,0.448
                                c-1.109,0.064-2.304,0.128-3.328,0.171c-3.179,0.128-6.101,0.213-8.491,0.213c-2.389,0-5.312-0.085-8.491-0.235
                                c-1.045-0.043-2.219-0.128-3.328-0.171c-2.347-0.128-4.757-0.256-7.36-0.448c-1.344-0.085-2.709-0.192-4.117-0.299
                                c-2.688-0.192-5.44-0.405-8.32-0.661c-1.429-0.128-2.837-0.235-4.309-0.363c-3.221-0.277-6.549-0.576-9.899-0.896
                                c-1.152-0.107-2.283-0.213-3.456-0.32c-4.544-0.427-9.152-0.896-13.717-1.344c-0.555-0.064-1.088-0.107-1.621-0.171
                                c-7.381-0.747-14.507-1.515-21.312-2.261c0.064-0.171,0.107-0.299,0.171-0.469c7.083-23.787,26.624-45.952,34.859-50.069
                                c3.968-1.813,26.731-6.293,50.901-6.293s46.933,4.48,50.88,6.293c8.277,4.117,27.819,26.283,34.901,50.091
                                c0.064,0.171,0.107,0.299,0.171,0.469C456.619,355.797,454.037,356.096,451.264,356.373z"/>
                                <path d="M458.667,0H53.333C23.915,0,0,23.915,0,53.333V64h512V53.333C512,23.915,488.085,0,458.667,0z"/>
                                <path d="M0,330.667C0,360.085,23.915,384,53.333,384h158.251c5.888,0,10.667-4.779,10.667-10.667
                                c0-2.816-1.088-5.376-2.859-7.275c-4.075-8.128-6.059-16.235-6.059-24.725h-160c-5.888,0-10.667-4.779-10.667-10.667
                                c0-26.752,18.133-49.963,44.053-56.448l32.021-7.979l1.557-6.272c-7.808-8.96-13.376-20.203-15.765-31.851
                                c-6.507-2.837-10.923-8.597-11.797-15.552l-2.304-18.56c-0.725-5.632,1.024-11.349,4.821-15.637
                                c1.28-1.429,2.709-2.667,4.288-3.669c-0.597-5.504-1.237-12.096-1.237-15.616c0-17.856,5.163-41.515,49.067-43.051
                                c14.891-9.365,30.229-9.365,37.035-9.365c23.104,0,34.603,10.219,40.192,18.752c11.776,17.984,4.331,30.485-1.173,36.309
                                l-1.941,1.963l-0.555,11.328c1.429,0.96,2.752,2.112,3.904,3.435c3.733,4.245,5.461,9.941,4.757,15.552l-2.304,18.517
                                c-0.832,6.72-4.992,12.331-10.688,15.275c-2.432,12.16-8.341,23.872-16.64,33.045l1.344,5.376l32.021,7.979
                                c15.083,3.776,27.371,13.291,35.136,25.771c11.136-15.531,23.829-27.627,34.965-33.195C316.928,260.032,349.589,256,373.333,256
                                s56.384,4.011,69.888,10.773c11.435,5.717,24.469,18.219,35.797,34.304c2.304,3.243,6.272,4.928,10.197,4.416
                                c3.712-0.469,5.483-0.875,11.029-0.299c3.029,0.299,5.973-0.661,8.256-2.688c2.219-2.005,3.499-4.885,3.499-7.893V85.333H0
                                V330.667z M416,128h42.667c5.888,0,10.667,4.779,10.667,10.667s-4.779,10.667-10.667,10.667H416
                                c-5.888,0-10.667-4.779-10.667-10.667S410.112,128,416,128z M309.333,128h64c5.888,0,10.667,4.779,10.667,10.667
                                s-4.779,10.667-10.667,10.667h-64c-5.888,0-10.667-4.779-10.667-10.667S303.445,128,309.333,128z M309.333,192h149.333
                                c5.888,0,10.667,4.779,10.667,10.667s-4.779,10.667-10.667,10.667H309.333c-5.888,0-10.667-4.779-10.667-10.667
                                S303.445,192,309.333,192z"/>
                            </g>
                        </g>
                    </g>
                </svg>',
                'position' => '3',
                'group_id' => null,
                'actor_id' => [$adminActor->id,$driverActor->id,],
                'role_id' => [$adminRole->id,$driverRole->id,],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'vehicle_dropdown' => 'view',
                            'assignVehicle' => 'view',
                            'incompleteRides' => 'view',
                            'incompleteRidesUpdate' => 'view',
                            'completedRides' => 'view',
                            'driverUpdate' => 'view',
                            'partner_dropdown' => 'view',
                            'cancelledRides' => 'view',
                            'getAuthenticatedUser'=> 'json',
                            'ride_assign_to_driver_pending'=> 'json',
                            'driver_order_create'=> 'json',
                            'driver_order_update'=> 'json',
                            'driver_create_customer'=> 'json',
                            'ride_assign_to_driver_inprogress'=>'json',
                            'updateRideStatus' => 'json',
                            'getallride_assign_to_driver'=>'json',
                            'getWalkinCustomers'=>'json',
                            'api_approvedRides'=>'json',        
                            'api_incompletedRides'=>'json',
                            'api_completedRides'=>'json',
                            'api_cancelledRides'=>'json',                                                                                                               
                            
                        ],
                    ],
                ],
            ],
            [ // missing name
                'module_id' => 15,
                'module_name' => 'ReceiptsModule',
                'name' => 'Receipts',
                'link' => 'order/receipts',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => null,
                'group_id' => '5',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 16,
                'module_name' => 'BulkPayment',
                'name' => 'Bulk Payment',
                'link' => 'bulkpayments',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => null,
                'group_id' => '5',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read_payment_window' => [
                        'is_read' => 0,
                        'methods' => [
                            'payment_window_index' => 'view',
                            'payment_window_store' => 'view',
                            'payment_window_create' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 17,
                'module_name' => 'Ledgers',
                'name' => 'Ledgers',
                'link' => 'ledgers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => null,
                'group_id' => '6',
                'actor_id' => $actors_without_superadmin,
                'role_id' => $roles_without_superadmin,
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'driver_ledger' => [
                        'is_read' => 0,
                        'methods' => [
                            'driver_ledger' => 'view',
                        ],
                    ],
                    'export' => [
                        'is_read' => 0,
                        'methods' => [
                            'driver_export' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 18,
                'module_name' => 'RidesStatus',
                'name' => 'Rides Status',
                'link' => 'rides_status/total-orders',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => '56',
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 19,
                'module_name' => 'Location',
                'name' => 'Location',
                'link' => 'locations',
                'icon' => '
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                'position' => null,
                'group_id' => '4',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 20,
                'module_name' => 'VehicleClass',
                'name' => 'Vehicle Class',
                'link' => 'vehicle_classes',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                'position' => null,
                'group_id' => '3',
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],

            [
                'module_id' => 21,
                'module_name' => 'UnapprovedAgents',
                'name' => 'Unapproved Agents',
                'link' => 'unapproved_agents',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                'position' => null,
                'group_id' => '2',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 22,
                'module_name' => 'VehicleModel',
                'name' => 'Vehicle Models',
                'link' => 'vehicle-models',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                </svg>',
                'position' => null,
                'group_id' => '3',
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 23,
                'module_name' => 'VehicleCompany',
                'name' => 'Vehicle Company',
                'link' => 'vehicle-companies',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                </svg>',
                'position' => null,
                'group_id' => '3',
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 24,
                'module_name' => 'Accounts',
                'name' => 'Accounts',
                'link' => 'accounts',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => '30',
                'group_id' => '7',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 25,
                'module_name' => 'AccountTypes',
                'name' => 'Account Types',
                'link' => 'account-types',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => '30',
                'group_id' => null,
                'actor_id' => [$superAdminActor->id],
                'role_id' => [$superAdminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],

            [ // missing name
                'module_id' => 26,
                'module_name' => 'DriverLedgers',
                'name' => 'Driver Ledgers',
                'link' => 'ledger/driver_ledger',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>',
                'position' => null,
                'group_id' => '6',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 27,
                'module_name' => 'LoadTypes',
                'name' => 'Load Types',
                'link' => 'load-types',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-truck"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>',
                'position' => '31',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 28,
                'module_name' => 'UnitMeasure',
                'name' => 'Unit Measure',
                'link' => 'unit-types',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 640 512"><path fill="#640d5f" d="M256 336h0c0-16.2 1.3-8.7-85.1-181.5-17.7-35.3-68.2-35.4-85.9 0C-2.1 328.8 0 320.3 0 336H0c0 44.2 57.3 80 128 80s128-35.8 128-80zM128 176l72 144H56l72-144zm512 160c0-16.2 1.3-8.7-85.1-181.5-17.7-35.3-68.2-35.4-85.9 0-87.1 174.3-85 165.8-85 181.5H384c0 44.2 57.3 80 128 80s128-35.8 128-80h0zM440 320l72-144 72 144H440zm88 128H352V153.3c23.5-10.3 41.2-31.5 46.4-57.3H528c8.8 0 16-7.2 16-16V48c0-8.8-7.2-16-16-16H383.6C369 12.7 346.1 0 320 0s-49 12.7-63.6 32H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h129.6c5.2 25.8 22.9 47 46.4 57.3V448H112c-8.8 0-16 7.2-16 16v32c0 8.8 7.2 16 16 16h416c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16z"/></svg>',
                'position' => '31',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [

                'module_id' => 29,
                'module_name' => 'BroadcastMessage',
                'name' => 'Broadcast Message',
                'link' => 'broadcast-messages',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#640d5f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-mail"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>',
                'position' => '31',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 30,
                'module_name' => 'GlJournalAccounts',
                'name' => 'Gl Journal Accounts',
                'link' => 'gl-journals',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                    <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => '31',
                'group_id' => '7',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'complete_status' => [
                        'is_read' => 0,
                        'methods' => [
                            'complete' => 'view',
                        ],
                    ],
                    'create' => [
                        'is_read' => 0,
                        'methods' => [
                            'add_new_row' => 'json',
                        ],
                    ],
                    'delete' => [
                        'is_read' => 0,
                        'methods' => [
                            'destroy_row' => 'json',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 31,
                'module_name' => 'Payment',
                'name' => 'Payments',
                'link' => 'payments',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                    <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => '35',
                'group_id' => 5,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 32,
                'module_name' => 'Product',
                'name' => 'Products',
                'link' => 'products',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package">
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
                'position' => '36',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 33,
                'module_name' => 'Activity',
                'name' => 'Maintenance Activity',
                'link' => 'activities',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-package">
                <line x1="16.5" y1="9.4" x2="7.5" y2="4.21"></line><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>',
                'position' => '36',
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [ // missing name
                'module_id' => 34,
                'module_name' => 'PaymentWindow',
                'name' => 'Payment Window',
                'link' => 'payment_window/index',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => null,
                'group_id' => '5',
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 35,
                'module_name' => 'Maintenance',
                'name' => 'Maintenance',
                'link' => 'maintenances',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => 90,
                'group_id' => 8,
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id,$driverActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id,$driverRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'delete' => [
                        'is_read' => 0,
                        'methods' => [
                            'destroy_row' => 'json',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 36,
                'module_name' => 'InvoiceDocumentType',
                'name' => 'InvoiceDocumentType',
                'link' => 'invoice-document-types',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                    <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => 37,
                'group_id' => null,
                'actor_id' => [$superAdminActor->id],
                'role_id' => [$superAdminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 37,
                'module_name' => 'TollTax',
                'name' => 'Toll Tax',
                'link' => 'toll-taxes',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
                <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"/>
                <path d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"/>
                </svg>',
                'position' => null,
                'group_id' => 8,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 38,
                'module_name' => 'FuelExpense',
                'name' => 'Fuel Expense',
                'link' => 'fuel-expenses',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
                <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"/>
                <path d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"/>
                </svg>',
                'position' => null,
                'group_id' => 8,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 39,
                'module_name' => 'Calendar',
                'name' => 'Calendar',
                'link' => 'calendar',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 30,
                'group_id' => null,
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 40,

                'module_name' => 'Companies',
                'name' => 'Companies',
                'link' => 'companies',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 35,
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],

            ],
            [
                'module_id' => 41,
                'module_name' => 'RentalVehicle',
                'name' => 'Monthly Rental',
                'link' => 'rental_vehicles',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => 10,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 42,
                'module_name' => 'DailyRental',
                'name' => 'Daily Rental',
                'link' => 'daily_rentals',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => 10,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 43,
                'module_name' => 'TourService',
                'name' => 'Tour Service',
                'link' => 'tour_services',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => 10,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 44,
                'module_name' => 'Inspection',
                'name' => 'Inspection',
                'link' => 'inspections',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-receipt" viewBox="0 0 16 16">
                    <path d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z"/>
                    <path d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5"/>
                </svg>',
                'position' => 100,
                'group_id' => 8,
                'actor_id' => [$adminActor->id, $vehicleManagerActor->id,$driverActor->id],
                'role_id' => [$adminRole->id, $vehicleManagerRole->id,$driverRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'delete' => [
                        'is_read' => 0,
                        'methods' => [
                            'destroy_row' => 'json',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 45,
                'module_name' => 'AgentOrders',
                'name' => 'Agent Rides',
                'link' => 'agentorders',
                'icon' => '<svg width="22" height="22" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>',
                'position' => '2',
                'group_id' => 10,
                'actor_id' => [$agentActor->id],
                'role_id' => [$agentRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'login_partner_orders' => 'view',
                        ],
                    ],
                    'update' => [
                        'is_read' => 0,
                        'methods' => [
                            'changePassword' => 'view',
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 46,
                'module_name' => 'Users',
                'name' => 'Users',
                'link' => 'jasper/report/users/html',
                'icon' => '<svg width="22" height="22" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>',
                'position' => 10,
                'group_id' => 11,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => true,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 47,
                'module_name' => 'PendingRentalInvoices',
                'name' => 'Pending Rental Invoices ',
                'link' => 'pending_rental_invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],


            [
                'module_id' => 48,
                'module_name' => 'trailBalance',
                'name' => 'Trial Balance',
                'link' => 'jasper/report/trial_balance_two_column_FF/html',
                'icon' => '<svg width="22" height="22" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>',
                'position' => 10,
                'group_id' => 11,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => true,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 47,
                'module_name' => 'PendingRentalInvoices',
                'name' => 'Pending Rental Invoices ',
                'link' => 'pending_rental_invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 47,
                'module_name' => 'PendingRentalInvoices',
                'name' => 'Pending Rental Invoices ',
                'link' => 'pending_rental_invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 47,
                'module_name' => 'PendingRentalInvoices',
                'name' => 'Pending Rental Invoices ',
                'link' => 'pending_rental_invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
                'position' => 31,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 48,
                'module_name' => 'MaintenanceApprovals',
                'name' => 'Maintenance Approvals',
                'link' => 'maintenance_approvals',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 49,
                'module_name' => 'PartnerLocation',
                'name' => 'Partner Location',
                'link' => 'partner-locations',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 9,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 50,
                'module_name' => 'ManufacturingCompany',
                'name' => 'Manufacturing Company',
                'link' => 'manufacturing-companies',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 51,
                'module_name' => 'Brand',
                'name' => 'Brands',
                'link' => 'brands',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 52,
                'module_name' => 'WareHouse',
                'name' => 'WareHouse',
                'link' => 'ware-houses',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 53,
                'module_name' => 'Locator',
                'name' => 'Locators',
                'link' => 'locators',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 54,
                'module_name' => 'ProductCategory',
                'name' => 'Product Categories',
                'link' => 'product-categories',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 55,
                'module_name' => 'ProductSubCategory',
                'name' => 'Product SubCategories',
                'link' => 'product-sub-categories',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 56,
                'module_name' => 'ProductType',
                'name' => 'Product Types',
                'link' => 'product-types',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-gear-wide-connected" viewBox="0 0 16 16">
                            <path d="M7.068.727c.243-.97 1.62-.97 1.864 0l.071.286a.96.96 0 0 0 1.622.434l.205-.211c.695-.719 1.888-.03 1.613.931l-.08.284a.96.96 0 0 0 1.187 1.187l.283-.081c.96-.275 1.65.918.931 1.613l-.211.205a.96.96 0 0 0 .434 1.622l.286.071c.97.243.97 1.62 0 1.864l-.286.071a.96.96 0 0 0-.434 1.622l.211.205c.719.695.03 1.888-.931 1.613l-.284-.08a.96.96 0 0 0-1.187 1.187l.081.283c.275.96-.918 1.65-1.613.931l-.205-.211a.96.96 0 0 0-1.622.434l-.071.286c-.243.97-1.62.97-1.864 0l-.071-.286a.96.96 0 0 0-1.622-.434l-.205.211c-.695.719-1.888.03-1.613-.931l.08-.284a.96.96 0 0 0-1.186-1.187l-.284.081c-.96.275-1.65-.918-.931-1.613l.211-.205a.96.96 0 0 0-.434-1.622l-.286-.071c-.97-.243-.97-1.62 0-1.864l.286-.071a.96.96 0 0 0 .434-1.622l-.211-.205c-.719-.695-.03-1.888.931-1.613l.284.08a.96.96 0 0 0 1.187-1.186l-.081-.284c-.275-.96.918-1.65 1.613-.931l.205.211a.96.96 0 0 0 1.622-.434zM12.973 8.5H8.25l-2.834 3.779A4.998 4.998 0 0 0 12.973 8.5m0-1a4.998 4.998 0 0 0-7.557-3.779l2.834 3.78zM5.048 3.967l-.087.065zm-.431.355A4.98 4.98 0 0 0 3.002 8c0 1.455.622 2.765 1.615 3.678L7.375 8zm.344 7.646.087.065z"/>
                            </svg>',
                'position' => 31,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,

                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 57,
                'module_name' => 'PurchaseOrder',
                'name' => 'Purchase Orders',
                'link' => 'purchase_orders',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 34,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 58,
                'module_name' => 'MaterialInout',
                'name' => 'Material Receipt',
                'link' => 'material_inout',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 34,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'deleteActivityRow' => 'json',
                            'fetchPoLines' => 'json',
                            'fetchPurchaseOrders' => 'json',
                            'getPurchaseOrderDate'=>'json',
                            'checkAvailableQuantity'=>'json',
                            
                        ],
                    ],
                ],
            ],
           
            [
                'module_id' => 59,
                'module_name' => 'PriceList',
                'name' => 'Price List',
                'link' => 'price-lists',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 34,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 60,
                'module_name' => 'InventoryMove',
                'name' => 'Inventory Move',
                'link' => 'inventory_move',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 34,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 61,
                'module_name' => 'InventoryConsumption',
                'name' => 'Inventory Consumption',
                'link' => 'inventory_consumptions',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 34,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 62,
                'module_name' => 'StockStorage',
                'name' => 'Stock Storage',
                'link' => 'stock-storages',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 2,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 63,
                'module_name' => 'PurchaseInvoice',
                'name' => 'Purchase Invoice',
                'link' => 'purchase_invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 2,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [
                    'read' => [
                        'is_read' => 1,
                        'methods' => [
                            'deleteActivityRow' => 'json',
                            'checkAvailableQuantity'=>'json',
                            
                        ],
                    ],
                ],
            ],
            [
                'module_id' => 64,
                'module_name' => 'PhysicalInventory',
                'name' => 'Physical Inventory',
                'link' => 'physical_inventory',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 2,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 65,
                'module_name' => 'ProductCosting',
                'name' => 'Product Costing',
                'link' => 'product-costings',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 2,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 66,
                'module_name' => 'Tax',
                'name' => 'Tax',
                'link' => 'taxes',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M256 0a256 256 0 1 1 0 512A256 256 0 1 1 256 0zM232 120V256c0 8 4 15.5 10.7 20l96 64c11 7.4 25.9 4.4 33.3-6.7s4.4-25.9-6.7-33.3L280 243.2V120c0-13.3-10.7-24-24-24s-24 10.7-24 24z"/></svg>',
                'position' => 2,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 67,
                'module_name' => 'Vendor',
                'name' => 'Vendor',
                'link' => 'vendors',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person" viewBox="0 0 18 18">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>',
                'position' => 3,
                'group_id' => null,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],
            [
                'module_id' => 68,
                'module_name' => 'M_MatchPo',
                'name' => 'M_MatchPo',
                'link' => 'm-match-pos',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person" viewBox="0 0 18 18">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>',
                'position' => 3,
                'group_id' => 12,
                'actor_id' => [$adminActor->id],
                'role_id' => [$adminRole->id],
                'is_report' => false,
                'use_default_permission_type' => true,
                'additional_permission_type' => [],
            ],

        ];

        $sidebargroups = [
            [
                'group_id' => 1,
                'name' => 'User & Roles',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
                'position' => '56',
            ],

            [
                'group_id' => 2,
                'name' => 'Business Agents',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-person" viewBox="0 0 18 18">
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>',
                'position' => '1',

            ],

            [
                'group_id' => 3,
                'name' => 'Vehicle Details',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                </svg>',
                'position' => '8',

            ],

            [
                'group_id' => 4,
                'name' => 'Trip Information',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-geo-alt" viewBox="0 0 16 16">
                <path d="M12.166 8.94c-.524 1.062-1.234 2.12-1.96 3.07A32 32 0 0 1 8 14.58a32 32 0 0 1-2.206-2.57c-.726-.95-1.436-2.008-1.96-3.07C3.304 7.867 3 6.862 3 6a5 5 0 0 1 10 0c0 .862-.305 1.867-.834 2.94M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10"/>
                <path d="M8 8a2 2 0 1 1 0-4 2 2 0 0 1 0 4m0 1a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                </svg>',
                'position' => '10',

            ],

            [
                'group_id' => 5,
                'name' => 'Payment',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-window-sidebar" viewBox="0 0 16 16">
                <path d="M2.5 4a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1m2-.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0m1 .5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1"/>
                <path d="M2 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2zm12 1a1 1 0 0 1 1 1v2H1V3a1 1 0 0 1 1-1zM1 13V6h4v8H2a1 1 0 0 1-1-1m5 1V6h9v7a1 1 0 0 1-1 1z"/>
                </svg>',
                'position' => '52',

            ],

            [
                'group_id' => 6,
                'name' => 'Ledgers',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-journals" viewBox="0 0 16 16">
                <path d="M5 0h8a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2 2 2 0 0 1-2 2H3a2 2 0 0 1-2-2h1a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1H1a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v9a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1H3a2 2 0 0 1 2-2"/>
                <path d="M1 6v-.5a.5.5 0 0 1 1 0V6h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V9h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 2.5v.5H.5a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1H2v-.5a.5.5 0 0 0-1 0"/>
                </svg>',
                'position' => '54',

            ],
            [
                'group_id' => 7,
                'name' => 'Accounts',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-calculator" viewBox="0 0 16 16">
                    <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                    <path d="M4 2.5a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.5.5h-7a.5.5 0 0 1-.5-.5zm0 4a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm3-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5zm0 3a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5z"/>
                </svg>',
                'position' => '30',

            ],
            [
                'group_id' => 8,
                'name' => 'Invoices',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                'position' => '55',

            ],
            [
                'group_id' => 9,
                'name' => 'Setups',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                'position' => '56',

            ],
            [
                'group_id' => 10,
                'name' => 'Rides',
                'icon' => '<svg width="22" height="22" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>',
                'position' => '2',

            ],
            [
                'group_id' => 11,
                'name' => 'Reports',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>',
                'position' => '150',

            ],
            [
                'group_id' => 12,
                'name' => 'Inventory',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-card-checklist" viewBox="0 0 16 16">
                    <path d="M14.5 3a.5.5 0 0 1 .5.5v9a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5zm-13-1A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2z"/>
                    <path d="M7 5.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 1 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0M7 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m-1.496-.854a.5.5 0 0 1 0 .708l-1.5 1.5a.5.5 0 0 1-.708 0l-.5-.5a.5.5 0 0 1 .708-.708l.146.147 1.146-1.147a.5.5 0 0 1 .708 0"/>
                    </svg>',
                'position' => '149',

            ],

        ];

        foreach ($sidebargroups as $group) {
            $group = SidebarGroups::updateOrCreate(
                [
                    'id' => $group['group_id'],
                ],
                [
                    'name' => $group['name'],
                    'icon' => $group['icon'],
                    'position' => $group['position'],
                ],
            );
        }

        foreach ($role_modules as $module) {
            $roleModule = RoleModule::updateOrCreate(
                [
                    'id' => $module['module_id'],
                ],
                [
                    'name' => $module['module_name'],
                    'is_report' => $module['is_report']?1:0,
                    'created_by' => $created_by,
                    'updated_by' => 1,
                ]
            );

            $defaultPermissionTypes = [
                'read' => [
                    'is_read' => 1,
                    'methods' => [
                        'index' => 'view',
                        'edit' => 'view',
                        'show' => 'view',
                        'api_index' => 'json',
                        'api_edit' => 'json',
                        'api_show' => 'json',
                    ],
                ],
                'create' => [
                    'is_read' => 0,
                    'methods' => [
                        'create' => 'view',
                        'store' => 'view',
                        'api_create' => 'json',
                        'api_store' => 'json',
                    ],
                ],
                'update' => [
                    'is_read' => 0,
                    'methods' => [
                        'edit' => 'view',
                        'update' => 'view',
                        'api_edit' => 'json',
                        'api_update' => 'json',
                    ],
                ],
                'delete' => [
                    'is_read' => 0,
                    'methods' => [
                        'destroy' => 'view',
                        'delete' => 'view',
                        'api_delete' => 'json',
                        'api_destroy' => 'json',
                    ],
                ],
                'import' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
                'export' => [
                    'is_read' => 0,
                    'methods' => [
                        // Excel exports; existing databases get this via the
                        // 2026_09_24_000001 register-rbac-web-actions migration.
                        'export' => 'view',
                    ],
                ],
                'print' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
                'global' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
            ];
            $reportPermissionTypes = [
                'read' => [
                    'is_read' => 1,
                    'methods' => [
                        'boot' => 'view',
                    ],
                ],
                'export' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
                'print' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
                'global' => [
                    'is_read' => 0,
                    'methods' => [],
                ],
            ];

            if($module['use_default_permission_type']){
                if($module['is_report']){
                    $PermissionTypes = self::array_deep_merge($reportPermissionTypes, $module['additional_permission_type']);
                }else{
                    $PermissionTypes = self::array_deep_merge($defaultPermissionTypes, $module['additional_permission_type']);
                }
            }else{
                $PermissionTypes = $module['additional_permission_type'];
            }
            // $PermissionTypes = array_merge($defaultPermissionTypes, $module['additional_permission_type']);

            foreach ($PermissionTypes as $action => $array) {
                $roleModuleStructure = RolePermissionType::updateOrCreate(
                    [
                        'role_module_id' => $roleModule->id,
                        'action' => $action,
                    ],
                    [
                        'is_read' => $array['is_read'],
                        'denial_msg' => 'You do not have ' . $action . ' access for ' . $roleModule->name,
                    ]
                );
                if ($array['is_read']) {
                    $sidebarItem = SidebarItems::updateOrCreate(
                        [
                            'link' => $module['link'],
                            'role_permission_type_id' => $roleModuleStructure->id,
                        ],
                        [
                            'sidebar_group_id' => $module['group_id'],
                            'link_name' => $module['name'],
                            'link_icon' => $module['icon'],
                            'link_position' => $module['position'],
                        ]
                    );
                }

                foreach ($array['methods'] as $method => $return_type) {
                    $rolePermissionTypeFunctions = RolePermissionTypeFunction::updateOrCreate([
                        'method' => $method,
                        'role_permission_type_id' => $roleModuleStructure->id,
                        'return_type' => $return_type,
                    ]);
                }
                foreach ($module['role_id'] as $role) {
                    // foreach ($roles as $role) {
                    //     $roleActor = $role[1];
                    //     $role = $role[0];
                    //     if ($actor == $roleActor) {
                            $rolePermission = RolePermission::updateOrCreate([
                                'role_id' => $role,
                                'role_module_id' => $roleModule->id,
                                'role_permission_type_id' => $roleModuleStructure->id,
                            ], [
                                'permission' => 1,
                            ]);
                    //     }
                    // }
                }

            }

            foreach ($module['actor_id'] as $actor) {
                $roleModuleActor = RoleModuleActors::firstOrCreate([
                    'actor_id' => $actor,
                    'role_module_id' => $roleModule->id,
                ]);
            }
            foreach ($module['role_id'] as $role) {
                $roleModuleActor = RoleHasModule::firstOrCreate([
                    'role_id' => $role,
                    'role_module_id' => $roleModule->id,
                ]);
            }
        }
    }
    public static function array_deep_merge(array $base, array $additional)
    {
        foreach ($additional as $key => $value) {
            if (is_array($value) && isset($base[$key]) && is_array($base[$key])) {
                $base[$key] = self::array_deep_merge($base[$key], $value);
            } else {
                $base[$key] = $value;
            }
        }
        return $base;
    }
}

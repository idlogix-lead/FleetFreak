

<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header" style = "  auth()->user()->theme == 'light-theme' ? 'background:#171717;' : 'background:black;' ">
        <div>
            @if (auth()->user()->theme == 'light-theme')
            {{-- <img src="/assets/images/new_logo-01.png" class="logo-icon ms-1" alt="logo icon"> --}}
                {{-- <img style="margin-top:12px; " src="/assets/images/new_logo-01.png" class="logo-icon" alt="logo icon"> --}}
                {{-- <img style="margin-top:12px; " src="/assets/images/navbar-fleetfreak-logo.png" class="logo-icon" alt="logo icon"> --}}
                <img style="margin-top:12px; " src="/assets/images/fleetfreak-logo-final-white-[Recovered].png" class="logo-icon" alt="logo icon">

            @else
                <img src="/assets/images/transport-dark-logo.png" class="logo-icon" alt="logo icon">
            @endif

        </div>
        <div>
            <h4 class="logo-text"><a href="{{url ('/')}}"><b></b></a></h4>
        </div>
        <div class="toggle-icon ms-auto text-light"><i class='bx bx-first-page me-4'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu nav-list" id="menu">
        <li>
            <a href="{{ url('/') }}" class="menu-title-color">
                <div class="parent-icon"><i class='bx bx-home'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
            {{-- <ul> --}}
                {{-- <li> <a href="{{ url('register') }}"><i class="bx bx-right-arrow-alt"></i>Create New Role</a> --}}

                {{-- <li> <a href="{{ url('/') }}"><i class="bx bx-right-arrow-alt"></i>Analytics</a>
                </li> --}}
                {{-- <li> <a href="{{ url('sales') }}"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                </li>
                <li> <a href="{{ url('ecommerce') }}"><i class="bx bx-right-arrow-alt"></i>eCommerce</a>
                </li>
                <li> <a href="{{ url('alternate') }}"><i class="bx bx-right-arrow-alt"></i>Alternate</a>
                </li>
                <li> <a href="{{ url('hospitality') }}"><i class="bx bx-right-arrow-alt"></i>Hospitality</a>
                </li> --}}
            {{-- </ul> --}}
        </li>




        @php

            // $Company = [
            //     [
            //         'name' => 'Products',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['products', 'products/*'],
            //         'route' => route('products.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>',
            //     ],

            //     [
            //         'name' => 'Sales',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['Sales', 'sales/*'],
            //         'route' => route('sales.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>',
            //     ],

            //     [
            //         'name' => 'Branch Details',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['Branch Details', 'branch_details/*'],
            //         'route' => route('branch_details.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path><line x1="3" y1="6" x2="21" y2="6"></line><path d="M16 10a4 4 0 0 1-8 0"></path></svg>',
            //     ],




            // ];


            // $Customers = [
            //     [
            //         'name' => 'Customers',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['customers', 'customers/*'],
            //         'route' => route('customers.index'),
            //         'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-check"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><polyline points="17 11 19 13 23 9"></polyline></svg>',
            //     ],
              //haris changes
                // [
                //     'name' => 'Machines',
                //     'permission' => true,
                //     'type' => 'link',
                //     'active_link' => ['machines', 'machines/*'],
                //     'route' => route('machines.index'),
                //     'icon' => '<svg  width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
                // ],
                // [
                //     'name' => 'complaints',
                //     'permission' => true,
                //     'type' => 'link',
                //     'active_link' => ['complaints', 'complaints/*'],
                //     'route' => route('complaints.index'),
                //     'icon' => '<svg  width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-hard-drive"><line x1="22" y1="12" x2="2" y2="12"></line><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path><line x1="6" y1="16" x2="6.01" y2="16"></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
                // ],
            // ];
            // $Service_Provider = [

            //     [
            //         'name' => 'Service Providers',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['Service_providers', 'Service_providers/*'],
            //         'route' => route('service_providers.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
            //     ],


            //     [
            //         'name' => 'Engineers',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['engineers', 'engineers/*'],
            //         'route' => route('engineers.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
            //     ],

            //     [
            //         'name' => 'Engineer Complaints',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['Engineer_Complaints', 'Engineer_Complaints/*'],
            //         'route' => route('engineer_complaints.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
            //     ],

            //     [
            //         'name' => 'Engineer Feedback',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['engineer_feedback', 'engineer_feedback/*'],
            //         'route' => route('engineer_feedback.index'),
            //         'icon' => '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-tool"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></line><line x1="10" y1="16" x2="10.01" y2="16"></line></svg>',
            //     ],
            // ];


            // $RoleModules = [
            //     [
            //         'name' => 'Role Modules',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['role_modules', 'role_modules/*'],
            //         'route' => route('role_modules.index'),
            //         'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //     ],

            //     [
            //         'name' => 'Role',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['role', 'roles/*'],
            //         'route' => route('roles.index'),
            //         'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //     ],




                // [
                //     'name' => 'Role Permission',
                //     'permission' => true,
                //     'type' => 'link',
                //     'active_link' => ['role_permissions', 'role_permissions/*'],
                //     'route' => route('role_permissions.index'),
                //     'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
                // ],
            // ];
            // $Routes = [
            //         [
            //         'name' => 'Receipt',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['order/receipts','order/receipt_details','order/receipt_details/*'],
            //         'route' => route('order.receipts'),
            //         'icon'   =>    '<svg xmlns= width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //     ],
            //         ];
//             $Packages = [
//                     [
//                     'name' => 'Package',
//                     'permission' => true,
//                     'type' => 'link',
//                     'active_link' => ['package', 'packages/*'],
//                     'route' => route('packages.index'),
//                     'icon'   =>    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-box" viewBox="0 0 16 16">
//   <path d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5 8 5.961 14.154 3.5zM15 4.239l-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464z"/>
// </svg>',
//                 ],
//                     ];
            // $Users = [
            //             [
            //                 'name' => 'User',
            //                 'permission' => true,
            //                 'type' => 'link',
            //                 'active_link' => ['user', 'users/*'],
            //                 'route' => route('users.index'),
            //                 'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //             ],
            //         ];
            // $Resources = [
            //             [
            //         'name' => 'Vehicle',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['vehicle', 'vehicles/*'],
            //         'route' => route('vehicles.index'),
            //         'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //             ],

            //             [
            //         'name' => 'Partner',
            //         'permission' => true,
            //         'type' => 'link',
            //         'active_link' => ['partner', 'partners/*'],
            //         'route' => route('partners.index'),
            //         'icon'   =>    '<svg xmlns= width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></line><polyline points="10 9 9 9 8 9"></polyline></path></svg>',
            //             ],
            //         ];
            // $RequestDetails = [
            //     [
            //         "name"=>"Request Info",
            //         "type"=>"dropdown",
            //         "id"=>"request_types",
            //         "active_link"=>['request_categories','request_categories/','request_types','request_types/'],
            //         "icon"=>'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-info link-icon"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
            //         "list"=>[
            //             [
            //                 'name' => 'Request Category',
            //                 'permission'=> auth()->user()->urole_from_session(App\Http\Controllers\RequestCategoryController::$role_name_id)->read,
            //                 'route' => route('request_categories.index'),
            //                 'active_link' => ['request_categories','request_categories/*']
            //             ],
            //             [
            //                 'name' => 'Request Items',
            //                 'permission'=> auth()->user()->urole_from_session(App\Http\Controllers\RequestTypeController::$role_name_id)->read,
            //                 'route' => route('request_types.index'),
            //                 'active_link' => ['request_types','request_types/*']
            //             ]
            //         ],
            //     ]
            // ];

            $permissions = auth()->user()->get_user_role_session_permissions();
            $items = $permissions
            ->where('permission',1)
            ->pluck('role_permission_type.sidebar')
            ->filter(function ($item) {
                return count($item) > 0;
            })
            ->flatMap(function ($item) {
                return $item instanceof \Illuminate\Support\Collection ? $item->toArray() : $item;
            })
            ->groupBy('sidebar_group_id') // Group items by `sidebar_group_id`
            ->map(function ($items, $groupId) {
                // Map each group into the custom structure
                if($groupId){
                    $group = $items[0]['group'];
                    return [
                        'name' => $group['name'], // Custom label for group
                        'type' => 'dropdown',
                        'icon' => $group['icon'], // Custom column value
                        'link_position' => $group['position'], // Custom column value
                        'list' => $items->map(function ($item) {
                            return [
                                'name' => $item['link_name'],
                                'permission' => true,
                                'type' => 'link',
                                'active_link' => [$item['link'], $item['link'].'/*'],
                                'link_position'=>$item['link_position'],
                                'route' => url($item['link']),
                                'icon'   =>  $item['link_icon'],
                            ];
                        })
                        ->values()
                        ->toArray(), // Use `values()` to reset array keys
                    ];


                }else{
                    return $items->map(function ($item) {
                        return [
                            'name'  =>   $item['link_name'],
                            'permission'    =>  true,
                            'type'  =>  'link',
                            'active_link' => [$item['link'], $item['link'].'/*'],
                            'link_position' => $item['link_position'],
                            'route' => url($item['link']),
                            'icon'  =>  $item['link_icon'],
                        ];
                    });
                }

            })
            ->flatMap(function ($item) {
                if(isset($item['type']) && $item['type']){
                    return [$item];
                }else{
                    return $item->toArray();
                }
            })
            ->values()
            ->toArray();
            // $items = [];
            // $groups = [];
            // $data = $permissions->pluck('role_permission_type.sidebar')->filter(function ($item) {
            //     return !empty($item); // Filter out empty arrays
            // })->toArray();
            // dd($data);

            // foreach($permissions->whereNotNull('role_permission_type.sidebar')->sortBy('role_permission_type.sidebar.link_position') as $permission){
            //     $structures = $permission->role_permission_type->sidebar;
            //     // foreach($structures as $group){
            //     //     dd($structures->toArray());

            //     // }
            //     foreach($structures as $structure){

            //         if(isset($structure->link) && $structure->link){
            //             // group check if()//

            //             // $items[] = [
            //             //     'name' => "Group Name",
            //             //     'type' => 'dropdown',
            //             //     'active_link' => [$structure->link, $structure->link.'/*'],
            //             //     'icon'   =>  $structure->link_icon,
            //             //     'id' => 1,
            //             //     'list'=> [
            //             //         [
            //             //             'name' => $structure->link_name,
            //             //             'active_link' => [$structure->link, $structure->link.'/*'],
            //             //             'permission' => $permission->permission,
            //             //             'link_position'=>$structure->link_position,
            //             //             'route' => url($structure->link),
            //             //         ]
            //             //     ]
            //             // ];

            //             $items[] = [
            //                 'name' => $structure->link_name,
            //                 'permission' => $permission->permission,
            //                 'type' => 'link',
            //                 'active_link' => [$structure->link, $structure->link.'/*'],
            //                 'link_position'=>$structure->link_position,
            //                 'route' => url($structure->link),
            //                 'icon'   =>  $structure->link_icon,
            //             ];
            //         }
            //     }
            //     // break;
            // }
        @endphp
        {{-- haris changes --}}
        {{-- <li class="menu-label">Company</li>
        @include('layouts.partials.menu', ['menu' =>  $Company]) --}}
        {{-- <li class="menu-label">Customers-Complaints</li> --}}
        {{-- @include('layouts.partials.menu', ['menu' => $Customers]) --}}

        {{-- <li class="menu-label">Service Provider</li>
        @include('layouts.partials.menu', ['menu' => $Service_Provider]) --}}
        {{-- <li class="menu-label">User & Roles</li>
        @include('layouts.partials.menu', ['menu' => $RoleModules])

        <li class="menu-label">Users</li>
        @include('layouts.partials.menu', ['menu' => $Users]) --}}
        {{-- @foreach($items as $name => $item) --}}
            {{-- <li class="menu-label">{{$name}}</li> --}}
            @include('layouts.partials.menu', ['menu' => $items])
        {{-- @endforeach --}}
        {{-- <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Select the ul element
                const ul = document.getElementById('nav-list');

                // Get a NodeList of li elements and convert it to an array
                const itemsArray = Array.from(ul.querySelectorAll('li.nav-item'));

                // Sort the array based on the position attribute
                itemsArray.sort((a, b) => {
                    return parseInt(a.getAttribute('position')) - parseInt(b.getAttribute('position'));
                });

                // Append the sorted items back to the ul
                itemsArray.forEach(item => ul.appendChild(item));
            });
        </script> --}}

        {{-- rough --}}
        {{-- <li class="menu-label">Routes</li> --}}
        {{-- @include('layouts.partials.menu', ['menu' => $Routes]) --}}
        {{-- <li class="menu-label">Packages</li>
        @include('layouts.partials.menu', ['menu' => $Packages]) --}}












        {{-- @include('layouts.partials.menu', ['menu' => $Customers]) --}}

        {{-- @include('layouts.partials.menu') --}}
        {{-- <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-home'></i>
                        </div>
                        <div class="menu-title">Dashboard</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('signup') }}"><i class="bx bx-right-arrow-alt"></i>Create New Role</a>

                        <li> <a href="{{ url('index') }}"><i class="bx bx-right-arrow-alt"></i>Analytics</a>
                        </li>
                        <li> <a href="{{ url('sales') }}"><i class="bx bx-right-arrow-alt"></i>Sales</a>
                        </li>
                        <li> <a href="{{ url('ecommerce') }}"><i class="bx bx-right-arrow-alt"></i>eCommerce</a>
                        </li>
                        <li> <a href="{{ url('alternate') }}"><i class="bx bx-right-arrow-alt"></i>Alternate</a>
                        </li>
                        <li> <a href="{{ url('hospitality') }}"><i class="bx bx-right-arrow-alt"></i>Hospitality</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-spa' ></i>
                        </div>
                        <div class="menu-title">Service Provider</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('roleModule-form') }}"><i class="bx bx-right-arrow-alt"></i>Role-Module</a>

                        </li>

                        <li> <a href="{{ url('role-Module-table') }}"><i class="bx bx-right-arrow-alt"></i> Table Role-Modules</a>

                        </li>

                        <li> <a href="{{ url('role-form') }}"><i class="bx bx-right-arrow-alt"></i>Role</a>

                        </li>

                        <li> <a href="{{ url('role-data-table') }}"><i class="bx bx-right-arrow-alt"></i> Role-Table</a>

                        </li>
                        <li> <a href="{{ url('engineer-form') }}"><i class="bx bx-right-arrow-alt"></i>Engineer</a>

                        </li>
                        <li> <a href="{{ url('new-complains') }}"><i class="bx bx-right-arrow-alt"></i>New Complains</a>
                        </li>
                        <li> <a href="{{ url('app-file-manager') }}"><i class="bx bx-right-arrow-alt"></i>Complains Status</a>
                        </li>
                        <li> <a href="{{ url('app-contact-list') }}"><i class="bx bx-right-arrow-alt"></i>Engineer Feedback</a>
                        </li>

                    </ul>
                </li> --}}
        {{-- <li> <a href="{{ url('app-to-do') }}"><i class="bx bx-right-arrow-alt"></i>Todo List</a>
                        </li>
                        <li> <a href="{{ url('app-invoice') }}"><i class="bx bx-right-arrow-alt"></i>Invoice</a>
                        </li>
                        <li> <a href="{{ url('app-fullcalender') }}"><i class="bx bx-right-arrow-alt"></i>Calendar</a>
                        </li> --}}
        {{-- <li class="menu-label">UI Elements</li>
                <li>
                    <a href="{{ url('widgets') }}">
                        <div class="parent-icon"><i class='bx bx-briefcase-alt-2'></i>
                        </div>
                        <div class="menu-title">Widgets</div>
                    </a>
                </li>
                <li>
                    <a href="javascript:;" class="has-arrow">
                        <div class="parent-icon"><i class='bx bx-cart-alt' ></i>
                        </div>
                        <div class="menu-title">Customer Complain</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('user-complaints-form') }}"><i class="bx bx-right-arrow-alt"></i>Customer Details</a>
                        </li>
                        <li> <a href="{{ url('machine-details-form') }}"><i class="bx bx-right-arrow-alt"></i>Machine Details</a>
                        </li>
                        <li> <a href="{{ url('complaint-details-form') }}"><i class="bx bx-right-arrow-alt"></i>Complain Details</a>
                        </li>
                        <li> <a href="{{ url('ecommerce-orders') }}"><i class="bx bx-right-arrow-alt"></i>Orders</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-gift'></i>
                        </div>
                        <div class="menu-title">Components</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('component-alerts') }}"><i class="bx bx-right-arrow-alt"></i>Alerts</a>
                        </li>
                        <li> <a href="{{ url('component-accordions') }}"><i class="bx bx-right-arrow-alt"></i>Accordions</a>
                        </li>
                        <li> <a href="{{ url('component-badges') }}"><i class="bx bx-right-arrow-alt"></i>Badges</a>
                        </li>
                        <li> <a href="{{ url('component-buttons') }}"><i class="bx bx-right-arrow-alt"></i>Buttons</a>
                        </li>
                        <li> <a href="{{ url('component-cards') }}"><i class="bx bx-right-arrow-alt"></i>Cards</a>
                        </li>
                        <li> <a href="{{ url('component-carousels') }}"><i class="bx bx-right-arrow-alt"></i>Carousels</a>
                        </li>
                        <li> <a href="{{ url('component-list-groups') }}"><i class="bx bx-right-arrow-alt"></i>List Groups</a>
                        </li>
                        <li> <a href="{{ url('component-media-object') }}"><i class="bx bx-right-arrow-alt"></i>Media Objects</a>
                        </li>
                        <li> <a href="{{ url('component-modals') }}"><i class="bx bx-right-arrow-alt"></i>Modals</a>
                        </li>
                        <li> <a href="{{ url('component-navs-tabs') }}"><i class="bx bx-right-arrow-alt"></i>Navs & Tabs</a>
                        </li>
                        <li> <a href="{{ url('component-navbar') }}"><i class="bx bx-right-arrow-alt"></i>Navbar</a>
                        </li>
                        <li> <a href="{{ url('component-paginations') }}"><i class="bx bx-right-arrow-alt"></i>Pagination</a>
                        </li>
                        <li> <a href="{{ url('component-popovers-tooltips') }}"><i class="bx bx-right-arrow-alt"></i>Popovers & Tooltips</a>
                        </li>
                        <li> <a href="{{ url('component-progress-bars') }}"><i class="bx bx-right-arrow-alt"></i>Progress</a>
                        </li>
                        <li> <a href="{{ url('component-spinners') }}"><i class="bx bx-right-arrow-alt"></i>Spinners</a>
                        </li>
                        <li> <a href="{{ url('component-notifications') }}"><i class="bx bx-right-arrow-alt"></i>Notifications</a>
                        </li>
                        <li> <a href="{{ url('component-avtars-chips') }}"><i class="bx bx-right-arrow-alt"></i>Avatrs & Chips</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-command' ></i>
                        </div>
                        <div class="menu-title">Content</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('content-grid-system') }}"><i class="bx bx-right-arrow-alt"></i>Grid System</a>
                        </li>
                        <li> <a href="{{ url('content-typography') }}"><i class="bx bx-right-arrow-alt"></i>Typography</a>
                        </li>
                        <li> <a href="{{ url('content-text-utilities') }}"><i class="bx bx-right-arrow-alt"></i>Text Utilities</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"> <i class='bx bx-atom'></i>
                        </div>
                        <div class="menu-title">Icons</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('icons-line-icons') }}"><i class="bx bx-right-arrow-alt"></i>Line Icons</a>
                        </li>
                        <li> <a href="{{ url('icons-boxicons') }}"><i class="bx bx-right-arrow-alt"></i>Boxicons</a>
                        </li>
                        <li> <a href="{{ url('icons-feather-icons') }}"><i class="bx bx-right-arrow-alt"></i>Feather Icons</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">Forms & Tables</li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-hourglass' ></i>
                        </div>
                        <div class="menu-title">Forms</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('user-complaints-form') }}"><i class="bx bx-right-arrow-alt"></i>Complaints form</a>
                        </li>
                        <li> <a href="{{ url('machine-details-form') }}"><i class="bx bx-right-arrow-alt"></i>Machine Details</a>
                        </li>
                        <li> <a href="{{ url('complaints-details') }}"><i class="bx bx-right-arrow-alt"></i>Complaints Details</a>
                        </li>

                        <li> <a href="{{ url('engineer-deatils') }}"><i class="bx bx-right-arrow-alt"></i>Engineer Details</a>
                        </li>
                        <li> <a href="{{ url('engineer-feedback') }}"><i class="bx bx-right-arrow-alt"></i>Engineer feedback</a>
                        </li>
                        <li> <a href="{{ url('form-layouts') }}"><i class="bx bx-right-arrow-alt"></i>Forms Layouts</a>
                        </li>
                        <li> <a href="{{ url('form-validations') }}"><i class="bx bx-right-arrow-alt"></i>Form Validation</a>
                        </li>
                        <li> <a href="{{ url('form-wizard') }}"><i class="bx bx-right-arrow-alt"></i>Form Wizard</a>
                        </li>
                        <li> <a href="{{ url('form-text-editor') }}"><i class="bx bx-right-arrow-alt"></i>Text Editor</a>
                        </li>
                        <li> <a href="{{ url('form-file-upload') }}"><i class="bx bx-right-arrow-alt"></i>File Upload</a>
                        </li>
                        <li> <a href="{{ url('form-date-time-pickes') }}"><i class="bx bx-right-arrow-alt"></i>Date Pickers</a>
                        </li>
                        <li> <a href="{{ url('form-select2') }}"><i class="bx bx-right-arrow-alt"></i>Select2</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-grid-alt"></i>
                        </div>
                        <div class="menu-title">Tables</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('table-basic-table') }}"><i class="bx bx-right-arrow-alt"></i>Basic Table</a>
                        </li>
                        <li> <a href="{{ url('table-datatable') }}"><i class="bx bx-right-arrow-alt"></i>Data Table</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">Pages</li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-lock-open-alt'></i>
                        </div>
                        <div class="menu-title">Authentication</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('authentication-signin') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign In</a>
                        </li>
                        <li> <a href="{{ url('authentication-signup') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign Up</a>
                        </li>
                        <li> <a href="{{ url('authentication-signin-with-header-footer') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign In with Header & Footer</a>
                        </li>
                        <li> <a href="{{ url('authentication-signup-with-header-footer') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Sign Up with Header & Footer</a>
                        </li>
                        <li> <a href="{{ url('authentication-forgot-password') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Forgot Password</a>
                        </li>
                        <li> <a href="{{ url('authentication-reset-password') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Reset Password</a>
                        </li>
                        <li> <a href="{{ url('authentication-lock-screen') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Lock Screen</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('user-profile') }}">
                        <div class="parent-icon"><i class='bx bx-user-pin' ></i>
                        </div>
                        <div class="menu-title">User Profile</div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('timeline') }}">
                        <div class="parent-icon"> <i class="bx bx-video-recording"></i>
                        </div>
                        <div class="menu-title">Timeline</div>
                    </a>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-error"></i>
                        </div>
                        <div class="menu-title">Errors</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('errors-404-error') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>404 Error</a>
                        </li>
                        <li> <a href="{{ url('errors-500-error') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>500 Error</a>
                        </li>
                        <li> <a href="{{ url('errors-coming-soon') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Coming Soon</a>
                        </li>
                        <li> <a href="{{ url('error-blank-page') }}" target="_blank"><i class="bx bx-right-arrow-alt"></i>Blank Page</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('faq') }}">
                        <div class="parent-icon"><i class="bx bx-help-circle"></i>
                        </div>
                        <div class="menu-title">FAQ</div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('pricing-table') }}">
                        <div class="parent-icon"><i class='bx bx-dollar-circle'></i>
                        </div>
                        <div class="menu-title">Pricing</div>
                    </a>
                </li>
                <li class="menu-label">Charts & Maps</li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-line-chart"></i>
                        </div>
                        <div class="menu-title">Charts</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('charts-apex-chart') }}"><i class="bx bx-right-arrow-alt"></i>Apex</a>
                        </li>
                        <li> <a href="{{ url('charts-chartjs') }}"><i class="bx bx-right-arrow-alt"></i>Chartjs</a>
                        </li>
                        <li> <a href="{{ url('charts-highcharts') }}"><i class="bx bx-right-arrow-alt"></i>Highcharts</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class='bx bx-map-pin' ></i>
                        </div>
                        <div class="menu-title">Maps</div>
                    </a>
                    <ul>
                        <li> <a href="{{ url('map-google-maps') }}"><i class="bx bx-right-arrow-alt"></i>Google Maps</a>
                        </li>
                        <li> <a href="{{ url('map-vector-maps') }}"><i class="bx bx-right-arrow-alt"></i>Vector Maps</a>
                        </li>
                    </ul>
                </li>
                <li class="menu-label">Others</li>
                <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-menu"></i>
                        </div>
                        <div class="menu-title">Menu Levels</div>
                    </a>
                    <ul>
                        <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level One</a>
                            <ul>
                                <li> <a class="has-arrow" href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Two</a>
                                    <ul>
                                        <li> <a href="javascript:;"><i class="bx bx-right-arrow-alt"></i>Level Three</a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ url('../../documentation/index.html') }}" target="_blank">
                        <div class="parent-icon"><i class="bx bx-folder"></i>
                        </div>
                        <div class="menu-title">Documentation</div>
                    </a>
                </li>
                <li>
                    <a href="{{ url('https://themeforest.net/user/codervent') }}" target="_blank">
                        <div class="parent-icon"><i class='bx bx-headphone' ></i>
                        </div>
                        <div class="menu-title">Support</div>
                    </a>
                </li> --}}
    </ul>
    <script>
        // JavaScript code to reorder the list items
        $(document).ready(function(){
            var $logoIcon = $('.logo-icon');
            var $toggleIcon = $('.toggle-icon');

            var originalLightSrc = '/assets/images/transport-dark-logo.png';
            var originalDarkSrc = '/assets/images/Zaroon_Logo-night.png';
            var toggleLogo = '/assets/images/transport-logo.png'; // This is the image you want to toggle to for light theme
            // This is the image you want to toggle to for dark theme
            var isLightTheme = @json(auth()->user()->theme == 'light-theme');
            var isToggled = false;

            $toggleIcon.on('click', function() {
                if (isLightTheme) {
                    if (isToggled) {
                        $logoIcon.attr('src', originalLightSrc);
                    } else {
                        $logoIcon.attr('src', toggleLogo);
                    }
                } else {
                    if (isToggled) {
                        $logoIcon.attr('src', originalDarkSrc);
                    } else {
                        $logoIcon.attr('src', toggleLogo);
                    }
                }
                isToggled = !isToggled;
            });
            const ul = $('.nav-list');
            // const ul = document.getElementById('nav-list');
            // console.log(ul);
            const itemsArray = Array.from(document.querySelectorAll('.nav-list > li.nav-item'));
            // console.log(itemsArray);

            itemsArray.sort((a, b) => {
                return parseInt(a.getAttribute('position')) - parseInt(b.getAttribute('position'));
            });

            // itemsArray.forEach(item => ul.appendChild(item));
            itemsArray.forEach(item => ul.append(item));

        })
    </script>
    <!--end navigation-->
</div>

@php
    use Carbon\Carbon;
@endphp

<head>
    <link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">
</head>

<header>

    <div class="topbar d-flex align-items-center"id='1'>
        {{-- <nav class="navbar navbar-expand bg-primary"> --}}
        {{-- <nav class="navbar navbar-expand-lg navbar-light bg-light"> --}}
        {{-- commented code starts here --}}
        {{-- <nav class="navbar navbar-expand-lg"> --}}
        <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
        </div>
        {{-- <div class="wrap me-auto">
                <a style="" href="{{ route('orders.create') }}" class="btn-attractive">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-plus-circle" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                        <path
                            d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
                    </svg>
                    Book Your Ride
                </a>
            </div> --}}
        {{-- @if (auth()->user()->actor_id == 2) --}}

        {{-- <div class="wrap">
            <a style="margin-left: 15px; background-color:#233abc;" href="{{ route('vehicle_loc') }}" class="btn-attractive">

            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                Vehicle Loc
            </a>
        </div> --}}
        {{-- @endif --}}

        {{-- commented code ends here --}}
        {{-- <div class="top-menu-left d-none d-lg-block">
                        <ul class="nav">
                              <li class="nav-item">
                                <a class="nav-link" href="{{ url('app-emailbox') }}"><i class='bx bx-envelope'></i></a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{url('app-chat-box')}}"><i class='bx bx-message'></i></a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="{{url('app-fullcalender')}}"><i class='bx bx-calendar'></i></a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="{{url('app-to-do')}}"><i class='bx bx-check-square'></i></a>
                              </li>
                          </ul>
                     </div> --}}
        {{-- <div class="search-bar flex-grow-1">
                        <div class="position-relative search-bar-box">
                            <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                            <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                        </div>
                    </div> --}}
        {{-- <div class="top-menu ms-auto">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item mobile-search-icon">
                                <a class="nav-link" href="#">   <i class='bx bx-search'></i>
                                </a>
                            </li> --}}
        {{-- <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <i class='bx bx-category'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <div class="row row-cols-3 g-3 p-3">
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
                                            </div>
                                            <div class="app-title">Teams</div>
                                        </div>
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
                                            </div>
                                            <div class="app-title">Projects</div>
                                        </div>
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
                                            </div>
                                            <div class="app-title">Tasks</div>
                                        </div>
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
                                            </div>
                                            <div class="app-title">Feeds</div>
                                        </div>
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
                                            </div>
                                            <div class="app-title">Files</div>
                                        </div>
                                        <div class="col text-center">
                                            <div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
                                            </div>
                                            <div class="app-title">Alerts</div>
                                        </div>
                                    </div>
                                </div>
                            </li> --}}
        {{-- <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
                                    <i class='bx bx-bell'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Notifications</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-notifications-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                                                ago</span></h6>
                                                    <p class="msg-info">5 new user registered</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
                                                ago</span></h6>
                                                    <p class="msg-info">You have recived new orders</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
                                                ago</span></h6>
                                                    <p class="msg-info">The pdf files generated</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
                                                ago</span></h6>
                                                    <p class="msg-info">5.1 min avarage time response</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Product Approved <span
                                                class="msg-time float-end">2 hrs ago</span></h6>
                                                    <p class="msg-info">Your new product has approved</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">New customer comments recived</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">Successfully shipped your item</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
                                                ago</span></h6>
                                                    <p class="msg-info">24 new authors joined last week</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
                                                ago</span></h6>
                                                    <p class="msg-info">45% less alerts last 4 weeks</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Notifications</div>
                                    </a>
                                </div>
                            </li> --}}
        {{-- <li class="nav-item dropdown dropdown-large">
                                <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
                                    <i class='bx bx-comment'></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="javascript:;">
                                        <div class="msg-header">
                                            <p class="msg-header-title">Messages</p>
                                            <p class="msg-header-clear ms-auto">Marks all as read</p>
                                        </div>
                                    </a>
                                    <div class="header-message-list">
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
                                                ago</span></h6>
                                                    <p class="msg-info">The standard chunk of lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
                                                sec ago</span></h6>
                                                    <p class="msg-info">Many desktop publishing packages</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
                                                ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
                                                min ago</span></h6>
                                                    <p class="msg-info">Making this the first true generator</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
                                                ago</span></h6>
                                                    <p class="msg-info">Duis aute irure dolor in reprehenderit</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">The passage is attributed to an unknown</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">The point of using Lorem</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">It was popularised in the 1960s</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
                                                ago</span></h6>
                                                    <p class="msg-info">Various versions have evolved over</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
                                                ago</span></h6>
                                                    <p class="msg-info">If you are going to use a passage</p>
                                                </div>
                                            </div>
                                        </a>
                                        <a class="dropdown-item" href="javascript:;">
                                            <div class="d-flex align-items-center">
                                                <div class="user-online">
                                                    <img src="/assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
                                                ago</span></h6>
                                                    <p class="msg-info">All the Lorem Ipsum generators</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    <a href="javascript:;">
                                        <div class="text-center msg-footer">View All Messages</div>
                                    </a>
                                </div>
                            </li> --}}
        {{-- </ul>
                    </div> --}}







        <div class="user-box dropdown border-light-2 test" style="">
            @if (auth()->user()->actor_id == 2)
                {{-- for unapproved agents --}}
                <li style="margin-right: -18px;" class="nav-item dropdown dropdown-large">
                    <a title="Unapproved Agents"
                        class="agent-click nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        href="{{ route('unapproved_agents.index') }}" role="button">
                        <span id="notificationCount" class=" span-val alert-count mx-1">
                            {{ App\Models\Partner::unapprovedAgentsCount() }}
                        </span>

                        <i class='bx bx-user-circle header-icons-style'></i>

                    </a>
                    {{-- for vehicle loc agents --}}
                    <li style="margin-right: -18px;" class="nav-item dropdown dropdown-large">
                            <a title="Vehicle Locations" class="agent-click nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                            href="{{ route('vehicle_loc') }}" role="button">


                            <i class='bx bx-map header-icons-style'></i>

                            </a>
            @endif
            @if (auth()->user()->actor_id == 2 || auth()->user()->actor_id == 4)
                {{-- for notification --}}
                <li style="margin-right: -18px;" class="nav-item dropdown dropdown-large">
                    <a class="bell-click nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        href="#" role="button" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas"
                        aria-controls="notificationOffcanvas">
                        {{-- <span id="notificationCount" class=" span-val alert-count mx-1">
                                @if (auth()->user()->actor_id == 2)
                                    {{ App\Models\Notification::unread_count() }}
                                @else --}}
                        {{-- for agent --}}
                        {{-- {{ App\Models\Notification::agent_unread_count() }}
                                @endif
                            </span> --}}

                        <i class='bx bx-bell header-icons-style'></i>
                        <!-- Blinking dot -->
                        <span class="notification-dot"></span>

                    </a>
                <li style="margin-right: -18px;" class="nav-item dropdown dropdown-large">
                    <a title="Create Ride" class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                        href="{{ route('orders.create') }}" role="button">


                        <i class='bx bx-plus-circle header-icons-style'></i>

                    </a>
            @endif
            <!-- Offcanvas Component -->
            <div style="background-color:{{ auth()->user()->theme == 'dark-theme' ? '#343a40' : '' }}"
                class="offcanvas offcanvas-end" tabindex="-1" id="notificationOffcanvas"
                aria-labelledby="notificationOffcanvasLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="notificationOffcanvasLabel">Notifications</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <div class="btn-group mb-3" role="group" aria-label="Message Type">
                        <button type="button" class="btn btn-sm btn-primary btn-custom btn-primary-custom"
                            id="showUnreadMessages">Unread Messages</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-custom btn-secondary-custom"
                            id="showAllMessages">All Messages</button>
                    </div>
                    <div class="messages-section" id="unreadMessagesSection">
                        <div class="header-notifications-list ">
                            {{-- for admin --}}
                            @if (auth()->user()->actor_id == 2)
                                @php
                                    $unread_notifications = App\Models\Notification::admin_unread_notifications();
                                @endphp

                                @foreach ($unread_notifications as $unread_notification)
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary text-primary"><i
                                                    class="bx bx-group"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">{{ $unread_notification->receiver->name }}<span
                                                        class="msg-time float-end">{{ $unread_notification->created_at->diffForHumans() }}</span>
                                                </h6>
                                                <p class="msg-info wrap-text">{{ $unread_notification->detail }}</p>
                                            </div>

                                        </div>
                                    </a>
                                @endforeach
                                {{-- ------ --}}
                            @else
                                {{-- for agent --}}
                                @php
                                    $unread_notifications = App\Models\Notification::agent_unread_notifications();
                                @endphp

                                @foreach ($unread_notifications as $unread_notification)
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary text-primary"><i
                                                    class="bx bx-group"></i>
                                            </div>

                                            <div class="flex-grow-1">

                                                <h6 class="msg-name">{{ $unread_notification->receiver->name }}<span
                                                        class="msg-time float-end">{{ $unread_notification->created_at->diffForHumans() }}</span>
                                                </h6>
                                                <p class="msg-info wrap-text">{{ $unread_notification->detail }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="messages-section d-none" id="allMessagesSection">
                        <div class="header-notifications-list ">
                            {{-- for admin --}}
                            @if (auth()->user()->actor_id == 2)
                                @php
                                    $all_notifications = App\Models\Notification::admin_all_notifications();
                                @endphp

                                @foreach ($all_notifications as $all_notification)
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary text-primary"><i
                                                    class="bx bx-group"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="msg-name">
                                                    {{ $all_notification->receiver->name ?? null }}<span
                                                        class="msg-time float-end">{{ $all_notification->created_at->diffForHumans() }}</span>
                                                </h6>
                                                <p class="msg-info wrap-text">{{ $all_notification->detail }}</p>
                                            </div>

                                        </div>
                                    </a>
                                @endforeach
                                {{-- ------ --}}
                            @else
                                {{-- for agent --}}
                                @php
                                    $all_notifications = App\Models\Notification::agent_all_notifications();
                                @endphp


                                @foreach ($all_notifications as $all_notification)
                                    <a class="dropdown-item" href="javascript:;">
                                        <div class="d-flex align-items-center">
                                            <div class="notify bg-light-primary text-primary"><i
                                                    class="bx bx-group"></i>
                                            </div>

                                            <div class="flex-grow-1">

                                                <h6 class="msg-name">{{ $all_notification->receiver->name }}<span
                                                        class="msg-time float-end">{{ $all_notification->created_at->diffForHumans() }}</span>
                                                </h6>
                                                <p class="msg-info wrap-text">{{ $all_notification->detail }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            </li>


            {{-- <a href="#"  class="float-right me-2"  >
                                  <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bell-fill" viewBox="0 0 16 16">

                                    <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2m.995-14.901a1 1 0 1 0-1.99 0A5 5 0 0 0 3 6c0 1.098-.5 6-2 7h14c-1.5-1-2-5.902-2-7 0-2.42-1.72-4.44-4.005-4.901"/>
                                  </svg>
                    </a> --}}


            {{-- @if (auth()->user()->actor_id == 2 || auth()->user()->actor_id == 4) --}}

            {{-- <a  href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
              <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
        </a> --}}

            {{-- @endif --}}
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                <li><a class="dropdown-item" href="{{ route('orders.create') }}">Create order</a></li>
                {{-- <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li> --}}
            </ul>



            {{--
                            <a href="{{ route('orders.create') }}"   class="float-right"  data-placement="left">
            {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0,0,256,256" width="80px" height="80px"><g fill-opacity="0.70196" fill="#0000ff" fill-rule="nonzero" stroke="none" stroke-width="1" stroke-linecap="butt" stroke-linejoin="miter" stroke-miterlimit="10" stroke-dasharray="" stroke-dashoffset="0" font-family="none" font-weight="none" font-size="none" text-anchor="none" style="mix-blend-mode: normal"><g transform="scale(3.2,3.2)"><path d="M45,9.79297l-36,6.82031v46.77344l36,6.82031v-7.20703h24v-46h-24zM43,12.20703v55.58594l-32,-6.0625v-43.46094zM40,15c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,19c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM45,19h22v42h-22v-8h4v-2h-4v-6h4v-2h-4v-6h4v-2h-4v-6h4v-2h-4zM40,23c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,27c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,27v2h11v-2zM17.45313,31l5.26953,9.03516l-5.75781,9.03125h4.84766l3.125,-5.83203c0.21875,-0.5625 0.35938,-0.98437 0.41797,-1.26172h0.05078c0.125,0.58984 0.24609,0.99609 0.36328,1.21484l3.11328,5.87891h4.82422l-5.56641,-9.10937l5.41797,-8.95703h-4.53906l-2.875,5.36719c-0.27344,0.69531 -0.46484,1.22266 -0.56641,1.57422h-0.04687c-0.16016,-0.58594 -0.33984,-1.09375 -0.54297,-1.52344l-2.58203,-5.41797zM40,31c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,35c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,35v2h11v-2zM40,39c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,43c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,43v2h11v-2zM40,47c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,51c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM51,51v2h11v-2zM40,55c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,59c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1zM40,63c-0.55078,0 -1,0.44922 -1,1c0,0.55078 0.44922,1 1,1c0.55078,0 1,-0.44922 1,-1c0,-0.55078 -0.44922,-1 -1,-1z"></path></g></g></svg> --}}
            {{-- <svg xmlns="" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg> --}}

            {{-- <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/>
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                </svg>
         </a> --}}







            <span class="">
                <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                    role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    @auth
                        @if (auth()->user()->image)
                            <img src="{{ asset('storage/' . auth()->user()->image) }}" class="user-img" alt="user avatar">
                        @else
                            <img src="data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxISEhAQEBMSFhUVFxYRFREVFREQGBUWFxUWGBUSFRUYHSggGBolHRYWITEiJSkrLi4uFx8zODMuNygtLisBCgoKDg0OGhAQGi4lHyUtLSstLS0tLS0tLS0tLS0tLS0tLSstLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAOEA4QMBEQACEQEDEQH/xAAbAAEAAgMBAQAAAAAAAAAAAAAAAwQBAgUGB//EAEAQAAIBAgMFBAcGBAQHAAAAAAABAgMRBCExBQYSQVFhcYGREyIyUqGxwSNCcoKS0RRTYvAWQ6LSBxUkY8Lh8f/EABoBAQADAQEBAAAAAAAAAAAAAAABAgQDBQb/xAAwEQEAAgIBAgMHAwQDAQAAAAAAAQIDEQQhMRJBUQUTMkJhcZFSgaEiI7HwFBXh0f/aAAwDAQACEQMRAD8A+4gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABq6i6oI2x6VdQbg9KuvzBuBVF1BuGyYSyAAAAAAAAAAAAAAAAAAAAAAAw2BHKuuQV8SKVVsnSNtGyQCNANANAADeNVrmRpO5SRr9RpPiSp30IWZAAAAAAAAAAAAAAAAAAEVStbQnSsyglJvUIYJAAAAAAAAAAAJ20IE8K3XzCYsmIWAAAAAAAAAAAAAAYbAr1Kt9NCdKTO0ZKAJAMN2zfmQOLjt6sLTuuPjfSmuP/V7PxMt+bir57+zdi9ncjJ11r79P/XHrb+L7lBv8U1H4JMzT7Sj5atlfY0/Nf8AEK738qfyYfqk/oU/7K36Y/Lp/wBNT9c/hvT38l96hHwqNfOJMe0p86/yifY0eV/4/wDXRwm+2HllONSn2tKa845/A709oY57xMMuT2TmrG6zE/w9DhcVCrHjpzjOPWLT8H0Zspet43WdvOyY7Y58N41KYuqAAAG9Oo13EG1mMr6ELsgAAAAAAAAAADDYFapUv3EqTLQkAAFTam0IYenKrUeSySWsnyjFdTlly1xV8VnXBhvmvFKPmu29v1cS7SfDC+VKLy/N7z7zw8/Jvl79vR9PxuFj48dOs+v+9nKi7/FGdrYjK/yJkgjNP9iNG2wSwmBYwW0amHmqlOTi9H0fZJc0dMeS1J3WXHNhplr4bx0fUNg7XhiaSqRyd3CcL+zJaru0a7Ge9gzRlpFnynJ484Mk0n9vs6R3cAAAA2hOxBvS1GV8yF2QAAAAAAAAACtWqXyWhKkyjJAAACJfMt89qutXnFP1KV4RXWS9uXnl3JHg8zN7zJqO0dP/AK+o9nceMWGLT3t1/byeeqO6Uo8s+/qjNDf5dEfpUnxLR+1Hmu0nXkjfozWv7cPHt7SI9JJ9YRyqJ2ck10kiYjXZG992yco5p8UfiOkp6wkspLii7P8AvJkdukp79YV6ta/immu3qWiFZljDxbd1Zvvafg0JnSIjb6LuJtuc28NWk5WXFTlJ3lZawb59U9dew9Pg8mbT4LT9nie0+HWke9pGo83sz0njgAABtTnZ9hBEraIXAAAAAAAAIq87ZEwrMq5KoEgADSrO0ZS6JvyREzqNkRuYh8Vqtv1k/W1d+d9bnzETvrL7bWo1Hkk2NgZ1puMLJc07ux0ik2nUOGTNGKNz+HXxe6lRZrhl5xfmrnScF47ONedjt8UOX/yytTycJW7uK3ijlalvOGimbHPa0IlTs2rZPk1z5lJdY15MUaDTfDez5ZvPsJ6yjpHmt4fZNZ34Kbzd8/VXx+haMdreTlbkY6ea9R3Jqyi3KpFS1tZtX7//AEaIwTpjtzq76Q4rwrhKUJx4Zwdnb5rsZnvE1nUt+O1b1iYdfdmpw4vDv+tR/UnH6nTizrNVx51fFx7x9H1Y+hfIgSAAAE1CfLyIlMSnIWAAAAAAw2BUnK7uS5sEpAAADDjfJ88iNbN6fFsTh1eUZfdbj5Ox8zO6zMPto1aIl6zcfCqNOc7aytfTRL9zXx46TLyefP8AXFfo9MaGBiUE9UmEo3hoP7qI1CfFIsNDp8xqDxSkjBLRJEoZJQ8Xvnh1GvCov8yFn3wevlJeRi5NesS9b2fbdZr6Ofu3LixeHS5VI3fas7EcaP7tfu7cyf7F/s+uH0L5ECQAAAJhC5CV1cq6MgAAAABFiJZW6kwrKuSgAAAAAIfHd4k6eKrx/wC7PLlZybXjZnzuamslo+svr+NfxYaT9Iex3RjbDxfWUn8bfQ0cf4Hnc6f70/s7LZ3YxMAQABkggPNb80r06U+kpR/VG/8A4Gfk9ol6Hs+f65j6ODuPSvjKPZKT0tpCT+hTjR/er/vlLvzp1xr/AO+cPrZ7z5YCQAAAATYeXIiUxKchYAAAAFWtK7JhSe7QkAAAAAA+Y76ULYur/UoS84pfNM8Hm11nn66fUezb741fpv8Ayh3o25XoqOB2dH1oq06+T4X7sFzlzbs7X66eng4lprHTo8XPyYm0zM9Xl/8ABGJr+viJ4qcnm36CdbP8VSpF/A0RiiPmr+XD3v0lD/hCvh3xUq9Wk1nxSo1sN5ypymPczPSJiftJ72I7xMfs+o7v7UTw9FV69GVVRSqSUkk5LLizS110Mt8GSs/DLtXLSY7ot6dpP+Gqxw1ejCtJJQm5+ynJcclwpu/DxWy1sTjwZLT8MlstIju+aLc2tX9apXrVXq5RoVsR/qqSgafcTHeYj7y4+9jyiZ/ZLLczFUPXw88VFrNfYzoZ/ihUl8h7qJ+av5Pez+mXp939s1sRTq4PHxtVUW6dbJKbjopJaTXhdX8cvJ4lorM66NPG5MRkiYnqn/4f0v8Aqo534YTlyyurfUwcHrmiZ9Jer7UnXHmN+cPpx7j5oAAAAADNN2aIFwhcAAADApNllAAAAAANajsm+xlbTqJlMdZiHkttbKp4yDjOMeOEk4VGryi1K909bOzyPOi9onxV7w9KK17Wjoh3LwSgsRFpcUKsqblbNpJWS7NWb+XabzWfLW2LBEV8Xrt0d6dpLB0KletP0ajCU1FK7dso00/fk2kuVzjGGZjrK85YiekbQbBxFSvQjiY8bg1GUqdSPDOKkr3fauaOd8Nq9YnbpXNW3SY05+E2RRq4jFznCLjGUYRjmlxcKc3Zc7282ar8jJjxUrWesxtxrira9pmCvsmlSxeFcIJRk5RlHVcXBJwdnzuvgRTPfJivWZ6x1LYq0vWYh2Nv414TDzxNTj4Yxc+CnHim0rcvHuSzZxrimY6r2yx20j2LiniqaxGHqqrBxjPhs1k73jFv7yaas+hF8E94naaZq9pjTm73YSNT+GikuOdVQ4rZ8LTv4aM78PJNPHPlEfypyKxbwx9f4XdkbOp4SnGlTiryblOSVnKTec3zfRdiMNr2tO7d2qK1+WNQ9VB5LuR6Udnmz3bEgAAAAAFuDyRVeGwAABrUeTCJVCyoAAAAAGGiJHn1TcaslbLn4nmTHhtMPSid1iXPlX/hcTUqSypV4q8+UKkMlfomnr1NtN5cUVj4q/4Zbapk3PaV7E7Tw9aHBUq0Zx7ZweXNa5rvKRGavTwz+EzGK3Xf8sT3hoUYNRq0/wAMWpyk7ac+4vFM9+mtImcVeu0O79CUaKlNWnUlKtJdHN3S8rHLk2ib6r2jp+HTDExXc956o94KcuFTpq86bjWiurpyu1+lyHFtEZNT2mNGeszTcd46rUt4MPiIJSqU7e7JqEo3VnFp27nyOtq56TrwuUe6t5s0NqYelDghVowjztOC7lrp2IpMZrdPDP4Xj3VfP+XPoTWIxMasc6VGLUZZ2lUlq49Ulz6lrROHF4Z+K38QiJ95k8Udo/y6cablWjlly7lqzHWJteIabW8NNu+eo81kAAAAAAFmg8iq0JAkAAaVtGET2VSykASAAAAABzsbTtPi6r+/77TFnrq22vBbddeipfkzi7o5YCi83Spv8kf2LxlvHzT+VPBX0hvSwtOPswhHujFfIiclp7zP5TFax2hKUSirO0oPtt5ohaOxVwtOXtQg++MX8zpF7R2mfypNaz3hpHAUVmqVNfkj+xM5bz3tP5R4K+kN087IouuYGn6zl0VkduPX+rbPnt/Tp0TaygAAAAAALGH08SJWqlISAAI62jCJ7KxZSAJAAAAAAirUVJZ+ZzvSLx1WpeaTuHGrRszBPSXoRO4SweRCGQIVRlb23fwZHVbf0a1MLe15P++nQa2bWCVWJvIJaYSnxSS6stWvitEK3t4azLsUaSirLzN9KRSNQw3vN53KQuqAAAAAAAsYfTxIlaqUhIAA1qrJhEqhZUAAAAAAAA5u0KOd+T+Ziz01bbXgvuNeivTODur4ipUjmuFrua8yJ3CY0r/x1ToiPFKfDDH8bU6LxHiPDCzhpVJZysl3Z+BMbRMQszRKFrZ1LWXgjTx6dfEzci/ywvmtlAkAAAAAABZoaFVo7JAkAAYaAplnMCQAAAAAAGs4JqzK2rFo1KazNZ3DnVqDi+zkzDkxzSWzHki8Iijo0dGPuryI0nZGlFaJeQ1BtuShNhqHFnyWT7+h1xY/H1ns5Zcnh6R3dGKtkjbEajUMffuySAAAAAAAARK3TWSKrw2CQAAAq1lZvzJhSe7QkAAAAAAAAOZtmuklCLXHdS4U7tRzza6Gfk78Efd343W0/ZTo4pPXJ/AxRLZNU6ZZDDklq0QKtfF8o+f7ETZMQs7ExcF9lKSUpNuMW1eVl61lztY18Tc1ll5XxQ7RqZgAAAAAAADMFdpEIXCHQAAAAEOIjzJhWUBKAAAAAAAFbH46nRg6lWSjFdXm+yK5vsJrWbTqETOnyTE7VqSrzxKk4zlJyTXJaKPakrLwN/u6zTwTHRyi0xO4d3B7y055V4uEvfguKL7XHVeFzzM3s6e9JbcfM8rujDG0Hmq9LxlwfBmOeJlj5ZaI5GOfNmWOw69qvS/K+P5COJmn5ZJ5GOPNTxW8mHgmqcZVH1fqL45/A04/Z15+Lo435lY7PJ19rVnWhXbtKDUoJZJWd7dz59T08XHpjp4YYsmS153L69sja9LEwjOlKLbV3C64ovnGS1yMlqzWdStE7XyqwAAAAAACXDx5kSmqwQsAAAADElfICnJWyJUCQAAAKW0Nq0aC+1qRi/d1k+6KzLVx2t2hWbRDzG0d+NVh6f56n0iv3NNOL+qXOcno8XtPF1KtSVSrJyk+fRdEuSNEVivSFd7VSUgAIAaAK9WV2EtsJOUZwnB2lFqSfRp3ImN9JVmdPd4Df2asq9KMv6oPgf6Xk/NHC3FjykjN6w9Ls7ebC1rKNRRk/uVPs33Z5PwbM9sN6+TrGSJdc5rshIAAEIW6cbKxC8NgkAAAAACHEQ5kwrMICUAHH2tvHQoXi5cU/wCXCza/E9InWmG1lJvEPH7T3txFW6g1Sj0h7XjPXysa6cesd+rnN5lwZNttvNvVvNs76UYJGtSFyJjaVZoonbVw6ZBLW0uqAcMuoGfR9W2BmUcrBG2aVO3eWiHK07bllQgdDZ228RQt6KpJL3H60f0vJeBzvirbvC0XmOz1myt+4u0cTDhf8yF3HvcXmvC5mvxZj4Xaub1evw2JhUip05KUXpJO6M0xMTqXWJiUpCyWhDmRKYhYIWAAAAAAAAKtWFiVJh4/fbbcqdsPSbUpLinJZNRekU+Tfy7zVx8UT/VLle3k8KbnIJAAAA1nBMiYEUqTK6Tto4voQnYkDbaNJ9w0jaaEEi8QhlxuSaaSpdArNUbVgrMaYCADt7p7aeGrLif2U2ozXJdKnevlc4Zsfir9V6W8Mvq9OF2ea2d1pIhdkAAAAAAAABiUb5AfKN8Nn1aWIqTq5qpJyhNaNco9jSsrHpYL1mkRHky3rMS4ZoUAAAAAAAAAAAAAAGgIZwsFJjTQKr2yNl1MTUVKks3m5PSMecpPp8znkyRSNytWs2nUPs+Aw3o6dOnxOXDGMXN6yskrs8q07nbfWNRpYISAAAAAAAAAAFbaGBp14SpVYqUXy6Pk0+T7S1bTWdwiYie75hvHu3Uwrcs50m8qltP6Z9H26P4HoYs8X6ebNek1cM7qBIAAAAAAAAAAAABiSuBe2DsCti58NNWin69Vr1Y9nbLs+RyyZYpHVFcc2l9X2Lselhafo6S7ZTftTfWT+nI8295vO5bK0isah0CiwAAAAAAAAAAAAGtSCknGSTTyaeaa6NAeK2/uOnephLJ6ui3l+SXLueXajXj5Oul/y42xejw+Jw06cnCpGUZLWMlZ/wDw21tFo3DjMaRFkAAAAAAAAAABvSpuTUYpyk8lFJtvuSImYjrI9jsHceUrTxfqx1VJP1n+KS9nuWfcY8nJjtR2ri9Xu8Nh4U4qFOKjFZKKVkjHMzM7l2iNJSEgAAAAAAAAAAAAAAACrtDZ1KvHhrQjNcr6ruazXgWraa9YRNYnu8ftTcHWWGqfkqfSa+q8TVTlz80OM4vR5fH7BxNG/pKM7e9Fcce+8b28TTXNS3aXOaTHdzUzoqACQAEBcC9gdkV61vRUpyXvWtH9Ty+JS2Wle8rRWZ7PT7M3Bm7PEVFFe5T9Z/qeS8mZr8v9MOkYvV7DZex6GHVqMFF85ayffJ5mW97X+KXWKxHZfKLAAAAAAAAAAAAAAAAAAAAAAFPF7LoVc6lKnJ9XGLfnqWi9q9pRNYly625uDl/luP4ZzXwbOkcjJHmpOOqrLcPC8pVl+aP+0v8A8q/0R7qpHcPC85Vn+aP+0f8AKyfQ91VZo7l4OOsJS/FOf0aKzyMk+afdVdLC7Gw9POnRpp9eFN/qeZznJae8rRWI8l8osAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAH//2Q=="
                                class="user-img  border border-primary" alt="user avatar">

                            {{-- <img src="{{ asset('storage/profile_images/default/default.jpg') }}" class="user-img" alt="user avatar"> --}}
                            {{-- <img src="data:image/jpeg;base64,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" class="user-img" alt="user avatar"> --}}
                        @endif
                    @endauth
                    {{-- <img src="data:image/jpeg;base64,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"
                        class="user-img" alt="user avatar"> --}}

                    {{-- <img src="data:image/jpeg;base64,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"  alt="user avatar" > --}}
                    <div class="user-info ps-3 pe-2">

                        <p class="user-name mb-0 h4 p-0"><b>{{ ucfirst(auth()->user()->name) }}</b></p>
                        {{-- <p class="designattion mb-0">{{ ucfirst(auth()->user()->type) }}</p> --}}
                        @if(!auth()->user()->is_super_admin)
                            <p class="designattion mb-0">{{ucfirst(auth()->user()->active_company_details()->name)}}</p>
                        @endif
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    @if(!auth()->user()->is_super_admin)
                        <li class="p-3 mb-3">
                            <div class="form-group">
                                <label for="active_company_dropdown">Active Company</label>
                                <select name="active_company_dropdown" id="active_company_dropdown" class="form-control">
                                    @foreach (\App\Models\Company::myCompaniesDropdown() as $company)
                                        <option value="{{$company->id}}" {{auth()->user()->active_company_id == $company->id? 'selected':''}}>{{$company->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    @endif
                    <li><a class="dropdown-item" href="{{ url('/') }}"><i
                                class="bx bx-user"></i><span>Dashboard</span></a>
                    </li>
                    <li><a class="dropdown-item" href="{{ route('user-profile.create') }}"><i
                                class="bx bx-user"></i><span>Profile</span></a>
                    </li>

                    {{-- <li><a class="dropdown-item" href="{{ url('ecommerce-orders') }}"><i class="bx bx-cog"></i><span>Orders</span></a>
                            </li>
                            <li><a class="dropdown-item" href="{{ url('index') }}"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
                            </li>
                            <li><a class="dropdown-item" href="{{ url('earnings') }}"><i class='bx bx-dollar-circle'></i><span>Earnings</span></a>
                            </li>
                            <li><a class="dropdown-item" href="{{ url('downloads') }}"><i class='bx bx-download'></i><span>Downloads</span></a>
                            </li> --}}
                    {{-- <li>
                                <div class="dropdown-divider mb-0"></div>
                            </li> --}}

                    {{-- <li><a class="dropdown-item" href="{{ url ('/profile') }}"><i class="bx bx-user"></i><span>Profile</span></a>
                            </li> --}}
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <li>
                            <button class="dropdown-item" type="submit"><i
                                    class='bx bx-log-out-circle'></i><span>Logout</span></button>
                        </li>
                    </form>

                    {{-- <li><a class="dropdown-item" href="{{ url('authentication-signin') }}"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                            </li> --}}
                </ul>
            </span>

        </div>
        </nav>
    </div>
</header>
@if (auth()->user()->theme === 'light-theme')
    <script>
        $(document).ready(function() {
            $('#1').removeClass('bg-dark shadow-none');
        });
    </script>
@endif
<script>
    $(document).ready(function() {

        $('#showUnreadMessages').on('click', function() {

            $('#unreadMessagesSection').removeClass('d-none');
            $('#allMessagesSection').addClass('d-none');
        });

        $('#showAllMessages').on('click', function() {

            $('#unreadMessagesSection').addClass('d-none');
            $('#allMessagesSection').removeClass('d-none');
        });
        $('.bell-click').on('click', function() {
            $('#notificationCount').text('0');
        });

        function toggleNotificationDot(hasNotifications) {
            if (hasNotifications) {
                $('.notification-dot').show(); // Show the dot
            } else {
                $('.notification-dot').hide(); // Hide the dot
            }
        }

        // Example: Simulate receiving a notification after 3 seconds
        setTimeout(function() {
            toggleNotificationDot(true); // Show the dot after 3 seconds
        }, 3000);

        // Example: Simulate removing the notification dot after 10 seconds
        // setTimeout(function() {
        //     toggleNotificationDot(false); // Hide the dot after 10 seconds
        // }, 10000);

        $('#active_company_dropdown').change(function(){
            company_id  = $(this).find('option:selected').val();
            // company_id = 2;
            if(company_id){
                $.post("{{route('company.change_active')}}", {
                    _token:"{{csrf_token()}}",
                    company_id: company_id,
                }).then(function(resp){
                    if(resp?.success){
                        // msgboxbox.show(resp.success,'success', null);
                        location.reload();
                    }
                }).fail(function(xhr){
                    if(xhr.responseJSON?.errors){
                        for(i in xhr.responseJSON.errors){
                            msgboxbox.show(xhr.responseJSON.errors[i]??'error','error', null);
                        }
                    }
                })
            }
        });
    });
</script>

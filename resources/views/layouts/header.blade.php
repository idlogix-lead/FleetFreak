{{--
    App shell: top bar (styles in public/assets/css/app-shell.css, behaviour in
    public/assets/js/app-shell.js). Same data calls and role gates as before:
    actor 2 = admin (unapproved agents, vehicle locations), actors 2 and 4 get the
    notifications and the create-ride shortcut.
--}}
@php
    $ffsUser = auth()->user();
    $ffsRoute = optional(request()->route())->getName();

    // The four dashboards: title fallback and the only pages that show Refresh.
    $ffsDashboards = [
        'dashboard' => 'Main Dashboard',
        'vehicle_dashboard' => 'Vehicle Dashboard',
        'driver_dashboard' => 'Driver Dashboard',
        'financial_dashboard' => 'Financial Dashboard',
    ];

    // Page title: @section('title'), else the active breadcrumb, else the dashboard name, else FleetFreak.
    // A generic active crumb (Create / Edit / Show) is prefixed with its parent: "Ride · Create".
    $ffsTitle = trim($__env->yieldContent('title'));
    if ($ffsTitle === '' && !empty($breadcrumbs) && is_iterable($breadcrumbs)) {
        $ffsCrumbs = collect($breadcrumbs)->values();
        $ffsIndex = $ffsCrumbs->search(fn ($crumb) => (bool) data_get($crumb, 'active'));
        $ffsIndex = $ffsIndex === false ? $ffsCrumbs->count() - 1 : $ffsIndex;
        $ffsTitle = trim((string) data_get($ffsCrumbs->get($ffsIndex), 'name', ''));
        $ffsParent = $ffsIndex > 0 ? trim((string) data_get($ffsCrumbs->get($ffsIndex - 1), 'name', '')) : '';
        if ($ffsParent !== '' && in_array(strtolower($ffsTitle), ['create', 'edit', 'show'], true)) {
            $ffsTitle = $ffsParent . ' · ' . $ffsTitle;
        }
    }
    if ($ffsTitle === '') {
        $ffsTitle = $ffsDashboards[$ffsRoute] ?? 'FleetFreak';
    }

    $ffsIsAdmin = $ffsUser->actor_id == 2;
    $ffsHasNotifications = $ffsUser->actor_id == 2 || $ffsUser->actor_id == 4;
    if ($ffsHasNotifications) {
        $unread_notifications = $ffsIsAdmin
            ? App\Models\Notification::admin_unread_notifications()
            : App\Models\Notification::agent_unread_notifications();
        $all_notifications = $ffsIsAdmin
            ? App\Models\Notification::admin_all_notifications()
            : App\Models\Notification::agent_all_notifications();
    }
    $ffsCompany = !$ffsUser->is_super_admin ? $ffsUser->active_company_details() : null;
@endphp

{{-- Global rules in header.css (.list-group-item, .dropdown-item:hover, .navbar-expand-lg) are used by pages. --}}
<link rel="stylesheet" href="{{ asset('assets/css/header.css') }}">

<header>
    <div class="topbar ffs-topbar" id="1">
        <button type="button" class="mobile-toggle-menu ffs-icon-btn" aria-label="Open menu">
            <i class='bx bx-menu'></i>
        </button>

        <div class="ffs-heading">
            <h1 class="ffs-title">{{ $ffsTitle }}</h1>
            <p class="ffs-date">{{ now()->format('l, j F Y') }}</p>
        </div>

        {{-- SAMPLE: no global search backend yet (Phase 7). Read-only; Ctrl/Cmd+K only focuses it. --}}
        <div class="ffs-search-slot">
            <div class="ffs-search" title="Search arrives in Phase 7">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="search" id="ffs-search" class="ffs-search-input" placeholder="Search vehicles, drivers, tasks&hellip;" aria-label="Search (sample, arrives in Phase 7)" readonly>
                <kbd class="ffs-kbd" id="ffs-search-kbd">&#8984;K</kbd>
            </div>
            {{-- <span class="ffs-sample" title="Search arrives in Phase 7">Sample</span> --}}
        </div>

        <div class="ffs-actions">
            @if (isset($ffsDashboards[$ffsRoute]))
                <button type="button" class="ffs-btn-primary" data-ffs-action="refresh">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                    <span class="ffs-btn-text">Refresh</span>
                </button>
            @endif

            {{-- Commented out on request (2026-10-01), together with the count query it needs: uncomment the block below to restore both. --}}
            {{-- @if ($ffsIsAdmin)
                @php
                    $ffsUnapprovedAgents = App\Models\Partner::unapprovedAgentsCount();
                @endphp
                <a class="ffs-icon-btn" href="{{ route('unapproved_agents.index') }}" title="Unapproved Agents" aria-label="Unapproved Agents">
                    <i class='bx bx-user-circle'></i>
                    <span id="notificationCount" class="ffs-badge" @if ($ffsUnapprovedAgents == 0) hidden @endif>{{ $ffsUnapprovedAgents }}</span>
                </a>
                <a class="ffs-icon-btn" href="{{ route('vehicle_loc') }}" title="Vehicle Locations" aria-label="Vehicle Locations">
                    <i class='bx bx-map'></i>
                </a>
            @endif --}}

            @if ($ffsHasNotifications)
                {{-- <a class="ffs-icon-btn" href="{{ route('orders.create') }}" title="Create Ride" aria-label="Create Ride">
                    <i class='bx bx-plus-circle'></i>
                </a> --}}
                <button type="button" class="ffs-icon-btn" data-bs-toggle="offcanvas" data-bs-target="#notificationOffcanvas"
                    aria-controls="notificationOffcanvas" title="Notifications" aria-label="Notifications ({{ $unread_notifications->count() }} unread)">
                    <i class='bx bx-bell'></i>
                    @if ($unread_notifications->count() > 0)
                        <span class="ffs-badge">{{ $unread_notifications->count() > 99 ? '99+' : $unread_notifications->count() }}</span>
                    @endif
                </button>
            @endif

            <div class="dropdown ffs-user">
                <button type="button" class="ffs-user-chip" data-bs-toggle="dropdown" data-bs-offset="0,8" aria-expanded="false">
                    @if ($ffsUser->image)
                        <img src="{{ asset('storage/' . $ffsUser->image) }}" class="ffs-avatar" alt="">
                    @else
                        <span class="ffs-avatar ffs-avatar-initial" aria-hidden="true">{{ strtoupper(mb_substr(trim($ffsUser->name) ?: '?', 0, 1)) }}</span>
                    @endif
                    <span class="ffs-user-text">
                        <span class="ffs-user-name">{{ ucfirst($ffsUser->name) }}</span>
                        @if (!$ffsUser->is_super_admin)
                            <span class="ffs-user-company">{{ ucfirst($ffsCompany->name) }}</span>
                        @endif
                    </span>
                    <svg class="ffs-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
                </button>
                <ul class="dropdown-menu dropdown-menu-end ffs-user-menu">
                    @if (!$ffsUser->is_super_admin)
                        <li class="ffs-company">
                            <label for="active_company_dropdown">Active Company</label>
                            <select name="active_company_dropdown" id="active_company_dropdown" class="ffs-select"
                                data-change-url="{{ route('company.change_active') }}">
                                @foreach (\App\Models\Company::myCompaniesDropdown() as $company)
                                    <option value="{{$company->id}}" {{$ffsUser->active_company_id == $company->id? 'selected':''}}>{{$company->name}}</option>
                                @endforeach
                            </select>
                        </li>
                    @endif
                    <li><a class="dropdown-item ffs-menu-item" href="{{ url('/') }}"><i class="bx bx-home-alt"></i><span>Dashboard</span></a></li>
                    <li><a class="dropdown-item ffs-menu-item" href="{{ route('user-profile.create') }}"><i class="bx bx-user"></i><span>Profile</span></a></li>
                    <li><hr class="ffs-menu-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="dropdown-item ffs-menu-item" type="submit"><i class='bx bx-log-out-circle'></i><span>Logout</span></button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    @if ($ffsHasNotifications)
        <div class="offcanvas offcanvas-end ffs-offcanvas" tabindex="-1" id="notificationOffcanvas" aria-labelledby="notificationOffcanvasLabel">
            <div class="offcanvas-header">
                <h5 class="ffs-offcanvas-title" id="notificationOffcanvasLabel">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="ffs-tabs" role="tablist" aria-label="Message Type">
                    <button type="button" class="ffs-tab is-active" id="showUnreadMessages" role="tab" aria-selected="true">
                        Unread <span class="ffs-tab-count">{{ $unread_notifications->count() }}</span>
                    </button>
                    <button type="button" class="ffs-tab" id="showAllMessages" role="tab" aria-selected="false">
                        All <span class="ffs-tab-count">{{ $all_notifications->count() }}</span>
                    </button>
                </div>

                <div class="ffs-notices" id="unreadMessagesSection">
                    @forelse ($unread_notifications as $unread_notification)
                        <div class="ffs-notice">
                            <span class="ffs-notice-icon"><i class="bx bx-group"></i></span>
                            <div class="ffs-notice-body">
                                <div class="ffs-notice-head">
                                    <span class="ffs-notice-name">{{ $unread_notification->receiver->name }}</span>
                                    <span class="ffs-notice-time">{{ $unread_notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="ffs-notice-text">{{ $unread_notification->detail }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="ffs-notices-empty">No unread notifications.</p>
                    @endforelse
                </div>

                <div class="ffs-notices d-none" id="allMessagesSection">
                    @forelse ($all_notifications as $all_notification)
                        <div class="ffs-notice">
                            <span class="ffs-notice-icon"><i class="bx bx-group"></i></span>
                            <div class="ffs-notice-body">
                                <div class="ffs-notice-head">
                                    <span class="ffs-notice-name">{{ $ffsIsAdmin ? ($all_notification->receiver->name ?? null) : $all_notification->receiver->name }}</span>
                                    <span class="ffs-notice-time">{{ $all_notification->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="ffs-notice-text">{{ $all_notification->detail }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="ffs-notices-empty">No notifications.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</header>
{{-- Not deferred: it defines ffsQuiet() and the AJAX error toasts before page scripts run. --}}
<script src="{{ asset('assets/js/app-shell.js') }}?v={{ filemtime(public_path('assets/js/app-shell.js')) }}"></script>

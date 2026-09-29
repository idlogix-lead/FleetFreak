{{--
    App shell: sidebar (styles in public/assets/css/app-shell.css, ffs- classes).
    Only the wrapper around <ul id="menu"> is new: the permission -> menu pipeline below,
    layouts.partials.menu and the position sort are unchanged. Collapse uses Synadmin's
    .toggle-icon handler and the active-link / metisMenu setup in public/assets/js/app.js.
--}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap">
{{-- Loaded here (inside body, before the sidebar paints) so it comes after bootstrap-extended.css, app.css and the theme CSS. --}}
<link rel="stylesheet" href="{{ asset('assets/css/app-shell.css') }}">

<div class="sidebar-wrapper ffs-sidebar">
    <div class="ffs-brand">
        <a href="{{ url('/') }}" class="ffs-brand-link" aria-label="FleetFreak home">
            <img src="/assets/images/fleetfreak-logo-final-white-[Recovered].png" class="ffs-brand-logo" alt="FleetFreak">
        </a>
    </div>
    <div class="ffs-nav-label">Navigation</div>
    <!--navigation-->
    <ul class="metismenu nav-list" id="menu">
        <li>
            <a href="#" class="has-arrow menu-title-color">
                <div class="parent-icon"><i class='bx bx-home'></i></div>
                <div class="menu-title">Dashboards</div>
            </a>
            <ul class="submenu">
                <li>
                    <a href="{{ url('/')}}" class="menu-title-color">
                        <div class="menu-title">Main Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('vehicle_dashboard') }}" class="menu-title-color">
                        <div class="menu-title">Vehicle Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('driver_dashboard') }}" class="menu-title-color">
                        <div class="menu-title">Driver Dashboard</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('financial_dashboard') }}" class="menu-title-color">
                        <div class="menu-title">Financial Dashboard</div>
                    </a>
                </li>
            </ul>
        </li>

        @php
            $permissions = auth()->user()->get_user_role_session_permissions();
            $items = $permissions
                ->where('permission', 1)
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
                    if ($groupId) {
                        $group = $items[0]['group'];
                        return [
                            'name' => $group['name'], // Custom label for group
                            'type' => 'dropdown',
                            'icon' => $group['icon'], // Custom column value
                            'link_position' => $group['position'], // Custom column value
                            'list' => $items
                                ->map(function ($item) {
                                    return [
                                        'name' => $item['link_name'],
                                        'permission' => true,
                                        'type' => 'link',
                                        'active_link' => [$item['link'], $item['link'] . '/*'],
                                        'link_position' => $item['link_position'],
                                        'route' => url($item['link']),
                                        'icon' => $item['link_icon'],
                                    ];
                                })
                                ->values()
                                ->toArray(), // Use `values()` to reset array keys
                        ];
                    } else {
                        return $items->map(function ($item) {
                            return [
                                'name' => $item['link_name'],
                                'permission' => true,
                                'type' => 'link',
                                'active_link' => [$item['link'], $item['link'] . '/*'],
                                'link_position' => $item['link_position'],
                                'route' => url($item['link']),
                                'icon' => $item['link_icon'],
                            ];
                        });
                    }
                })
                ->flatMap(function ($item) {
                    if (isset($item['type']) && $item['type']) {
                        return [$item];
                    } else {
                        return $item->toArray();
                    }
                })
                ->values()
                ->toArray();
        @endphp
        @include('layouts.partials.menu', ['menu' => $items])
    </ul>
    <script>
        // JavaScript code to reorder the list items
        $(document).ready(function() {
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
    <div class="ffs-sidebar-foot">
        {{-- .toggle-icon: Synadmin's collapse / hover-expand toggle (app.js); below 1025px it closes the drawer. --}}
        <button type="button" class="toggle-icon ffs-collapse" aria-label="Collapse sidebar">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
            <span class="ffs-collapse-text">Collapse</span>
            <span class="ffs-expand-text">Keep open</span>
            <span class="ffs-close-text">Close menu</span>
        </button>
    </div>
</div>

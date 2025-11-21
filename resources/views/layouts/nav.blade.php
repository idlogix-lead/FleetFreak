<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header"
        style = "  auth()->user()->theme == 'light-theme' ? 'background:#171717;' : 'background:black;' ">
        <div>
            @if (auth()->user()->theme == 'light-theme')

            {{-- <img src="/assets/images/new_logo-01.png" class="logo-icon ms-1" alt="logo icon"> --}}
                {{-- <img style="margin-top:12px; " src="/assets/images/new_logo-01.png" class="logo-icon" alt="logo icon"> --}}
                {{-- <img style="margin-top:12px; " src="/assets/images/navbar-fleetfreak-logo.png" class="logo-icon" alt="logo icon"> --}}
                <img style="" src="/assets/images/fleetfreak-logo-final-white-[Recovered].png" class="logo-icon" alt="logo icon">

            @else
                <img src="/assets/images/transport-dark-logo.png" class="logo-icon" alt="logo icon">
            @endif

        </div>
        <div>
            <h4 class="logo-text"><a href="{{ url('/') }}"><b></b></a></h4>
        </div>

        <div class="toggle-icon ms-1" style="width: 30px !important; height:30px !important;"><i class='bx bx-first-page'>k</i>

        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu nav-list" id="menu">
        <li>
            <a href="#" class="menu-title-color">
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
            var $logoIcon = $('.logo-icon');
            var $toggleIcon = $('.toggle-icon');

            var originalLightSrc = '/assets/images/fleetfreak-logo-final-white-[Recovered].png';
            var originalDarkSrc = '/assets/images/Zaroon_Logo-night.png';
            var toggleLogo =
            '/assets/images/transport-logo.png'; // This is the image you want to toggle to for light theme
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
                //  $logoIcon.css({
                //             width: '10px', // Set the width (adjust as needed)
                //             height: '30px'  // Adjust the height automatically to maintain the aspect ratio
                //         });
               
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

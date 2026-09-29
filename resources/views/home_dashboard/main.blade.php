@extends('layouts.app')

@php
    /*
    |--------------------------------------------------------------------------
    | PLACEHOLDER DATA - sample values copied from the Figma design
    |--------------------------------------------------------------------------
    | Every value below is fake. This array is the ONLY source of sample data on
    | this page: each component tagged SAMPLE reads from here and nowhere else.
    | Phase 7 replaces this array with real sources; the markup stays as it is.
    | Checklist: docs/design-tokens-dashboard.md
    */
    $placeholderData = [
        'kpis' => [
            'total_vehicles' => ['value' => '148', 'change' => '+3', 'positive' => true, 'sub' => 'Fleet assets'],
            // Value is real (Vehicle::countByStatus); only the badge and sub-label are samples.
            'active_vehicles' => ['change' => '+5', 'positive' => true, 'sub' => '75.7% utilization'],
            'in_maintenance' => ['value' => '14', 'change' => '+2', 'positive' => false, 'sub' => '9.5% of fleet'],
            // Value is real ($drivers->count()); only the badge and sub-label are samples.
            'total_drivers' => ['change' => '+4', 'positive' => true, 'sub' => 'Certified & active'],
            'monthly_revenue' => ['value' => '$84,320', 'change' => '+12%', 'positive' => true, 'sub' => 'vs last month'],
            'overdue_tasks' => ['value' => '7', 'change' => '-1', 'positive' => true, 'sub' => 'Needs attention'],
        ],
        'mileage' => [
            'period' => 'Last 6 months',
            'labels' => ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            'actual' => [48200, 52100, 49800, 55400, 61200, 58900],
            'target' => [50000, 50000, 52000, 52000, 58000, 60000],
        ],
        'maintenance_costs' => [
            'period' => 'Last 6 months',
            'labels' => ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
            'scheduled' => [4200, 3900, 4500, 3700, 5100, 4800],
            'unscheduled' => [1800, 2100, 900, 1400, 2300, 1600],
        ],
        'fleet_status' => [
            ['name' => 'Active', 'value' => 112, 'tone' => 'brand'],
            ['name' => 'Idle', 'value' => 22, 'tone' => 'lilac'],
            ['name' => 'Maintenance', 'value' => 14, 'tone' => 'amber'],
        ],
        'insights' => [
            [
                'icon' => "\u{1F4C8}",
                'title' => 'Fleet Utilization Up',
                'body' => 'Active vehicle rate improved by 4.2% this month. Consider reallocating 6 idle vehicles to Route B.',
                'tag' => 'Efficiency',
            ],
            [
                'icon' => "\u{26FD}",
                'title' => 'Fuel Cost Spike',
                'body' => "Fleet fuel costs rose 9% vs Aug. Review Truck #FF-042 and #FF-018 \u{2014} above-average consumption detected.",
                'tag' => 'Cost Alert',
            ],
            [
                'icon' => "\u{1F501}",
                'title' => 'Maintenance Pattern',
                'body' => '3 vehicles due for 10,000 km service this week. Bundle scheduling to reduce downtime to a single 2-day window.',
                'tag' => 'Optimization',
            ],
        ],
        'next_actions' => [
            ['priority' => 'high', 'category' => 'Finance', 'title' => "Generate Invoice \u{2014} Apex Fuel Co.", 'due' => 'Today', 'overdue' => true],
            ['priority' => 'high', 'category' => 'Maintenance', 'title' => "Overdue Service \u{2014} Truck #FF-042 (14 days past)", 'due' => 'Overdue', 'overdue' => true],
            ['priority' => 'high', 'category' => 'Maintenance', 'title' => "Overdue Inspection \u{2014} Van #FF-019", 'due' => 'Overdue', 'overdue' => true],
            ['priority' => 'medium', 'category' => 'Finance', 'title' => "Generate Invoice \u{2014} Metro Logistics", 'due' => 'Tomorrow', 'overdue' => false],
            ['priority' => 'medium', 'category' => 'Driver', 'title' => "Renew License \u{2014} D. Patel (exp. Oct 2)", 'due' => 'In 3 days', 'overdue' => false],
            ['priority' => 'low', 'category' => 'Compliance', 'title' => "Upload Insurance Doc \u{2014} Vehicle #FF-103", 'due' => 'In 5 days', 'overdue' => false],
        ],
    ];

    $isAdmin = auth()->user()->actor_id == 2;
    $filterFrom = request()->input('date');
    $filterTo = request()->input('to_date');
    $sampleTag = '<span class="ffd-sample" title="Sample data &mdash; replaced in Phase 7">Sample</span>';
@endphp

@section('style')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap">
@endsection

@section('wrapper')
    {{-- Loaded here (inside body) so it comes after Bootstrap and app.css in the cascade. --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main-dashboard.css') }}">

    <div class="ffd">

        {{-- Page header --}}
        <div class="ffd-page-head">
            <div>
                <h1 class="ffd-page-title">Main Dashboard</h1>
                <p class="ffd-page-date">{{ now()->format('l, j F Y') }}</p>
            </div>
            <div class="ffd-page-actions">
                @if (!empty($agentName))
                    <span class="ffd-chip" id="ffd-agent-chip">{{ $agentName }}</span>
                @endif
                @if ($filterFrom || $filterTo)
                    <span class="ffd-chip ffd-chip-muted">{{ $filterFrom ?: '...' }} &rarr; {{ $filterTo ?: '...' }}</span>
                @endif
                <button type="button" class="ffd-btn ffd-btn-primary" data-ffd-action="refresh">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="23 4 23 10 17 10"></polyline><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path></svg>
                    Refresh
                </button>
                <button type="button" class="ffd-btn ffd-btn-soft" data-bs-toggle="offcanvas" data-bs-target="#ffd-filter" aria-controls="ffd-filter">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    Filter
                </button>
            </div>
        </div>

        {{-- Filter panel: same fields and GET parameters as the old dashboard --}}
        <div class="offcanvas offcanvas-end ffd-offcanvas" tabindex="-1" id="ffd-filter" aria-labelledby="ffd-filter-title">
            <div class="offcanvas-header">
                <h2 class="ffd-offcanvas-title" id="ffd-filter-title">Filter dashboard</h2>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form method="GET" action="{{ url()->current() }}" class="ffd-filter-form">
                    @if ($isAdmin)
                        <div class="ffd-field">
                            <label for="ffd-agent">Agent</label>
                            <select name="agent" class="form-select ffd-input" id="ffd-agent">
                                <option value="">Search by agent name...</option>
                                @php
                                    $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                                @endphp
                                @foreach ($businessPartners as $business_partner)
                                    <option value="{{ $business_partner->id }}"
                                        {{ request()->input('agent') == $business_partner->id ? 'selected' : '' }}>
                                        {{ Str::title($business_partner->company_name) }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="agent_id" value="{{ request()->input('agent') }}">
                            <input type="hidden" name="agent_name" value="{{ request()->input('agent_name') }}">
                        </div>
                    @endif
                    <div class="ffd-field">
                        <label for="ffd-date">From Date</label>
                        <input type="date" name="date" class="form-control ffd-input" id="ffd-date" value="{{ request()->input('date') }}">
                    </div>
                    <div class="ffd-field">
                        <label for="ffd-to-date">To Date</label>
                        <input type="date" name="to_date" class="form-control ffd-input" id="ffd-to-date" value="{{ request()->input('to_date') }}">
                    </div>
                    <div class="ffd-filter-actions">
                        <button type="submit" class="ffd-btn ffd-btn-primary">Search</button>
                        <button type="button" class="ffd-btn ffd-btn-soft" id="ffd-filter-reset">Reset</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="ffd-section-row">
            <span class="ffd-section-label">Fleet overview &middot; {{ now()->format('M Y') }}</span>
            @if ($isAdmin)
                <span class="ffd-section-note">{!! $sampleTag !!} design placeholder data until Phase 7</span>
            @endif
        </div>

        @if ($isAdmin)
            @php
                // Real values: the same model methods the old dashboard called, called once each here.
                $vehicleStatus = \App\Models\Vehicle::countByStatus();
                $paymentStatus = \App\Models\OrderDetail::vehivcle_details();

                $kpiTiles = [
                    ['key' => 'total_vehicles', 'label' => 'Total Vehicles', 'icon' => "\u{1F697}", 'real' => null],
                    ['key' => 'active_vehicles', 'label' => 'Active Vehicles', 'icon' => "\u{2705}", 'real' => $vehicleStatus['active']],
                    ['key' => 'in_maintenance', 'label' => 'In Maintenance', 'icon' => "\u{1F527}", 'real' => null],
                    ['key' => 'total_drivers', 'label' => 'Total Drivers', 'icon' => "\u{1F464}", 'real' => $drivers->count()],
                    ['key' => 'monthly_revenue', 'label' => 'Monthly Revenue', 'icon' => "\u{1F4B0}", 'real' => null],
                    ['key' => 'overdue_tasks', 'label' => 'Overdue Tasks', 'icon' => "\u{26A0}\u{FE0F}", 'real' => null],
                ];
            @endphp

            {{-- KPI tiles --}}
            <div class="ffd-kpis">
                @foreach ($kpiTiles as $tile)
                    @php
                        $sample = $placeholderData['kpis'][$tile['key']];
                        $valueIsReal = $tile['real'] !== null;
                    @endphp
                    <div class="ffd-kpi" @unless ($valueIsReal) data-ffd-sample @endunless>
                        <div class="ffd-kpi-top">
                            <span class="ffd-kpi-icon" aria-hidden="true">{{ $tile['icon'] }}</span>
                            <span class="ffd-change {{ $sample['positive'] ? 'is-pos' : 'is-neg' }} {{ $valueIsReal ? 'is-sample' : '' }}"
                                @if ($valueIsReal) data-ffd-sample title="Sample value &mdash; replaced in Phase 7" @endif>{{ $sample['change'] }}</span>
                        </div>
                        <div>
                            <div class="ffd-kpi-value">{{ $valueIsReal ? $tile['real'] : $sample['value'] }}</div>
                            <div class="ffd-kpi-label">
                                <span>{{ $tile['label'] }}</span>
                                @unless ($valueIsReal)
                                    {!! $sampleTag !!}
                                @endunless
                            </div>
                            <div class="ffd-kpi-sub">
                                <span>{{ $sample['sub'] }}</span>
                                @if ($valueIsReal)
                                    {!! $sampleTag !!}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Vehicle Status / Vehicle Assignment / Payment (real data) --}}
            <div class="ffd-grid ffd-grid-3">
                <section class="ffd-panel">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Vehicle Status</h2>
                            <p class="ffd-panel-sub">Vehicles by status</p>
                        </div>
                    </header>
                    <ul class="ffd-legend">
                        <li class="ffd-legend-row">
                            <span class="ffd-dot ffd-tone-success"></span>
                            <span class="ffd-legend-label">Active</span>
                            <span class="ffd-legend-value">{{ $vehicleStatus['active'] }}</span>
                        </li>
                        <li class="ffd-legend-row">
                            <span class="ffd-dot ffd-tone-warning"></span>
                            <span class="ffd-legend-label">Inactive</span>
                            <span class="ffd-legend-value">{{ $vehicleStatus['inactive'] }}</span>
                        </li>
                        <li class="ffd-legend-row">
                            <span class="ffd-dot ffd-tone-danger"></span>
                            <span class="ffd-legend-label">Sold</span>
                            <span class="ffd-legend-value">{{ $vehicleStatus['sold'] }}</span>
                        </li>
                    </ul>
                </section>

                <section class="ffd-panel">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Vehicle Assignment</h2>
                            <p class="ffd-panel-sub">Rides by driver assignment</p>
                        </div>
                    </header>
                    <div class="ffd-stats">
                        <a class="ffd-stat" href="{{ route('driver_assignments.incomplete_rides') }}">
                            <span class="ffd-stat-value ffd-text-success">{{ $incompleteOrdersWithDriver }}</span>
                            <span class="ffd-stat-label">Assigned with driver</span>
                        </a>
                        <a class="ffd-stat" href="{{ route('driver_assignments.index') }}">
                            <span class="ffd-stat-value ffd-text-danger">{{ $approvedOrders }}</span>
                            <span class="ffd-stat-label">Unassigned</span>
                        </a>
                        <a class="ffd-stat" href="{{ route('driver_assignments.incomplete_rides') }}">
                            <span class="ffd-stat-value ffd-text-success">{{ $incompleteOrderWithoutDriver }}</span>
                            <span class="ffd-stat-label">Assigned without driver</span>
                        </a>
                    </div>
                </section>

                <section class="ffd-panel">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Payment</h2>
                            <p class="ffd-panel-sub">Completed rides by payment</p>
                        </div>
                    </header>
                    <ul class="ffd-legend">
                        <li class="ffd-legend-row">
                            <span class="ffd-dot ffd-tone-success"></span>
                            <span class="ffd-legend-label">Paid</span>
                            <span class="ffd-legend-value">{{ $paymentStatus['paid'] }}</span>
                        </li>
                        <li class="ffd-legend-row">
                            <span class="ffd-dot ffd-tone-warning"></span>
                            <span class="ffd-legend-label">Unpaid</span>
                            <span class="ffd-legend-value">{{ $paymentStatus['unpaid'] }}</span>
                        </li>
                    </ul>
                </section>
            </div>

            {{-- Fleet Mileage vs Target + Fleet Insights (sample data) --}}
            <div class="ffd-grid ffd-grid-main-side">
                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Fleet Mileage vs Target</h2>
                            <p class="ffd-panel-sub">{{ $placeholderData['mileage']['period'] }}</p>
                        </div>
                        {!! $sampleTag !!}
                    </header>
                    <div class="ffd-chart">
                        <canvas id="ffd-chart-mileage" role="img" aria-label="Fleet mileage vs target, sample data"></canvas>
                    </div>
                </section>

                <section class="ffd-panel ffd-panel-flex" data-ffd-sample>
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Fleet Insights</h2>
                            <p class="ffd-panel-sub">AI-powered recommendations</p>
                        </div>
                        {!! $sampleTag !!}
                    </header>
                    <div class="ffd-insights">
                        @foreach ($placeholderData['insights'] as $insight)
                            <article class="ffd-insight">
                                <span class="ffd-insight-icon" aria-hidden="true">{{ $insight['icon'] }}</span>
                                <div class="ffd-insight-body">
                                    <div class="ffd-insight-head">
                                        <h3 class="ffd-insight-title">{{ $insight['title'] }}</h3>
                                        <span class="ffd-tag">{{ $insight['tag'] }}</span>
                                    </div>
                                    <p class="ffd-insight-text">{{ $insight['body'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>
                    <div class="ffd-panel-foot">
                        <button type="button" class="ffd-btn ffd-btn-outline ffd-btn-block" aria-disabled="true" title="Sample &mdash; available in Phase 7">View All Insights &rarr;</button>
                    </div>
                </section>
            </div>

            {{-- Maintenance Costs + Fleet Status + Next Actions (sample data) --}}
            <div class="ffd-grid ffd-grid-main-two">
                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Maintenance Costs</h2>
                            <p class="ffd-panel-sub">{{ $placeholderData['maintenance_costs']['period'] }}</p>
                        </div>
                        {!! $sampleTag !!}
                    </header>
                    <div class="ffd-chart">
                        <canvas id="ffd-chart-maintenance" role="img" aria-label="Maintenance costs, sample data"></canvas>
                    </div>
                </section>

                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Fleet Status</h2>
                            <p class="ffd-panel-sub">{{ array_sum(array_column($placeholderData['fleet_status'], 'value')) }} total vehicles</p>
                        </div>
                        {!! $sampleTag !!}
                    </header>
                    <div class="ffd-chart ffd-chart-donut">
                        <canvas id="ffd-chart-fleet-status" role="img" aria-label="Fleet status, sample data"></canvas>
                    </div>
                    <ul class="ffd-legend ffd-legend-compact">
                        @foreach ($placeholderData['fleet_status'] as $status)
                            <li class="ffd-legend-row">
                                <span class="ffd-dot ffd-tone-{{ $status['tone'] }}"></span>
                                <span class="ffd-legend-label">{{ $status['name'] }}</span>
                                <span class="ffd-legend-value">{{ $status['value'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </section>

                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head ffd-panel-head-wrap">
                        <div>
                            <h2 class="ffd-panel-title">Next Actions {!! $sampleTag !!}</h2>
                            <p class="ffd-panel-sub">{{ count($placeholderData['next_actions']) }} pending tasks</p>
                        </div>
                        <div class="ffd-pills" role="group" aria-label="Filter tasks by priority">
                            <button type="button" class="ffd-pill is-active" data-ffd-filter="all" aria-pressed="true">All</button>
                            <button type="button" class="ffd-pill" data-ffd-filter="high" aria-pressed="false">High</button>
                            <button type="button" class="ffd-pill" data-ffd-filter="medium" aria-pressed="false">Medium</button>
                            <button type="button" class="ffd-pill" data-ffd-filter="low" aria-pressed="false">Low</button>
                        </div>
                    </header>
                    <div class="ffd-tasks-head" aria-hidden="true">
                        <span>Task</span><span>Cat.</span><span>Due</span>
                    </div>
                    <div class="ffd-tasks">
                        @foreach ($placeholderData['next_actions'] as $task)
                            <div class="ffd-task ffd-prio-{{ $task['priority'] }} {{ $task['overdue'] ? 'is-overdue' : '' }}" data-priority="{{ $task['priority'] }}">
                                <span class="ffd-task-title" title="{{ $task['title'] }}">{{ $task['title'] }}</span>
                                <span class="ffd-task-cat">{{ $task['category'] }}</span>
                                <span class="ffd-task-due">{{ $task['due'] }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="ffd-panel-foot ffd-panel-foot-split">
                        <button type="button" class="ffd-btn ffd-btn-primary ffd-btn-sm" aria-disabled="true" title="Sample &mdash; available in Phase 7">Gen. Invoice</button>
                        <button type="button" class="ffd-btn ffd-btn-soft ffd-btn-sm" aria-disabled="true" title="Sample &mdash; available in Phase 7">Schedule</button>
                    </div>
                </section>
            </div>

            <div class="ffd-section-row">
                <span class="ffd-section-label">Rides &amp; assignments</span>
            </div>

            {{-- Rides Order Status + Top Agents (real data) --}}
            <div class="ffd-grid ffd-grid-main-side">
                <section class="ffd-panel">
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Rides Order Status</h2>
                            <p class="ffd-panel-sub">Rides by status</p>
                        </div>
                    </header>
                    <div class="ffd-chart ffd-chart-lg">
                        <canvas id="ffd-chart-rides" role="img" aria-label="Rides order status"></canvas>
                    </div>
                </section>

                <section class="ffd-panel ffd-panel-flex">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Top Agents</h2>
                            <p class="ffd-panel-sub">Top 5 by total sales</p>
                        </div>
                    </header>
                    <ul class="ffd-list">
                        @forelse ($topAgents as $topAgent)
                            <li class="ffd-list-item">
                                <span class="ffd-avatar" aria-hidden="true">{{ Str::upper(Str::substr($topAgent['agent_name'] ?? '', 0, 1)) }}</span>
                                <span class="ffd-list-main">
                                    <span class="ffd-list-title">{{ $topAgent['agent_name'] }}</span>
                                    <span class="ffd-list-sub">Total sales</span>
                                </span>
                                <span class="ffd-list-value">{{ $topAgent['total_sales'] }}</span>
                            </li>
                        @empty
                            <li class="ffd-empty">No agents to show.</li>
                        @endforelse
                    </ul>
                </section>
            </div>

            {{-- Today Vehicle Assignment + Drivers (real data) --}}
            <div class="ffd-grid ffd-grid-main-side">
                <section class="ffd-panel">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Today Vehicle Assignment</h2>
                            <p class="ffd-panel-sub">Estimated hours booked per vehicle today</p>
                        </div>
                    </header>
                    <div id="ffd-chart-vehicle-bookings" class="ffd-apex"></div>
                </section>

                <section class="ffd-panel ffd-panel-flex">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Drivers</h2>
                            <p class="ffd-panel-sub">Company drivers</p>
                        </div>
                    </header>
                    <ul class="ffd-list">
                        @forelse ($drivers as $driver)
                            <li class="ffd-list-item">
                                <span class="ffd-avatar" aria-hidden="true">{{ Str::upper(Str::substr($driver->name ?? '', 0, 1)) }}</span>
                                <span class="ffd-list-main">
                                    <span class="ffd-list-title">{{ $driver->name }}</span>
                                    <span class="ffd-list-sub">{{ $driver->email }}</span>
                                </span>
                            </li>
                        @empty
                            <li class="ffd-empty">No drivers to show.</li>
                        @endforelse
                    </ul>
                </section>
            </div>

            {{-- Vehicle Details (real data) --}}
            <section class="ffd-panel ffd-block">
                <header class="ffd-panel-head">
                    <div>
                        <h2 class="ffd-panel-title">Vehicle Details</h2>
                        <p class="ffd-panel-sub">Rides scheduled for today</p>
                    </div>
                </header>
                <div class="ffd-table-wrap">
                    <table class="ffd-table">
                        <thead>
                            <tr>
                                <th>No #</th>
                                <th>Ride No</th>
                                <th>Vehicle No</th>
                                <th>Driver Name</th>
                                <th>Customer Name</th>
                                <th>Customer Whatsapp No</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($vehicle_assignments as $vehicle_assignment)
                                <tr>
                                    <td class="ffd-mono">#55879</td>
                                    <td class="ffd-strong">{{ $vehicle_assignment->id ?? null }}</td>
                                    <td>{{ $vehicle_assignment->vehicle->vehicle_identification_number ?? null }}</td>
                                    <td>{{ $vehicle_assignment->driver->name ?? null }}</td>
                                    <td>{{ $vehicle_assignment->order->partner_customer->name ?? null }}</td>
                                    <td>{{ $vehicle_assignment->order->partner_customer->whatsapp_no ?? 'unavailable' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="ffd-empty">No vehicle assignments for today.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            {{-- Upcoming Busy Rides (real data) --}}
            <section class="ffd-panel ffd-block">
                <header class="ffd-panel-head">
                    <div>
                        <h2 class="ffd-panel-title">Upcoming Busy Rides (Next 7 Days)</h2>
                        <p class="ffd-panel-sub">Rides with a vehicle, incomplete or in progress</p>
                    </div>
                </header>
                <div class="ffd-table-wrap">
                    <table class="ffd-table">
                        <thead>
                            <tr>
                                <th>No #</th>
                                <th>Driver Name</th>
                                <th>Vehicle No</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Route Name</th>
                                <th>Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orderDetailsWithVehicle as $order_details_vehicle)
                                @php
                                    // Same status-to-colour mapping as the old dashboard's badges.
                                    $statusTone = match ($order_details_vehicle->status) {
                                        'completed' => 'success',
                                        'cancelled' => 'danger',
                                        'unapproved' => 'warning',
                                        default => 'brand',
                                    };
                                @endphp
                                <tr>
                                    <td class="ffd-mono">#55879</td>
                                    <td class="ffd-strong">{{ $order_details_vehicle->driver->name ?? null }}</td>
                                    <td>{{ $order_details_vehicle->vehicle->vehicle_identification_number ?? null }}</td>
                                    <td class="ffd-mono">{{ $order_details_vehicle->date ?? null }}</td>
                                    <td><span class="ffd-status ffd-status-{{ $statusTone }}">{{ Str::title($order_details_vehicle->status ?? '') }}</span></td>
                                    <td>{{ $order_details_vehicle->rate_list->name ?? null }}</td>
                                    <td class="ffd-mono">{{ $order_details_vehicle->rate ?? null }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="ffd-empty">No busy rides in the next 7 days.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>
        @else
            {{-- Agents and other roles: the same content as the old dashboard, restyled. No sample components. --}}
            <div class="ffd-grid ffd-grid-main-side">
                <section class="ffd-panel">
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Rides Order Status</h2>
                            <p class="ffd-panel-sub">Rides by status</p>
                        </div>
                    </header>
                    <div class="ffd-chart ffd-chart-lg">
                        <canvas id="ffd-chart-rides" role="img" aria-label="Rides order status"></canvas>
                    </div>
                </section>

                <section class="ffd-panel ffd-panel-flex">
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Customers</h2>
                            <p class="ffd-panel-sub">Your customers</p>
                        </div>
                    </header>
                    <ul class="ffd-list">
                        @forelse ($agentallcustomer as $customer)
                            <li class="ffd-list-item">
                                <span class="ffd-avatar" aria-hidden="true">{{ Str::upper(Str::substr($customer->name ?? '', 0, 1)) }}</span>
                                <span class="ffd-list-main">
                                    <span class="ffd-list-title">{{ $customer->name }}</span>
                                    <span class="ffd-list-sub">{{ $customer->email }}</span>
                                </span>
                            </li>
                        @empty
                            <li class="ffd-empty">No customers to show.</li>
                        @endforelse
                    </ul>
                </section>
            </div>
        @endif
    </div>

    @php
        // Chart data for main-dashboard.js. "real" is wired exactly as the old dashboard;
        // "sample" comes only from $placeholderData.
        $ffdData = [
            'real' => [
                'rides' => $isAdmin
                    ? [$totalOrders, $pendingOrders, $incompleteOrders, $completedOrders, $approvedOrders, $unapprovedOrders, $cancelOrders]
                    : [$agenttotalOrders, $agentpendingOrders, $agentincompleteOrders, $agentcompletedOrders, $agentapprovedOrders, $agentunapprovedOrders, $agentcancelOrders],
                'vehicleBookings' => $isAdmin ? $vehicleBookings : [],
            ],
            'sample' => $isAdmin
                ? [
                    'mileage' => $placeholderData['mileage'],
                    'maintenanceCosts' => $placeholderData['maintenance_costs'],
                    'fleetStatus' => $placeholderData['fleet_status'],
                ]
                : null,
        ];
    @endphp
    <script type="application/json" id="ffd-data">@json($ffdData)</script>
@endsection

@section('script')
    @if ($isAdmin)
        <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    @endif
    <script src="{{ asset('assets/js/main-dashboard.js') }}"></script>
@endsection

@extends('layouts.app')

@php
    /*
    |--------------------------------------------------------------------------
    | PLACEHOLDER DATA - sample values copied from the Figma design
    |--------------------------------------------------------------------------
    | Every value below is fake. This array is the ONLY source of sample data on
    | this page: each card marked SAMPLE reads from here and nowhere else.
    | Phase 7 replaces this array with real sources; the markup stays as it is.
    | Checklist: docs/design-tokens-dashboard.md
    */
    $placeholderData = [
        'kpis' => [
            // Total Vehicles, Active Vehicles and Total Drivers show real values ($kpiTiles below);
            // only their change badges and sub-labels are samples.
            'total_vehicles' => ['change' => '+3', 'positive' => true, 'sub' => 'Fleet assets'],
            'active_vehicles' => ['change' => '+5', 'positive' => true, 'sub' => '75.7% utilization'],
            'in_maintenance' => ['value' => '14', 'change' => '+2', 'positive' => false, 'sub' => '9.5% of fleet'],
            'total_drivers' => ['change' => '+4', 'positive' => true, 'sub' => 'Certified & active'],
            'monthly_revenue' => ['value' => '$84,320', 'change' => '+12%', 'positive' => true, 'sub' => 'vs last month'],
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
    // One SAMPLE chip per card; its tooltip says which parts of the card are samples.
    $sampleChip = fn (string $what) => '<span class="ffd-sample" title="' . e($what) . '">Sample</span>';
@endphp

@section('style')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=DM+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap">
@endsection

@section('wrapper')
    {{-- Loaded here (inside body) so it comes after Bootstrap and app.css in the cascade.
         ?v= is the file's modified time, so a changed file is never served from an old browser cache. --}}
    <link rel="stylesheet" href="{{ asset('assets/css/main-dashboard.css') }}?v={{ filemtime(public_path('assets/css/main-dashboard.css')) }}">

    <div class="ffd">
        @if ($isAdmin)
            @php
                // Real values: the same model call the old dashboard made, called once here.
                // vehicles.is_status is active / inactive / sold; Total Vehicles is the current fleet, sold excluded.
                $vehicleStatus = \App\Models\Vehicle::countByStatus();
                $realNote = 'Sample: change badge and sub-label. The %s is real.';

                $kpiTiles = [
                    ['key' => 'total_vehicles', 'label' => 'Total Vehicles', 'icon' => "\u{1F697}",
                        'real' => $vehicleStatus['active'] + $vehicleStatus['inactive'], 'note' => sprintf($realNote, 'vehicle count (active + inactive)')],
                    ['key' => 'active_vehicles', 'label' => 'Active Vehicles', 'icon' => "\u{2705}",
                        'real' => $vehicleStatus['active'], 'note' => sprintf($realNote, 'active vehicle count')],
                    ['key' => 'in_maintenance', 'label' => 'In Maintenance', 'icon' => "\u{1F527}",
                        'real' => null, 'note' => 'Sample: every value on this card. No source yet (Phase 7).'],
                    ['key' => 'total_drivers', 'label' => 'Total Drivers', 'icon' => "\u{1F464}",
                        'real' => $drivers->count(), 'note' => sprintf($realNote, 'driver count')],
                    ['key' => 'monthly_revenue', 'label' => 'Monthly Revenue', 'icon' => "\u{1F4B0}",
                        'real' => null, 'note' => 'Sample: every value on this card. No source yet (Phase 7).'],
                ];
            @endphp

            <div class="ffd-section-row">
                <span class="ffd-section-label">Fleet overview &middot; {{ now()->format('M Y') }}</span>
            </div>

            {{-- Row 1: five KPIs --}}
            <div class="ffd-kpis">
                @foreach ($kpiTiles as $tile)
                    @php
                        $sample = $placeholderData['kpis'][$tile['key']];
                    @endphp
                    <div class="ffd-kpi" data-ffd-sample>
                        <div class="ffd-kpi-top">
                            <span class="ffd-kpi-icon" aria-hidden="true">{{ $tile['icon'] }}</span>
                            <span class="ffd-kpi-marks">
                                {!! $sampleChip($tile['note']) !!}
                                <span class="ffd-change {{ $sample['positive'] ? 'is-pos' : 'is-neg' }}">{{ $sample['change'] }}</span>
                            </span>
                        </div>
                        <div>
                            <div class="ffd-kpi-value">{{ $tile['real'] !== null ? number_format($tile['real']) : $sample['value'] }}</div>
                            <div class="ffd-kpi-label">{{ $tile['label'] }}</div>
                            <div class="ffd-kpi-sub">{{ $sample['sub'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Row 2: Fleet Mileage vs Target + Fleet Insights --}}
            <div class="ffd-grid ffd-grid-main-side">
                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Fleet Mileage vs Target</h2>
                            <p class="ffd-panel-sub">{{ $placeholderData['mileage']['period'] }}</p>
                        </div>
                        {!! $sampleChip('Sample: every value on this chart. No source yet (Phase 7).') !!}
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
                        {!! $sampleChip('Sample: every insight on this card. No insights engine yet (Phase 7).') !!}
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

            {{-- Row 3: Maintenance Costs + Fleet Status + Next Actions --}}
            <div class="ffd-grid ffd-grid-main-two">
                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head ffd-panel-head-chart">
                        <div>
                            <h2 class="ffd-panel-title">Maintenance Costs</h2>
                            <p class="ffd-panel-sub">{{ $placeholderData['maintenance_costs']['period'] }}</p>
                        </div>
                        {!! $sampleChip('Sample: every value on this chart. No source yet (Phase 7).') !!}
                    </header>
                    <div class="ffd-chart">
                        <canvas id="ffd-chart-maintenance" role="img" aria-label="Maintenance costs, sample data"></canvas>
                    </div>
                </section>

                {{-- The design's "148 total vehicles" subtitle is left out on purpose: it would contradict the real Total Vehicles KPI. --}}
                <section class="ffd-panel" data-ffd-sample>
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Fleet Status</h2>
                        </div>
                        {!! $sampleChip("Sample: the donut's figures are design placeholders, unrelated to the KPIs above.") !!}
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
                    <header class="ffd-panel-head">
                        <div>
                            <h2 class="ffd-panel-title">Next Actions</h2>
                            <p class="ffd-panel-sub">{{ count($placeholderData['next_actions']) }} pending tasks</p>
                        </div>
                        {!! $sampleChip('Sample: every task on this card. No task source yet (Phase 7).') !!}
                    </header>
                    {{-- The design puts the pills beside the title; in a 340px card they don't fit, so they get their own row. --}}
                    <div class="ffd-pills ffd-pills-row" role="group" aria-label="Filter tasks by priority">
                        <button type="button" class="ffd-pill is-active" data-ffd-filter="all" aria-pressed="true">All</button>
                        <button type="button" class="ffd-pill" data-ffd-filter="high" aria-pressed="false">High</button>
                        <button type="button" class="ffd-pill" data-ffd-filter="medium" aria-pressed="false">Medium</button>
                        <button type="button" class="ffd-pill" data-ffd-filter="low" aria-pressed="false">Low</button>
                    </div>
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
        @else
            {{-- The design is an admin fleet view with no agent version; agents keep their real dashboard on /. --}}
            <section class="ffd-panel ffd-redirect">
                <h2 class="ffd-panel-title">Your dashboard is on the home page</h2>
                <p class="ffd-redirect-text">This page shows fleet-wide figures for administrators. Your rides and customers are on your own dashboard.</p>
                <a class="ffd-btn ffd-btn-primary" href="{{ url('/') }}">Open my dashboard</a>
            </section>
        @endif
    </div>

    @if ($isAdmin)
        @php
            // Chart data for main-dashboard.js: sample values from $placeholderData only.
            $ffdData = [
                'sample' => [
                    'mileage' => $placeholderData['mileage'],
                    'maintenanceCosts' => $placeholderData['maintenance_costs'],
                    'fleetStatus' => $placeholderData['fleet_status'],
                ],
            ];
        @endphp
        <script type="application/json" id="ffd-data">@json($ffdData)</script>
    @endif
@endsection

@section('script')
    @if ($isAdmin)
        <script src="{{ asset('assets/js/main-dashboard.js') }}?v={{ filemtime(public_path('assets/js/main-dashboard.js')) }}"></script>
    @endif
@endsection

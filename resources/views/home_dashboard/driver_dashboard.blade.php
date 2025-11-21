@extends('layouts.app')
<style>
    .page-content {
        padding: 0 !important;
    }
</style>
@section('style')
    {{-- <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" /> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection

@section('wrapper')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <h3>Driver Dashboard</h3>


                    <div class="d-flex"> <!-- Added a wrapper div for buttons -->

                        <button class="btn btn-primary btn-sm me-2" onclick="location.reload();"><i
                                class="bi bi-arrow-clockwise"></i>Refresh</button> <!-- Refresh button -->
                        <button id="filter-toggle" class="btn btn-success btn-sm" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" onclick="openNav()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-filter">
                                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>
                <div style="background-color:{{ auth()->user()->theme == 'dark-theme' ? '#343a40' : '' }}"
                    class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Fleet Freak</h5>
                        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>


                    <form method="GET" action="{{ route('vehicle_dashboard') }}">
                        <div class="row">
                            @if (auth()->user()->actor_id == 2)
                                <!-- New Agent Select Field -->
                                <div class="col-md-9 col-sm-9 col-xs-9 ms-5 mt-3">
                                    <div class="form-group">
                                        <label style="color: #1A2E97;" for="agent">Agent</label>
                                        <select name="agent" class="form-control" id="agent">
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
                                        <input type="hidden" name="agent_id" id="agent_id"
                                            value="{{ request()->input('agent') }}">
                                        <input type="hidden" name="agent_name" id="agent_name"
                                            value="{{ request()->input('agent_name') }}">
                                        {{-- <input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}"> --}}

                                    </div>
                                </div>
                            @endif
                            <div class="col-md-9 col-sm-9 col-xs-9  mt-3 ms-5">
                                <div class="form-group">
                                    <label style="color: #1A2E97;" for="date">From Date</label>
                                    <input type="date" name="date" class="form-control" id="date"
                                        value="{{ request()->input('date') }}">

                                </div>
                            </div>
                            <div class="col-md-9 col-sm-9 col-xs-9 ms-5 mt-3">
                                <div class="form-group">
                                    <label style="color: #1A2E97;" for="to_date">To Date</label>
                                    <input type="date" name="to_date" class="form-control" id="to_date"
                                        value="{{ request()->input('to_date') }}">

                                </div>
                            </div>
                            {{-- <div class="col-md-6 d-flex align-items-end mt-3 ms-3"> --}}
                            <div class=" mt-3 d-flex justify-content-center">
                                <button class="btn btn-success btn-sm" id="searchBtn" type="submit">Search</button>
                                <button class="btn btn-primary btn-sm ms-5" id="resetBtn" type="button">Reset</button>
                            </div>



                        </div>
                    </form>
                </div>
                @if (auth()->user()->actor_id == 2)
                    <div class="row mb-2 ">
                        <div class="col">
                            <div class="card radius-10 mb-0">
                                <div class="card-body">
                                    <h6>Cnic Expired Details</h6>
                                    <div class="d-flex align-items-center">
                                        {{-- <div>
                                                <h5 class="mb-1">Today Vehicle Assignment</h5>
                                            </div> --}}
                                        {{-- <div class="ms-auto">
                                                <a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
                                            </div> --}}
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table table-montserrat table-hover text-center table-striped">
                                                <tr class="thead t-head-clr table-secondary ">
                                                    <th>No #</th>
                                                    <th>Driver Name</th>
                                                    <th>Email</th>
                                                    <th>Whatsapp No </th>
                                                    <th>Cnic</th>
                                                    <th>Status</th>


                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @php
                                                    $i=0;
                                                @endphp
                                                @foreach ($cnic_expiry_date_details as $cnic_expiry_detail)
                                                    <tr class="text-center">
                                                        <td>{{$i+=1}}</td>
                                                        <td><a href="{{ route('drivers.edit',$cnic_expiry_detail->id) }}"> {{ $cnic_expiry_detail->name ?? null }} </a></td>
                                                        <td>{{ $cnic_expiry_detail->email ?? null }}</td>
                                                        <td>{{ $cnic_expiry_detail->whatsapp_no ?? null }}</td>
                                                        <td>{{ $cnic_expiry_detail->cnic ?? null }}</td>
                                                        <td>
                                                            <span class="badge bg-light-danger text-danger  ">{{ Str::title('Expired') }}

                                                            </span>
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 ">
                        <div class="col">
                            <div class="card radius-10 mb-0">
                                <div class="card-body">
                                    <h6>License Expired Details</h6>
                                    <div class="d-flex align-items-center">
                                        {{-- <div>
                                                <h5 class="mb-1">Today Vehicle Assignment</h5>
                                            </div> --}}
                                        {{-- <div class="ms-auto">
                                                <a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
                                            </div> --}}
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table table-montserrat table-hover text-center table-striped">
                                                <tr class="thead t-head-clr table-secondary ">
                                                    <th>No #</th>
                                                    <th>Driver Name</th>
                                                    <th>Email</th>
                                                    <th>Experience</th>
                                                    <th>Whatsapp no </th>
                                                    <th>License no </th>
                                                    <th>Status </th>



                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @php
                                                    $i=0;
                                                @endphp
                                                @foreach ($license_expiry_date_details as $license_expiry_detail)
                                                    <tr class="text-center">
                                                        <td>{{$i+=1}}</td>

                                                        </td>
                                                        <td><a href="{{ route('drivers.edit',$license_expiry_detail->id) }}"> {{ $license_expiry_detail->name ?? null }} </a></td>
                                                        <td>{{ $license_expiry_detail->email ?? null }}</td>
                                                        <td>{{ $license_expiry_detail->experience ?? null }}</td>
                                                        <td>{{ $license_expiry_detail->whatsapp_no ?? null }}</td>
                                                        <td>{{ $license_expiry_detail->driver_license ?? null }}</td>
                                                        <td>
                                                            <span class="badge bg-light-danger text-danger  ">{{ Str::title('Expired') }}

                                                            </span>
                                                        </td>

                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-1 text-center"></h5>
                    </div>
                    <div class = "row">
                        <div class="col">
                            <div class="card radius-10 overflow-hidden">
                                <div class="card-body">

                                    <div id="driverBarChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
   document.addEventListener('DOMContentLoaded', function () {
            const options = {
                chart: {
                    type: 'bar',
                    height: 350
                },
                series: [{
                    name: 'Expiry Counts',
                    data: [{{ $license_expiry_count }}, {{ $cnic_expiry_count }}] // Pass counts here
                }],
                xaxis: {
                    categories: ['License Expiry', 'CNIC Expiry'], // Labels for the bars
                },
                title: {
                    text: 'Expiry Counts',
                    align: 'center'
                },
                // colors: ['#008000', '#800080'], // Customize bar colors
                plotOptions: {
                    bar: {
                        columnWidth: '20%', // Adjust bar width (percentage value)
                    }
                }

            };

            const chart = new ApexCharts(document.querySelector("#driverBarChart"), options);
            chart.render();
        });
 </script>
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('driverBarChart').getContext('2d');

            var rideOrderChart = new Chart(ctx, {
                type: 'bar',
                // type:line,
                data: {
                    labels: [
                        'Cnic Expiry',
                        'License Expiry',

                    ],
                    datasets: [{
                        label: 'Expiry Counts',
                        data: [{{ $license_expiry_count }}, {{ $cnic_expiry_count }}], // Replace these counts dynamically
                        backgroundColor: [
                            '#4e73df',
                            '#f6c23e',

                        ],
                        borderColor: [
                            '#4e73df',
                            '#f6c23e',

                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            beginAtZero: true
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script> --}}

    {{-- <script>
        // Sample data for the line chart
        const labels = ['Total Rides', 'Pending Rides', 'Incomplete Rides', 'Completed Rides', 'Approved Rides',
            'Unapproved Rides', 'Cancelled Rides'
        ];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Ride Orders',
                // data: [12, 19, 3, 5, 2, 3, 7], // Replace with your actual data counts
                data: [
                    {{ $totalOrders }},
                    {{ $pendingOrders }},
                    {{ $incompleteOrders }},
                    {{ $completedOrders }},
                    {{ $approvedOrders }},
                    {{ $unapprovedOrders }},
                    {{ $cancelOrders }}
                ],
                fill: false, // Set to true if you want to fill the area under the line
                borderColor: 'rgba(75, 192, 192, 1)', // Line color
                tension: 0.1 // Adjust the tension for line smoothing
            }]
        };

        // Configurations for the line chart
        const config = {
            type: 'line', // Change to 'line' for a line chart
            data: data,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Render the line chart
        const rideOrderChart = new Chart(
            document.getElementById('rideOrderChart'),
            config
        );
    </script> --}}


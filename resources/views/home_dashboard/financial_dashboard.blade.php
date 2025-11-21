@extends('layouts.app')
<style>
    
    /* .page-content {
        padding: 0 !important;
    } */
    #chart1 {
        width: 100%;
        height: 300px;
    }
    .btn-group-round .btn {
        border-radius: 20px;
    }
    .radius-10 {
        border-radius: 10px;
    }
</style>
@section('style')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection

@section('wrapper')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <h3>Financial Dashboard</h3>


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


                    <form method="GET" action="{{ route('financial_dashboard') }}">
                        <div class="row">
                            {{-- @if (auth()->user()->actor_id == 2)
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

                                    </div>
                                </div>
                            @endif --}}
                            <div class="col-md-9 col-sm-9 col-xs-9  mt-3 ms-5">
                                <div class="form-group">
                                    <label style="color: #1A2E97;" for="from_date">From Date</label>
                                    <input type="date" name="from_date" class="form-control" id="date"
                                        value="{{ request()->input('from_date') }}">

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
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3  row-cols-xl-4">
                        <div class="col">
                            <!-- Add your target URL here -->
                               <div class="card radius-10 overflow-hidden bg-totalrides">
                                   <div class="card-body">
                                       <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i></span>
                                       <div class="d-flex align-items-center">
       
                                           <div>
       
                                               <h5 class="mb-0 text-white fw-bold">Total Receivables</h5>
                                               {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i></div> --}}
       
                                               <small class="text-white">Receivables.</small>
                                               <h5 class="mb-0 text-white">{{ abs($totalReceivables) }}</h5>
       
                                           </div>
       
                                       </div>
                                       <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                           <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                       </div>
                                   </div>
                               </div>
                        </div>

                        <div class="col">
                            <!-- Add your target URL here -->
                               <div class="card radius-10 overflow-hidden bg-pendingrides">
                                   <div class="card-body">
                                       <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i></span>
                                       <div class="d-flex align-items-center">
       
                                           <div>
       
                                               <h5 class="mb-0 text-white fw-bold">Total Payables</h5>
                                               {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i></div> --}}
       
                                               <small class="text-white">Payables.</small>
                                               <h5 class="mb-0 text-white">{{ abs($total_payable) }}</h5>
       
                                           </div>
       
                                       </div>
                                       <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                           <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                       </div>
                                   </div>
                               </div>
                        </div>

                        <div class="col">
                            <!-- Add your target URL here -->
                               <div class="card radius-10 overflow-hidden bg-Ohhappiness">
                                   <div class="card-body">
                                       <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i></span>
                                       <div class="d-flex align-items-center">
       
                                           <div>
       
                                               <h5 class="mb-0 text-white fw-bold">Total Revenue</h5>
                                               {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i></div> --}}
       
                                               <small class="text-white">Revenue.</small>
                                               <h5 class="mb-0 text-white">{{ abs($total_revenue) }}</h5>
       
                                           </div>
       
                                       </div>
                                       <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                           <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                       </div>
                                   </div>
                               </div>
                        </div>

                        <div class="col">
                            <!-- Add your target URL here -->
                               <div class="card radius-10 overflow-hidden bg-unapprovedrides">
                                   <div class="card-body">
                                       <span class="ms-auto text-white float-end"><i class='bx bx-message font-30'></i></span>
                                       <div class="d-flex align-items-center">
       
                                           <div>
       
                                               <h5 class="mb-0 text-white fw-bold">Total Expenses</h5>
                                               {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i></div> --}}
       
                                               <small class="text-white">Expenses.</small>
                                               <h5 class="mb-0 text-white">{{ abs($total_expenses) }}</h5>
       
                                           </div>
       
                                       </div>
                                       <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                           <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                       </div>
                                   </div>
                               </div>
                        </div>
                    </div>
                @endif
                <div class="card">
                    <div class="card-header border-bottom-0 bg-transparent">
                        <div class="d-lg-flex align-items-center">
                            <div>
                                <h6 class="font-weight-bold mb-2 mb-lg-0">Monthly Revenue</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="chart1"></div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3"> 
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="" id="chart7"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Fuel Cost</h5>
                                    </div>
                                    <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                                </div>
                                <div class="" id="chartFuel"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Maintenance Cost</h5>
                                    </div>
                                    <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                                </div>
                                <div class="" id="chartMaintenance"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
                    <!-- Card 1: Spans 2 columns -->
                    <div class="col d-flex col-md-4 col-xl-8"> <!-- Adjusted column size -->
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="" id="costPerMileChart"></div>
                            </div>
                        </div>
                    </div>
                
                   
                    <div class="col d-flex">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-1">Others Cost</h5>
                                    </div>
                                    <div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i></div>
                                </div>
                                <div class="" id="otherCost"></div>
                            </div>
                        </div>
                    </div>
                
                </div>
                

            </div>
        </div>
    </div>
<script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="assets/plugins/highcharts/js/highcharts.js"></script>
<script src="assets/plugins/highcharts/js/exporting.js"></script>
<script src="assets/plugins/highcharts/js/variable-pie.js"></script>
<script src="assets/plugins/highcharts/js/export-data.js"></script>
<script src="assets/plugins/highcharts/js/accessibility.js"></script>
<script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
<script>
    var revenuechartData = @json($revenuechartData);
    var cost_expenses = @json($cost_expenses);
    var othersCostExpense = @json($othersCostExpense);
    var othersCostExpenseTotalCount = @json($othersCostExpenseTotalCount);
    console.log(othersCostExpenseTotalCount);
    var otherchartData = Object.keys(othersCostExpense).map(function(accountName) {
        console.log("heloooo");
    return {
        name: accountName,   // Name of the expense category
        y: othersCostExpense[accountName] // The total for each category
    };
    console.log(otherchartData);
});
</script>
<script src="assets/js/financial_graph.js"></script>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#resetBtn').click(function() {
                // Reset all form fields
                $('#agent').val('');
                $('#date').val('');
                $('#to_date').val('');
                // $('#selectedAgent').hide();
            });
    })
</script>

{{-- <script>
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
 </script> --}}
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


@extends('layouts.app')
<style>
    /* .page-content {
        padding: 0 !important;
    } */
    .toastui-calendar-detail-container .toastui-calendar-section-header{
        margin-bottom: 0% !important;
    }
    .toastui-calendar-template-popupDetailDate{
        color: #640d5f !important;
    }

    /* .toastui-calendar-ic-state-b {
        background: none !important;
    } */
    .toastui-calendar-icon.toastui-calendar-ic-user-b{
        background: none !important;
    }
</style>
{{-- <link rel="stylesheet" href="https://uicdn.toast.com/tui.calendar/latest/tui-calendar.css" />
<script src="https://uicdn.toast.com/tui.calendar/latest/tui-calendar.min.js"></script> --}}
<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js') }}" ></script>


<!-- main content -->
<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
<link rel="stylesheet" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.css">
<link rel="stylesheet" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.css">

<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.js"></script>
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
@section('wrapper')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <h3>Vehicle Dashboard</h3>


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


                    <form id="filterForm" >
                        <div class="row">
                            <div class="col-md-9 col-sm-9 col-xs-9 ms-5 mt-3">
                                <div class="form-group">
                                    <label style="color: #1A2E97;" for="vehicle_id">Select Vehicle</label>
                                    <select name="vehicle_id" id="vehicle_id" class="form-control">
                                        <option value="">Select Vehicle</option>
                                        @foreach (App\Models\Vehicle::VehicleDropdown() as $vehicle)
                                            <option value="{{ $vehicle->id }}" @if (request()->input('vehicle_id') == $vehicle->id) selected @endif>
                                                {{ $vehicle->vehicle_no }}
                                            </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            @if (auth()->user()->actor_id == 2)
                                <!-- New Agent Select Field -->
                                <div class="col-md-9 col-sm-9 col-xs-9 ms-5 mt-3">
                                    <div class="form-group">
                                       
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
                            <script> 
                            $('#resetBtn').click(function() {
                                // Reset all form fields
                                $('#vehicle_id').val('');
                                $('#date').val('');
                                $('#to_date').val('');
                                // $('#selectedAgent').hide();
                            });</script>



                        </div>
                    </form>
                </div>
                <div class="row row-cols-1 row-cols-lg-3">
					<div class="col">
						<div class="card radius-10">
							<div class="card-body">
								<div id="chart4"></div>
							</div>
						</div>
					</div>
                    <div class="col">
						<div class="card radius-10">
							<div class="card-body">

								<div id="chart6"></div>
							</div>
						</div>
                        
					</div>
                    <div class="col-12 col-lg-4 mt-3">
						<div class="card radius-10 bg-gradient-burning">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<img src="assets/images/icons/appointment-book.png" width="45" alt="" />
									<div class="ms-auto text-end">
										<p class="mb-0 text-white"><i class='bx bxs-arrow-from-bottom'></i> 2.69%</p>
										<p class="mb-0 text-white">Over Due</p>
									</div>
								</div>
								<div class="d-flex align-items-center mt-3">
									<div class="flex-grow-1">
										<p class="mb-1 text-white">Vehicle Inspection</p>
										<h4 class="mb-0 text-white font-weight-bold">{{$inspection_overdue}}</h4>
									</div>
									<div id="chart2"></div>
								</div>
							</div>
						</div>
						<div class="card radius-10 bg-gradient-blues">
							<div class="card-body">
								<div class="d-flex align-items-center">
									<img src="assets/images/icons/surgery.png" width="45" alt="" />
									<div class="ms-auto text-end">
										<p class="mb-0 text-white"><i class='bx bxs-arrow-from-bottom'></i> 3.56%</p>
										<p class="mb-0 text-white">Due Soon</p>
									</div>
								</div>
								<div class="d-flex align-items-center mt-3">
									<div class="flex-grow-1">
										<p class="mb-1 text-white">Vehicle Inspection</p>
										<h4 class="mb-0 text-white font-weight-bold">{{$inspection_duesoon}}</h4>
									</div>
									<div id="chart3"></div>
								</div>
							</div>
						</div>
                        {{-- <div class="card radius-10 w-100">
                            <div class="card-body">
                                <h5 class="mb-3">Fleets Performance</h5>
                                <div id="fleet-performance"></div>
                            </div>
                        </div> --}}
					</div>
				</div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <h5 class="mb-3">Fleets Performance</h5>
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Vehicle</th>
                                            <th>Performance</th>
                                            <th>Revenue</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($topVehicles as $index => $vehicle)
                                        @php
                                            // Calculate percentage
                                            $percentage = ($maxRevenue > 0) ? ($vehicle->total_revenue / $maxRevenue) * 100 : 0;
                                            // Dynamic color logic based on revenue percentage
                                            $progressClass = ($percentage >= 70) ? 'bg-success' :
                                                            (($percentage >= 40) ? 'bg-warning' : 'bg-danger');
                                        @endphp
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td># {{ $vehicle['vehicle_no'] }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="progress w-75" style="height: 6px;">
                                                            <div class="progress-bar {{ $progressClass }}" 
                                                                role="progressbar" 
                                                                style="width: {{ $percentage }}%;" 
                                                                aria-valuenow="{{ $percentage }}" 
                                                                aria-valuemin="0" 
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span class="ms-5">{{ number_format($percentage, 0) }}%</span>
                                                </td>
                                                <td>
                                                    <strong>${{$vehicle['total_revenue']}}</strong>
                                                   
                                                     {{-- <span class="{{ $vehicle['change'] >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $vehicle['change'] >= 0 ? '↑' : '↓' }}
                                            </span> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                
                    {{-- <div class="col-md-4">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <h5 class="mb-3">Another Card</h5>
                                <p>Content for the second column.</p>
                            </div>
                        </div>
                    </div>
                
                    <div class="col-md-4">
                        <div class="card radius-10 w-100">
                            <div class="card-body">
                                <h5 class="mb-3">Another Card</h5>
                                <p>Content for the third column.</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
                
                @if (auth()->user()->actor_id == 2)  
                    <div class="row mb-2 ">
                        <div class="col">
                            <div class="card radius-10 mb-0">
                                <div class="card-body">
                                    <h6>Route Expire Details</h6>
                                    <div class="d-flex align-items-center">
                                        {{-- <div>
                                                <h5 class="mb-1">Today Vehicle Assignment</h5>
                                            </div> --}}
                                        {{-- <div class="ms-auto">
                                                <a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
                                            </div> --}}
                                    </div>

                                    <div class="table-responsive mt-3" style="max-height: 310px; overflow-y: auto; overflow-x: auto;">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table table-montserrat table-hover text-center table-striped">
                                                <tr class="thead t-head-clr table-secondary">
                                                    <th>No #</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver Name</th>
                                                    <th>Route Permit No</th>
                                                    <th>Route Permit Expiry Date</th>
                                                    <th>Status</th>


                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @php
                                                    $i=0;
                                                @endphp
                                                @foreach ($route_expiry_details as $route_expiry_detail)
                                                    <tr class="text-center">
                                                        <td>{{$i+=1}}</td>
                                                        <td>
                                                            <a href="{{ route('vehicles.edit',$route_expiry_detail->id) }}">
                                                                {{ $route_expiry_detail->vehicle_no ?? null }}
                                                            </a>
                                                                  
                                                        </td>
                                                        </td>
                                                        <td>{{ $route_expiry_detail->driver->name ?? null }}</td>
                                                        <td>{{ $route_expiry_detail->route_permits_no ?? null }}</td>
                                                        <td>{{ $route_expiry_detail->route_permits_expiry_date ?? '---' }}</td>
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
                                    <h6>Vehicle Inspection</h6>
                                    <div class="d-flex align-items-center">
                                        {{-- <div>
                                                <h5 class="mb-1">Today Vehicle Assignment</h5>
                                            </div> --}}
                                        {{-- <div class="ms-auto">
                                                <a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
                                            </div> --}}
                                    </div>
                                    <div class="table-responsive mt-3" style="max-height: 300px; overflow-y: auto; overflow-x: auto;">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table table-montserrat table-hover text-center table-striped">
                                                <tr class="thead t-head-clr table-secondary">
                                                    <th>No #</th>
                                                    <th>Vehicle No</th>
                                                    <th>Model</th>
                                                    <th>Driver Name</th>
                                                    <th>date</th>
                                                    <th>Start Time</th>
                                                    <th>End Time</th>
                                                    <th>Status</th>
                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @php
                                                    $i=0;
                                                @endphp
                                                @foreach ($maintainence_vehicles as $maintainence_vehicle)
                                                    <tr class="text-center">
                                                        <td>{{$i+=1}}</td>
                                                        <td>
                                                            {{ $maintainence_vehicle->vehicle->vehicle_no ?? 'N/A' }}      
                                                        </td>
                                                        <td>
                                                            {{ $maintainence_vehicle->vehicle->vehicleModel->name ?? 'N/A' }}      
                                                        </td>
                                                        </td>
                                                        <td>{{ $maintainence_vehicle->vehicle->driver->name ?? 'N/A' }}</td>
                                                        <td>{{ $maintainence_vehicle->date ?? '---' }}</td>
                                                        <td>{{ $maintainence_vehicle->start_time ?? '---' }}</td>
                                                        <td>{{ $maintainence_vehicle->end_time ?? '---' }}</td>
                                                        <td>
                                                            <span class="badge bg-light-warning text-warning  ">{{ Str::title('Engaged') }}

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
                                    <h6>Vehicle Busy Rides</h6>
                                    <div class="d-flex align-items-center">
                                        {{-- <div>
                                                <h5 class="mb-1">Today Vehicle Assignment</h5>
                                            </div> --}}
                                        {{-- <div class="ms-auto">
                                                <a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
                                            </div> --}}
                                    </div>

                                    {{-- <div class="table-responsive mt-3" style="max-height: 310px; overflow-y: auto; overflow-x: auto;">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table table-montserrat text-center table-hover table-striped">
                                                <tr class="thead t-head-clr table-secondary">
                                                    <th>No #</th>
                                                    <th>Vehicle No</th>
                                                    <th>Model</th>
                                                    <th>Driver Name</th>
                                                    <th>Trip type</th>
                                                    <th>Start date</th>
                                                    <th>End date</th>
                                                    <th>Time</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @php
                                                    $i=0;
                                                @endphp
                                                 @foreach ($vehicle_busy as $tripType => $vehicles)
                                                 @foreach ($vehicles as $vehicle)
                                                    <tr class="text-center">
                                                        <td>{{$i+=1}}</td>
                                                        <td>
                                                            {{ $vehicle->vehicle->vehicle_no ?? '---' }}      
                                                        </td>
                                                        <td>
                                                            {{ $vehicle->vehicle->vehicleModel->name ?? null }}      
                                                        </td>
                                                        </td>
                                                        <td>{{ $vehicle->vehicle->driver->name ?? 'N/A' }}</td>
                                                        <td>{{ $vehicle->order->trip_type ?? 'N/A' }}</td>
                                                        <td>{{ $vehicle->date ?? '---' }}</td>
                                                        <td>{{ $vehicle->end_date ?? '---' }}</td>
                                                        <td>{{ $vehicle->pickup_time ?? '---' }}</td>
                                                        <td>
                                                            <span class="badge bg-light-warning text-warning  ">{{ Str::title('Engaged') }}

                                                            </span>
                                                        </td>
                                                        
                                                    </tr>
                                                @endforeach
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div> --}}
                                    {{-- <div id="calendar" style="height: 800px;"></div> --}}
                                   <!-- Calendar toolbar -->
                                    <div class="calendar-toolbar">
                                        <input type="hidden" id="defaultViewType" value="month">

                                        <div class="btn-group ml-3 mr-2">
                                            <button type="button" class="btn dropdown-toggle monthly-color" id="viewToggleBtn" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                Monthly
                                            </button>

                                            <div class="dropdown-menu">
                                                <button class="dropdown-item" id="dayViewBtn">Daily</button>
                                                <button class="dropdown-item" id="weekViewBtn">Weekly</button>
                                                <button class="dropdown-item" id="monthViewBtn">Monthly</button>
                                            </div>
                                        </div>
                                        <div>
                                            <button id="todayBtn" class="todaybtn btn btn-danger">Today</button>
                                            <div class="btn-group ml-2" role="group" aria-label="Basic example" style="margin-left: 10px !important;">
                                                <button id="prevBtn" class="btn btn-rounded-x prev-next prev-btn"><</button>
                                                <button id="nextBtn" class="btn btn-rounded-x prev-next next-btn">></button>
                                            </div>
                                            <span id="selectedMonth" class="ml-2"></span>
                                        </div>
                                    </div>

                                    <!-- Calendar container -->
                                    <div id="calendar"></div>


                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
				</div>
            </div>
        </div>
    </div>
<script src="assets/plugins/highcharts/js/highcharts.js"></script>
<script src="assets/plugins/highcharts/js/highcharts-more.js"></script>
<script src="assets/plugins/highcharts/js/variable-pie.js"></script>
<script src="assets/plugins/highcharts/js/solid-gauge.js"></script>
<script src="assets/plugins/highcharts/js/highcharts-3d.js"></script>
<script src="assets/plugins/highcharts/js/cylinder.js"></script>
<script src="assets/plugins/highcharts/js/funnel3d.js"></script>
<script src="assets/plugins/highcharts/js/exporting.js"></script>
<script src="assets/plugins/highcharts/js/export-data.js"></script>
<script src="assets/plugins/highcharts/js/accessibility.js"></script>
<script>

    // most vehicle revenue performance:
    // var vehicleData = @json($topVehicles);
    // -------------------
    var vehicle_rides_busy_count =  @json($vehicle_rides_busy_count);

    var maintainence_vehicles_count = {"Inspection": @json($maintainence_vehicles_count)};

    var vehicle_rides_busy_count = Object.entries(vehicle_rides_busy_count).map(([key, value]) => ({
            name: key,
            y: value
        }));
    // Convert maintainence_vehicles_count to the same structure
    var maintainenceData = Object.entries(maintainence_vehicles_count).map(([key, value]) => ({
        name: key,
        y: value
    }));
    vehicle_rides_busy_count = vehicle_rides_busy_count.concat(maintainenceData);

    // all rides count
    var countsByTripType =  @json($countsByTripType);
    var tripNames = Object.keys(countsByTripType); // Trip types (e.g., 'monthly_booking', 'tour_booking', etc.)
    var tripCounts = Object.values(countsByTripType); // Corresponding counts for each trip type
    var barColors = ['#7c6cfb', '#02c9ef', '#f7a103', '#FF5733', '#33FF57'];
    
    document.addEventListener('DOMContentLoaded', function () {
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "{{ route('vehicle_dashboard') }}", 
                type: "GET",
                data: $(this).serialize(),
                beforeSend: function() {
                    $('#dashboardResults').html('<div class="text-center">Loading...</div>');
                },
                success: function(response) {
                    $('#dashboardResults').html(response);
                },
                error: function(xhr) {
                    console.log(xhr.responseText);
                }
            });
        });

        // Reset button function
        $('#resetBtn').click(function() {
            $('#filterForm')[0].reset();
            $('#filterForm').submit(); // Trigger AJAX request after reset
        });
    });

   
        // Initialize the calendar
        // document.getelementbyClassName('toastui-calendar-template-popupDetailState').style.display = 'none';
        // Initialize the calendar
        let calendar = new tui.Calendar('#calendar', {
            defaultView: 'month', // Default view type
            taskView: false, // Hide task view
            theme: {
                    // Light background color for month schedule items
                    'month.schedule.backgroundColor': '#e0f7fa',
                    'month.schedule.borderRadius': '4px',
                    'month.schedule.border': '1px solid #80deea',
                    'month.schedule.color': '#004d40', // Text color
                    },
            scheduleView: true, // Show schedule view
            useCreationPopup: true, // Enable event creation popup
            useDetailPopup: true, // Enable event detail popup
            template: {
                time: function(schedule) {
                    console.log(schedule);
                    return `
                    ${toTitleCase(schedule.raw.vehicle_no)} - ${
                        schedule.raw.pickup_time
                            ? new Date(`2025-01-29T${schedule.raw.pickup_time}`).toLocaleTimeString('en-US', {
                                hour: '2-digit',
                                minute: '2-digit',
                                hour12: true
                            })
                            : schedule.title
                    }`; // Ensure only the title is displayed
                } ,
                popupDetailBody({
                    body,
                    raw
                }) {
                    // Check if user_id is not 3 (admin) before displaying the buttons
                    // const showButtons = raw.user_id !== 3;

                    return `
                        <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333; line-height: 1.5; padding: 10px; border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f9f9f9;">
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;" width="16" height="16" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                                <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                                </svg>
                        
                                <strong style="color: #ff5722;">Vehicle Model:</strong> <span style="margin-left: 4px;">${toTitleCase(raw.vehicle_model)}</span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-calendar"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line>
                                </svg>
                                <strong style="color: #4caf50;">Trip Date:</strong> <span style="margin-left: 4px;">${raw.date}</span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 8px;">
                                
                            <svg xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-map-pin"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                
                                <strong style="color: #2196f3;">Route:</strong> <span style="margin-left: 4px;">${toTitleCase(raw.from_loc ?? '---')} - ${toTitleCase(raw.to_loc ?? '---')}</span>
                            </div>
                            <div style="display: flex; align-items: center;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px;">
                                    <circle cx="10" cy="10" r="10" fill="black"/>
                                    <text x="10" y="13" font-size="10" text-anchor="middle" fill="white">!</text>
                                </svg>
                                <b style="color: #f44336;">Status:</b> <span style="margin-left: 4px;">${toTitleCase(raw.status)}</span>
                            </div>
                        </div>
                        `;
                },
                popupDetailDate: function(schedule) {
                    return `${schedule.raw.date} `; // Display the vehicle number and driver name
                },
                
            },
        });

        // Load events from the server
        var events = @json($events); // Replace with your backend event data
        console.log(events); // Log the events to check the data
        // Clear existing events and add new ones
        // calendar.clear(); // Clear any existing events
        // events.forEach(event => calendar.createEvents([event])); // Add events dynamically
        // Clear existing events and add new ones
        if (events && Array.isArray(events)) {
            calendar.clear(); // Clear any existing events
            events.forEach(event => {
                // Ensure the event data is valid
                if (event.id && event.vehicle_no && event.driver && event.start && event.title) {
                    calendar.createEvents([{
                id: event.id,
                calendarId: '1', // Optional: If you use multiple calendars
                title: event.title,
                state:'',
                category: 'time', // Specify this is a time-based event
                start: event.start,
                end: event.end,
                isReadOnly: true, // Prevent editing the event
                color: '#fff',
                backgroundColor: '#dc3545', // Red for holidays
                borderColor: '#bd2130',// Border color
                body: 'test description',
                raw: {
                    status:event.status,
                    vehicle_no: event.vehicle_no,
                    driver: event.driver,
                    pickup_time: event.pickup_time,
                    vehicle_model: event.vehicle_model,
                    from_loc: event.from_loc,
                    to_loc: event.to_loc,
                    date: event.date,
                }
            }]);
        }  else {
                    console.warn('Skipping invalid event:', event); // Warn if event data is incomplete
                }
            });
        } else {
            console.error('No events data found or data is not in the correct format.');
        }

        // Update the displayed month
        function updateSelectedMonth() {
            const currentDate = calendar.getDate(); // Get the current date
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1; // Months are zero-based
            document.getElementById('selectedMonth').textContent = `${year}-${month.toString().padStart(2, '0')}`;
        }
        updateSelectedMonth(); // Update the initial display

        // Toolbar button functionality
        document.getElementById('todayBtn').addEventListener('click', () => {
            calendar.today(); // Navigate to today
            updateSelectedMonth(); // Update the month display
        });

        document.getElementById('prevBtn').addEventListener('click', () => {
            calendar.prev(); // Navigate to the previous period
            updateSelectedMonth();
        });

        document.getElementById('nextBtn').addEventListener('click', () => {
            calendar.next(); // Navigate to the next period
            updateSelectedMonth();
        });

        // View toggle functionality
        document.getElementById('dayViewBtn').addEventListener('click', () => {
            calendar.changeView('day', true); // Change to daily view
            document.getElementById('viewToggleBtn').textContent = 'Daily';
        });

        document.getElementById('weekViewBtn').addEventListener('click', () => {
            calendar.changeView('week', true); // Change to weekly view
            document.getElementById('viewToggleBtn').textContent = 'Weekly';
        });

        document.getElementById('monthViewBtn').addEventListener('click', () => {
            calendar.changeView('month', true); // Change to monthly view
            document.getElementById('viewToggleBtn').textContent = 'Monthly';
        });
        function toTitleCase(str) {
            return str
                .toLowerCase()
                .split(' ')
                .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                .join(' ');
        }
    });

    
</script>

<script src="assets/js/vehicle_dashboard.js"></script>

@endsection

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('rideOrderChart').getContext('2d');

            var rideOrderChart = new Chart(ctx, {
                type: 'bar',
                // type:line,
                data: {
                    labels: [
                        'Total Rides',
                        'Pending Rides',
                        'Incomplete Rides',
                        'Completed Rides',
                        'Approved Rides',
                        'Unapproved Rides',
                        'Cancel Rides'
                    ],
                    datasets: [{
                        label: 'Number of Rides',
                        data: [120, 50, 30, 200, 80, 40, 15], // Replace these counts dynamically
                        backgroundColor: [
                            '#4e73df',
                            '#f6c23e',
                            '#e74a3b',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b'
                        ],
                        borderColor: [
                            '#4e73df',
                            '#f6c23e',
                            '#e74a3b',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b'
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


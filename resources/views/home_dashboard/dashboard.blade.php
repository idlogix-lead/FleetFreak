@extends('layouts.app')
<style>
    .page-content {
        padding: 0 !important;
    }
</style>
@section('style')
    <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
@endsection

@section('wrapper')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="d-flex justify-content-between align-items-center pt-4">
                    <h3>FleetFreak Dashboard</h3>


                    <div class="d-flex"> <!-- Added a wrapper div for buttons -->
                        @if (isset($agentName) && !empty($agentName))
                            <!-- Check if agent name exists and is not empty -->
                            {{-- <h4 id="selectedAgent"> --}}
                            <div class="agent-box" id="selectedAgent">
                                {{-- Selected Agent: {{ $agentName }}<br> --}}
                                {{ $agentName }}
                            </div>
                            {{-- </h4> --}}
                        @endif
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


                    <form method="GET" action="{{ route('dashboard') }}">
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
                    <div class="my-4">
                        {{-- for admin  --}}
                        @php
                            $agent = request()->input('agent');
                            $date = request()->input('date');
                            $toDate = request()->input('to_date');
                        @endphp


                        <div class="row">
                            <div class="col-md-4 d-flex">
                                <div class="card w-100 card-color">
                                    <div class="card-body">
                                        <h5 class="card-title">Vehicle Status</h5>

                                        <ul class="list-group custom-bullets">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center borderless py-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#28a745"
                                                    class="bi bi-dot" viewBox="0 0 16 16">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                </svg> Active
                                                <span
                                                    class="badge bg-success rounded-pill ms-auto">{{ \App\Models\Vehicle::countByStatus()['active'] }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center borderless py-0">
                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                        fill="#ffc107" class="bi bi-dot" viewBox="0 0 16 16">
                                                        <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                    </svg>Inactive
                                                </span>
                                                <span
                                                    class="badge bg-warning rounded-pill ms-auto">{{ \App\Models\Vehicle::countByStatus()['inactive'] }}</span>
                                            </li>

                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center borderless py-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                    fill="#dc3545" class="bi bi-dot" viewBox="0 0 16 16">
                                                    <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                </svg>Sold
                                                <span
                                                    class="badge bg-danger rounded-pill ms-auto">{{ \App\Models\Vehicle::countByStatus()['sold'] }}</span>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>


                            <!-- Second Card -->
                            <div class="col-md-4 d-flex">
                                <div class="card w-100 card-color">
                                    <div class="card-body">
                                        <h5 class="card-title ">Vehicle Assignment</h5>
                                        <div
                                            class="d-flex justify-content-between justify-content-md-center align-items-center flex-column flex-md-row">
                                            <!-- Flex container for side-by-side layout on larger screens, vertical on smaller screens -->
                                            <!-- Assigned count -->
                                            <div class= "mt-4 text-center">
                                                <h2 class="fw-bold mb-0"
                                                    style="color: rgb(38, 165, 38) !important; margin-right:30px;">
                                                    {{ $incompleteOrdersWithDriver }}</h2> <!-- Bold number -->
                                               <a href="{{ route('driver_assignments.incomplete_rides') }}"><span class="text-muted" style="margin-right:30px;">Assigned with driver</span></a>
                                                <!-- Label under the number -->
                                            </div>
                                            <!-- Unassigned count -->
                                            <div class="mt-1 text-center">
                                                <h2 class="fw-bold mb-0" style="color: rgb(197, 78, 78) !important; ">
                                                    {{ $approvedOrders }}</h2> <!-- Bold number -->
                                               <a href="{{ route('driver_assignments.index') }}"><span class="text-muted">Unassigned</span></a> <!-- Label under the number -->
                                            </div>
                                              <div class= "mt-4 text-center">
                                                <h2 class="fw-bold mb-0"
                                                    style="color: rgb(38, 165, 38) !important; margin-right:30px;">
                                                    {{ $incompleteOrderWithoutDriver }}</h2> <!-- Bold number -->
                                               <a href="{{ route('driver_assignments.incomplete_rides') }}"><span class="text-muted" style="margin-right:30px;">Assigned without driver</span></a>
                                                <!-- Label under the number -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <!-- Third Card -->

                            <div class="col-md-4 d-flex">
                                <div class="card w-100 card-color">
                                    {{-- <div class="card-header">Payment</div> --}}
                                    <div class="card-body">
                                        <h5 class="card-title">Payment</h5>
                                        <ul class="list-group custom-bullets">
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center borderless py-0">
                                                <div class="d-flex align-items-center"> <!-- Flex container for icon and text -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                        fill="#28a745" class="bi bi-dot me-2" viewBox="0 0 16 16">
                                                        <!-- Added 'me-2' for margin -->
                                                        <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                    </svg>
                                                    <span>Paid</span>
                                                </div>
                                                <span
                                                    class="badge bg-success rounded-pill">{{ \App\Models\OrderDetail::vehivcle_details()['paid'] }}</span>
                                            </li>
                                            <li
                                                class="list-group-item d-flex justify-content-between align-items-center borderless py-0">
                                                <div class="d-flex align-items-center"> <!-- Flex container for icon and text -->
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40"
                                                        fill="#ffc107" class="bi bi-dot me-2" viewBox="0 0 16 16">
                                                        <!-- Added 'me-2' for margin -->
                                                        <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3" />
                                                    </svg>
                                                    <span>Unpaid</span>
                                                </div>
                                                <span
                                                    class="badge bg-warning rounded-pill">{{ \App\Models\OrderDetail::vehivcle_details()['unpaid'] }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Third Card -->
                        </div>
                        <h4 class="text-center mb-3 mt-4">Rides Order Status</h4>
                        <!-- Use text-center class from Bootstrap for centering -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card custom-card"> <!-- Use a custom class -->
                                    <div class="card-body custom-card-body"> <!-- Use a custom class for the body -->
                                        <canvas id="rideOrderChart" class="custom-canvas"></canvas>
                                        <!-- Use a custom class for the canvas -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @else
                    {{-- for agent --}}
                    @php
                        // $agent = auth()->user()->partner_id;
                        $date = request()->input('date');
                        $toDate = request()->input('to_date');
                    @endphp
                    <div class="my-4">
                        <h4 class="text-center mb-3">Rides Order Status</h4>
                        <!-- Use text-center class from Bootstrap for centering -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card custom-card"> <!-- Use a custom class -->
                                    <div class="card-body custom-card-body"> <!-- Use a custom class for the body -->
                                        <canvas id="rideOrderChart" class="custom-canvas"></canvas>
                                        <!-- Use a custom class for the canvas -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
                @if (auth()->user()->actor_id == 2)
                    <div>
                        <h5 class="mb-1 text-center">Today Vehicle Assignment</h5>
                    </div>
                    <div class = "row">
                        <div class="col">
                            <div class="card radius-10 overflow-hidden">
                                <div class="card-body">
                                    {{-- @php
                                            $countries = App\Models\Partner::CustomerCountry();
                                        @endphp

                                        @if ($countries->isNotEmpty())
                                            <div class="form-group col-md-4">
                                                <label for="country-filter">Select Country:</label>
                                                <form id="filter-form" method="GET" action="{{ route('dashboard') }}">
                                                    <select id="country-filter" name="country" class="form-control">
                                                        <option value="">All Countries</option>
                                                        @foreach ($countries as $customer)
                                                            <option value="{{ $customer->country }}" {{ request('country') == $customer->country ? 'selected' : '' }}>
                                                                {{ $customer->country }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </div>

                                        @endif --}}


                                    <div id="vehiclesBarChart"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 ">
                        <div class="col">
                            <div class="card radius-10 mb-0">
                                <div class="card-body">
                                    <h4>Vehicle Details</h4>
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
                                            <thead class="table-light table-montserrat ">
                                                <tr class="t-head-clr">
                                                    <th>No #</th>
                                                    <th>Ride No</th>
                                                    <th>Vehicle No</th>
                                                    <th>Driver Name</th>
                                                    <th>Customer Name</th>
                                                    <th>Customer Whatsapp No</th>


                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @foreach ($vehicle_assignments as $vehicle_assignment)
                                                    <tr>
                                                        <td>#55879</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <img src="assets/images/avatars/avatar-1.png"
                                                                        class="rounded-circle" width="46" height="46"
                                                                        alt="" />
                                                                </div>
                                                                <div class="ms-2">
                                                                    <h6 class="mb-1 font-14">{{ $vehicle_assignment->id ?? null }}
                                                                    </h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $vehicle_assignment->vehicle->vehicle_identification_number ?? null }}
                                                        </td>
                                                        <td>{{ $vehicle_assignment->driver->name ?? null }}</td>
                                                        <td>{{ $vehicle_assignment->order->partner_customer->name ?? null }}</td>
                                                        <td>{{ $vehicle_assignment->order->partner_customer->whatsapp_no ?? 'unavailable' }}
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
                @endif
                @if (auth()->user()->actor_id == 2)
                    <div class="row">
                        <div class="col-12 col-xl-4 d-flex">
                            <div class="card radius-10 w-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h5 class="mb-0">Top Agents</h5>
                                        </div>
                                        <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="customers-list p-3 mb-3" style="overflow-y: auto;">
                                    @foreach ($topAgents as $topAgent)
                                        <div
                                            class="customers-list-item d-flex align-items-center border-top border-bottom p-2 cursor-pointer">
                                            <div class="">
                                                <img src="assets/images/avatars/default.png" class="rounded-circle"
                                                    width="46" height="46" alt="" />
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $topAgent['agent_name'] }}</h6>
                                                {{-- <p class="mb-0 font-13 text-secondary">{{$customer->email}}</p> --}}
                                            </div>
                                            <div class="list-inline d-flex customers-contacts ms-auto">
                                                <h6><b>Total Sales: </b><span>{{ $topAgent['total_sales'] }}</span></h6>

                                            </div>
                                            {{-- <div class="list-inline d-flex customers-contacts ms-auto">	<a href="javascript:;" class="list-inline-item"><i class='bx bxs-envelope'></i></a>
                                            <a href="javascript:;" class="list-inline-item"><i class='bx bxs-phone' ></i></a>
                                            <a href="javascript:;" class="list-inline-item"><i class='bx bx-dots-vertical-rounded'></i></a>
                                        </div> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- drivers --}}
                        <div class="col-12 col-xl-4 d-flex">
                            <div class="card radius-10 w-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h5 class="mb-0">Drivers</h5>
                                        </div>
                                        <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="customers-list p-3 mb-3" style="overflow-y: auto;">
                                    @foreach ($drivers as $driver)
                                        <div
                                            class="customers-list-item d-flex align-items-center border-top border-bottom p-2 cursor-pointer">
                                            <div class="">
                                                <img src="assets/images/avatars/default.png" class="rounded-circle"
                                                    width="46" height="46" alt="" />
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $driver->name }}</h6>
                                                <p class="mb-0 font-13 text-secondary">{{ $driver->email }}</p>
                                            </div>
                                            {{-- <div class="list-inline d-flex customers-contacts">	<a href="javascript:;" class="list-inline-item"><i class='bx bxs-envelope'></i></a>
                                                <a href="javascript:;" class="list-inline-item"><i class='bx bxs-phone' ></i></a>
                                                <a href="javascript:;" class="list-inline-item"><i class='bx bx-dots-vertical-rounded'></i></a>
                                            </div> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- for agent --}}
                    <div class="row">
                        <div class="col-12 col-xl-4 d-flex">
                            <div class="card radius-10 w-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h5 class="mb-0">Customers</h5>
                                        </div>
                                        <div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="customers-list p-3 mb-3">
                                    @foreach ($agentallcustomer as $customer)
                                        <div
                                            class="customers-list-item d-flex align-items-center border-top border-bottom p-2 cursor-pointer">
                                            <div class="">
                                                <img src="assets/images/avatars/default.png" class="rounded-circle"
                                                    width="46" height="46" alt="" />
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1 font-14">{{ $customer->name }}</h6>
                                                <p class="mb-0 font-13 text-secondary">{{ $customer->email }}</p>
                                            </div>
                                            {{-- <div class="list-inline d-flex customers-contacts ms-auto"> <a href="javascript:;"
                                                        class="list-inline-item"><i class='bx bxs-envelope'></i></a>
                                                    <a href="javascript:;" class="list-inline-item"><i
                                                            class='bx bxs-phone'></i></a>
                                                    <a href="javascript:;" class="list-inline-item"><i
                                                            class='bx bx-dots-vertical-rounded'></i></a>
                                                </div> --}}
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if (auth()->user()->actor_id == 2)
                    <div class="row">
                        <div class="col">
                            <div class="card radius-10 mb-5">
                                <div class="card-body ">
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <h5 class="mb-1">Upcoming Busy Rides (Next 7 Days)</h5>
                                        </div>
                                    </div>

                                    <div class="table-responsive mt-3">
                                        <table class="table align-middle mb-0">
                                            @include('ledgers.partials.app')
                                            <thead class="table-light table-montserrat">
                                                <tr class="t-head-clr">
                                                    <th>No #</th>
                                                    <th>Driver Name</th>
                                                    <th>Vehicle No</th>
                                                    <th>Date</th>
                                                    <th>status</th>
                                                    <th>Route Name</th>
                                                    <th>Rate</th>

                                                    {{-- <th>Actions</th> --}}
                                                </tr>
                                            </thead>
                                            <tbody class="body-font">
                                                @foreach ($orderDetailsWithVehicle as $order_details_vehicle)
                                                    <tr>
                                                        <td>#55879</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <img src="assets/images/avatars/avatar-1.png"
                                                                        class="rounded-circle" width="46" height="46"
                                                                        alt="" />
                                                                </div>
                                                                <div class="ms-2">
                                                                    <h6 class="mb-1 font-14">
                                                                        {{ $order_details_vehicle->driver->name ?? null }}</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ $order_details_vehicle->vehicle->vehicle_identification_number ?? null }}
                                                        </td>
                                                        <td>{{ $order_details_vehicle->date ?? null }}</td>
                                                        <td>
                                                            @php
                                                                switch ($order_details_vehicle->status) {
                                                                    case 'completed':
                                                                        $badgeClass = 'success';
                                                                        break;
                                                                    case 'cancelled':
                                                                        $badgeClass = 'danger';
                                                                        break;
                                                                    case 'draft':
                                                                        $badgeClass = 'primary';
                                                                        break;
                                                                    case 'unapproved':
                                                                        $badgeClass = 'warning';
                                                                        break;
                                                                    default:
                                                                        $badgeClass = 'primary';
                                                                }
                                                            @endphp
                                                            <span
                                                                class="badge bg-light-{{ $badgeClass }} text-{{ $badgeClass }}  ">{{ Str::title($order_details_vehicle->status ?? null) }}</span>
                                                        </td>
                                                        <td>{{ $order_details_vehicle->rate_list->name ?? null }}</td>
                                                        <td>
                                                            {{ $order_details_vehicle->rate ?? null }}
                                                        </td>


                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div><!--end row-->
                @endif
            </div>
        </div>
    </div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        $('#filter-toggle').click(function() {
            $('#filter-dropdown').toggle();
        });
        $('#resetBtn').click(function() {
            // Reset all form fields
            $('#agent').val('');
            $('#date').val('');
            $('#to_date').val('');
            $('#selectedAgent').hide();
        });
        var options = {
            series: [{{ $bookedCount }}],
            chart: {
                type: 'pie',
                height: 400, // Set the height of the chart
                width: '100%' // Set the width of the chart
            },
            labels: ['Booked Vehicles'],
            colors: ['#ff6384'],
            legend: {
                position: 'top'
            },
            tooltip: {
                y: {
                    formatter: function(value) {
                        return value.toFixed();
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#vehiclePieChart"), options);
        chart.render();
        // bar chart:
        var options = {
            series: [{
                name: 'Paid Rides',
                data: [{{ $paidrides }}]
            }, {
                name: 'Unpaid Rides',
                data: [{{ $unpaidrides }}]
            }],
            chart: {
                type: 'bar',
                height: 300
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    endingShape: 'rounded'
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: ['Rides']
            },
            yaxis: {
                title: {
                    text: 'Count'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return val.toFixed();
                    }
                }
            },
            colors: ['#c9cbcf', '#2c3e50']
        };

        var chart = new ApexCharts(document.querySelector("#paidunpaidChart"), options);
        chart.render();
        // ------vehicle details

        var vehicleBookings = @json($vehicleBookings);

        var options = {
            chart: {
                type: 'bar',
                height: 350
            },
            series: [{
                name: 'Estimated Time (in hours)',
                data: vehicleBookings,
                colors: ['#775DD0']
            }],
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '30%',
                    endingShape: 'flat'
                }
            },
            xaxis: {
                title: {
                    text: 'Vehicles'
                },
                categories: vehicleBookings.map(item => `${item.x} (${item.vehiclesCount})`)
            },
            yaxis: {
                title: {
                    text: 'Time (in hours)'
                },
                min: 0,
                max: 12,
                tickAmount: 11,
                labels: {
                    formatter: function(val) {
                        return val.toFixed(0); // Show whole numbers only
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#vehiclesBarChart"), options);
        chart.render();

        // Handle country filter change
        $('#country-filter').on('change', function() {
            $('#filter-form').submit(); // Submit the form on change
        });
    });
</script>

@section('script')
    <script src="assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
    <script src="assets/plugins/highcharts/js/highcharts.js"></script>
    <script src="assets/plugins/highcharts/js/exporting.js"></script>
    <script src="assets/plugins/highcharts/js/variable-pie.js"></script>
    <script src="assets/plugins/highcharts/js/export-data.js"></script>
    <script src="assets/plugins/highcharts/js/accessibility.js"></script>
    <script src="assets/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
    <script src="assets/js/index2.js"></script>
    <script>
        new PerfectScrollbar('.customers-list');
        new PerfectScrollbar('.store-metrics');
        new PerfectScrollbar('.product-list');
    </script>


    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>
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
    <script>
        // Sample data for the line chart
        const labels = ['Total Rides', 'Pending Rides', 'Incomplete Rides', 'Completed Rides', 'Approved Rides',
            'Unapproved Rides', 'Cancelled Rides'
        ];
        // Determine if the user is an agent or an admin
        const isAdmin =
            {{ auth()->user()->actor_id == 2 ? 'true' : 'false' }}; // Adjust this line based on your user role logic

        const data = {
            labels: labels,
            datasets: [{
                label: 'Ride Orders',
                // Data for the chart (Replace with your actual data counts)
                // Check if the user is admin or agent and assign the corresponding data
                data: isAdmin ? [
                    {{ $totalOrders }},
                    {{ $pendingOrders }},
                    {{ $incompleteOrders }},
                    {{ $completedOrders }},
                    {{ $approvedOrders }},
                    {{ $unapprovedOrders }},
                    {{ $cancelOrders }}
                ] : [
                    {{ $agenttotalOrders }},
                    {{ $agentpendingOrders }},
                    {{ $agentincompleteOrders }},
                    {{ $agentcompletedOrders }},
                    {{ $agentapprovedOrders }},
                    {{ $agentunapprovedOrders }},
                    {{ $agentcancelOrders }}
                ],
                fill: false,

                // Set a different color for each data point (dot)
                borderColor: '#895486', // Black line (optional)
                pointBackgroundColor: ['#4CAF50', '#FF9800', '#F44336', '#2196F3', '#9C27B0', '#FFC107',
                    '#795548'
                ], // Colors for dots
                pointBorderColor: ['#4CAF50', '#FF9800', '#F44336', '#2196F3', '#9C27B0', '#FFC107',
                    '#795548'
                ], // Border color for dots
                backgroundColor: ['#4CAF50', '#FF9800', '#F44336', '#2196F3', '#9C27B0', '#FFC107',
                    '#795548'
                ], // Colors for legend labels

                pointRadius: 5, // Size of the dots
                pointHoverRadius: 7, // Size of the dots on hover

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
                        labels: {
                            usePointStyle: true, // Use point style instead of a square box
                            pointStyle: 'circle', // Shape of the legend box (now a circle to match dots)
                            padding: 20,
                            // Use the backgroundColor for the legend boxes
                            generateLabels: function(chart) {
                                let labels = chart.data.datasets[0].backgroundColor;
                                return labels.map((color, index) => ({
                                    text: chart.data.labels[index],
                                    fillStyle: color,
                                    strokeStyle: color,
                                    pointStyle: 'circle'
                                }));
                            }
                        }
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
    </script>
@endsection

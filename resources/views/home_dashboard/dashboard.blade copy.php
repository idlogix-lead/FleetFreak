@extends('layouts.app')
<style>
    .chart-size {
        max-width: 700px;
        max-height: 300px;
    }
/*
    .btn-filter {
    color: #fff !important;
    background-color: #fbae42 !important;
    border-color: #fbae42 !important;
}
   .btn-filter:hover {
  color: #fff;
  background-color: #fbae42;
  border-color: #fbae42;
} */
</style>
@section('style')
    <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
    <link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
@endsection

@section('wrapper')

<div class="container ">
    <button id="filter-toggle" class="btn btn-success float-end btn-sm " data-bs-toggle="offcanvas"
        data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" class="openbtn" onclick="openNav()"><svg
            xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="feather feather-filter">
            <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
        </svg> Filter
    </button>
     <div  style="background-color:{{auth()->user()->theme=='dark-theme' ? '#343a40' : ''}}" class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Fleet Freak</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>


        <form method="GET" action="{{ route('dashboard') }}">
            <div class="row">
                @if (auth()->user()->actor_id == 2)
                    <!-- New Agent Select Field -->
                    {{-- <div class="col-md-12 mt-3 d-flex justify-content-center"> --}}
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
                            <input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
                            <input type="hidden" name="agent_name" id="agent_name"
                                value="{{ request()->input('agent_name') }}">
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


                {{-- </div> --}}

            </div>
        </form>
    </div>
    <br>
     <br>



    <div class="">
        {{--  --}}
        {{-- <div class="container "> --}}
            {{-- <div class="card">
						<div class="card-header bg-light">

							<button id="filter-toggle" class="btn btn-success btn-sm"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>        Filter</button>
						</div>
						<div id="filter-dropdown" class="card-body" style="display: none;">
							<form method="GET" action="{{ route('dashboard') }}">
								<div class="row">
									@if (auth()->user()->actor_id == 2)
									<!-- New Agent Select Field -->
									<div class="col-md-3">
										<div class="form-group">
											<label for="agent">Agent</label>
											<select name="agent" class="form-control" id="agent">
												<option value="">Search by agent name...</option>
												@php
													$businessPartners = App\Models\Partner::BusinessPartnerDropdown();
												@endphp
												@foreach ($businessPartners as $business_partner)
													<option value="{{ $business_partner->id }}"
													{{ request()->input('agent') == $business_partner->id ? 'selected' : '' }}
													>{{ Str::title($business_partner->company_name) }}</option>
												@endforeach
											</select>
											<input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
											<input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}">
										</div>
									</div>
									@endif
									<div class="col-md-3">
										<div class="form-group">
											<label for="date">From Date</label>
											<input type="date" name="date" class="form-control" id="date" value="{{ request()->input('date')}}" >

										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="to_date"> To Date</label>
											<input type="date" name="to_date" class="form-control" id="to_date" value="{{ request()->input('to_date')}}" >

										</div>
									</div>
									<div class="col-md-3 d-flex align-items-end">
										<button class="btn btn-secondary" id="searchBtn" type="submit">Search</button>
									</div>
								</div>
							</form>
						</div>
					</div> --}}


            {{-- <div class="card"> --}}
            {{-- <div class=""> --}}









            {{-- <div class="offcanvas-header">
                                    <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                </div>
                                <div class="offcanvas-body">
                                    <div>
                                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                    </div>
                                    <div class="dropdown mt-3">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                        Dropdown button
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li><a class="dropdown-item" href="#">Action</a></li>
                                        <li><a class="dropdown-item" href="#">Another action</a></li>
                                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    </ul>
                                    </div> --}}
            {{-- </div>







						{{-- <div id="filter-dropdown" class="card-body" style="display: none;">
							<form method="GET" action="{{ route('dashboard') }}">

								{{-- <div class="row">
									@if (auth()->user()->actor_id == 2)
									<!-- New Agent Select Field -->
									<div class="col-md-3">
										<div class="form-group">
											<label for="agent">Agent</label>
											<select name="agent" class="form-control" id="agent">
												<option value="">Search by agent name...</option>
												@php
													$businessPartners = App\Models\Partner::BusinessPartnerDropdown();
												@endphp
												@foreach ($businessPartners as $business_partner)
													<option value="{{ $business_partner->id }}"
													{{ request()->input('agent') == $business_partner->id ? 'selected' : '' }}
													>{{ Str::title($business_partner->company_name) }}</option>
												@endforeach
											</select>
											<input type="hidden" name="agent_id" id="agent_id" value="{{ request()->input('agent') }}">
											<input type="hidden" name="agent_name" id="agent_name" value="{{ request()->input('agent_name') }}">
										</div>
									</div>
									@endif
									<div class="col-md-3">
										<div class="form-group">
											<label for="date">From Date</label>
											<input type="date" name="date" class="form-control" id="date" value="{{ request()->input('date')}}" >

										</div>
									</div>
									<div class="col-md-3">
										<div class="form-group">
											<label for="to_date"> To Date</label>
											<input type="date" name="to_date" class="form-control" id="to_date" value="{{ request()->input('to_date')}}" >

										</div>
									</div>
									<div class="col-md-3 d-flex align-items-end">
										<button class="btn btn-secondary" id="searchBtn" type="submit">Search</button>
									</div>
								</div> --}}
            {{-- </form> --}}
            {{-- </div>  --}}
        </div>


        {{-- <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div>
                    Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images,
                    lists, etc.
                </div>
                <div class="dropdown mt-3">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                        data-bs-toggle="dropdown">
                        Dropdown button
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="#">Action</a></li>
                        <li><a class="dropdown-item" href="#">Another action</a></li>
                        <li><a class="dropdown-item" href="#">Something else here</a></li>
                    </ul>
                </div>
            </div>
        </div> --}}







        {{--  --}}
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3  row-cols-xl-4">
            @if (auth()->user()->actor_id == 2)
                {{-- for admin  --}}
                @php
                    $agent = request()->input('agent');
                    $date = request()->input('date');
                    $toDate = request()->input('to_date');
                @endphp
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'total-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-totalrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i></span>
                                <div class="d-flex align-items-center">

                                    <div>

                                        <h5 class="mb-0 text-white fw-bold">Total Rides</h5>
                                        {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i></div> --}}

                                        <small class="text-white">The overview of total ride count.</small>
                                        <h5 class="mb-0 text-white">{{ $totalOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col">
                    <a href="{{ route('pending_orders.index', ['ordertype' => 'pending-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-pendingrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg></i>
                                </span>
                                <div class="d-flex align-items-center">

                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Pending Rides</h5>
                                        <small class="text-white">Orders awaiting for admin approval</small>
                                        <h5 class="mb-0 text-white">{{ App\Models\Order::where('overall_status','pending')->count()}}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'incomplete-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-incompleterides">
                            <div class="card-body">
                                {{-- <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="20" height="20" fill="currentColor" class="bi bi-hourglass-split"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                    </svg></i>
                                </span> --}}
                                <span class="ms-auto text-white float-end"><svg width="19" height="19"
                                    fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                    clip-rule="evenodd">
                                    <path
                                        d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z" />
                                </svg>
                            </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Incomplete Rides</h5>
                                        <small class="text-white">Rides that are still in progress but not completed.</small>
                                        <h5 class="mb-0 text-white">{{ $incompleteOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'completed-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-completerides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="20" height="20" fill="currentColor" class="bi bi-check2-circle"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                        <path
                                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                </span>

                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Completed Rides</h5>
                                        <small class="text-white">Summary of all completed rides</small>
                                        <h5 class="mb-0 text-white">{{ $completedOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'approved-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden  bg-approvedrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg width="19" height="19"
                                        fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                        clip-rule="evenodd">
                                        <path
                                            d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z" />
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Approved Rides</h5>
                                        <small class="text-white">Rides that are approved by admin</small>
                                        <h5 class="mb-0 text-white">{{ $approvedOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'unapproved-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-unapprovedrides">
                            <div class="card-body">
                                {{-- <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i>
                                </span> --}}
                               <span class="ms-auto text-white float-end">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x-square"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="9" y1="9" x2="15" y2="15"></line><line x1="15" y1="9" x2="9" y2="15"></line></svg>
                                </span>

                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Unapproved Rides</h5>
                                        <small class="text-white">Rides that are unapproved by admin</small>
                                        <h5 class="mb-0 text-white">{{ $unapprovedOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('dashboard.orders', ['ordertype' => 'cancelled-orders', 'agent' => $agent, 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none"> <!-- Add your target URL here -->
                        <div class="card radius-10 overflow-hidden bg-Ohhappiness">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="25" fill="currentColor" class="bi bi-x-circle"
                                        viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                        <path
                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white fw-bold">Cancel Rides</h5>
                                        <small class="text-white">Total Rides that are cancelled by admin</small>
                                        <h5 class="mb-0 text-white">{{ $cancelOrders }}</h5>

                                    </div>

                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @else
                {{-- for agent --}}
                @php
                    // $agent = auth()->user()->partner_id;
                    $date = request()->input('date');
                    $toDate = request()->input('to_date');
                @endphp
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'total-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                            <div class="card radius-10 overflow-hidden bg-totalrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i></span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Total Rides</h5>
                                        <small class="text-white">The overview of total ride count.</small>
                                        <h5 class="mb-0 text-white">{{ $agenttotalOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'pending-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                         <div class="card radius-10 overflow-hidden bg-pendingrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-clock">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg></i>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Pending Rides</h5>
                                        <small class="text-white">Orders awaiting for admin approval</small>
                                        <h5 class="mb-0 text-white">{{ $agentpendingOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'approved-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                           <div class="card radius-10 overflow-hidden  bg-approvedrides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg width="22" height="22"
                                        fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                        clip-rule="evenodd">
                                        <path
                                            d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z" />
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Approved Rides</h5>
                                        <small class="text-white">Rides that are approved by admin</small>
                                        <h5 class="mb-0 text-white">{{ $agentapprovedOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'unapproved-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                         <div class="card radius-10 overflow-hidden bg-unapprovedrides">
                            <div class="card-body">
                                {{-- <span class="ms-auto text-white float-end"><i class='bx bx-cart font-30'></i>
                                </span> --}}
                               <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-bookmark-x" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6.146 5.146a.5.5 0 0 1 .708 0L8 6.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 7l1.147 1.146a.5.5 0 0 1-.708.708L8 7.707 6.854 8.854a.5.5 0 1 1-.708-.708L7.293 7 6.146 5.854a.5.5 0 0 1 0-.708"/>
                                    <path d="M2 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v13.5a.5.5 0 0 1-.777.416L8 13.101l-5.223 2.815A.5.5 0 0 1 2 15.5zm2-1a1 1 0 0 0-1 1v12.566l4.723-2.482a.5.5 0 0 1 .554 0L13 14.566V2a1 1 0 0 0-1-1z"/>
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">UnApproved Rides</h5>
                                        <small class="text-white">Rides that are unapproved by admin</small>
                                        <h5 class="mb-0 text-white">{{ $agentunapprovedOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'completed-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                     <div class="card radius-10 overflow-hidden bg-completerides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="25" fill="currentColor" class="bi bi-check2-circle"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                                        <path
                                            d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Completed Rides</h5>
                                        <small class="text-white">Summary of all completed rides</small>
                                        <h5 class="mb-0 text-white">{{ $agentcompletedOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'incomplete-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                        <div class="card radius-10 overflow-hidden bg-incompleterides">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="25" fill="currentColor" class="bi bi-hourglass-split"
                                        viewBox="0 0 16 16">
                                        <path
                                            d="M2.5 15a.5.5 0 1 1 0-1h1v-1a4.5 4.5 0 0 1 2.557-4.06c.29-.139.443-.377.443-.59v-.7c0-.213-.154-.451-.443-.59A4.5 4.5 0 0 1 3.5 3V2h-1a.5.5 0 0 1 0-1h11a.5.5 0 0 1 0 1h-1v1a4.5 4.5 0 0 1-2.557 4.06c-.29.139-.443.377-.443.59v.7c0 .213.154.451.443.59A4.5 4.5 0 0 1 12.5 13v1h1a.5.5 0 0 1 0 1zm2-13v1c0 .537.12 1.045.337 1.5h6.326c.216-.455.337-.963.337-1.5V2zm3 6.35c0 .701-.478 1.236-1.011 1.492A3.5 3.5 0 0 0 4.5 13s.866-1.299 3-1.48zm1 0v3.17c2.134.181 3 1.48 3 1.48a3.5 3.5 0 0 0-1.989-3.158C8.978 9.586 8.5 9.052 8.5 8.351z" />
                                    </svg></i>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Incomplete Rides</h5>
                                        <small class="text-white">Rides that are still in progress but not completed.</small>
                                        <h5 class="mb-0 text-white">{{ $agentincompleteOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col">
                    <a href="{{ route('agent.rides_window', ['ordertype' => 'cancelled-orders', 'date' => $date, 'to_date' => $toDate]) }}"
                        class="text-decoration-none">
                        <div class="card radius-10 overflow-hidden bg-Ohhappiness">
                            <div class="card-body">
                                <span class="ms-auto text-white float-end"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="25" height="25" fill="currentColor" class="bi bi-x-circle"
                                        viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                        <path
                                            d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708" />
                                    </svg>
                                </span>
                                <div class="d-flex align-items-center">
                                    <div>
                                        <h5 class="mb-0 text-white  fw-bold">Cancelled Rides</h5>
                                        <small class="text-white">Total Rides that are cancelled by admin</small>
                                        <h5 class="mb-0 text-white">{{ $agentcancelOrders }}</h5>
                                    </div>
                                    {{-- <div class="ms-auto text-white"><i class='bx bx-cart font-30'></i>
                                    </div> --}}
                                </div>
                                <div class="progress bg-white-2 radius-10 mt-3" style="height:4.5px;">
                                    <div class="progress-bar bg-white" role="progressbar" style="width: 46%"></div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            @endif

        </div><!--end row-->
        @if (auth()->user()->actor_id == 2)
            <div>
                <h5 class="mb-1 text-center">Today Vehicle Assignment</h5>
            </div>

            <div class = "row">



                {{-- <div class="col">
						<div class="card radius-10 overflow-hidden">
							<div class="card-body">
								@php
									$countries = App\Models\Partner::CustomerCountry();
								@endphp

								@if ($countries->isNotEmpty())
									<div class="form-group">
										<label for="country-filter">Select Country:</label>
										<select id="country-filter" class="form-control">
											<option value="">All Countries</option>
											@foreach ($countries as $customer)
												<option value="{{ $customer->country }}">{{ $customer->country }}</option>
											@endforeach
										</select>
									</div>
								@endif

								<div id="vehiclesBarChart"></div>
							</div>
						</div>
					</div> --}}
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


                {{-- <div class="col">
						<div class="card radius-10 overflow-hidden">
							<div class="card-body">
								<h6 class="text-center"><b>Paid & Unpaid Rides</b></h6>
								<div class="chart-container" style="height: 300px;">
									<div id="paidunpaidChart"></div>
								</div>
							</div>
						</div>
					</div> --}}
            </div>
            <div class="row mb-2 ">
                <div class="col">
                    {{-- <div class="card radius-10 overflow-hidden">
							<div class="card-body">
								<h6 class="text-center"><b>Booked Vehicles</b></h6>
								<div id="vehiclePieChart" class="chart-size"></div>
							</div>
						</div> --}}

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
                                                            <h6 class="mb-1 font-14">{{ $vehicle_assignment->id??null  }}</h6>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $vehicle_assignment->vehicle->vehicle_identification_number??null  }}</td>
                                                <td>{{ $vehicle_assignment->driver->name??null }}</td>
                                                <td>{{ $vehicle_assignment->order->partner_customer->name??null  }}</td>
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
        {{-- end row --}}

        {{-- <div class="row">
			    <div class="col-12 col-xl-8 d-flex">
				  <div class="card radius-10 w-100">
						<div class="card-body">
							<div class="" id="chart5"></div>
						</div>
					</div>
				</div>
				<div class="col-12 col-xl-4 d-flex">
				  <div class="card radius-10 w-100">
						<div class="card-body">
							<div class="d-flex align-items-center">
									<div>
										<h5 class="mb-1">Sales Target</h5>
									</div>
									<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
									</div>
								</div>
							<div class="mt-4" id="chart6"></div>
							<div class="d-flex align-items-center">
									<div>
										<h2 class="mb-0">2248</h2>
										<p class="mb-0">/2,800 target</p>
									</div>
									<div class="ms-auto d-flex align-items-center border radius-10 px-2">
									  <i class='bx bxs-checkbox font-22 me-1 text-primary'></i><span>Marketing Sales</span>
									</div>
							  </div>
						</div>
					</div>
				</div>
			  </div><!--end row--> --}}


        {{-- <div class="row row-cols-1 row-cols-xl-2">
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
									<h5 class="mb-1">Sales Report</h5>
								</div>
								<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
								</div>
							</div>
							<div class="" id="chart8"></div>
						</div>
					</div>
				</div>
			  </div><!--end row--> --}}



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

                {{-- Employees --}}

                {{-- <div class="col-12 col-xl-4 d-flex">
					<div class="card radius-10 w-100">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-0">Employees</h5>
								</div>
								<div class="font-22 ms-auto"><i class='bx bx-dots-horizontal-rounded'></i>
								</div>
							</div>
						</div>

						<div class="customers-list p-3 mb-3">
							@foreach ($employees as $employee)
							<div class="customers-list-item d-flex align-items-center border-top border-bottom p-2 cursor-pointer">
								<div class="">
									<img src="assets/images/avatars/avatar-7.png" class="rounded-circle" width="46" height="46" alt="" />
								</div>
								<div class="ms-2">
									<h6 class="mb-1 font-14">{{$employee->name}}</h6>
									<p class="mb-0 font-13 text-secondary">{{$employee->email}}</p>
								</div>
								<div class="list-inline d-flex customers-contacts ms-auto">	<a href="javascript:;" class="list-inline-item"><i class='bx bxs-envelope'></i></a>
									<a href="javascript:;" class="list-inline-item"><i class='bx bxs-phone' ></i></a>
									<a href="javascript:;" class="list-inline-item"><i class='bx bx-dots-vertical-rounded'></i></a>
								</div>
							</div>
							@endforeach
						</div>
					</div>
				 </div> --}}
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

        {{-- <div class="col-12 col-xl-4 d-flex">
					<div class="card radius-10 w-100 ">
						<div class="card-body">
							<div class="d-flex align-items-center">
								<div>
									<h5 class="mb-1">Top Products</h5>
								</div>
								<div class="font-22 ms-auto"><i class="bx bx-dots-horizontal-rounded"></i>
								</div>
							</div>
						</div>

						<div class="product-list p-3 mb-3">

							 <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/01.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Black Boost Chair</h6>
									<p class="mb-0">148 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$246.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/03.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Red Single Sofa</h6>
									<p class="mb-0">122 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$328.14</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/04.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Pink Rounded Sofa</h6>
									<p class="mb-0">105 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$124.35</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/05.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Brown Single Table</h6>
									<p class="mb-0">201 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$158.34</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/06.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Grey Long Chair</h6>
									<p class="mb-0">146 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">158.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/07.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Beautiful Sofa</h6>
									<p class="mb-0">210 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$520.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/08.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Grey Stand Table</h6>
									<p class="mb-0">115 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$345.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/09.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Brown Single Table</h6>
									<p class="mb-0">116 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$126.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/10.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Four Leg Chair</h6>
									<p class="mb-0">154 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$425.24</h6>
								</div>
							  </div>

							  <div class="d-flex align-items-center py-3 border-bottom cursor-pointer">
								<div class="product-img me-2">
									 <img src="assets/images/products/11.png" alt="product img">
								  </div>
								<div class="">
									<h6 class="mb-0 font-14">Blue Light T-Shirt</h6>
									<p class="mb-0">186 Sales</p>
								</div>
								<div class="ms-auto">
									<h6 class="mb-0">$149.34</h6>
								</div>
							  </div>

						</div>
					</div>
				 </div> --}}
        {{-- </div><!--end row--> --}}

        @if (auth()->user()->actor_id == 2)
            <div class="row">
                <div class="col">
                    <div class="card radius-10 mb-0">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Upcoming Busy Rides (Next 7 Days)</h5>
                                </div>
                                {{-- <div class="ms-auto">
										<a href="javscript:;" class="btn btn-primary btn-sm radius-30">View All Products</a>
									</div> --}}
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
                                                <td>{{ $order_details_vehicle->vehicle->vehicle_identification_number ?? null  }}
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
                                                        class="badge bg-light-{{ $badgeClass }} text-{{ $badgeClass }}  ">{{ Str::title($order_details_vehicle->status ?? null ) }}</span>
                                                </td>
                                                <td>{{ $order_details_vehicle->rate_list->name?? null }}</td>
                                                <td>
                                                    {{ $order_details_vehicle->rate ?? null }}
                                                </td>


                                                {{-- <td></td> --}}
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
        {{-- @if (auth()->user()->actor_id != 2)
            <div class="card">
                <div class="card-body">
                    <div class="pb-4">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="card-title">{{ __('Ledgers Last (7 days)') }}</h3>

                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id='order' class="table table-hover fixed-table">
                            <thead class="thead table-light">
                                <tr>
                                    <th>No #</th>
                                    <th>Date</th>
                                    <th>agent</th>
                                    <th>Customer</th>
                                    <th>Tr Type</th>
                                    <th>Transaction No</th>
                                    <th>Description</th>
                                    <th>Debit</th>
                                    <th>Credit</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $row_id = 1;
                                    $row_index = $row_id - 1;
                                    $runningTotal = 0;
                                    $debitTotal = 0;
                                    $creditTotal = 0;
                                    $i = 0;
                                @endphp
                                @foreach ($ledgerEntries as $entry)
                                    @php
                                        $runningTotal = $runningTotal + $entry->debit - $entry->credit;
                                        $creditTotal = $creditTotal + $entry->credit;
                                        $debitTotal = $debitTotal + $entry->debit;
                                    @endphp
                                    <tr id="order_row{{ $row_id }}" data-row-index="{{ $row_index }}">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $entry->tr_date }}</td>
                                        <td>{{ $entry->agent }}</td>
                                        <td>{{ $entry->customer }}</td>
                                        <td>{{ $entry->trtype }}</td>
                                        <td>{{ $entry->tr_no }}</td>
                                        <td>{{ $entry->description }}</td>

                                        <td>{{ $entry->debit - $entry->credit >= 0 ? $entry->debit - $entry->credit : 0 }}
                                        </td>
                                        <td>{{ $entry->debit - $entry->credit < 0 ? $entry->debit - $entry->credit : 0 }}
                                        </td>
                                        <td>{{ $runningTotal }}</td>

                                    </tr>
                                    @php
                                        $row_id++;
                                        $row_index = $row_id - 1;
                                    @endphp
                                @endforeach
                                <tr id="order_row{{ $row_id }}" data-row-index="{{ $row_index }}">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>Total</td>
                                    <td>{{ $debitTotal }}</td>
                                    <td>{{ $creditTotal }}</td>
                                    <td>{{ $runningTotal }}</td>

                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

    </div>

    </div>
    @endif --}}

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
@endsection

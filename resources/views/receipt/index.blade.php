@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))

        <div style="display: flex; justify-content: space-between; align-items: center;">

            <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
            </svg>
             {{ __('Receipts') }}
            </h3>
            {{-- search query --}}

            @php
            $search_link ='order.receipts';
            $create_link = 'receipts.create';
            $export_link = 'export.receipts';
            $table_id='#order';
            @endphp
            @include('layouts.partials.overall_search_query')
            @include('layouts.partials.datatable_sorting')
        </div>
        @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
        

        {{-- <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="unassigned-rides" data-bs-toggle="tab" data-bs-target="#unassigned" type="button" role="tab" aria-controls="unassigned" aria-selected="true">Unassigned Rides</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="incomplete-rides" data-bs-toggle="tab" data-bs-target="#incomplete" type="button" role="tab" aria-controls="incomplete" aria-selected="false">Incomplete Rides</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="completed-rides" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">Completed Rides</button>
            </li>
        </ul>

        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="unassigned" role="tabpanel" aria-labelledby="unassigned-rides"></div>
            <div class="tab-pane fade" id="incomplete" role="tabpanel" aria-labelledby="incomplete-rides"></div>
            <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-rides"></div>
        </div> --}}
    @endif

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title">{{ __('Receipts') }}</h3>
                            </div>
                        </div> --}}

                        <div class="table-responsive">
                            {{-- <form action="{{ route('driver_assignments.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search by agent name..." name="query" value="{{ request()->input('query') }}">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search by customer name..." name="customer" value="{{ request()->input('customer') }}">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group mb-3">
                                            <input type="date" class="form-control" name="date" value="{{ request()->input('date') }}">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form> --}}
                            {{-- <form action="{{ route('driver_assignments.index') }}" method="GET">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group mb-3">
                                            <input type="text" class="form-control" placeholder="Search by agent name..." name="query" value="{{ request()->input('query') }}">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form> --}}

                            <table id='order'  class="table-montserrat table-sm table table-hover text-center">
                                @include('ledgers.partials.app')
                                <thead class="thead t-head-clr table-light">
                                    <tr class="t-head-clr">
                                        <th>No</th>
                                        <th>Order No</th>
                                        <th>Business Partner</th>
                                        <th>Amount</th>
                                        {{-- <th>Customer Name</th>
                                        <th>Pick Location</th>
                                        <th>Drop Location</th> --}}
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $order->order->order_no }}</td>
                                            <td>{{ Str::title($order->order->partner_business->name) }}</td>
                                            {{-- <td>{{ Str::title($order->order->partner_customer->name) }}</td>
                                            <td>{{ Str::title($order->rate_list->route->from) }}</td>
                                            <td>{{ Str::title($order->rate_list->route->to) }}</td> --}}
                                            <td>{{ Str::title($order->rate??$order->driver_rate) }}</td>
                                            <td>{{ date('F j, Y') }}</td>
                                            <td>{{ Str::title($order->order->description) }}</td>
                                            <td> <a class="btn btn-primary float-right ms-1" href="{{ route('order.receiptdetails',$order->order->id) }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a></td>

                                            {{-- <td>{{ Str::title($order->pickup_time) }}</td> --}}

                                            {{-- <td>
                                                <form class="vehicle-form" id="vehicle_form_{{ $order->id }}" action="{{ route('driver_assignments.assign_vehicle') }}" method="POST">
                                                @csrf
                                                    <input type="hidden" name="row_id" value="{{ $order->id }}">
                                                    <input type="hidden" name="driver_id" id="driver_id{{ $order->id }}">
                                                    <select name='vehicle_id' onchange="select_vehicle({{ $order->id }})" id="vehicle_id{{ $order->id }}" class='form-control vehicle-select' required>
                                                        <option value="">-- Select --</option>
                                                        @foreach(App\Models\Vehicle::VehicleDropdown() as $vehicle)
                                                            <option value='{{ $vehicle->id }}' driver-id='{{ $vehicle->driver_id ?? '' }}' driver-name='{{ $vehicle->driver->name ?? 'No driver assigned' }}' {{ $vehicle->id == $order->vehicle_id ? 'selected' : '' }}>
                                                                {{ $vehicle->car_company->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </form>
                                            </td> --}}


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $orders->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
});
</script>

@extends('layouts.app')
@section('wrapper')
<style>
    th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
       

            
        {{-- <nav aria-label="breadcrumb">
            
            <ol class="breadcrumb">
                @foreach ($breadcrumbs as $crumb)
                    <li class="breadcrumb-item {{$crumb['active']?'breadcrumb-active':null}} "><a href="{{$crumb['link']}}">{{$crumb['name']}}</a></li>
                @endforeach
                <a class="mx-5" href="{{ route('driver_assignments.index') }}">unassigned approved orders</a>
            </ol>
        </nav> --}}
        
    @endif
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card ">
                    <div class="card-body pb-0">
                        <div class="pb-0">
                            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>{{ __(' Filterations') }}</h3>
                            </div>
                            @php
                                $link = 'driver_assignments.cancelled_rides'
                            @endphp
                            @include('driver-assignment.partials.filter')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title"><?xml version="1.0" encoding="iso-8859-1"?>
                                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                    <svg fill="currentColor" height="22" width="22" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                         viewBox="0 0 512 512" xml:space="preserve">
                                    <g>
                                        <g>
                                            <g>
                                                <path d="M512,341.333c0-8.917-6.635-15.147-16.128-15.147c-5.355,0-14.528,2.752-22.421,7.296
                                                    c-10.453-22.016-27.52-41.536-39.723-47.616c-8.085-4.032-35.627-8.533-60.395-8.533c-24.768,0-52.309,4.501-60.416,8.533
                                                    c-12.181,6.08-29.248,25.6-39.701,47.616c-7.915-4.544-17.088-7.296-22.421-7.296c-4.053,0-7.552,1.067-10.325,3.157
                                                    c-3.733,2.795-5.803,7.061-5.803,11.989c0,16.64,12.907,28.181,14.4,29.44c1.195,1.024,2.56,1.643,3.968,2.048
                                                    c-1.344,3.84-2.539,7.744-3.499,11.627C280.789,386.069,320,392.704,320,416c0,5.888-4.779,10.667-10.667,10.667
                                                    s-10.667-4.779-10.667-10.667c-3.2-4.203-26.987-8.981-52.757-10.304c-0.32,3.605-0.576,7.211-0.576,10.304
                                                    c0,15.936,1.472,43.115,10.667,57.003v17.664c0,11.968,9.365,21.333,21.333,21.333h21.333c11.968,0,21.333-9.365,21.333-21.333
                                                    v-7.232c12.885,3.221,32.981,7.232,53.333,7.232c20.352,0,40.448-4.011,53.333-7.232v7.232c0,11.968,9.365,21.333,21.333,21.333
                                                    h21.333c11.968,0,21.333-9.365,21.333-21.333v-17.664c9.195-13.888,10.667-41.067,10.667-57.003
                                                    c0-3.115-0.256-6.699-0.576-10.304c-25.792,1.344-49.6,6.272-53.035,11.371c0,5.888-4.651,10.155-10.517,10.155
                                                    c-5.888,0-10.539-5.312-10.539-11.2c0-23.296,39.211-29.931,70.464-31.552c-0.96-3.904-2.176-7.787-3.499-11.627
                                                    c1.408-0.405,2.773-1.024,3.968-2.048C499.093,369.515,512,357.973,512,341.333z M394.667,426.667H352
                                                    c-5.888,0-10.667-4.779-10.667-10.667c0-5.888,4.779-10.667,10.667-10.667h42.667c5.888,0,10.667,4.779,10.667,10.667
                                                    C405.333,421.888,400.555,426.667,394.667,426.667z M451.264,356.373c-0.043,0-0.085,0-0.107,0.021
                                                    c-4.288,0.469-8.725,0.917-13.184,1.387c-0.555,0.064-1.088,0.107-1.643,0.171c-4.565,0.469-9.173,0.917-13.717,1.344
                                                    c-1.173,0.107-2.304,0.213-3.456,0.32c-3.349,0.32-6.656,0.619-9.899,0.896c-1.472,0.128-2.88,0.235-4.309,0.363
                                                    c-2.859,0.235-5.611,0.448-8.299,0.661c-1.408,0.107-2.795,0.213-4.139,0.299c-2.603,0.171-5.013,0.32-7.36,0.448
                                                    c-1.109,0.064-2.304,0.128-3.328,0.171c-3.179,0.128-6.101,0.213-8.491,0.213c-2.389,0-5.312-0.085-8.491-0.235
                                                    c-1.045-0.043-2.219-0.128-3.328-0.171c-2.347-0.128-4.757-0.256-7.36-0.448c-1.344-0.085-2.709-0.192-4.117-0.299
                                                    c-2.688-0.192-5.44-0.405-8.32-0.661c-1.429-0.128-2.837-0.235-4.309-0.363c-3.221-0.277-6.549-0.576-9.899-0.896
                                                    c-1.152-0.107-2.283-0.213-3.456-0.32c-4.544-0.427-9.152-0.896-13.717-1.344c-0.555-0.064-1.088-0.107-1.621-0.171
                                                    c-7.381-0.747-14.507-1.515-21.312-2.261c0.064-0.171,0.107-0.299,0.171-0.469c7.083-23.787,26.624-45.952,34.859-50.069
                                                    c3.968-1.813,26.731-6.293,50.901-6.293s46.933,4.48,50.88,6.293c8.277,4.117,27.819,26.283,34.901,50.091
                                                    c0.064,0.171,0.107,0.299,0.171,0.469C456.619,355.797,454.037,356.096,451.264,356.373z"/>
                                                <path d="M458.667,0H53.333C23.915,0,0,23.915,0,53.333V64h512V53.333C512,23.915,488.085,0,458.667,0z"/>
                                                <path d="M0,330.667C0,360.085,23.915,384,53.333,384h158.251c5.888,0,10.667-4.779,10.667-10.667
                                                    c0-2.816-1.088-5.376-2.859-7.275c-4.075-8.128-6.059-16.235-6.059-24.725h-160c-5.888,0-10.667-4.779-10.667-10.667
                                                    c0-26.752,18.133-49.963,44.053-56.448l32.021-7.979l1.557-6.272c-7.808-8.96-13.376-20.203-15.765-31.851
                                                    c-6.507-2.837-10.923-8.597-11.797-15.552l-2.304-18.56c-0.725-5.632,1.024-11.349,4.821-15.637
                                                    c1.28-1.429,2.709-2.667,4.288-3.669c-0.597-5.504-1.237-12.096-1.237-15.616c0-17.856,5.163-41.515,49.067-43.051
                                                    c14.891-9.365,30.229-9.365,37.035-9.365c23.104,0,34.603,10.219,40.192,18.752c11.776,17.984,4.331,30.485-1.173,36.309
                                                    l-1.941,1.963l-0.555,11.328c1.429,0.96,2.752,2.112,3.904,3.435c3.733,4.245,5.461,9.941,4.757,15.552l-2.304,18.517
                                                    c-0.832,6.72-4.992,12.331-10.688,15.275c-2.432,12.16-8.341,23.872-16.64,33.045l1.344,5.376l32.021,7.979
                                                    c15.083,3.776,27.371,13.291,35.136,25.771c11.136-15.531,23.829-27.627,34.965-33.195C316.928,260.032,349.589,256,373.333,256
                                                    s56.384,4.011,69.888,10.773c11.435,5.717,24.469,18.219,35.797,34.304c2.304,3.243,6.272,4.928,10.197,4.416
                                                    c3.712-0.469,5.483-0.875,11.029-0.299c3.029,0.299,5.973-0.661,8.256-2.688c2.219-2.005,3.499-4.885,3.499-7.893V85.333H0
                                                    V330.667z M416,128h42.667c5.888,0,10.667,4.779,10.667,10.667s-4.779,10.667-10.667,10.667H416
                                                    c-5.888,0-10.667-4.779-10.667-10.667S410.112,128,416,128z M309.333,128h64c5.888,0,10.667,4.779,10.667,10.667
                                                    s-4.779,10.667-10.667,10.667h-64c-5.888,0-10.667-4.779-10.667-10.667S303.445,128,309.333,128z M309.333,192h149.333
                                                    c5.888,0,10.667,4.779,10.667,10.667s-4.779,10.667-10.667,10.667H309.333c-5.888,0-10.667-4.779-10.667-10.667
                                                    S303.445,192,309.333,192z"/>
                                            </g>
                                        </g>
                                    </g>
                                    </svg>
                                    {{ __(' Driver Assignment') }}
                                </h3>   
                            </div>
                            <ul class="nav nav-tabs" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                  <button class="nav-link " id="unassigned-rides" data-bs-toggle="tab" data-bs-target="#unassigned" type="button" role="tab" aria-controls="home" aria-selected="false">Approved & Unassigned Rides</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                  <button class="nav-link" id="incomplete-rides" data-bs-toggle="tab" data-bs-target="#incomplete" type="button" role="tab" aria-controls="profile" aria-selected="false">Incomplete Rides</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="completed-rides" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="complete" aria-selected="false">Completed Rides</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="cancelled-rides" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancel" aria-selected="true">Cancelled Rides</button>
                                </li>
                               
                              </ul>
                              <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade " id="unassigned" role="tabpanel" aria-labelledby="unassigned-rides"></div>
                                <div class="tab-pane fade " id="incomplete" role="tabpanel" aria-labelledby="incomplete-rides"></div>
                                <div class="tab-pane fade" id="completed" role="tabpanel" aria-labelledby="completed-rides"></div>
                                <div class="tab-pane fade show active" id="cancelled" role="tabpanel" aria-labelledby="cancelled-rides"></div>
                            </div>
                        </div>
                    
                        <div class="table-responsive">
                            <table id='order' class="table table-hover">
                                <thead class="thead table-light">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Order No</th>
										<th>Name</th>
										<th>Created By</th>
										<th>Customer Name </th>
										<th>Pick Location </th>
										<th>Drop Location </th>

										{{-- <th>Status</th> --}}
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Assigned Vehicle</th>
                                        <th>Driver</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											<td>{{ $order->order->order_no }}</td>
											<td>{{ Str::title($order->order->partner_business->name) }}</td>
											<td>{{ Str::title($order->order->user->actor->name ?? null) }}</td>
											<td>{{ Str::title($order->order->partner_customer->name) }}</td>
                                            <td>{{Str::title($order->rate_list->route->fromLoc->name ?? $order->driver_pickup_loc) }}</td>
                                            <td>{{Str::title($order->rate_list->route->toLoc->name ?? $order->driver_dropoff_loc) }}</td>
                                            <td>{{Str::title($order->date ?? null) }}</td>
                                            <td>{{Str::title($order->pickup_time ?? null) }}</td>
											<td>
                                                
                                                    
                                                <input type="hidden" name="row_id" value="{{ $order->id }}">
                                                <select name='vehicle_id' class='form-control vehicle-select' disabled>
                                                    <option value="">-- Select --</option>
                                                    @foreach(App\Models\Vehicle::VehicleDropdown() as $vehicle)
                                                        <option value='{{ $vehicle->id }}' {{ $vehicle->id == $order->vehicle_id ? 'selected' : '' }}>
                                                            {{ $vehicle->vehicleModel->carCompany->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                
                                            
                                            </td>
                                            <td>
                                                    <input type="hidden" name="row_id" value="{{ $order->id }}">
                                                    <select name='driver_id' class='form-control driver-select' disabled>
                                                        <option value="">-- Select --</option>
                                                        @foreach(App\Models\Driver::DriverDropdown() as $driver)
                                                            <option value='{{ $driver->id }}' {{ $driver->id == $order->driver_id ? 'selected' : '' }}>
                                                                {{ $driver->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    
                                                
                                            
                                            </td>
                                            


                                            {{-- <td style='display:flex;'>
                                                <form action="{{ route('orders.destroy',$order->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php 
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$order->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$order->id"
                                                        ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                               
                                                <a class="btn btn-outline-primary float-right ms-1" href="{{ route('driver_assignments.edit',$order->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                <a class="btn btn-outline-success float-right ms-1" href="{{ route('driver_assignments.show',$order->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> 
                                            </td>--}}
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
        // Handle Approved Vehicles tab click
        $('#unassigned-rides').click(function() {
            // Redirect to the desired route
            window.location.href = "{{ route('driver_assignments.index') }}";
        });
        $('#incomplete-rides').click(function() {
            // Redirect to the desired route
            console.log('enter in incomplete rides');
            window.location.href = "{{ route('driver_assignments.incomplete_rides') }}";
        });
        $('#completed-rides').click(function() {
            // Redirect to the desired route
            console.log('enter in completed rides');
            window.location.href = "{{ route('driver_assignments.completed_rides') }}";
        });
        $('#cancelled-rides').click(function() {
            // Redirect to the desired route
            console.log('enter in cancelled rides');
            window.location.href = "{{ route('driver_assignments.cancelled_rides') }}";
        });

        // Handle form submission
        // $('.vehicle-form').submit(function(e) {
        //     e.preventDefault();

        //     var formData = $(this).serialize();
        //     var csrfToken = '{{ csrf_token() }}';
            
        //     var rowId = $(this).find('input[name="row_id"]').val();
        //     // var vehicleId = $(this).find('select[name="vehicle_id"]').val();

        //     $.post('{{ route("driver_assignments.incomplete_rides_update") }}', {
        //         "_token": csrfToken,
        //         "row_id": rowId
        //     })
        //     .then(function(data) {
        //         if (data.success) {
        //             toastr.options = {"positionClass": "toast-top-right"};
        //             toastr.success("Status updated successfully!", 'Success');
        //         } else {
        //             toastr.options = {"positionClass": "toast-top-right"};
        //             toastr.error(data.message || 'Failed to assign ride status.', 'Error');
        //         }
        //     })
        //     .fail(function(xhr) {
        //         console.error('Error:', xhr.responseText);
        //         toastr.options = {"positionClass": "toast-top-right"};
        //         toastr.error(xhr.responseJSON?.message || 'An error occurred while assigning ride status', 'Error');
        //     });
        // });
    });
</script>

       
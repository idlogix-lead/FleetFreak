@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
    <div style="display: flex; justify-content: space-between; align-items: center;">

        <h4 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
            <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
          </svg>
            {{ __(' Vehicle') }}
        </h4>
        {{-- search query --}}
        @php
        $search_link ='vehicles.index';
        $create_link = 'vehicles.create';
        $export_link = 'export.vehicles';
        $table_id='#vehicle'
       @endphp
       @include('layouts.partials.overall_search_query')
       @include('layouts.partials.datatable_sorting')

        {{-- <div class="float-right mx-2">
            <a href="{{ route('vehicles.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            </a>
        </div> --}}
    </div>
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-bus-front-fill" viewBox="0 0 16 16">
                                    <path d="M16 7a1 1 0 0 1-1 1v3.5c0 .818-.393 1.544-1 2v2a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5V14H5v1.5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1-.5-.5v-2a2.5 2.5 0 0 1-1-2V8a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1V2.64C1 1.452 1.845.408 3.064.268A44 44 0 0 1 8 0c2.1 0 3.792.136 4.936.268C14.155.408 15 1.452 15 2.64V4a1 1 0 0 1 1 1zM3.552 3.22A43 43 0 0 1 8 3c1.837 0 3.353.107 4.448.22a.5.5 0 0 0 .104-.994A44 44 0 0 0 8 2c-1.876 0-3.426.109-4.552.226a.5.5 0 1 0 .104.994M8 4c-1.876 0-3.426.109-4.552.226A.5.5 0 0 0 3 4.723v3.554a.5.5 0 0 0 .448.497C4.574 8.891 6.124 9 8 9s3.426-.109 4.552-.226A.5.5 0 0 0 13 8.277V4.723a.5.5 0 0 0-.448-.497A44 44 0 0 0 8 4m-3 7a1 1 0 1 0-2 0 1 1 0 0 0 2 0m8 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0m-7 0a1 1 0 0 0 1 1h2a1 1 0 1 0 0-2H7a1 1 0 0 0-1 1"/>
                                  </svg>
                                    {{ __(' Vehicle') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('vehicles.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                                        <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    {{ __('Create New') }}
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsive ">
                            <table id='vehicle' class="table-sm table-montserrat table table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th class="px-3">No</th>
										<th class="px-3">Image</th>

										<th class="px-3">Vehicle Identification Number</th>
										<th class="px-3">Company Id</th>
										<th class="px-3">Model</th>
										{{-- <th class="px-3">Year</th> --}}
										{{-- <th class="px-3">Color</th> --}}
										<th class="px-3">Vehicle Number</th>
										{{-- <th>Registration No</th> --}}
										{{-- <th>Ownership</th>
										<th>Fuel Type</th>
										<th>Engine Type</th>
										<th>Transmission Type</th> --}}
										{{-- <th class="px-3">Vehicle Class</th> --}}
										{{-- <th>Weight</th> --}}
										{{-- <th class="px-3">Car Condition</th> --}}
										{{-- <th>Is Ac</th> --}}
										<th class="px-3">Status</th>
										<th class="px-3">Reason</th>

                                        <th class="px-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody style="vertical-align: middle;" class="body-font">
                                    @foreach ($vehicles as $index=>$vehicle)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>
                                                <div class = "d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret">
                                                    <img src="{{ $vehicle->image ? asset('storage/'.$vehicle->image) : asset('image/default_vehicle/default.png') }}" class="user-img" alt="user avatar">

                                                </div>
                                            </td>

											<td>{{ $vehicle->vehicle_identification_number }}</td>

											<td>{{ $vehicle->vehicleModel->carCompany->name?? null }}</td>
											<td>{{ $vehicle->vehicleModel->name ??null }}</td>
											{{-- <td>{{ $vehicle->year }}</td> --}}
											{{-- <td>{{ $vehicle->color }}</td> --}}
											<td>{{ $vehicle->vehicle_no }}</td>
											{{-- <td>{{ $vehicle->registration_no }}</td> --}}
											{{-- <td>{{ $vehicle->ownership }}</td>
											<td>{{ $vehicle->fuel_type }}</td>
											<td>{{ $vehicle->engine_type }}</td> --}}
											{{-- <td>{{ $vehicle->transmission_type }}</td> --}}
											{{-- <td>{{ $vehicle->vehicle_class_id }}</td> --}}
											{{-- <td>{{ $vehicle->weight }}</td> --}}
											{{-- <td>{{ $vehicle->car_condition }}</td> --}}
											{{-- <td>{{ $vehicle->is_ac }}</td> --}}
											<td>{{ $vehicle->is_status }}</td>
											<td>{{ $vehicle->reason }}</td>

                                            <td class="d-flex justify-content-center align-items-center">
                                                {{-- <form action="{{ route('vehicles.destroy',$vehicle->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$vehicle->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$vehicle->id"
                                                        ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                                </form> --}}

                                                {{-- <a style="font-size: 0.75rem;" class="btn btn-outline-primary btn-sm float-right ms-1 my-3 mx-1" href="{{ route('vehicles.edit',$vehicle->id) }}"> --}}
                                                <a style="font-size: 0.75rem;" class="link-color btn-sm float-right ms-1 my-3 mx-1" href="{{ route('vehicles.edit',$vehicle->id) }}">

                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                {{-- <a class="btn btn-outline-success float-right ms-1 mx-1 my-3" href="{{ route('vehicles.show',$vehicle->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> --}}
                                                @if($vehicle->is_status!='sold')
                                                <form action="{{ route('vehicle.change-status',$vehicle->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    @php
                                                    $icon = '
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2 "><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Status Change",
                                                        'body' => 'Please Choose Your Vehicle Status for '. $vehicle->model.'!',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "changestatus-$vehicle->id"
                                                        ];
                                                    @endphp

                                                    @include('vehicle.partials.status', ['data'=>$model])
                                                </form>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $vehicles->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
        {{-- canvas --}}

    <!-- Filter Side Modal -->
    <div class="offcanvas offcanvas-end custom-modal-width" tabindex="-1" id="filterModal" aria-labelledby="filterModalLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterModalLabel">Filter Rides</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Filter Form -->
            <form action="{{route('vehicles.index')}}" method="GET">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="orderNo" class="form-label">Order No</label>
                            <input type="text" class="form-control form-control-sm custom-input-width" id="orderNo" name="order_no" placeholder="Enter Order No">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="customerPartner" class="form-label">Customer</label>
                            <input type="text" class="form-control form-control-sm custom-input-width" id="customerPartner" name="customer_partner" placeholder="Enter Customer Partner">
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="agent" class="form-label">Agent</label>
                            <input type="text" class="form-control form-control-sm custom-input-width" id="agent" name="agent" placeholder="Enter Agent Name">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary custom-btn-filter btn-sm">Apply Filters</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger custom-btn btn-sm" onclick="resetFilters()">Reset</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {{-- end canvas --}}
@endsection
{{--
@push('plugin-scripts')
    <script src="{{ asset('assets/plugins/datatables-net/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-net-bs4/dataTables.bootstrap4.js') }}"></script>
@endpush

@push('custom-scripts')
    <script>
        $(function() {
            'use strict';

            $(function() {
                $('#vehicle').DataTable({
                    //"aLengthMenu": [
                    //    [10, 30, 50,100, -1],
                    //    [10, 30, 50,100, "All"]
                    //],
                    //"iDisplayLength": 10,
                    "bPaginate":false,
                    "language": {
                        search: ""
                    }
                });
                $('#vehicle').each(function() {
                    var datatable = $(this);
                    // SEARCH - Add the placeholder for Search and Turn this into in-line form control
                    var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
                    search_input.attr('placeholder', 'Search');
                    search_input.removeClass('form-control-sm');
                    // LENGTH - Inline-Form control
                    var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
                    length_sel.removeClass('form-control-sm');
                });
            });
        });
    </script>
@endpush
--}}

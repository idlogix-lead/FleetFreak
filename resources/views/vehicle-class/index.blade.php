@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 class="card-title">
            {{ __('Vehicle Class') }}
        </h3>
        {{-- search query --}}
        @php
        $search_link ='vehicle_classes.index';
        $create = 'vehicle_classes.create';
        $table_id='#vehicleClass';
    @endphp
    {{-- @include('layouts.partials.overall_search_query') --}}
    @include('layouts.partials.search_query_perPage')
    @include('layouts.partials.datatable_sorting')


    </div>
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">




                                {{-- <div class="float-right">
                                    <a href="{{ route('vehicle_classes.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">

                                    {{ __('Create New') }}
                                    </a>
                                </div> --}}
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id='vehicleClass' class="table table-sm table-montserrat table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No</th>
										<th>Name</th>
										<th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($vehicleClasses as $vehicleClass)
                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $vehicleClass->name }}</td>
											<td>{{ $vehicleClass->description }}</td>

                                            <td class="d-flex justify-content-center align-items-center">

                                                <form action="{{ route('vehicle_classes.destroy',$vehicleClass->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea536f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$vehicleClass->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$vehicleClass->id"
                                                        ];
                                                    @endphp
                                                    @include('vehicle-class.partials.del_model', ['data'=>$model])
                                                </form>
                                                    <a class="mx-1 link-color" href="{{ route('vehicle_classes.edit',$vehicleClass->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                <a class="mx-1 eye-color" href="{{ route('vehicle_classes.show',$vehicleClass->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $vehicleClasses->links() !!}
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
            <form action="{{route('vehicle_classes.index')}}" method="GET">
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
                $('#vehicleClass').DataTable({
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
                $('#vehicleClass').each(function() {
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

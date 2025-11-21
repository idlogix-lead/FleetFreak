@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
    <div style="display: flex; justify-content: space-between; align-items: center;">

        <h4 class="card-title"><svg width="26" height="26" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>
            {{ __(' Daily Rental') }}
        </h4>
        {{-- search query --}}

        @php
        $search_link ='daily_rentals.index';
        $create = 'daily_rentals.create';
        $table_id='#daily-rentals'
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
                        {{-- <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title"><svg width="26" height="26" fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" clip-rule="evenodd"><path d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z"/></svg>
                                    {{ __(' Order') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('orders.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
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
                        <div class="table-responsive">
                            {{-- table id was order --}}
                            <table id='daily-rentals' class="table table-montserrat table-sm table-hover text-center ">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No #</th>

										<th>Order No <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
										<th>Customer Partner<span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
										<th>Agent <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
										<th>Status <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                                        <th>Actions <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($orders as $order)
                                    <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $order->order_no }}</td>
											<td>{{Str::title($order->partner_customer->name??null) }}</td>
											<td>{{Str::title($order->partner_business->company_name??null) }}</td>
											<td>@php
                                                switch($order->overall_status) {
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
                                            <span class="badge bg-light-{{$badgeClass}} text-{{$badgeClass}} ">{{ Str::title($order->overall_status) }}</span></td>

                                            <td style='padding-right:5%;'>
                                                @if ($order->overall_status !='cancelled' && $order->overall_status !='completed')
                                                    @if(auth()->user()->actor_id==2)
                                                        <form action="{{ route('daily_rentals.destroy',$order->id) }}" method="POST">
                                                            @csrf
                                                            @method('DELETE')

                                                            @php
                                                            $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea536f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                            $model=[
                                                                'notify_btn' => $icon,
                                                                'function' => "Delete",
                                                                'body' => 'Please Confirm do you realy want to Delete '.$order->id.' ?',
                                                                'btn-color' => 'danger',
                                                                'float' => "end",
                                                                'id' => "del-$order->id"
                                                                ];
                                                            @endphp
                                                            @include('rental-vehicle.partials.del_model', ['data'=>$model])
                                                        </form>
                                                    @endif
                                                    <a style="padding:20px 0 0 40px;" class="ms-1 link-color" href="{{ route('daily_rentals.edit',$order->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>

                                                @endif
                                                {{-- <a style="margin: 0px 0px;" class="" href="{{ route('orders.show',$order->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="green" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> --}}


                                            </td>
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
    {{-- canvas --}}

    <!-- Filter Side Modal -->
    <div class="offcanvas offcanvas-end custom-modal-width" tabindex="-1" id="filterModal" aria-labelledby="filterModalLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterModalLabel">Filter Rides</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <!-- Filter Form -->
            <form action="{{route('daily_rentals.index')}}" method="GET">
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
        {{-- // console.log("this is current  path: " window.location.pathname); --}}
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
                $('#order').DataTable({
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
                $('#order').each(function() {
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

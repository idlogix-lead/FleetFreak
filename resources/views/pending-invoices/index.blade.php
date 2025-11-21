@extends('layouts.app')
<style>

    @media print {
        .no-print {
            display: none !important;
        }
    }
</style>
@section('wrapper')
    @if(isset($breadcrumbs))
    <div style="display: flex; justify-content: space-between; align-items: center;">

        <h4 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
          </svg>
            {{ __('Pending Monthly Invoices') }}
        </h4>
        @php
        $search_link ='pending_rental_invoices.index';
        $create= 'pending_rental_invoices.create';
        // $export_link = 'toll-taxes-journals';
        $table_id='#partner';
        @endphp

        {{-- @include('layouts.partials.overall_search_query') --}}
        @include('layouts.partials.search_query_perPage')
        @include('layouts.partials.datatable_sorting')
        </div>
         {{-- search query --}}

        {{-- @php
        $link ='payment_window.index'
        @endphp
        @include('layouts.partials.overall_search_query') --}}
        {{-- --------------- --}}

        {{-- <div class="float-right mx-2">
            <a href="{{ route('payment_window.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="12" y1="8" x2="12" y2="16"></line>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
            {{-- {{ __('Create New') }} --}}
            {{-- </a>
        </div>
    </div> --}}
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="pb-4"> --}}
                            {{-- <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                                    <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                                  </svg>
                                    {{ __('Business') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('business_agents.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
                                        <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus-square">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <line x1="12" y1="8" x2="12" y2="16"></line>
                                            <line x1="8" y1="12" x2="16" y2="12"></line>
                                        </svg>
                                    {{ __('Create New') }}
                                    </a>
                                </div>
                            </div>--}}
                        {{-- </div> --}}
                        <div class="table-responsive ">
                            <table id='partner' class="table-montserrat table-sm table table-hover text-center">
                                @include('ledgers.partials.app')
                                <thead class="thead t-head-clr table-light ">
                                    <tr class="t-head-clr">
                                        <th>No</th>

										<th>Order No</th>
										<th>Order Detail No</th>
										<th>Trip Type</th>
										{{-- <th>Agent</th> --}}
										<th>Customer</th>
                                        <th>Amount</th>
										<th>Status</th>				
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($order_details as $orders)
                                    {{-- @php
                                    dd($payment_header->createdBy->actor_id);
                                    @endphp --}}
                                            <tr>
                                                <td>{{ ++$i }}</td>

                                                <td>{{ $orders->order->order_no ?? null}}</td>
                                                <td>{{ $orders->id ?? null}}</td>
                                                {{-- <td>{{ $payment_header->partner_business->name ?? null }}</td> --}}
                                                <td>{{ $orders->order->trip_type ?? null }}</td>
                                                <td>{{ $orders->order->partner_customer->name ?? null}}</td>
                                                <td>{{ $orders->rate ?? null}}</td>
                                                <td>
                                                    <span class="badge bg-light-primary text-primary  ">{{ Str::title($orders->status) }}
                                                    </span>
                                                </td>
                                                {{-- <td>{{ $payment_line->payment_type }}</td> --}}


                                                <td class="d-flex justify-content-center align-items-center">
                                                    <a href="javascript:void(0)" onclick="printOrderDetails({{ $orders->id }})" class="text-primary ms-2" title="Print">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-printer">
                                                            <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                            <path d="M6 18H4a2 2 0 0 1-2-2V10a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v6a2 2 0 0 1-2 2h-2"></path>
                                                            <rect x="6" y="14" width="12" height="8"></rect>
                                                        </svg>
                                                        Print
                                                    </a>
                                                    {{-- <form action="{{ route('partners.destroy',$partner->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')

                                                        @php
                                                        $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                        $model=[
                                                            'notify_btn' => $icon,
                                                            'function' => "Delete",
                                                            'body' => 'Please Confirm do you realy want to Delete '.$partner->id.' ?',
                                                            'btn-color' => 'danger',
                                                            'float' => "end",
                                                            'id' => "del-$partner->id"
                                                            ];
                                                        @endphp
                                                        @include('partials.modal', ['data'=>$model])
                                                    </form> --}}
                                                    {{-- @if ($payment_header->status== 'draft') --}}




                                                    {{-- <a class="btn btn-outline-primary btn-sm float-right ms-1" href="{{ route('payment_window.edit', [
                                                        'id' => $payment_header->id,

                                                    ]) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                                            <line x1="12" y1="5" x2="12" y2="19"></line>
                                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                                        </svg>
                                                    </a> --}}
                                                    {{-- @else --}}
                                                    {{-- not available
                                                    @endif --}}
                                                    {{-- <a class="btn btn-outline-success float-right ms-1" href="{{ route('partners.show',$partner->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    </a> --}}
                                                </td>
                                            </tr>
                                        
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- {!! $partner_lines->links() !!} --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>
    function printOrderDetails(orderId) {
        // Open a new window or tab with print content
        const printWindow = window.open(`/print-order/${orderId}`, '_blank');
        
        // If you want the print dialog to appear automatically
        printWindow.onload = function () {
            printWindow.print();
        };
    }
</script>

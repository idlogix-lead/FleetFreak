@extends('layouts.app')

@section('wrapper')
{{-- for th styling --}}
<style>
    th {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
    @if(isset($breadcrumbs))
     <div style="display: flex; justify-content: space-between; align-items: center;">

            <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
                <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
            </svg>
             {{ __(' Bulk Payments') }}
            </h3>
            {{-- search query --}}

            @php
            $search_link ='bulkpayments.index';
            $create = 'bulkpayments.create';
            $export_link = 'export.bulkpayments';
            $table_id='#order';
            @endphp
            {{-- @include('layouts.partials.overall_search_query') --}}
            @include('layouts.partials.search_query_perPage')
            @include('layouts.partials.datatable_sorting')
        </div>
    
        @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
    @endif
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="pb-0">
                            <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>{{ __(' Filterations') }}</h3>
                            </div>
                            @php
                                $link = 'bulkpayments.index';
                            @endphp
                            @include('bulk-payment.partials.filter')
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="pb-4">
                            {{-- <div style="display: flex; justify-content: space-between; align-items: center;">
                                <h3 class="card-title">
                                    <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                                  {{ __(' Bulk Payments') }}</h3>
                            </div>
                        </div> --}}

                        <div class="table-responsive">

                            <table id='order' class="table table-montserrat table-sm table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No # </th>
                                        <th>Order No </th>
                                        <th>Order Detail No </th>
                                        {{-- <th>Agent Name</th> --}}
                                        <th>Customer Name</th>
                                        <th>Driver</th>
                                        <th>Amount</th>
                                        <th>Action</th>
                                   
                                        
                                    </tr>
                                </thead>
                                <tbody style="vertical-align: middle;" class="body-font">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $order->order->order_no }}</td>
                                            <td>{{ $order->id }}</td>
                                            <td>{{ Str::title($order->order->partner_customer->name) }}</td>
                                        
                                            <td>{{$order->driver->name??null}}</td>
                                            <td>{{$order->rate?? $order->driver_rate}}</td>
                                            <td>
                                                <button type="button" class="btn pay-now-btn btn-sm" 
                                             
                                                data-order-no="{{ $order->order->id }}"
                                                data-order-detail-no="{{ $order->id }}"
                                                data-status-id= "{{'paid'}}"
                                                data-customer-name="{{ Str::title($order->order->partner_customer->name) }}"
                                                data-customer-id="{{$order->order->partner_customer->id}}"
                                                data-agent-id="{{$order->order->partner_business->id}}"
                                                data-driver-name="{{ $order->driver->name ?? null }}"
                                                data-amount="{{ $order->rate ?? null }}"> 
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#00A978" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                                    <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                                                    <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z"/>
                                                    <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                                                    <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567"/>
                                                  </svg>
                                                </button>

                                                <button type="button" class="btn draft-btn btn-sm" 
                                                
                                                data-order-no="{{ $order->order->id }}"
                                                data-order-detail-no="{{ $order->id }}"
                                                data-customer-name="{{ Str::title($order->order->partner_customer->name) }}"
                                                data-status-id= "{{'draft'}}"
                                                data-customer-id="{{$order->order->partner_customer->id}}"
                                                data-agent-id="{{$order->order->partner_business->id}}"
                                                data-driver-name="{{ $order->driver->name ?? null }}"
                                                data-amount="{{ $order->rate ?? null }}">
                                                
                                                
                                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#5660AB" class="bi bi-file-earmark-text" viewBox="0 0 16 16">
                                                    <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5L14 4.5zm-3-.5a1 1 0 0 0 1-1V2H9.5L8 3.5H11zm-7 5v-1h5v1H4zm0 2v-1h3v1H4z"/>
                                                </svg>
                                                </button>
                                                @include('bulk-payment.partials.model')

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
@endsection

<script>
    $(document).ready(function() {
      
});
</script>

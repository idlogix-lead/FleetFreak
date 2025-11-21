@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
    <div style="display: flex; justify-content: space-between; align-items: center;">

        <h4 class="card-title"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-people-fill" viewBox="0 0 16 16">
            <path d="M7 14s-1 0-1-1 1-4 5-4 5 3 5 4-1 1-1 1zm4-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5.784 6A2.24 2.24 0 0 1 5 13c0-1.355.68-2.75 1.936-3.72A6.3 6.3 0 0 0 5 9c-4 0-5 3-5 4s1 1 1 1zM4.5 8a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
          </svg>
            {{ __('Payments') }}
        </h4>
            @php
                $search_link ='payments.index';
                $create= 'payments.create';
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
            <a href="{{ route('payments.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">
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

										<th>Payment No</th>
										<th>Date</th>
										<th>Business Partner</th>
										{{-- <th>Customer</th> --}}
										<th>Status</th>
										<th>Total Amount</th>
										<th>Description</th>



                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($payment_headers as $payment_header)
                                    {{-- @php
                                    dd($payment_header->createdBy->actor_id);
                                    @endphp --}}
                                    @if($payment_header->createdBy && $payment_header->createdBy->role_id == 2)

                                            <tr>
                                                @php
                                                $payment_header_id = $payment_header->id;
                                                // dd($payment_header_id);
                                                @endphp
                                                <td>{{ ++$i }}</td>

                                                <td>{{ $payment_header->payment_no ?? null}}</td>
                                                <td>{{ $payment_header->date ?? null}}</td>
                                                <td>{{ $payment_header->partner_business->name ?? null }}</td>
                                                {{-- <td>{{ $payment_header->partner_customer->name ?? null }}</td> --}}
                                                <td>{{ $payment_header->status ?? null}}</td>
                                                <td>{{ $payment_header->total_amount ?? null}}</td>
                                                <td>{{ $payment_header->description?? null }}</td>
                                                {{-- <td>{{ $payment_line->payment_type }}</td> --}}


                                                <td class="d-flex justify-content-center align-items-center">
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
                                                    @if ($payment_header->status== 'draft')


{{--
                                                        <button type="button" class="btn pay-now-btn btn-sm" data-bs-toggle="modal" data-bs-target="#payNowModal" >


                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="#00A978" class="bi bi-cash-coin" viewBox="0 0 16 16">
                                                            <path fill-rule="evenodd" d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8m5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0"/>
                                                            <path d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195z"/>
                                                            <path d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083q.088-.517.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2 2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1z"/>
                                                            <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 6 6 0 0 1 3.13-1.567"/>
                                                          </svg>
                                                        </button> --}}

                                                        @include('payment.partials.model')

                                                    <a class="mx-1 link-color" href="{{ route('payments.edit',$payment_header->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                    </a>
                                                    @else
                                                    not available
                                                    @endif
                                                    {{-- <a class="btn btn-outline-success float-right ms-1" href="{{ route('partners.show',$partner->id) }}">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                    </a> --}}
                                                </td>
                                            </tr>
                                        @endif
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
<script>
    $(document).ready(function () {
    const payNowForm = $('#payNowForm');

    $('.pay-now-btn').on('click', function () {
        const paymentHeaderId = $(this).data('id'); // Get the ID from the clicked button's data-id
        const actionUrl = payNowForm.attr('action').replace(':id', paymentHeaderId); // Replace :id with actual ID
        payNowForm.attr('action', actionUrl); // Set the updated action
    });
});
</script>
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
                $('#partner').DataTable({
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
                $('#partner').each(function() {
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

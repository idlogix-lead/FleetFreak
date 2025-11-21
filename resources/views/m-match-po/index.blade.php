@extends('layouts.app')
@section('wrapper')
    @php
    $table_id ='#mMatchPo';
    $create='m-match-pos.create';
    @endphp
    @include('layouts.partials.datatable_sorting')

    <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">
                {{ __('M Match Po') }}
        </h3>
        <div>
            @include('layouts.partials.search_query_perPage')
        </div>
    </div>
    @if(isset($breadcrumbs))
        <div>
          @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
        </div>
    @endif

    <div class="container-fluid">

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body-not-used">
                        <div class="table-responsive">
                            <table id='mMatchPo' class="table-montserrat table-sm table table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No</th>
                                        
										{{-- <th>Client Id</th>
										<th>Company Id</th> --}}
										{{-- <th>Description</th> --}}
										<th>Product Id</th>
										<th>PO</th>
										<th>Material Inout Line</th>
										<th>PI Line</th>
										<th>Document Type</th>
										<th>Quantity</th>
										<th>Transaction Date</th>
										{{-- <th>Account Date</th> --}}

                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($mMatchPos as $mMatchPo)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
											{{-- <td>{{ $mMatchPo->client_id }}</td>
											<td>{{ $mMatchPo->company_id }}</td> --}}
											{{-- <td>{{ $mMatchPo->description }}</td> --}}
											<td>{{ $mMatchPo->product->name }}</td>
											<td>{{ $mMatchPo->orderLine->order->order_no }}</td>
											<td>{{ $mMatchPo->materialInoutLine->materialInout->document_no }}</td>
											<td>{{ $mMatchPo->invoiceLine->invoice->document_no }}</td>
											<td>{{ $mMatchPo->invoiceDocumentType->name }}</td>
											<td>{{ $mMatchPo->quantity }}</td>
											<td>{{ $mMatchPo->transaction_date }}</td>
											{{-- <td>{{ $mMatchPo->account_date }}</td> --}}

                                            <td style='min-width:150px;'>
                                                ---
                                                {{-- <a class="btn btn-sm btn-outline-primary float-end ms-1" href="{{ route('m-match-pos.edit',$mMatchPo->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                <a class="btn btn-sm btn-outline-success float-end ms-1" href="{{ route('m-match-pos.show',$mMatchPo->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>
                                                <form action="{{ route('m-match-pos.destroy',$mMatchPo->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$mMatchPo->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$mMatchPo->id"
                                                        ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                                </form> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $mMatchPos->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                $('#mMatchPo').DataTable({
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
                $('#mMatchPo').each(function() {
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

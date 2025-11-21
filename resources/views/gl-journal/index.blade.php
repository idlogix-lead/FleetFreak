@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
       <div style="display: flex; justify-content: space-between; align-items: center;">

                <h3 class="card-title">
                    {{ __('Gl Journal') }}
                </h3>
                {{-- search query --}}

                @php
                    $search_link ='gl-journals.index';
                    $create_link = 'gl-journals.create';
                    $export_link = 'export.gl-journals';
                    $table_id='#glJournal';
                @endphp

                @include('layouts.partials.overall_search_query')
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

                                <h3 class="card-title">
                                    {{ __('Gl Journal') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('gl-journals.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">

                                    {{ __('Create New') }}
                                    </a>
                                </div>
                            </div>
                        </div> --}}
                        <div class="table-responsive">
                            <table id='glJournal' class="table table-hover table-montserrat table-sm  text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>Document No</th>
										<th>Transaction Date</th>
										<th>Debit</th>
										<th>Credit</th>
										<th>Document Status</th>
										<th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($glJournals as $glJournal)
                                        <tr>
                                            {{-- <td>{{ ++$i }}</td> --}}

											{{-- <td>{{ $glJournal->company_id }}</td> --}}
											<td>{{ $glJournal->document_no }}</td>
											<td>{{ $glJournal->transaction_date }}</td>
											<td>{{ $glJournal->debit }}</td>
											<td>{{ $glJournal->credit }}</td>
											<td>{{ $glJournal->status }}</td>
											<td>{{ $glJournal->description }}</td>

                                            <td style='display:flex;'>
                                                <form action="{{ route('gl-journals.destroy',$glJournal->id) }}" method="POST" class="p-0 m-0">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$glJournal->id.' ?',
                                                        'btn-color' => ' text-danger p-0',
                                                        'float' => "end",
                                                        'id' => "del-$glJournal->id"
                                                        ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                                    <style>
                                                        #btn_del-{{$glJournal->id}}{
                                                           margin:0px !important;
                                                        }
                                                    </style>
                                                </form>

                                                <a class="text-primary float-right ms-1 p-0" href="{{ route('gl-journals.edit',$glJournal->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                {{-- <a class="btn btn-outline-success btn-sm float-right ms-1" href="{{ route('gl-journals.show',$glJournal->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $glJournals->links() !!}
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
                $('#glJournal').DataTable({
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
                $('#glJournal').each(function() {
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

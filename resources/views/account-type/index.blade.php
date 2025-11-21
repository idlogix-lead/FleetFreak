@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-4">
                            <div style="display: flex; justify-content: space-between; align-items: center;">

                                <h3 class="card-title">
                                    {{ __('Account Type') }}
                                </h3>

                                <div class="float-right">
                                    <a href="{{ route('account-types.create') }}" class="btn btn-outline-primary float-right"  data-placement="left">

                                    {{ __('Create New') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table id='accountType' class="table table-hover">
                                <thead class="thead">
                                    <tr>

										<th>Parent</th>
										<th>Name</th>
										{{-- <th>Code</th> --}}
										{{-- <th>Company Id</th> --}}
										<th>Is Active</th>
										{{-- <th>Description</th> --}}

                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accountTypes as $accountType)
                                        <tr>
											<td>
                                                {{ $accountType?->parent?->code }}
                                                 -
                                                {{ $accountType?->parent?->name }}
                                            </td>
											{{-- <td>{{ $accountType->code }}</td> --}}
											<td>
                                                {{-- {{ $accountType->parent_id?'--':'' }} --}}
                                                {{ $accountType->code }}
                                                 -
                                                {{ $accountType->name }}
                                            </td>
											{{-- <td>{{ $accountType->company_id }}</td> --}}
											<td>
                                                {{-- <input type="checkbox" name="" checked readonly id=""> --}}
                                                {{ $accountType->is_active ? 'Yes' : 'No' }}

                                            </td>
											{{-- <td>{{ $accountType->description }}</td> --}}

                                            <td>

                                                <a class="btn btn-outline-primary float-end ms-1 btn-sm" href="{{ route('account-types.edit',$accountType->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                {{-- <a class="btn btn-outline-success float-end ms-1  btn-sm" href="{{ route('account-types.show',$accountType->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a> --}}
                                                <form action="{{ route('account-types.destroy',$accountType->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$accountType->id.' ?',
                                                        'btn-color' => 'danger ',
                                                        'float' => "end",
                                                        'id' => "del-$accountType->id"
                                                    ];
                                                    @endphp
                                                    @include('partials.modal', ['data'=>$model])
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $accountTypes->links() !!}
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
                $('#accountType').DataTable({
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
                $('#accountType').each(function() {
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

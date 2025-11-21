@extends('layouts.app')
@section('wrapper')
    @php
    $table_id ='#locator';
    $create='locators.create';
    @endphp
    @include('layouts.partials.datatable_sorting')

    <div style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title">
                {{ __('Locator') }}
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
                            <table id='locator' class="table-montserrat table-sm table table-hover text-center">
                                <thead class="thead t-head-clr table-light">
                                    <tr>
                                        <th>No</th>
                                        
										<th>Warehouse</th>
										<th>Code</th>
										<th>Locator Type</th>
										<th>Is Active</th>
										<th>Is Default</th>
										<th>Relative Priority</th>
										<th>Aisle</th>
										<th>Bin</th>
										<th>Level</th>

                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="body-font">
                                    @foreach ($locators as $locator)
                                        <tr>
                                            <td>{{ ++$i }}</td>

											<td>{{ $locator->wareHouse->name }}</td>
											<td>{{ $locator->code }}</td>
											<td>{{ $locator->locator_type }}</td>
											<td>{{ $locator->is_active }}</td>
											<td>{{ $locator->is_default }}</td>
											<td>{{ $locator->relative_priority }}</td>
											<td>{{ $locator->aisle }}</td>
											<td>{{ $locator->bin }}</td>
											<td>{{ $locator->level }}</td>

                                            <td class="d-flex justify-content-center align-items-center">

                                                <a class="mx-1 link-color" href="{{ route('locators.edit',$locator->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                                </a>
                                                <a class="mx-1 eye-color" href="{{ route('locators.show',$locator->id) }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-eye"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                                </a>
                                                <form action="{{ route('locators.destroy',$locator->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')

                                                    @php
                                                    $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#ea536f" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>';
                                                    $model=[
                                                        'notify_btn' => $icon,
                                                        'function' => "Delete",
                                                        'body' => 'Please Confirm do you realy want to Delete '.$locator->id.' ?',
                                                        'btn-color' => 'danger',
                                                        'float' => "end",
                                                        'id' => "del-$locator->id"
                                                        ];
                                                    @endphp
                                                    @include('location.model.del-model', ['data'=>$model])
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $locators->links() !!}
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
                $('#locator').DataTable({
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
                $('#locator').each(function() {
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

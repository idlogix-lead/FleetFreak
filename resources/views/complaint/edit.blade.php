@extends('layouts.app')

@section('wrapper')
    @if (isset($breadcrumbs))
        @include('layouts.partials.breadcrumb', compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                @includeif('partials.errors')

                {{-- <div class="card card-default">
                    <h4 class="p-4">
                        <span class="card-title">{{ __('Update') }} Complaint</span>
                    </h4>
                <div class="card-body">
                    <form method="POST" action="{{ route('complaints.update', $complaint->id) }}" role="form"
                        enctype="multipart/form-data">
                        {{ method_field('PATCH') }}
                        @csrf

                        @include('complaint.form')

                    </form>
                </div> --}}
                <button type="save" class="btn btn-outline-{{ $edit['btn-color'] }} float-{{ $edit['float'] }}  t modd"
                    data-bs-toggle="modal" data-bs-target="#exampleModal{{ $edit['save'] }}" id="btn_{{ $edit['id'] }}">
                    {!! $edit['notify_btn'] !!}
                </button>

                <div id="{{ $edit['id'] }}" class="modal fade" tabindex="-1" role="dialog"
                    aria-labelledby="{{ $edit['id'] }}Label" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="{{ $edit['id'] }}Label">{{ $edit['function'] }} Edit</h5>
                                <!-- Corrected closing tag -->
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>{{ $edit['body'] }}</p>
                                <!-- Embedding the form -->
                                <form id="modalForm" method="POST"
                                    action="{{ route('complaints.update', $complaint->id) }}" role="form"
                                    enctype="multipart/form-data">
                                    {{ method_field('PATCH') }}
                                    @csrf
                                    @include('complaint.form')
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button type="submit" form="modalForm"
                                    class="btn btn-{{ $edit['btn-color'] }}">{{ $edit['notify_btn'] }}</button>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        </div>
    </section>
@endsection

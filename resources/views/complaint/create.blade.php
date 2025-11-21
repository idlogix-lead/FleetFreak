@extends('layouts.app')

@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default">
                    <h4 class="p-4">
                        <span class="card-title">{{ __('Create') }} Complaint</span>
                    </h4>
                    <div class="card-body">
                        <form method="POST" action="{{ route('complaints.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('complaint.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

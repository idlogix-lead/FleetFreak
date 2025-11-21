@extends('layouts.app')


@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Activity</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('activities.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $activity->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $activity->description }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $activity->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $activity->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

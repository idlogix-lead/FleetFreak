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
                            <span class="card-title">{{ __('Show') }} Route</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('routes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $route->name }}
                        </div>
                        <div class="form-group">
                            <strong>From:</strong>
                            {{ $route->from }}
                        </div>
                        <div class="form-group">
                            <strong>To:</strong>
                            {{ $route->to }}
                        </div>
                        <div class="form-group">
                            <strong>Distance:</strong>
                            {{ $route->distance }}
                        </div>
                        <div class="form-group">
                            <strong>Distance Unit:</strong>
                            {{ $route->distance_unit }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

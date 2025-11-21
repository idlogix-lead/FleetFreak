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
                            <span class="card-title">{{ __('Show') }} Service Provider</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('service_providers.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Address:</strong>
                            {{ $serviceProvider->address }}
                        </div>
                        <div class="form-group">
                            <strong>City:</strong>
                            {{ $serviceProvider->city }}
                        </div>
                        <div class="form-group">
                            <strong>Contact:</strong>
                            {{ $serviceProvider->contact }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

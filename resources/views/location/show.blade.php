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
                            <span class="card-title">{{ __('Show') }} Location</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('locations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $location->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $location->description }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

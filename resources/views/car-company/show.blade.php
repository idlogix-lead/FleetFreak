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
                            <span class="card-title">{{ __('Show') }} Car Company</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('car-companies.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $carCompany->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $carCompany->description }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

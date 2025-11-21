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
                            <span class="card-title">{{ __('Show') }} Brand</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('brands.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $brand->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $brand->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $brand->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $brand->description }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $brand->code }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

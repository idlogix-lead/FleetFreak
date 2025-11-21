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
                            <span class="card-title">{{ __('Show') }} Locator</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('locators.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $locator->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $locator->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Warehouse Id:</strong>
                            {{ $locator->warehouse_id }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $locator->code }}
                        </div>
                        <div class="form-group">
                            <strong>Locator Type:</strong>
                            {{ $locator->locator_type }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $locator->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>Is Default:</strong>
                            {{ $locator->is_default }}
                        </div>
                        <div class="form-group">
                            <strong>Relative Priority:</strong>
                            {{ $locator->relative_priority }}
                        </div>
                        <div class="form-group">
                            <strong>Aisle:</strong>
                            {{ $locator->aisle }}
                        </div>
                        <div class="form-group">
                            <strong>Bin:</strong>
                            {{ $locator->bin }}
                        </div>
                        <div class="form-group">
                            <strong>Level:</strong>
                            {{ $locator->level }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

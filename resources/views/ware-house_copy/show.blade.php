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
                            <span class="card-title">{{ __('Show') }} Ware House</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('ware-houses.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $wareHouse->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $wareHouse->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $wareHouse->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $wareHouse->description }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $wareHouse->code }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $wareHouse->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>In Transit:</strong>
                            {{ $wareHouse->in_transit }}
                        </div>
                        <div class="form-group">
                            <strong>Address:</strong>
                            {{ $wareHouse->address }}
                        </div>
                        <div class="form-group">
                            <strong>Source Warehouse Id:</strong>
                            {{ $wareHouse->source_warehouse_id }}
                        </div>
                        <div class="form-group">
                            <strong>Is Disallow Negative Inv:</strong>
                            {{ $wareHouse->is_disallow_negative_inv }}
                        </div>
                        <div class="form-group">
                            <strong>Locator Id:</strong>
                            {{ $wareHouse->locator_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

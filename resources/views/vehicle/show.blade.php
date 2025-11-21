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
                            <span class="card-title">{{ __('Show') }} Vehicle</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('vehicles.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                        <div class = "d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret">
                            <img src="{{ asset('storage/'.$vehicle->image) }}" class="user-img" alt="user avatar">
                            </div>
                        </div>
                        <div class="form-group">
                            <strong>Vehicle Identification Number:</strong>
                            {{ $vehicle->vehicle_identification_number }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $vehicle->vehicle_company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Model:</strong>
                            {{ $vehicle->model }}
                        </div>
                        <div class="form-group">
                            <strong>Year:</strong>
                            {{ $vehicle->year }}
                        </div>
                        <div class="form-group">
                            <strong>Color:</strong>
                            {{ $vehicle->color }}
                        </div>
                        <div class="form-group">
                            <strong>License Plate Number:</strong>
                            {{ $vehicle->license_plate_number }}
                        </div>
                        <div class="form-group">
                            <strong>Registration:</strong>
                            {{ $vehicle->registration }}
                        </div>
                        <div class="form-group">
                            <strong>Ownership:</strong>
                            {{ $vehicle->ownership }}
                        </div>
                        <div class="form-group">
                            <strong>Fuel Type:</strong>
                            {{ $vehicle->fuel_type }}
                        </div>
                        <div class="form-group">
                            <strong>Engine Type:</strong>
                            {{ $vehicle->engine_type }}
                        </div>
                        <div class="form-group">
                            <strong>Transmission Type:</strong>
                            {{ $vehicle->transmission_type }}
                        </div>
                        <div class="form-group">
                            <strong>Vehicle Class Id:</strong>
                            {{ $vehicle->vehicle_class_id }}
                        </div>
                        <div class="form-group">
                            <strong>Weight:</strong>
                            {{ $vehicle->weight }}
                        </div>
                        <div class="form-group">
                            <strong>Car Condition:</strong>
                            {{ $vehicle->car_condition }}
                        </div>
                        <div class="form-group">
                            <strong>Is Ac:</strong>
                            {{ $vehicle->is_ac }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

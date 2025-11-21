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
                            <span class="card-title">{{ __('Show') }} Machine</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('machines.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Customer Id:</strong>
                            {{ $machine->customer_id }}
                        </div>
                        <div class="form-group">
                            <strong>Serial Number:</strong>
                            {{ $machine->serial_number }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $machine->name }}
                        </div>
                        <div class="form-group">
                            <strong>Model:</strong>
                            {{ $machine->model }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $machine->description }}
                        </div>
                        <div class="form-group">
                            <strong>Image:</strong>
                            {{ $machine->image }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

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
                            <span class="card-title">{{ __('Show') }} Tax</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('taxes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $tax->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $tax->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $tax->name }}
                        </div>
                        <div class="form-group">
                            <strong>Rate:</strong>
                            {{ $tax->rate }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $tax->description }}
                        </div>
                        <div class="form-group">
                            <strong>Is Default:</strong>
                            {{ $tax->is_default }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $tax->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>Valid From:</strong>
                            {{ $tax->valid_from }}
                        </div>
                        <div class="form-group">
                            <strong>Type:</strong>
                            {{ $tax->type }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

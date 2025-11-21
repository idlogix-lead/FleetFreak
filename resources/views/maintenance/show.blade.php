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
                            <span class="card-title">{{ __('Show') }} Invoice</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('maintenances.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="form-group">
                            <strong>Document No:</strong>
                            {{ $maintenance->document_no }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $maintenance->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Vehicle Id:</strong>
                            {{ $maintenance->vehicle_id }}
                        </div>
                        <div class="form-group">
                            <strong>Business Partner Id:</strong>
                            {{ $maintenance->business_partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $maintenance->date }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $maintenance->description }}
                        </div>
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $maintenance->total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Grand Total Amount:</strong>
                            {{ $maintenance->grand_total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Document Status:</strong>
                            {{ $maintenance->document_status }}
                        </div>
                        <div class="form-group">
                            <strong>Document Type:</strong>
                            {{ $maintenance->document_type }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

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
                            <span class="card-title">{{ __('Show') }} Toll Tax</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('toll-taxes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                     <div class="card-body">

                        <div class="form-group">
                            <strong>Document No:</strong>
                            {{ $tolltax->document_no }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $tolltax->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Vehicle Id:</strong>
                            {{ $tolltax->vehicle_id }}
                        </div>
                        <div class="form-group">
                            <strong>Business Partner Id:</strong>
                            {{ $tolltax->business_partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $tolltax->date }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $tolltax->description }}
                        </div>
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $tolltax->total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Grand Total Amount:</strong>
                            {{ $tolltax->grand_total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Document Status:</strong>
                            {{ $tolltax->document_status }}
                        </div>
                        {{-- <div class="form-group">
                            <strong>Document Type:</strong>
                            {{ $tolltax->document_type }}
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

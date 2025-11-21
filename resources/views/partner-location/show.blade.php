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
                            <span class="card-title">{{ __('Show') }} Partner Location</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('partner-locations.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Partner Id:</strong>
                            {{ $partnerLocation->partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>Address1:</strong>
                            {{ $partnerLocation->address1 }}
                        </div>
                        <div class="form-group">
                            <strong>Address2:</strong>
                            {{ $partnerLocation->address2 }}
                        </div>
                        <div class="form-group">
                            <strong>Address3:</strong>
                            {{ $partnerLocation->address3 }}
                        </div>
                        <div class="form-group">
                            <strong>Primary Contact Person:</strong>
                            {{ $partnerLocation->primary_contact_person }}
                        </div>
                        <div class="form-group">
                            <strong>Secondary Contact Person:</strong>
                            {{ $partnerLocation->secondary_contact_person }}
                        </div>
                        <div class="form-group">
                            <strong>Prefix Phone:</strong>
                            {{ $partnerLocation->prefix_phone }}
                        </div>
                        <div class="form-group">
                            <strong>Phone No:</strong>
                            {{ $partnerLocation->phone_no }}
                        </div>
                        <div class="form-group">
                            <strong>Prefix Whatsapp:</strong>
                            {{ $partnerLocation->prefix_whatsapp }}
                        </div>
                        <div class="form-group">
                            <strong>Whatsapp No:</strong>
                            {{ $partnerLocation->whatsapp_no }}
                        </div>
                        <div class="form-group">
                            <strong>City:</strong>
                            {{ $partnerLocation->city }}
                        </div>
                        <div class="form-group">
                            <strong>Country:</strong>
                            {{ $partnerLocation->country }}
                        </div>
                        <div class="form-group">
                            <strong>Is Default:</strong>
                            {{ $partnerLocation->is_default }}
                        </div>
                        <div class="form-group">
                            <strong>Ship Address:</strong>
                            {{ $partnerLocation->ship_address }}
                        </div>
                        <div class="form-group">
                            <strong>Invoice Address:</strong>
                            {{ $partnerLocation->invoice_address }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

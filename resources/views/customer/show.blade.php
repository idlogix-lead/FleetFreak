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
                            <span class="card-title">{{ __('Show') }} Partner</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('partners.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $partner->name }}
                        </div>
                        <div class="form-group">
                            <strong>Partner Type:</strong>
                            {{ $partner->partner_type }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $partner->email }}
                        </div>
                        <div class="form-group">
                            <strong>Phone No:</strong>
                            {{ $partner->phone_no }}
                        </div>
                        <div class="form-group">
                            <strong>Whatsapp No:</strong>
                            {{ $partner->whatsapp_no }}
                        </div>
                        <div class="form-group">
                            <strong>Cnic:</strong>
                            {{ $partner->cnic }}
                        </div>
                        <div class="form-group">
                            <strong>Address1:</strong>
                            {{ $partner->address1 }}
                        </div>
                        <div class="form-group">
                            <strong>Address2:</strong>
                            {{ $partner->address2 }}
                        </div>
                        <div class="form-group">
                            <strong>Address3:</strong>
                            {{ $partner->address3 }}
                        </div>
                        <div class="form-group">
                            <strong>City:</strong>
                            {{ $partner->city }}
                        </div>
                        <div class="form-group">
                            <strong>Country:</strong>
                            {{ $partner->country }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

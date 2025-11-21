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
                            <span class="card-title">{{ __('Show') }} Rental Vehicle</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('daily_rentals.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Order No:</strong>
                            {{ $order->order_no }}
                        </div>
                        <div class="form-group">
                            <strong>Customer Partner Id:</strong>
                            {{ $order->customer_partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>Business Partner Id:</strong>
                            {{ $order->business_partner_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

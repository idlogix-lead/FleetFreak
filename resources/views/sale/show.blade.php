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
                            <span class="card-title">{{ __('Show') }} Sale</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('sales.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $sale->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $sale->quantity }}
                        </div>
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $sale->total_amount }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

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
                            <span class="card-title">{{ __('Show') }} Product Costing</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('product-costings.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $productCosting->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $productCosting->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $productCosting->description }}
                        </div>
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $productCosting->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Current Cost:</strong>
                            {{ $productCosting->current_cost }}
                        </div>
                        <div class="form-group">
                            <strong>Current Qty:</strong>
                            {{ $productCosting->current_qty }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

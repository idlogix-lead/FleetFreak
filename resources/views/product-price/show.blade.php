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
                            <span class="card-title">{{ __('Show') }} Product Price</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('product-prices.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $productPrice->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $productPrice->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $productPrice->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Price List Version Id:</strong>
                            {{ $productPrice->price_list_version_id }}
                        </div>
                        <div class="form-group">
                            <strong>List Price:</strong>
                            {{ $productPrice->list_price }}
                        </div>
                        <div class="form-group">
                            <strong>Standard Price:</strong>
                            {{ $productPrice->standard_price }}
                        </div>
                        <div class="form-group">
                            <strong>Limit Price:</strong>
                            {{ $productPrice->limit_price }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $productPrice->description }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $productPrice->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>Is Defalut:</strong>
                            {{ $productPrice->is_defalut }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

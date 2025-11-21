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
                            <span class="card-title">{{ __('Show') }} Product</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('products.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $product->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $product->description }}
                        </div>
                        <div class="form-group">
                            <strong>Product Image:</strong>
                            {{ $product->product_image }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $product->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Cost Price:</strong>
                            {{ $product->cost_price }}
                        </div>
                        <div class="form-group">
                            <strong>Sale Price:</strong>
                            {{ $product->sale_price }}
                        </div>
                        <div class="form-group">
                            <strong>Unit:</strong>
                            {{ $product->unit }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

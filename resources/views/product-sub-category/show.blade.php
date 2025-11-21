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
                            <span class="card-title">{{ __('Show') }} Product Sub Category</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('product-sub-categories.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $productSubCategory->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $productSubCategory->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $productSubCategory->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $productSubCategory->description }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $productSubCategory->code }}
                        </div>
                        <div class="form-group">
                            <strong>Product Category Id:</strong>
                            {{ $productSubCategory->product_category_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

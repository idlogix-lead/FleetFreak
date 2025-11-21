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
                            <span class="card-title">{{ __('Show') }} M Match Po</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('m-match-pos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Client Id:</strong>
                            {{ $mMatchPo->client_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $mMatchPo->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $mMatchPo->description }}
                        </div>
                        <div class="form-group">
                            <strong>Product Id:</strong>
                            {{ $mMatchPo->product_id }}
                        </div>
                        <div class="form-group">
                            <strong>Po Line Id:</strong>
                            {{ $mMatchPo->po_line_id }}
                        </div>
                        <div class="form-group">
                            <strong>Material Inout Line Id:</strong>
                            {{ $mMatchPo->material_inout_line_id }}
                        </div>
                        <div class="form-group">
                            <strong>Pi Line Id:</strong>
                            {{ $mMatchPo->pi_line_id }}
                        </div>
                        <div class="form-group">
                            <strong>Document Type Id:</strong>
                            {{ $mMatchPo->document_type_id }}
                        </div>
                        <div class="form-group">
                            <strong>Quantity:</strong>
                            {{ $mMatchPo->quantity }}
                        </div>
                        <div class="form-group">
                            <strong>Transaction Date:</strong>
                            {{ $mMatchPo->transaction_date }}
                        </div>
                        <div class="form-group">
                            <strong>Account Date:</strong>
                            {{ $mMatchPo->account_date }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

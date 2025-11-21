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
                            <span class="card-title">{{ __('Show') }} Fuel Expense</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('fuel-expenses.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                       <div class="card-body">

                        <div class="form-group">
                            <strong>Document No:</strong>
                            {{ $fuelExpense->document_no }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $fuelExpense->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Vehicle Id:</strong>
                            {{ $fuelExpense->vehicle_id }}
                        </div>
                        <div class="form-group">
                            <strong>Business Partner Id:</strong>
                            {{ $fuelExpense->business_partner_id }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $fuelExpense->date }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $fuelExpense->description }}
                        </div>
                        <div class="form-group">
                            <strong>Total Amount:</strong>
                            {{ $fuelExpense->total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Grand Total Amount:</strong>
                            {{ $fuelExpense->grand_total_amount }}
                        </div>
                        <div class="form-group">
                            <strong>Document Status:</strong>
                            {{ $fuelExpense->document_status }}
                        </div>
                        {{-- <div class="form-group">
                            <strong>Document Type:</strong>
                            {{ $fuelExpense->document_type }}
                        </div> --}}

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

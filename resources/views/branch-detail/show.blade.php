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
                            <span class="card-title">{{ __('Show') }} Branch Detail</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('branch_details.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Branche Name:</strong>
                            {{ $branchDetail->branche_name }}
                        </div>
                        <div class="form-group">
                            <strong>Location:</strong>
                            {{ $branchDetail->location }}
                        </div>
                        <div class="form-group">
                            <strong>Branche Manager:</strong>
                            {{ $branchDetail->branche_manager }}
                        </div>
                        <div class="form-group">
                            <strong>Manager Contact:</strong>
                            {{ $branchDetail->manager_contact }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $branchDetail->status }}
                        </div>
                        <div class="form-group">
                            <strong>Total Sales:</strong>
                            {{ $branchDetail->total_sales }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

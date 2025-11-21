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
                            <span class="card-title">{{ __('Show') }} Complaint</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('complaints.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Customer Id:</strong>
                            {{ $complaint->customer_id }}
                        </div>
                        <div class="form-group">
                            <strong>Machine Id:</strong>
                            {{ $complaint->machine_id }}
                        </div>
                        <div class="form-group">
                            <strong>Problem Statment:</strong>
                            {{ $complaint->problem_statment }}
                        </div>
                        <div class="form-group">
                            <strong>Date:</strong>
                            {{ $complaint->date }}
                        </div>
                        <div class="form-group">
                            <strong>Status:</strong>
                            {{ $complaint->status }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

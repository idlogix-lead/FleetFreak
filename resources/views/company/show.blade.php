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
                            <span class="card-title">{{ __('Show') }} Company</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('companies.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $company->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $company->description }}
                        </div>
                        <div class="form-group">
                            <strong>Address1:</strong>
                            {{ $company->address1 }}
                        </div>
                        <div class="form-group">
                            <strong>Address2:</strong>
                            {{ $company->address2 }}
                        </div>
                        <div class="form-group">
                            <strong>Address3:</strong>
                            {{ $company->address3 }}
                        </div>
                        <div class="form-group">
                            <strong>City:</strong>
                            {{ $company->city }}
                        </div>
                        <div class="form-group">
                            <strong>Country:</strong>
                            {{ $company->country }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

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
                            <span class="card-title">{{ __('Show') }} Account Type</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('account-types.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $accountType->name }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $accountType->code }}
                        </div>
                        <div class="form-group">
                            <strong>Parent Id:</strong>
                            {{ $accountType->parent_id }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $accountType->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $accountType->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $accountType->description }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

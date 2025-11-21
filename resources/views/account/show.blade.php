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
                            <span class="card-title">{{ __('Show') }} Account</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('accounts.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $account->name }}
                        </div>
                        <div class="form-group">
                            <strong>Code:</strong>
                            {{ $account->code }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $account->description }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $account->is_active }}
                        </div>
                        <div class="form-group">
                            <strong>Is Summary:</strong>
                            {{ $account->is_summary }}
                        </div>
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $account->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Account Type Id:</strong>
                            {{ $account->account_type_id }}
                        </div>
                        <div class="form-group">
                            <strong>Account Subtype Id:</strong>
                            {{ $account->account_subtype_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

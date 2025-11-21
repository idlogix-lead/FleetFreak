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
                            <span class="card-title">{{ __('Show') }} Client</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('clients.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $client->name }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $client->description }}
                        </div>
                        <div class="form-group">
                            <strong>User Id:</strong>
                            {{ $client->user_id }}
                        </div>
                        <div class="form-group">
                            <strong>Is Active:</strong>
                            {{ $client->is_active }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

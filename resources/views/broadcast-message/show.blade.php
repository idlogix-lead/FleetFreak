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
                            <span class="card-title">{{ __('Show') }} Broadcast Message</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('broadcast-messages.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Company Id:</strong>
                            {{ $broadcastMessage->company_id }}
                        </div>
                        <div class="form-group">
                            <strong>Title:</strong>
                            {{ $broadcastMessage->title }}
                        </div>
                        <div class="form-group">
                            <strong>Message:</strong>
                            {{ $broadcastMessage->message }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $broadcastMessage->description }}
                        </div>
                        <div class="form-group">
                            <strong>Broadcast Type:</strong>
                            {{ $broadcastMessage->broadcast_type }}
                        </div>
                        <div class="form-group">
                            <strong>Broadcast Frequency:</strong>
                            {{ $broadcastMessage->broadcast_frequency }}
                        </div>
                        <div class="form-group">
                            <strong>Expired Date:</strong>
                            {{ $broadcastMessage->expired_date }}
                        </div>
                        <div class="form-group">
                            <strong>Expired:</strong>
                            {{ $broadcastMessage->expired }}
                        </div>
                        <div class="form-group">
                            <strong>Published:</strong>
                            {{ $broadcastMessage->published }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

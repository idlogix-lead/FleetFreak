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
                            <span class="card-title">{{ __('Show') }} User</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('users.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Type:</strong>
                            {{ $user->type }}
                        </div>
                        <div class="form-group">
                            <strong>Name:</strong>
                            {{ $user->name }}
                        </div>
                        <div class="form-group">
                            <strong>Email:</strong>
                            {{ $user->email }}
                        </div>
                        <div class="form-group">
                            <strong>Role Id:</strong>
                            {{ $user->role_id }}
                        </div>
                        <div class="form-group">
                            <strong>Cnic:</strong>
                            {{ $user->CNIC }}
                        </div>
                        <div class="form-group">
                            <strong>Phone No:</strong>
                            {{ $user->phone_no }}
                        </div>
                        <div class="form-group">
                            <strong>Description:</strong>
                            {{ $user->description }}
                        </div>
                        <div class="form-group">
                            <strong>Image:</strong>
                            {{ $user->image }}
                        </div>
                        <div class="form-group">
                            <strong>Theme:</strong>
                            {{ $user->theme }}
                        </div>
                        <div class="form-group">
                            <strong>Sidebar Color:</strong>
                            {{ $user->sidebar_color }}
                        </div>
                        <div class="form-group">
                            <strong>Header Color:</strong>
                            {{ $user->header_color }}
                        </div>
                        <div class="form-group">
                            <strong>Actor Id:</strong>
                            {{ $user->actor_id }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

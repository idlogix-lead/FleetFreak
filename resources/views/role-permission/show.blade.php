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
                            <span class="card-title">{{ __('Show') }} Role Permission</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('role_permissions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Role Module Id:</strong>
                            {{ $rolePermission->role_module_id }}
                        </div>
                        <div class="form-group">
                            <strong>Role Id:</strong>
                            {{ $rolePermission->role_id }}
                        </div>
                        <div class="form-group">
                            <strong>Create:</strong>
                            {{ $rolePermission->create }}
                        </div>
                        <div class="form-group">
                            <strong>Read:</strong>
                            {{ $rolePermission->read }}
                        </div>
                        <div class="form-group">
                            <strong>Update:</strong>
                            {{ $rolePermission->update }}
                        </div>
                        <div class="form-group">
                            <strong>Delete:</strong>
                            {{ $rolePermission->delete }}
                        </div>
                        <div class="form-group">
                            <strong>Recover:</strong>
                            {{ $rolePermission->recover }}
                        </div>
                        <div class="form-group">
                            <strong>Global:</strong>
                            {{ $rolePermission->global }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

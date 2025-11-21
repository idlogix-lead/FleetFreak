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
                            <span class="card-title">{{ __('Show') }} Role Module Structure</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary" href="{{ route('role_permission_types.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <strong>Role Module Id:</strong>
                            {{ $roleModuleStructure->role_module_id }}
                        </div>
                        <div class="form-group">
                            <strong>Action:</strong>
                            {{ $roleModuleStructure->action }}
                        </div>
                        <div class="form-group">
                            <strong>Return:</strong>
                            {{ $roleModuleStructure->return }}
                        </div>
                        <div class="form-group">
                            <strong>Denial Msg:</strong>
                            {{ $roleModuleStructure->denial_msg }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

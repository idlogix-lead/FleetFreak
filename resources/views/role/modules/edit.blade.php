@extends('layouts.app')
@section('wrapper')
    @if(isset($breadcrumbs))
        @include('layouts.partials.breadcrumb',compact('breadcrumbs'))
    @endif
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">
                Role Modules
            </h6>
            <form action="{{route('roles.modules.update', $role_id)}}" method="post">
                @csrf
                @php
                    $edit = true;
                @endphp
                <div class="row">
                    @include("role.modules.form")
                </div>

                <div class="box-footer mt20">
                    @php
                    $model=[
                        'notify_btn' => "Save",
                        'function' => "Save",
                        'body' => 'Please Confirm do you realy want to Save?',
                        'btn-color' => 'primary',
                        'float' => "end mt-2",
                        'id' => "save"
                    ];
                    @endphp
                    @include('partials.modal', ['data'=>$model])
                </div>
            </form>

        </div>
    </div>
@endsection

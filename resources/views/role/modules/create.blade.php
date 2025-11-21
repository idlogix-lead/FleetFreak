@extends('layouts.app')
@section('wrapper')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">
                Role Modules
            </h6>
            <form action="{{route('roles.store')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col md-6">
                        <div class="form-group">
                            <label for="">Role Name</label>
                            <input type="text" placeholder="Manager, Cashier,  e.t.c" name="name"  id="name" class="form-control">
                        </div>
                    </div>
                    
                    @include("role.modules.form")
                </div>

                <div class="box-footer mt20">
                    @php
                        $model=[
                            'notify_btn' => "Next",
                            'function' => "Next",
                            'body' => 'Please Confirm do you realy want to Save & Next?',
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

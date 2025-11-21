<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_module_id">Role Module Id</label>
                <input type="text" placeholder="Role Module Id" name="role_module_id" class="form-control {{($errors->has('role_module_id') ? ' is-invalid' : '')}}" id="role_module_id" value="{{$roleModuleStructure->role_module_id}}">
                {!! $errors->first('role_module_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="action">Action</label>
                <input type="text" placeholder="Action" name="action" class="form-control {{($errors->has('action') ? ' is-invalid' : '')}}" id="action" value="{{$roleModuleStructure->action}}">
                {!! $errors->first('action', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="return">Return</label>
                <input type="text" placeholder="Return" name="return" class="form-control {{($errors->has('return') ? ' is-invalid' : '')}}" id="return" value="{{$roleModuleStructure->return}}">
                {!! $errors->first('return', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="denial_msg">Denial Msg</label>
                <input type="text" placeholder="Denial Msg" name="denial_msg" class="form-control {{($errors->has('denial_msg') ? ' is-invalid' : '')}}" id="denial_msg" value="{{$roleModuleStructure->denial_msg}}">
                {!! $errors->first('denial_msg', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

        </div>
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
</div>
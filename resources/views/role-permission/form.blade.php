<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_module_id">Role Module Id</label>
                <input type="text" placeholder="Role Module Id" name="role_module_id" class="form-control {{($errors->has('role_module_id') ? ' is-invalid' : '')}}" id="role_module_id" value="{{$rolePermission->role_module_id}}">
                {!! $errors->first('role_module_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_id">Role Id</label>
                <input type="text" placeholder="Role Id" name="role_id" class="form-control {{($errors->has('role_id') ? ' is-invalid' : '')}}" id="role_id" value="{{$rolePermission->role_id}}">
                {!! $errors->first('role_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="create">Create</label>
                <input type="text" placeholder="Create" name="create" class="form-control {{($errors->has('create') ? ' is-invalid' : '')}}" id="create" value="{{$rolePermission->create}}">
                {!! $errors->first('create', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="read">Read</label>
                <input type="text" placeholder="Read" name="read" class="form-control {{($errors->has('read') ? ' is-invalid' : '')}}" id="read" value="{{$rolePermission->read}}">
                {!! $errors->first('read', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="update">Update</label>
                <input type="text" placeholder="Update" name="update" class="form-control {{($errors->has('update') ? ' is-invalid' : '')}}" id="update" value="{{$rolePermission->update}}">
                {!! $errors->first('update', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="delete">Delete</label>
                <input type="text" placeholder="Delete" name="delete" class="form-control {{($errors->has('delete') ? ' is-invalid' : '')}}" id="delete" value="{{$rolePermission->delete}}">
                {!! $errors->first('delete', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="recover">Recover</label>
                <input type="text" placeholder="Recover" name="recover" class="form-control {{($errors->has('recover') ? ' is-invalid' : '')}}" id="recover" value="{{$rolePermission->recover}}">
                {!! $errors->first('recover', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="global">Global</label>
                <input type="text" placeholder="Global" name="global" class="form-control {{($errors->has('global') ? ' is-invalid' : '')}}" id="global" value="{{$rolePermission->global}}">
                {!! $errors->first('global', '<div class="invalid-feedback">:message</div>') !!}
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
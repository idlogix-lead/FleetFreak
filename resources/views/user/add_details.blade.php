<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="user_type">User Type</label>
                    <select name="user_type" id="user_type" class="form-control{{ $errors->has('user_type') ? ' is-invalid' : '' }}">     
                        <option value="administrator" {{ $vehicle->transmission_type === 'administrator' ? 'selected' : '' }}>Administrator</option>
                        <option value="driver" {{ $vehicle->transmission_type === 'driver' ? 'selected' : '' }}>Driver</option>
                        <option value="agent" {{ $vehicle->transmission_type === 'agent' ? 'selected' : '' }}>Agent</option>
                    </select>
                    {!! $errors->first('user_type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="is_employee">Is Employee</label>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_employee" name="is_employee" {{ $user->is_employee ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_employee">Employee</label>
                    </div>
                    {!! $errors->first('is_employee', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
      
            <div class="col-md-6">
                <div class="form-group">
                    <label for="role_id">Role Name</label>
                    <select name="role_id" id="role_id" class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}">
                        <option value="">Select Role</option>
                        @foreach($vehicle_class as $v_class)
                            <option value="{{ $v_class->id }}">{{ $v_class->name }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('role_id', '<div class="invalid-feedback">:message</div>') !!}
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
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$user->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" placeholder="Email" name="email" class="form-control {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{$user->email}}">
                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_id">Role Name</label>
                <select name="role_id" id="role_id" class="form-control {{ $errors->has('role_id') ? ' is-invalid' : '' }}">
                    <option value="">Select Role</option>
                    {{-- $roles: Role::assignableBy, the same rule UserController@update validates against. --}}
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ $role->id == $user->role_id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('role_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            <input type="hidden" name="role_id_hidden" id="role_id_hidden">
        </div>
        {{-- @if(auth()->user()->role->actor_id == 4) --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_ids">Manage Vehicles</label>
                <select name="vehicle_ids[]" multiple id="vehicle_ids" class="form-control {{ $errors->has('vehicle_ids') ? ' is-invalid' : '' }}">
                    <option value="" disabled>Select Role</option>
                    @foreach(App\Models\Vehicle::VehicleManagerDropdown($user->id) as $vehicle)
                        <option value="{{ $vehicle->id }}" {{$user?->vehicleManagers?->where('vehicle_id', $vehicle->id)?->first()?'selected':''}} >{{ $vehicle->registration_no }}</option>
                    @endforeach
                </select>
                {!! $errors->first('vehicle_ids', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- @endif --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no1">Phone No 1</label>
                <input type="text" placeholder="phone no 1" name="phone_no1" class="form-control {{($errors->has('phone_no1') ? ' is-invalid' : '')}}" id="phone_no1" value="{{$user->phone_no1}}">
                {!! $errors->first('phone_no1', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no2">Phone No 2</label>
                <input type="text" placeholder="Phone No 2" name="phone_no2" class="form-control {{($errors->has('phone_no2') ? ' is-invalid' : '')}}" id="phone_no2" value="{{$user->phone_no2}}">
                {!! $errors->first('phone_no2', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$user->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control"  name="image" accept="image/*"/>
                <p>{{ $user->image ? basename($user->image) : 'No file chosen' }}</p>
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
<script>
    $('form').on('submit', function() {
    var selectedRoleId = $('#role_id').val(); // Get the selected value from the dropdown
    $('#role_id_hidden').val(selectedRoleId); // Set the hidden field value
    });

    $(document).ready(function(){
        $('#vehicle_ids').select2({
            width:'100%',
            multiple:true,
        });
        $('#role_id').select2({
            width:'100%',
        });
    })
</script>

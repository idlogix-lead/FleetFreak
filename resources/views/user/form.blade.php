<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" placeholder="Type" name="type" class="form-control {{($errors->has('type') ? ' is-invalid' : '')}}" id="type" value="{{$user->type}}">
                {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$user->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" placeholder="Email" name="email" class="form-control {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{old('email',$user->email)}}" autofocus required>
                <div class="invalid-feedback" id="email-error">Please enter a valid email address.</div>
                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role_id">Role Name</label>
                <select name="role_id" id="role_id" class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">Select Role</option>

                    {{-- @foreach(App\Models\Role::dropdown(auth()->user()->client_id) as $role)
                        {{-- <option value="{{ $role->id }}" {{$user->role_id ==$role->id ? 'selected': ''}}>{{ $role->name }}</option> --}}
                    {{-- <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option> --}} 

                    @foreach(App\Models\Role::dropdown( client_id:auth()->user()->client_id) as $role)
                        <option value="{{ $role->id }}" {{$user->role_id ==$role->id ? 'selected': ''}}>{{ $role->name }}</option>

                    @endforeach
                </select>
                {!! $errors->first('role_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- @if($user->role->actor_id == 4) --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_ids">Manage Vehicles</label>
                <select name="vehicle_ids[]" multiple id="vehicle_ids" class="form-control {{ $errors->has('vehicle_ids') ? ' is-invalid' : '' }}">
                    <option value="" disabled>Select Role</option>
                    @foreach(App\Models\Vehicle::VehicleManagerDropdown() as $vehicle)
                        <option value="{{ $vehicle->id }}" >{{ $vehicle->registration_no }}</option>
                    @endforeach
                </select>
                {!! $errors->first('vehicle_ids', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- @endif --}}

        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no1">phone no 1</label>
                <input type="text" placeholder="Phone No 1" name="phone_no1" class="form-control {{($errors->has('phone_no1') ? ' is-invalid' : '')}}" id="phone_no1" value="{{old('phone_no1',$user->phone_no1)}}" autofocus required>
                {!! $errors->first('phone_no1', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no2">Phone No 2</label>
                <input type="text" placeholder="Phone No 2" name="phone_no2" class="form-control {{($errors->has('phone_no2') ? ' is-invalid' : '')}}" id="phone_no2" value="{{old('phone_no2',$user->phone_no2)}}" autofocus >
                {!! $errors->first('phone_no2', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$user->description)}}">
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
         <div class="col-md-6">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control {{($errors->has('password') ? ' is-invalid' : '')}}" placeholder="New Password"
                 pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                 title="Password must be at least 8 characters long and include uppercase, lowercase, and a number."
                autofocus required>
                <div class="invalid-feedback" id="password-error">
                 Password must be at least 8 characters long and include uppercase, lowercase, and a number.
                 </div>
                {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="confirm_password">Confirm password</label>

                  <input type="password" name="password_confirmation" class="form-control {{($errors->has('confirm_password') ? ' is-invalid' : '')}}" placeholder="Confirm Password"
                    id="password_confirmation" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                            title="Password must be at least 8 characters long and include uppercase, lowercase, and a number."  autofocus
                        required>
                        <div class="invalid-feedback" id="confirm-password-error">
                        Password must be at least 8 characters long and include uppercase, lowercase, and a number.
                    </div>

                {!! $errors->first('confirm_password', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{--<div class="col-md-6">
            <div class="form-group">
                <label for="sidebar_color">Sidebar Color</label>
                <input type="text" placeholder="Sidebar Color" name="sidebar_color" class="form-control {{($errors->has('sidebar_color') ? ' is-invalid' : '')}}" id="sidebar_color" value="{{$user->sidebar_color}}">
                {!! $errors->first('sidebar_color', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="header_color">Header Color</label>
                <input type="text" placeholder="Header Color" name="header_color" class="form-control {{($errors->has('header_color') ? ' is-invalid' : '')}}" id="header_color" value="{{$user->header_color}}">
                {!! $errors->first('header_color', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="actor_id">Actor Id</label>
                <input type="text" placeholder="Actor Id" name="actor_id" class="form-control {{($errors->has('actor_id') ? ' is-invalid' : '')}}" id="actor_id" value="{{$user->actor_id}}">
                {!! $errors->first('actor_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

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
@include('layouts.partials.form-email&password-validation');
<script>
    $('#phone_no1').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 22);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
    $('#phone_no2').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        var elevenDigitValue = numericValue.slice(0, 22);
        // Update the input field value
        $(this).val(elevenDigitValue);
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

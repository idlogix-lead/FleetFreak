<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name</label>
                    {{-- <input type="text" placeholder="Name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" value="{{$serviceProvider->user->name}}"> --}}
                    <input type="text" placeholder="Name" name="name" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" value="{{isset($serviceProvider->user->name)? $serviceProvider->user->name : null}}">

                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- @dd($serviceProvider->all()); --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" placeholder="Email" name="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" value="{{isset($serviceProvider->user->email)? $serviceProvider->user->email : null}}">
                    {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" placeholder="Address" name="address" class="form-control {{($errors->has('address') ? ' is-invalid' : '')}}" id="address" value="{{$serviceProvider->address}}">
                    {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- @dd($serviceProvider->address); --}}

            <div class="col-md-6">
                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" placeholder="City" name="city" class="form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{$serviceProvider->city}}">
                    {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Contact</label>
                    <input type="text" placeholder="Contact" name="contact" class="form-control {{($errors->has('contact') ? ' is-invalid' : '')}}" id="contact" value="{{$serviceProvider->contact}}">
                    {!! $errors->first('contact', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Password</label>
                    <input type="text" placeholder="password" name="password" class="form-control {{($errors->has('password') ? ' is-invalid' : '')}}" id="password" value="">
                    {!! $errors->first('password', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
    
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Confirm Password</label>
                    <input type="text" placeholder="confirm password" name="password_confirmation" class="form-control {{($errors->has('password_confirmation') ? ' is-invalid' : '')}}" id="password_confirmation" value="">
                    {!! $errors->first('password_confirmation', '<div class="invalid-feedback">:message</div>') !!}
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
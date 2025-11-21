<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$company->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$company->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address1">Address1</label>
                <input type="text" placeholder="Address1" name="address1" class="form-control {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{$company->address1}}">
                {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address2">Address2</label>
                <input type="text" placeholder="Address2" name="address2" class="form-control {{($errors->has('address2') ? ' is-invalid' : '')}}" id="address2" value="{{$company->address2}}">
                {!! $errors->first('address2', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address3">Address3</label>
                <input type="text" placeholder="Address3" name="address3" class="form-control {{($errors->has('address3') ? ' is-invalid' : '')}}" id="address3" value="{{$company->address3}}">
                {!! $errors->first('address3', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" placeholder="City" name="city" class="form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{$company->city}}">
                {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" placeholder="Country" name="country" class="form-control {{($errors->has('country') ? ' is-invalid' : '')}}" id="country" value="{{$company->country}}">
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
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
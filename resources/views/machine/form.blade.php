<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <label for="customer_id">Customer Id</label>
                <input type="text" placeholder="Customer Id" name="customer_id" class="form-control {{($errors->has('customer_id') ? ' is-invalid' : '')}}" id="customer_id" value="{{$machine->customer_id}}">
                {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
     
{{--         
        <div class="col-md-6">
            <div class="form-group">
                <label for="customer_id">Customer Id</label>
                <select name="customer_id" id="customer_id" class="form-control{{ $errors->has('customer_id') ? ' is-invalid' : '' }}">
                    <option value="" disabled selected>Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="isset({{ $customer->id }})" {{ $machine->customer_id == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('customer_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

        <div class="col-md-6">
            <div class="form-group">
                <label for="serial_number">Serial Number</label>
                <input type="text" placeholder="Serial Number" name="serial_number" class="form-control {{($errors->has('serial_number') ? ' is-invalid' : '')}}" id="serial_number" value="{{$machine->serial_number}}">
                {!! $errors->first('serial_number', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$machine->name}}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" placeholder="Model" name="model" class="form-control {{($errors->has('model') ? ' is-invalid' : '')}}" id="model" value="{{$machine->model}}">
                {!! $errors->first('model', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$machine->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" placeholder="Image" name="image" class="form-control {{($errors->has('image') ? ' is-invalid' : '')}}" id="image" value="{{$machine->image}}">
                {!! $errors->first('image', '<div class="invalid-feedback">:message</div>') !!}
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

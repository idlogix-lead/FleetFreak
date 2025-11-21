<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="branche_name">Branche Name</label>
                <input type="text" placeholder="Branche Name" name="branche_name" class="form-control {{($errors->has('branche_name') ? ' is-invalid' : '')}}" id="branche_name" value="{{$branchDetail->branche_name}}">
                {!! $errors->first('branche_name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" placeholder="Location" name="location" class="form-control {{($errors->has('location') ? ' is-invalid' : '')}}" id="location" value="{{$branchDetail->location}}">
                {!! $errors->first('location', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="branche_manager">Branche Manager</label>
                <input type="text" placeholder="Branche Manager" name="branche_manager" class="form-control {{($errors->has('branche_manager') ? ' is-invalid' : '')}}" id="branche_manager" value="{{$branchDetail->branche_manager}}">
                {!! $errors->first('branche_manager', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="manager_contact">Manager Contact</label>
                <input type="text" placeholder="Manager Contact" name="manager_contact" class="form-control {{($errors->has('manager_contact') ? ' is-invalid' : '')}}" id="manager_contact" value="{{$branchDetail->manager_contact}}">
                {!! $errors->first('manager_contact', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="status">Status</label>
                <input type="text" placeholder="Status" name="status" class="form-control {{($errors->has('status') ? ' is-invalid' : '')}}" id="status" value="{{$branchDetail->status}}">
                {!! $errors->first('status', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="total_sales">Total Sales</label>
                <input type="text" placeholder="Total Sales" name="total_sales" class="form-control {{($errors->has('total_sales') ? ' is-invalid' : '')}}" id="total_sales" value="{{$branchDetail->total_sales}}">
                {!! $errors->first('total_sales', '<div class="invalid-feedback">:message</div>') !!}
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
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$productCosting->client_id}}">
                {!! $errors->first('client_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$productCosting->company_id}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$productCosting->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="product_id">Product Id</label>
                <input type="text" placeholder="Product Id" name="product_id" class="form-control {{($errors->has('product_id') ? ' is-invalid' : '')}}" id="product_id" value="{{$productCosting->product_id}}">
                {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="current_cost">Current Cost</label>
                <input type="text" placeholder="Current Cost" name="current_cost" class="form-control {{($errors->has('current_cost') ? ' is-invalid' : '')}}" id="current_cost" value="{{$productCosting->current_cost}}">
                {!! $errors->first('current_cost', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="current_qty">Current Qty</label>
                <input type="text" placeholder="Current Qty" name="current_qty" class="form-control {{($errors->has('current_qty') ? ' is-invalid' : '')}}" id="current_qty" value="{{$productCosting->current_qty}}">
                {!! $errors->first('current_qty', '<div class="invalid-feedback">:message</div>') !!}
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
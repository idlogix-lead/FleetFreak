<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="product_id">Product Id</label>
                <input type="text" placeholder="Product Id" name="product_id" class="form-control {{($errors->has('product_id') ? ' is-invalid' : '')}}" id="product_id" value="{{$sale->product_id}}">
                {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" placeholder="Quantity" name="quantity" class="form-control {{($errors->has('quantity') ? ' is-invalid' : '')}}" id="quantity" value="{{$sale->quantity}}">
                {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="total_amount">Total Amount</label>
                <input type="text" placeholder="Total Amount" name="total_amount" class="form-control {{($errors->has('total_amount') ? ' is-invalid' : '')}}" id="total_amount" value="{{$sale->total_amount}}">
                {!! $errors->first('total_amount', '<div class="invalid-feedback">:message</div>') !!}
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
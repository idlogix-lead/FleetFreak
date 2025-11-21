<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$mMatchPo->client_id}}">
                {!! $errors->first('client_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{$mMatchPo->company_id}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$mMatchPo->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="product_id">Product Id</label>
                <input type="text" placeholder="Product Id" name="product_id" class="form-control {{($errors->has('product_id') ? ' is-invalid' : '')}}" id="product_id" value="{{$mMatchPo->product_id}}">
                {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="po_line_id">Po Line Id</label>
                <input type="text" placeholder="Po Line Id" name="po_line_id" class="form-control {{($errors->has('po_line_id') ? ' is-invalid' : '')}}" id="po_line_id" value="{{$mMatchPo->po_line_id}}">
                {!! $errors->first('po_line_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="material_inout_line_id">Material Inout Line Id</label>
                <input type="text" placeholder="Material Inout Line Id" name="material_inout_line_id" class="form-control {{($errors->has('material_inout_line_id') ? ' is-invalid' : '')}}" id="material_inout_line_id" value="{{$mMatchPo->material_inout_line_id}}">
                {!! $errors->first('material_inout_line_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="pi_line_id">Pi Line Id</label>
                <input type="text" placeholder="Pi Line Id" name="pi_line_id" class="form-control {{($errors->has('pi_line_id') ? ' is-invalid' : '')}}" id="pi_line_id" value="{{$mMatchPo->pi_line_id}}">
                {!! $errors->first('pi_line_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="document_type_id">Document Type Id</label>
                <input type="text" placeholder="Document Type Id" name="document_type_id" class="form-control {{($errors->has('document_type_id') ? ' is-invalid' : '')}}" id="document_type_id" value="{{$mMatchPo->document_type_id}}">
                {!! $errors->first('document_type_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="text" placeholder="Quantity" name="quantity" class="form-control {{($errors->has('quantity') ? ' is-invalid' : '')}}" id="quantity" value="{{$mMatchPo->quantity}}">
                {!! $errors->first('quantity', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="transaction_date">Transaction Date</label>
                <input type="text" placeholder="Transaction Date" name="transaction_date" class="form-control {{($errors->has('transaction_date') ? ' is-invalid' : '')}}" id="transaction_date" value="{{$mMatchPo->transaction_date}}">
                {!! $errors->first('transaction_date', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="account_date">Account Date</label>
                <input type="text" placeholder="Account Date" name="account_date" class="form-control {{($errors->has('account_date') ? ' is-invalid' : '')}}" id="account_date" value="{{$mMatchPo->account_date}}">
                {!! $errors->first('account_date', '<div class="invalid-feedback">:message</div>') !!}
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
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$productType->client_id}}">
                {!! $errors->first('client_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_id">Company</label>
                <input readonly type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$productType->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('',$productType->description)}}" autofocus>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $productType->is_active) ? 'checked' : '' }} checked>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default" {{ old('is_default', $productType->is_default) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" placeholder="Code" name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{$productType->code}}" autofocus>
                {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
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
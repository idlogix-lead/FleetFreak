<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
      
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="company_id">Company  </label>     
                <input type="text" readonly name="company_id" class="form-control form-control-sm small-input {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control form-control-sm {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{ old('name', $tax->name) }}">
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="rate">Rate</label>
                <input type="number" placeholder="Rate" name="rate" class="form-control form-control-sm {{($errors->has('rate') ? ' is-invalid' : '')}}" id="rate" value="{{old('rate',$tax->rate) }}">
                {!! $errors->first('rate', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control form-control-sm {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$tax->description) }}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="valid_from">Valid From</label>
                <input type="date" placeholder="Valid From" name="valid_from" class="form-control form-control-sm {{($errors->has('valid_from') ? ' is-invalid' : '')}}" id="valid_from" value="{{old('valid_from',$tax->valid_from) }}">
                {!! $errors->first('valid_from', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="is_default">Is Default</label>
                <input type="text" placeholder="Is Default" name="is_default" class="form-control form-control-sm {{($errors->has('is_default') ? ' is-invalid' : '')}}" id="is_default" value="{{$tax->is_default}}">
                {!! $errors->first('is_default', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">                
                <!-- Toggle Switch -->
                <label class="switch">
                    <input type="checkbox" value="1" name="is_active" id="is_active"
                        {{ (isset($tax) && $tax->is_active === 0) ? '' : 'checked' }}>
                    <span class="slider round"></span>
                </label>
                <!-- Label for the toggle -->
                <label class="form-check-label" for="is_active" style="margin-left: 10px;">Is Active</label>
            </div>
        </div>
        
        {{-- <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label for="type">Type</label>
                <input type="text" placeholder="Type" name="type" class="form-control form-control-sm {{($errors->has('type') ? ' is-invalid' : '')}}" id="type" value="{{$tax->type}}">
                {!! $errors->first('type', '<div class="invalid-feedback">:message</div>') !!}
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
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$productCategory->client_id}}">
                {!! $errors->first('client_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input readonly type="text" placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$productCategory->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="material_policy">Material Policy</label>
                <select name="material_policy" class="form-control red-border-select2 {{ $errors->has('material_policy') ? ' is-invalid' : '' }}" id="material_policy" autofocus required>
                    <option value="lifo" {{ $productCategory->material_policy == 'lifo' ? 'selected' : '' }}>LIFO</option>
                    <option value="fifo" {{ $productCategory->material_policy == 'fifo' ? 'selected' : '' }}>FIFO</option>
                </select>
                {!! $errors->first('material_policy', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>   
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($productCategory) && $productCategory->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>  
        @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\ProductCategory::where('company_id',auth()->user()->active_company())->where('default', 1)->exists();
        @endphp

        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="default" id="default_hidden">

                <input type="checkbox" value="1" name="default" class="form-check-input" id="default"
                    {{ old('default', $productCategory->default) ? 'checked' : '' }}
                    @if($isActiveDisabled && $productCategory->default== 0) disabled @endif>
                <label class="form-check-label" for="default">Is Default</label>
            </div>
        </div>   
        {{-- <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="default" id="default_hidden">

                <input type="checkbox" value="1" name="default" class="form-check-input" id="default" {{ old('default', $productCategory->default) ? 'checked' : '' }}>
                <label class="form-check-label" for="default">Is Default</label>
            </div>
        </div> --}}
        <div class="col-md-4 my-2">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" rows="4">{{ old('description', $productCategory->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
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
<script>
    $(document).ready(function () {
        $('#material_policy').select2();
        
    });
</script>
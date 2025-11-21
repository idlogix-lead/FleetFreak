<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$productPrice->client_id}}">
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
        <div class="col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="product_id">Product</label>
                <select name="product_id" id="product_id"
                    class="form-control red-border-select2 {{ $errors->has('product_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Product::dropdown() as $product)
                        <option value="{{ $product->id }}"
                            {{ $productPrice->product_id== $product->id ? 'selected' : '' }}>
                            {{ Str::title($product->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="price_list_version_id">Version</label>
                <select name="price_list_version_id" id="price_list_version_id"
                    class="form-control red-border-select2 {{ $errors->has('price_list_version_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\PriceListVersion::versions() as $version)
                        <option value="{{ $version->id }}"
                            {{ $productPrice->price_list_version_id== $version->id ? 'selected' : '' }}>
                            {{ Str::title($version->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('price_list_version_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
       
        <div class="col-md-4">
            <div class="form-group">
                <label for="list_price">List Price</label>
                <input type="number" placeholder="List Price" name="list_price" class="form-control {{($errors->has('list_price') ? ' is-invalid' : '')}}" id="list_price" value="{{old('list_price',$productPrice->list_price)}}">
                {!! $errors->first('list_price', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="standard_price">Standard Price</label>
                <input type="number" placeholder="Standard Price" name="standard_price" class="form-control {{($errors->has('standard_price') ? ' is-invalid' : '')}}" id="standard_price" value="{{old('standard_price',$productPrice->standard_price)}}">
                {!! $errors->first('standard_price', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="limit_price">Limit Price</label>
                <input type="number" placeholder="Limit Price" name="limit_price" class="form-control {{($errors->has('limit_price') ? ' is-invalid' : '')}}" id="limit_price" value="{{old('limit_price',$productPrice->limit_price)}}">
                {!! $errors->first('limit_price', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-2">
            <div class="form-group">
                <label for="description">Description</label>
                <textarea name="description" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" rows="4">{{ old('description', $productPrice->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($productPrice) && $productPrice->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>
        @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\ProductPrice::where('company_id',auth()->user()->active_company())->where('is_active',1)->where('is_default', 1)->exists();
        @endphp

        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default"
                    {{ old('is_default', $productPrice->is_default) ? 'checked' : '' }}
                    @if($isActiveDisabled && $productPrice->is_default== 0) disabled @endif>
                <label class="form-check-label" for="is_default">Is Default</label>
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
        $('#product_id').select2();
        $('#price_list_version_id').select2();
    });
</script>
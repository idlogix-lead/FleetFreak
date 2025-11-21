{{-- @php
    $disabled = '';
    if ($stockStorage->document_status == 'completed') {
        $disabled = 'disabled';
    }
@endphp --}}
<style>
    

   
        .rm-row svg:hover{
            color:  #fff;
            fill:  red;
            cursor: pointer;
        }
        .state-wrapper{
            font-size: 14px;
            color:black;
        }

        .error {
            border: 1px solid red !important; /* Red border for error state */
        }
        /* .small-input {
            max-width: 180px; 
        } */
</style>

<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="company_id">Company  </label>     
                <input type="text" readonly name="company_id" class="form-control small-input form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="locator_id"> Locator </label>
                <select disabled  name="locator_id" id="locator_id"
                    class="form-control red-border-select2 {{ $errors->has('locator_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Locator::locators() as $locator)
                        <option value="{{ $locator->id }}"
                            {{ old('locator_id', $stockStorage->locator_id)== $locator->id ? 'selected' : '' }}>
                            {{ Str::title($locator->locator_type) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('locator_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="product_id"> Product </label>
                <select disabled  name="product_id" id="product_id"
                    class="form-control red-border-select2 {{ $errors->has('product_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @foreach (App\Models\Product::dropdown() as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $stockStorage->product_id)== $product->id ? 'selected' : '' }}>
                            {{ Str::title($product->name) }}</option>
                    @endforeach
                </select>
                {{-- <input type="text" placeholder="Business Partner Id" name="business_partner_id" class="form-control {{($errors->has('business_partner_id') ? ' is-invalid' : '')}}" id="business_partner_id" value="{{$maintenance->business_partner_id}}"> --}}
                {!! $errors->first('product_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-sm-6 col-md-3 col-lg-3">
            <div class="form-group">
                <label class="text-sm" for="on_hand_qty">On Hand Qty  </label>     
                <input readonly type="number" name="on_hand_qty" class="form-control small-input form-control-sm {{($errors->has('on_hand_qty') ? ' is-invalid' : '')}}" id="on_hand_qty" value="{{ old('on_hand_qty', $stockStorage->on_hand_qty) }}">
                {!! $errors->first('on_hand_qty', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
       
        <div class="col-sm-12 col-md-12 col-lg-12">
            <div class="form-group">
                <label class="text-sm" for="description">Description</label>
                <textarea readonly name="description" 
                    class="form-control form-control-sm {{ $errors->has('description') ? ' is-invalid' : '' }}" 
                    id="description" rows="3">{{ old('description', $stockStorage->description) }}</textarea>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">
        
                <!-- Checkbox input -->
                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active"
                    {{ (isset($stockStorage) && $stockStorage->is_active === 0) ? '' : 'checked' }}>
                
                <!-- Checkbox label -->
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div> --}}
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                {{-- <input type="hidden" value="0" name="is_active" id="is_active_hidden"> --}}
                
                <!-- Toggle Switch -->
                <label class="switch">
                    <input disabled type="checkbox" value="1" name="is_active" id="is_active"
                        {{ (isset($stockStorage) && $stockStorage->is_active === 0) ? '' : 'checked' }}>
                    <span class="slider round"></span>
                </label>
                
                <!-- Label for the toggle -->
                <label class="form-check-label" for="is_active" style="margin-left: 10px;">Is Active</label>
            </div>
        </div>
        @php
            // Check if there's any activity that is marked as active
            $isActiveDisabled = \App\Models\StockStorage::where('company_id',auth()->user()->active_company())->where('is_active',1)->where('is_default', 1)->exists();
        @endphp
    
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <!-- Hidden input to ensure a value is always sent (even when unchecked) -->
                {{-- <input type="hidden" value="0" name="is_default" id="is_default_hidden"> --}}
                
                <!-- Toggle Switch -->
                <label class="switch">
                    <input disabled type="checkbox" value="1" name="is_default" id="is_default"
                        {{ old('is_default', $stockStorage->is_default) ? 'checked' : '' }}
                        @if($isActiveDisabled && $stockStorage->is_default == 0) disabled @endif>
                    <span class="slider round"></span>
                </label>
                
                <!-- Label for the toggle -->
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        $('#product_id').select2();
        $('#locator_id').select2();
    });
</script>

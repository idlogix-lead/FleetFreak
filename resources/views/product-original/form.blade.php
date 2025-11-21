<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="company_id">Company  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$product->name??'')}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sku_no">Sku No</label>
                <input type="text" placeholder="Sku No" name="sku_no" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('sku',$product->sku)}}" autofocus required>
                {!! $errors->first('sku_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$product->description)}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="product_category_id">Product Category</label>
                <select name="product_category_id" class="form-control red-border-select2 {{ $errors->has('product_category_id') ? ' is-invalid' : '' }}" id="product_category_id" required>
                    <option value="">Select Category</option>
                    @foreach (\App\Models\ProductCategory::allCategory() as $category)
                        <option value="{{$category->id}}" {{$category->id == $product->product_category_id ? 'selected':''}}>{{$category->name}}</option>    
                    @endforeach
                </select>
                {!! $errors->first('product_category_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>      
        <div class="col-md-6">
            <div class="form-group">
                <label for="product_sub_category_id">Product Sub Category</label>
                <select name="product_sub_category_id" class="form-control red-border-select2 {{ $errors->has('product_sub_category_id') ? ' is-invalid' : '' }}" id="product_sub_category_id" required>
                    <option value="">Select SubCategory</option>
                    @foreach (\App\Models\ProductSubCategory::allSubCategory() as $category)
                        <option value="{{$category->id}}" {{$category->id == $product->product_sub_category_id ? 'selected':''}}>{{$category->name}}</option>    
                    @endforeach
                </select>
                {!! $errors->first('product_sub_category_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>      
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="text" placeholder="Product Image" name="product_image" class="form-control {{($errors->has('product_image') ? ' is-invalid' : '')}}" id="product_image" value="{{$product->product_image}}">
                {!! $errors->first('product_image', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="cost_price">Cost Price</label>
                <input type="text" placeholder="Cost Price" name="cost_price" class="form-control {{($errors->has('cost_price') ? ' is-invalid' : '')}}" id="cost_price" value="{{old('cost_price',$product->cost_price)}}" autofocus required>
                {!! $errors->first('cost_price', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="sale_price">Sale Price</label>
                <input type="text" placeholder="Sale Price" name="sale_price" class="form-control {{($errors->has('sale_price') ? ' is-invalid' : '')}}" id="sale_price" value="{{old('sale_price',$product->sale_price)}}" autofocus required>
                {!! $errors->first('sale_price', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="unit">Unit</label>
                <select style="min-width:100px;"  name="unit" id="unit" class="form-control red-border-select2" placeholder="" required>
                    <option value="" >-- Select --</option>
                    @foreach (App\Models\UnitMeasure::unit_type() as $unit)

                        <option value='{{ $unit->id }}'
                            {{ $unit->id == $product->unit_measure_id ? 'selected' : ''}}>
                            {{ $unit->name }}

                        </option>
                    @endforeach

                </select>
                {{-- <input type="text" placeholder="Unit" name="unit" class="form-control {{($errors->has('unit') ? ' is-invalid' : '')}}" id="unit" value="{{$product->unit}}"> --}}
                {!! $errors->first('unit', '<div class="invalid-feedback">:message</div>') !!}
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
        $('#product_sub_category_id').select2();
        $('#product_category_id').select2();
        $('#unit').select2();
    });
    $('#name').on('input', function() {
        var inputValue = $(this).val();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
    $('#sale_price,#cost_price').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
</script>

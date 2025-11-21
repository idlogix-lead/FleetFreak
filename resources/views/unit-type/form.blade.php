<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_id">Company  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" readonly name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$unitType->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$unitType->description)}}" autofocus>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="symbol">Description</label>
                <input type="text" placeholder="symbol" name="description" class="form-control {{($errors->has('symbol') ? ' is-invalid' : '')}}" id="symbol" value="{{old('symbol',$unitType->symbol)}}" autofocus>
                {!! $errors->first('symbol', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="symbol">Symbol</label>
                <select name="symbol" class="form-control {{ $errors->has('symbol') ? ' is-invalid' : '' }}" id="symbol">
                    <option value="">Select a symbol</option>
                    @foreach(\App\Models\UnitMeasure::measurementSymbols() as $symbol)
                        <option value="{{ $symbol }}" {{ $unitType->symbol == $symbol ? 'selected' : '' }}>
                            {{ $symbol }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('symbol', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="uncefact_code">UN/CEFACT Code</label>
                <input type="text" placeholder="uncefact_code" name="uncefact_code" class="form-control {{($errors->has('uncefact_code') ? ' is-invalid' : '')}}" id="uncefact_code" value="{{old('uncefact_code',$unitType->uncefact_code)}}" autofocus>
                {!! $errors->first('uncefact_code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="uom_code">UOM Code</label>
                <input type="text" placeholder="uom_code" name="uom_code" class="form-control {{($errors->has('uom_code') ? ' is-invalid' : '')}}" id="uom_code" value="{{old('uom_code',$unitType->uom_code)}}">
                {!! $errors->first('uom_code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="uom_type">UOM Type</label>
                <input type="text" placeholder="uom_type" name="uom_type" class="form-control {{($errors->has('uom_type') ? ' is-invalid' : '')}}" id="uom_type" value="{{old('uom_type',$unitType->uom_type)}}" autofocus>
                {!! $errors->first('uom_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="std_precision">Standard Precision</label>
                <input type="text" placeholder="std_precision" name="std_precision" class="form-control {{($errors->has('std_precision') ? ' is-invalid' : '')}}" id="std_precision" value="{{old('std_precision',$unitType->std_precision)}}" autofocus>
                {!! $errors->first('std_precision', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cost_precision">Cost Precision</label>
                <input type="text" placeholder="cost_precision" name="cost_precision" class="form-control {{($errors->has('cost_precision') ? ' is-invalid' : '')}}" id="cost_precision" value="{{old('cost_precision',$unitType->cost_precision)}}" autofocus>
                {!! $errors->first('cost_precision', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default" {{ old('is_default', $unitType->is_default) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $unitType->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
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
    $(document).ready(function() {
        $('#symbol').select2();
    })

    $('#name').on('input', function() {
        var inputValue = $(this).val();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
</script>

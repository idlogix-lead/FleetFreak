<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$wareHouse->client_id}}">
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
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$wareHouse->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{old('description',$wareHouse->description)}}" autofocus>
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" placeholder="Code" name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{old('code',$wareHouse->code)}}" autofocus>
                {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" placeholder="Address" name="address" class="form-control {{($errors->has('address') ? ' is-invalid' : '')}}" id="address" value="{{old('address',$wareHouse->address)}}" autofocus>
                {!! $errors->first('address', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="source_warehouse_id">Source WareHouse</label>
                <select name="source_warehouse_id" class="form-control {{ $errors->has('source_warehouse_id') ? ' is-invalid' : '' }}" id="source_warehouse_id" autofocus required>
                    <option value="">Select a Source Warehouse</option>
                    @foreach(\App\Models\WareHouse::warehouses() as $source_warehouse)
                        <option value="{{ $source_warehouse->id }}" {{ $wareHouse->source_warehouse_id == $source_warehouse->id ? 'selected' : '' }}>
                            {{ $source_warehouse->name }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('source_warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="locator_id">Locators</label>
                <select name="locator_id" class="form-control {{ $errors->has('locator_id') ? ' is-invalid' : '' }}" id="locator_id" autofocus required>
                    <option value="">Select a Locator</option>
                    @foreach(\App\Models\Locator::all_locators() as $locators)
                        <option value="{{ $locators->id }}" {{ $wareHouse->locator_id == $locators->id ? 'selected' : '' }}>
                            {{ $locators->locator_type }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('locator_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>

        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $wareHouse->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="in_transit" id="in_transit_hidden">

                <input type="checkbox" value="1" name="in_transit" class="form-check-input" id="in_transit" {{ old('in_transit', $wareHouse->in_transit) ? 'checked' : '' }}>
                <label class="form-check-label" for="in_transit">In Transit</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_disallow_negative_inv" id="is_disallow_negative_inv_hidden">

                <input type="checkbox" value="1" name="is_disallow_negative_inv" class="form-check-input" id="is_disallow_negative_inv" {{ old('is_disallow_negative_inv', $wareHouse->is_disallow_negative_inv) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_disallow_negative_inv">Is Disallow Negative Inv</label>
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
        $('#locator_id').select2();
        $('#source_warehouse_id').select2();
    })

</script>
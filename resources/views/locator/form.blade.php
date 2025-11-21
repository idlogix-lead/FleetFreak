<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
        {{-- <div class="col-md-4">
            <div class="form-group">
                <label for="client_id">Client Id</label>
                <input type="text" placeholder="Client Id" name="client_id" class="form-control {{($errors->has('client_id') ? ' is-invalid' : '')}}" id="client_id" value="{{$locator->client_id}}">
                {!! $errors->first('client_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="company_id">Company Id</label>
                <input type="text" readonly placeholder="Company Id" name="company_id" class="form-control {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}">
                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="warehouse_id">WareHouse</label>
                <select name="warehouse_id" class="form-control {{ $errors->has('warehouse_id') ? ' is-invalid' : '' }}" id="warehouse_id">
                    <option value="">Select a Source Warehouse</option>
                    @foreach(\App\Models\WareHouse::warehouses() as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ $locator->warehouse_id == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('warehouse_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="code">Code</label>
                <input type="text" placeholder="Code" name="code" class="form-control {{($errors->has('code') ? ' is-invalid' : '')}}" id="code" value="{{$locator->code}}">
                {!! $errors->first('code', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="locator_type">Locator Type</label>
                <input type="text" placeholder="Locator Type" name="locator_type" class="form-control {{($errors->has('locator_type') ? ' is-invalid' : '')}}" id="locator_type" value="{{$locator->locator_type}}">
                {!! $errors->first('locator_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="relative_priority">Relative Priority</label>
                <input type="text" placeholder="Relative Priority" name="relative_priority" class="form-control {{($errors->has('relative_priority') ? ' is-invalid' : '')}}" id="relative_priority" value="{{$locator->relative_priority}}">
                {!! $errors->first('relative_priority', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="aisle">Aisle (X)</label>
                <input type="number" placeholder="Aisle" name="aisle" class="form-control {{($errors->has('aisle') ? ' is-invalid' : '')}}" id="aisle" value="{{$locator->aisle}}">
                {!! $errors->first('aisle', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="bin">Bin (Y)</label>
                <input type="number" placeholder="Bin" name="bin" class="form-control {{($errors->has('bin') ? ' is-invalid' : '')}}" id="bin" value="{{$locator->bin}}">
                {!! $errors->first('bin', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="level">Level (Z)</label>
                <input type="number" placeholder="Level" name="level" class="form-control {{($errors->has('level') ? ' is-invalid' : '')}}" id="level" value="{{$locator->level}}">
                {!! $errors->first('level', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_active" id="is_active_hidden">

                <input type="checkbox" value="1" name="is_active" class="form-check-input" id="is_active" {{ old('is_active', $locator->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Is Active</label>
            </div>
        </div>
        <div class="col-md-4 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default" {{ old('is_default', $locator->is_default) ? 'checked' : '' }}>
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
        $('#warehouse_id').select2();
    });
</script>
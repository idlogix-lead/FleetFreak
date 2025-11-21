<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
<style>
    .toggle-fields {
     display: none;
     /* Hide fields by default */
 }
</style>
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
                    <label for="vehicle_identification_number">Vehicle Identification Number</label>
                    <input type="text" placeholder="Vehicle Identification Number" name="vehicle_identification_number"
                        class="form-control {{ $errors->has('vehicle_identification_number') ? ' is-invalid' : '' }}"
                        id="vehicle_identification_number"
                        value="{{ old('vehicle_identification_number', $vehicle->vehicle_identification_number) }}"
                        autofocus required>
                    {!! $errors->first('vehicle_identification_number', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="chassis_no">Vehicle Chassis Number</label>
                    <input type="text" placeholder="Vehicle Chassis Number" name="chassis_no"
                        class="form-control {{ $errors->has('chassis_no') ? ' is-invalid' : '' }}"
                        id="chassis_no"
                        value="{{ old('chassis_no', $vehicle->chassis_no) }}"
                        >
                    {!! $errors->first('chassis_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
              <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="fitness_certificate_no">Fitness Certificate Number</label>
                    <input type="text" placeholder="Fitness Certificate Number" name="fitness_certificate_no"
                        class="form-control {{ $errors->has('fitness_certificate_no') ? ' is-invalid' : '' }}"
                        id="fitness_certificate_no"
                        value="{{ old('fitness_certificate_no', $vehicle->fitness_certificate_no) }}"
                        >
                    {!! $errors->first('fitness_certificate_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="car_company_id">Company   <span style="color: red;">*</label>
                <select name="car_company_id" id="car_company_id" class="form-control{{ $errors->has('car_company_id') ? ' is-invalid' : '' }}" autofocus>
                    <option value="">Select Company</option>
                    @foreach ($company as $comp)
                        <option value="{{ $comp->id }}" {{$vehicle->car_company_id==$comp->id ? 'selected' : ''}}>{{ $comp->name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('car_company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="driver_id">Driver</label>
                    <select name="driver_id" id="driver_id"
                        class="form-control{{ $errors->has('driver_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select --</option>

                        @foreach (App\Models\Driver::DriverDropdown() as $driver)
                            <option value="{{ $driver->id }}"
                                {{ $vehicle->driver_id == $driver->id ? 'selected' : '' }}>
                                {{ Str::title($driver->name) }}
                            </option>
                        @endforeach
                    </select>

                    {!! $errors->first('driver_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_manager_id">Vehicle Manager</label>
                    <select name="vehicle_manager_id" id="vehicle_manager_id" class="form-control {{ $errors->has('vehicle_manager_id') ? ' is-invalid' : '' }}" autofocus required>
                        <option value="">Select User</option>
                        @foreach(App\Models\User::vehicleManagerDropdown() as $user)
                            <option value="{{ $user->id }}" {{$user?->vehicleManagers?->where('vehicle_id', $vehicle->id)?->first()?'selected':''}} >{{ $user->name }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('vehicle_manager_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="model">Model <span style="color: red;">*</label>
                    <select name="model" id="model" 
                        class="form-control{{ $errors->has('model') ? ' is-invalid' : '' }}" autofocus required>
                        <option value="">Select Model</option>
                        @php
                            $user = auth()->user();
                            $company = $user->companies()->first(); // Get the first associated company
                            // Fetch vehicle models associated with the user's company
                            $vehicleModels = \App\Models\VehicleModel::when($company, function ($query) use ($company) {
                                return $query->whereHas('vehicleClass', function ($q) use ($company) {
                                    $q->where('company_id', $company->id);
                            });
                            })->get(); // Get models
                        @endphp
                        @foreach ($vehicleModels as $model)
                            <option value="{{ $model->id }}"
                                {{ $vehicle->vehicle_model_id == $model->id ? 'selected' : '' }}>{{ $model->name }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('model', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="model">Model</label>
                <input type="text" placeholder="Model" name="model" class="form-control {{($errors->has('model') ? ' is-invalid' : '')}}" id="model" value="{{old('model',$vehicle->model)}}" autofocus required>
                {!! $errors->first('model', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="year">Year</label>
                    <input type="text" placeholder="Year" name="year"
                        class="form-control {{ $errors->has('year') ? ' is-invalid' : '' }}" id="year"
                        value="{{ old('year', $vehicle->year) }}">
                    {!! $errors->first('year', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="color">Color</label>
                    <input type="text" placeholder="Color" name="color"
                        class="form-control {{ $errors->has('color') ? ' is-invalid' : '' }}" id="color"
                        value="{{ old('color', $vehicle->color) }}">
                    {!! $errors->first('color', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_no">Vehicle Number</label>
                    <input type="text" placeholder="Vehicle Number" name="vehicle_no" autofocus required
                        class="form-control {{ $errors->has('vehicle_no') ? ' is-invalid' : '' }}" id="vehicle_no"
                        value="{{ old('vehicle_no', $vehicle->vehicle_no) }}">
                    {!! $errors->first('vehicle_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="registration_no">Registration No</label>
                    <input type="text" placeholder="Registration No" name="registration_no"
                        class="form-control {{ $errors->has('registration_no') ? ' is-invalid' : '' }}"
                        id="registration_no" value="{{ old('registration_no', $vehicle->registration_no) }}"
                        >
                    {!! $errors->first('registration_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="ownership">Ownership</label>
                    <select name="ownership" id="ownership"
                        class="form-control{{ $errors->has('ownership') ? ' is-invalid' : '' }} ">
                        <option value="owned" {{ $vehicle->transmission_type === 'owned' ? 'selected' : '' }}>Owned
                        </option>
                        <option value="leased" {{ $vehicle->transmission_type === 'leased' ? 'selected' : '' }}>Leased
                        </option>
                    </select>
                    {!! $errors->first('ownership', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="fuel_type">Fuel Type</label>
                    <select name="fuel_type" id="fuel_type"
                        class="form-control{{ $errors->has('fuel_type') ? ' is-invalid' : '' }}">
                        <option value="petrol" {{ $vehicle->fuel_type === 'petrol' ? 'selected' : '' }}>Petrol</option>
                        <option value="diesel" {{ $vehicle->fuel_type === 'diesel' ? 'selected' : '' }}>Diesel</option>
                        <option value="electric" {{ $vehicle->fuel_type === 'electric' ? 'selected' : '' }}>Electric
                        </option>
                        <option value="cng" {{ $vehicle->fuel_type === 'cng' ? 'selected' : '' }}>CNG</option>
                    </select>
                    {!! $errors->first('fuel_type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="engine_type">Engine Type</label>
                    <input type="text" placeholder="Engine Type" name="engine_type"
                        class="form-control {{ $errors->has('engine_type') ? ' is-invalid' : '' }}" id="engine_type"
                        value="{{ old('engine_type', $vehicle->engine_type) }}">
                    {!! $errors->first('engine_type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="transmission_type">Transmission Type</label>
                    <select name="transmission_type" id="transmission_type"
                        class="form-control{{ $errors->has('transmission_type') ? ' is-invalid' : '' }}">
                        <option value="automatic" {{ $vehicle->transmission_type === 'automatic' ? 'selected' : '' }}>
                            automatic</option>
                        <option value="manual" {{ $vehicle->transmission_type === 'manual' ? 'selected' : '' }}>manual
                        </option>
                    </select>
                    {!! $errors->first('transmission_type', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_class_id">Vehicle Class <span style="color: red;">*</label>
                <select name="vehicle_class_id" id="vehicle_class_id" class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}">
                    <option value="">Select Vehicle Class</option>
                    @foreach ($vehicle_class as $v_class)
                        <option value="{{ $v_class->id }}" {{$vehicle->vehicle_class_id == $v_class->id ? 'selected' : ''}}>{{ $v_class->name }}</option>


                    @endforeach
                </select>
                {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="weight">Weight</label>
                    <input type="text" placeholder="Weight" name="weight"
                        class="form-control {{ $errors->has('weight') ? ' is-invalid' : '' }}" id="weight"
                        value="{{ old('weight', $vehicle->weight) }}">
                    {!! $errors->first('weight', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="milage">Milage</label>
                    <input type="text" placeholder="Milage" name="milage"
                        class="form-control {{ $errors->has('milage') ? ' is-invalid' : '' }}" id="milage"
                        value="{{ old('milage', $vehicle->milage) }}">
                    {!! $errors->first('milage', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="car_condition">Car Condition</label>
                    <select name="car_condition" id="car_condition"
                        class="form-control{{ $errors->has('car_condition') ? ' is-invalid' : '' }}">
                        <option value="new" {{ $vehicle->car_condition === 'new' ? 'selected' : '' }}>New</option>
                        <option value="used" {{ $vehicle->car_condition === 'used' ? 'selected' : '' }}>Used
                        </option>
                        <option value="excellent" {{ $vehicle->car_condition === 'excellent' ? 'selected' : '' }}>
                            Excellent</option>
                        <option value="fair" {{ $vehicle->car_condition === 'fair' ? 'selected' : '' }}>Fair
                        </option>
                    </select>
                    {!! $errors->first('car_condition', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="maintenance_interval_days">Maintenance Interval Days</label>
                    <input type="text" placeholder="Maintenance Interval days" name="maintenance_interval_days"
                        class="form-control {{ $errors->has('maintenance_interval_days') ? ' is-invalid' : '' }}"
                        id="maintenance_interval_days"
                        value="{{ old('maintenance_interval_days', $vehicle->maintenance_interval_days) }}">
                    {!! $errors->first('maintenance_interval_days', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="maintenance_oilchange_interval_km">Maintenance_oilchange_interval_km</label>
                    <input type="text"  placeholder="Maintenance_oilchange_interval_km"
                        name="maintenance_oilchange_interval_km"
                        class="form-control {{ $errors->has('maintenance_oilchange_interval_km') ? ' is-invalid' : '' }}"
                        id="maintenance_oilchange_interval_km"
                        value="{{ old('maintenance_oilchange_interval_km', $vehicle->maintenance_oilchange_interval_km) }}"
                        >
                    {!! $errors->first('maintenance_oilchange_interval_km', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <h5 class="mt-2 toggle-fields">Vehicle Route</h5>
            <hr class="toggle-fields">
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="route_permits_no">Route Permits Number</label>
                    <input type="text" placeholder="Route Permits Number" name="route_permits_no"
                        class="form-control {{ $errors->has('route_permits_no') ? ' is-invalid' : '' }}"
                        id="route_permits_no"
                        value="{{ old('route_permits_no', $vehicle->route_permits_no) }}"
                        >
                    {!! $errors->first('route_permits_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="route_permits_expiry_date">Route Permits Expiry Date</label>
                    <input type="date" placeholder="Route Permits Expiry Date" name="route_permits_expiry_date"
                        class="form-control {{ $errors->has('route_permits_expiry_date') ? ' is-invalid' : '' }}"
                        id="route_permits_expiry_date"
                        value="{{ old('route_permits_expiry_date', $vehicle->route_permits_expiry_date) }}"
                        >
                    {!! $errors->first('route_permits_expiry_date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <h5 class="mt-2 toggle-fields">Vehicle Insurance</h5>
            <hr class="toggle-fields">
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="insurance_no">Insurance No</label>
                    <input type="text" placeholder="Insurance No" name="insurance_no"
                        class="form-control {{ $errors->has('insurance_no') ? ' is-invalid' : '' }}"
                        id="insurance_no"
                        value="{{ old('insurance_no', $vehicle->insurance_no) }}"
                        >
                    {!! $errors->first('insurance_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="insurance_provider">Insurance Provider Name</label>
                    <input type="text" placeholder="Insurance Provider Name" name="insurance_provider"
                        class="form-control {{ $errors->has('insurance_provider') ? ' is-invalid' : '' }}"
                        id="insurance_provider"
                        value="{{ old('insurance_provider', $vehicle->insurance_provider) }}"
                        >
                    {!! $errors->first('insurance_provider', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="insurance_provider_contact_no">Insurance Provider Contact Number</label>
                    <div class="input-group">
                        <!-- Country Code Dropdown -->
                        <select class="form-select" id="prefix_insurance_provider_contact_no" name="prefix_insurance_provider_contact_no">
                            @foreach (App\Models\CountryCode::phone_codes() as $country)
                                <option value="{{ $country->phonecode }}"
                                    {{ old('prefix_insurance_provider_contact_no', $vehicle->prefix_insurance_provider_contact_no ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                    data-iso="{{ strtolower($country->iso) }}">
                                    {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_whatsapp == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                    +{{ $country->iso . '(' . $country->phonecode . ')' }}
                            @endforeach
                        </select>
                        <input type="text" placeholder="Insurance Provider Contact Number" name="insurance_provider_contact_no"
                            class="form-control {{ $errors->has('insurance_provider_contact_no') ? ' is-invalid' : '' }}"
                            id="insurance_provider_contact_no" value="{{ old('insurance_provider_contact_no', $vehicle->insurance_provider_contact_no ?? '') }}" 
                            >
                        {!! $errors->first('insurance_provider_contact_no', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
             {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="insurance_provider_contact_no">Insurance Provider Contact Number</label>
                    <input type="text" placeholder="Insurance Provider Contact Number" name="insurance_provider_contact_no"
                        class="form-control {{ $errors->has('insurance_provider_contact_no') ? ' is-invalid' : '' }}"
                        id="insurance_provider_contact_no"
                        value="{{ old('insurance_provider_contact_no', $vehicle->insurance_provider_contact_no) }}"
                        autofocus required>
                    {!! $errors->first('insurance_provider_contact_no', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="insurance_start_date">Insurance Start Date</label>
                    <input type="date" placeholder="Insurance Start Date" name="insurance_start_date"
                        class="form-control {{ $errors->has('insurance_start_date') ? ' is-invalid' : '' }}"
                        id="insurance_start_date"
                        value="{{ old('insurance_start_date', $vehicle->insurance_start_date) }}"
                        >
                    {!! $errors->first('insurance_start_date', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 my-3 toggle-fields">
                <div class="form-group">

                    <div class="form-check">
                        <input type="hidden" name="is_ac" value='0'>
                        <input type="checkbox" name="is_ac" value='1' class="form-check-input"
                            id="is_ac" {{ $vehicle->is_ac ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_ac">Is_Ac</label>
                    </div>
                    {!! $errors->first('is_ac', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields" style="display: none;">
                <div class="form-group">
                    <label for="is_status">Is Status</label>
                    <select name="is_status" id="is_status"
                        class="form-control{{ $errors->has('is_status') ? ' is-invalid' : '' }}">
                        <option value="active" {{ $vehicle->is_status === 'active' ? 'selected' : '' }}>Active
                        </option>
                        <option value="inactive" {{ $vehicle->is_status === 'inactive' ? 'selected' : '' }}>Inactive
                        </option>
                        <option value="sold" {{ $vehicle->is_status === 'sold' ? 'selected' : '' }}>Sold</option>

                    </select>
                    {!! $errors->first('is_status', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-md-6 toggle-fields">

                <div class="form-group">
                    <label for="image">Image</label>
                    <input type="file" class="form-control" name="image" accept="image/*" />
                    <p>{{ $vehicle->image ? basename($vehicle->image) : 'No file chosen' }}</p>
                </div>
            </div>
            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="condition">Condition</label>
                <select name="condition" id="condition" class="form-control{{ $errors->has('condition') ? ' is-invalid' : '' }}">
                    <option value="automatic" {{ $vehicle->transmission_type === 'automatic' ? 'selected' : '' }}>new</option>
                    <option value="manual" {{ $vehicle->transmission_type === 'manual' ? 'selected' : '' }}>used</option>
                    <option value="manual" {{ $vehicle->transmission_type === 'manual' ? 'selected' : '' }}>excellent</option>
                    <option value="manual" {{ $vehicle->transmission_type === 'manual' ? 'selected' : '' }}>used</option>
                </select>
                {!! $errors->first('condition', '<div class="invalid-feedback">:message</div>') !!}
            </div> --}}
            {{-- </div> --}}

        </div>
    </div>
    <div class="box-footer mt20">
        @php
            $model = [
                'notify_btn' => 'Save',
                'function' => 'Save',
                'body' => 'Please Confirm do you realy want to Save?',
                'btn-color' => 'primary',
                'float' => 'end mt-2',
                'id' => 'save',
            ];
        @endphp
        @include('partials.modal', ['data' => $model])
    </div>
</div>
<script>
    $(document).ready(function() {
        // $('#registration_no').on('input', function() {
        //     var inputValue = $(this).val().trim();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/[^0-9-]/g, '');
        //     // Update the input field value
        //     $(this).val(numericValue);
        // });
        // $('#vehicle_no').on('input', function() {
        //     var inputValue = $(this).val().trim();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/[^0-9-]/g, '');
        //     // Update the input field value
        //     $(this).val(numericValue);
        // });
        $('#color').on('input', function() {
        var inputValue = $(this).val();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
     $('#year').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
    $('#milage,#weight').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });


     $('#maintenance_interval_days').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
     $('#maintenance_oilchange_interval_km').on('input', function() {
        var inputValue = $(this).val().trim();
        // Remove non-numeric characters
        var numericValue = inputValue.replace(/\D/g, '');
        // Limit to exactly 11 numbers
        // var elevenDigitValue = numericValue.slice(0, 11);
        // Update the input field value
        $(this).val(numericValue);
    });
     function formatCountry(option) {
            if (!option.id) {
                return option.text;
            }
            var iso = $(option.element).data('iso');
            var flag = $('<span><span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text +
                '</span>');
            return flag;
        }
       $('#prefix_insurance_provider_contact_no').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            placeholder: "Code",
            width: '30%' // Adjust width as needed
        });
    });
   
</script>

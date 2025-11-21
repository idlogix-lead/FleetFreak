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
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{$vehicleModel->name}}" required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="description">Description</label>
                <input type="text" placeholder="Description" name="description" class="form-control {{($errors->has('description') ? ' is-invalid' : '')}}" id="description" value="{{$vehicleModel->description}}">
                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_company_id">Company   <span style="color: red;">*</label>
                <select name="vehicle_company_id" id="vehicle_company_id" class="form-control{{ $errors->has('vehicle_company_id') ? ' is-invalid' : '' }}" autofocus>
                    <option value="">Select Company</option>
                    @foreach(App\Models\VehicleCompany::all_companies() as $comp)
                        <option value="{{ $comp->id }}" {{$vehicleModel->vehicle_company_id==$comp->id ? 'selected' : ''}}>{{ $comp->name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('vehicle_company_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_class_id">Class   <span style="color: red;">*</label>
                <select name="vehicle_class_id" id="vehicle_class_id" class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}" autofocus>
                    <option value="">Select Vehicle Class</option>
                    @foreach(App\Models\VehicleClass::vehicle_class() as $class)
                        <option value="{{ $class->id }}" {{$vehicleModel->vehicle_class_id==$class->id ? 'selected' : ''}}>{{ $class->name }}</option>
                    @endforeach
                </select>
                {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

        <div class="col-md-6">
            <div class="form-group">
                <label for="vehicle_class_id">Class <span style="color: red;">*</span></label>
                <select name="vehicle_class_id" id="vehicle_class_id" class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}" autofocus>
                    <option value="">Select Vehicle Class</option>
                    @php
                        // Retrieve the authenticated user's company
                        $user = auth()->user();
                        $company = $user->companies()->first();

                        // Fetch vehicle classes for the associated company
                        $vehicleClasses = $company ? App\Models\VehicleClass::where('company_id', $company->id)->get() : collect();
                    @endphp

                    @foreach ($vehicleClasses as $class)
                        <option value="{{ $class->id }}" {{ $vehicleModel->vehicle_class_id == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
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
        $('#name').on('input', function() {
            var inputValue = $(this).val();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            // Limit to exactly 11 numbers
            // var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(numericValue);
        });
    })
</script>

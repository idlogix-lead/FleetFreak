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
                    <input type="text" placeholder="Name" name="name"
                        class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                        value="{{ old('name', $ratelist->name) }}" autofocus required>
                    {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" placeholder="Description" name="description"
                        class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" id="description"
                        value="{{ old('description', $ratelist->description) }}" autofocus>
                    {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="route_id">Routes</label>
                    @php
                        $user = auth()->user();
                        $company = $user->companies()->first();
                        $routes = \App\Models\Route::where('company_id', $company->id)->get();
                    @endphp
                    {{-- <select name="route_id[]" id="route_id" class="form-control" multiple>
                    @foreach ($routes as $route)
                        <option value="{{ $route->id }}" >{{ Str::title($route->name)  }}</option>
                    @endforeach 
                </select> --}}
                    <select name="route_id" id="route_id" class="form-control" autofocus required>
                        <option value=''>-- Select --</option>
                        @foreach ($routes as $route)
                            <option value="{{ $route->id }}"
                                {{ $ratelist->route_id == $route->id ? 'selected' : null }}>{{ Str::title($route->name) }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="text" placeholder="Price" name="price"
                        class="form-control {{ $errors->has('price') ? ' is-invalid' : '' }}" id="price"
                        value="{{ old('price', $ratelist->price) }}" autofocus required>
                    {!! $errors->first('price', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_class_id">Vehicle Class</label>
                    <select name="vehicle_class_id" id="vehicle_class_id"
                        class="form-control {{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}" autofocus
                        required>
                        <option value=''>-- Select -- </option>
                        @php
                            $user = auth()->user();
                            $company = $user->companies()->first();
                            $vehicleclass = \App\Models\VehicleClass::where('company_id', $company->id)->get();
                        @endphp
                        @foreach ($vehicle_class as $v_class)
                            <option value="{{ $v_class->id }}"
                                {{ $ratelist->vehicle_class_id == $v_class->id ? 'selected' : null }}>
                                {{ Str::title($v_class->name) }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div> --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="vehicle_class_id">Vehicle Class</label>
                    <select name="vehicle_class_id" id="vehicle_class_id"
                        class="form-control {{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}" autofocus required>
                        <option value=''>-- Select -- </option>
                        @php
                            $user = auth()->user();
                            $company = $user->companies()->first();
                            $vehicleclass = \App\Models\VehicleClass::where('company_id', $company->id)->get();
                        @endphp
                        @foreach ($vehicleclass as $v_class)
                            <option value="{{ $v_class->id }}"
                                {{ $ratelist->vehicle_class_id == $v_class->id ? 'selected' : null }}>
                                {{ Str::title($v_class->name) }}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="estimated_time_hour">Estimated Time (Hour)</label>
                    <input type="text" placeholder="Estimated_time" name="estimated_time_hour"
                        class="form-control {{ $errors->has('estimated_time_hour') ? ' is-invalid' : '' }}"
                        id="estimated_time_hour"
                        value="{{ old('estimated_time_hour', intdiv($ratelist->estimated_time, 60)) }}" autofocus>
                    {!! $errors->first('estimated_time_hour', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="estimated_time_min">Estimated Time (Min)</label>
                    <input type="text" placeholder="Estimated_time" name="estimated_time_min"
                        class="form-control {{ $errors->has('estimated_time_min') ? ' is-invalid' : '' }}"
                        id="estimated_time_min" value="{{ old('estimated_time_min', $ratelist->estimated_time % 60) }}"
                        autofocus>
                    {!! $errors->first('estimated_time_min', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>


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
    function updateNameField() {
        var routeName = $('#route_id option:selected');
        var vehicleClassName = $('#vehicle_class_id option:selected');
        if (routeName.val().length > 0 && vehicleClassName.val().length > 0) {
            // Concatenate route and vehicle class names
            var newName = routeName.text() + ' - ' + vehicleClassName.text();
            // Set the concatenated name as the value of the name input field
            $('#name').val(newName);
        } else {
            $('#name').val('');

        }
    }

    $(document).ready(function() {
        $('#route_id, #vehicle_class_id').change(updateNameField);

        @if (!isset($ratelist->route_id) && !isset($ratelist->vehicle_class_id))
            updateNameField();
        @endif
        $('#price').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });
    });
</script>

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
                <label for="name">Name </label>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$route->name)}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="from">From Location</label>
                <select name="from" id="from" class="form-control {{ $errors->has('from') ? ' is-invalid' : '' }} red-border-select2" autofocus required>
                    <option value=''>-- Select -- </option>
                    @php
                        $from_locations = App\Models\Location::all_locations();
                    @endphp
                    @foreach($from_locations as $from_location)
                        <option value="{{ $from_location->id }}" {{ $route->from_loc == $from_location->id ? 'selected' : '' }}>
                            {{ Str::title($from_location->name) }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('from', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="from">From Location</label>
                <select name="from" id="from" class="form-control {{ $errors->has('from') ? ' is-invalid' : '' }}" autofocus required>
                    <option value=''>-- Select -- </option>
                    @php
                        $from_locations = App\Models\Location::all_locations()
                    @endphp
                    @foreach($from_locations as $from_location)
                        <option value="{{ $from_location->name }}" {{$route->from==$from_location->name ? 'selected' : null}}>{{ Str::title($from_location->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('from', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="to">To Location</label>
                <select name="to" id="to" class="form-control {{ $errors->has('to') ? ' is-invalid' : '' }} red-border-select2" autofocus required>
                    <option value=''>-- Select -- </option>
                    @php
                        $to_locations = App\Models\Location::all_locations()
                    @endphp
                    @foreach($to_locations as $to_location)
                        <option value="{{ $to_location->id }}" {{$route->to_loc==$to_location->id ? 'selected' : null}}>{{ Str::title($to_location->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('to', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="from">From Location</label>
                <input type="text" placeholder="From" name="from" class="form-control {{($errors->has('from') ? ' is-invalid' : '')}}" id="from" value="{{old('from',$route->from)}}" autofocus required>
                {!! $errors->first('from', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="to">To Location </label>
                <input type="text" placeholder="To" name="to" class="form-control {{($errors->has('to') ? ' is-invalid' : '')}}" id="to" value="{{old('to',$route->to)}}" autofocus required>
                {!! $errors->first('to', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="distance">Distance  </label>
                <input type="text" placeholder="Distance" name="distance" class="form-control {{($errors->has('distance') ? ' is-invalid' : '')}}" id="distance" value="{{old('distance',$route->distance)}}" autofocus required>
                {!! $errors->first('distance', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="distance_unit" >Distance Unit </span> </label>
                <select name="distance_unit" required
                id="distance_unit" class="form-control{{ $errors->has('distance_unit') ? ' is-invalid' : '' }}"  autofocus>
                    <option> Select</option>
                    <option value="km" {{ $route->distance_unit === 'km' ? 'selected' : '' }}>Km</option>
                    <option value="miles" {{ $route->distance_unit === 'miles' ? 'selected' : '' }}>Miles</option>
                    <option value="meters" {{ $route->distance_unit === 'meters' ? 'selected' : '' }}>Meters</option>
                </select>
                {!! $errors->first('distance_unit', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_flight" id="is_flight_hidden">

                <input type="checkbox" value="1" name="is_flight" class="form-check-input" id="is_flight" {{ old('is_flight', $route->is_flight) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_flight">Is Flight</label>
            </div>
        </div>


        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="rate_with_fuel">Rate with Fuel</label>
                <input type="text" placeholder="Rate with Fuel" name="rate_with_fuel" class="form-control{{ $errors->has('rate_with_fuel') ? ' is-invalid' : '' }}" id="rate_with_fuel" value="{{ old('rate_with_fuel', $route_rate->rate_with_fuel) }}">
                @error('rate_with_fuel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div> --}}
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="rate_without_fuel">Rate without Fuel</label>
                <input type="text" placeholder="Rate without Fuel" name="rate_without_fuel" class="form-control{{ $errors->has('rate_without_fuel') ? ' is-invalid' : '' }}" id="rate_without_fuel" value="{{ old('rate_without_fuel', $route_rate->rate_without_fuel) }}">
                @error('rate_without_fuel')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div> --}}

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
<style>
</style>
<script>
    $(document).ready(function() {
        // select2
        $('#from').select2({
            placeholder: "-- Select --",
            width: '100%',
            allowClear: true
        });
        $('#to').select2({
            placeholder: "-- Select --",
            width: '100%',
            allowClear: true
        });
        // ----------end--------------
        $('#distance').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
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
        $('#rate_wit_fuel').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
        $('#rate_without_fuel').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(elevenDigitValue);
        });
    })
</script>

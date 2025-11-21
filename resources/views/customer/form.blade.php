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
            {{-- @if($form_type == 'all') --}}
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="partner_type" >Partner Type</label>
                        <select name="partner_type" id="partner_type" class="form-control{{ $errors->has('partner_type') ? ' is-invalid' : '' }}"  disabled>
                            <option> Select</option>
                            <option value="business_customer" selected {{ isset($partner)?$partner->actors->name === 'business_customer' ? 'selected' : '' : '' }}>Business Customer</option>

                        </select>
                        {!! $errors->first('partner_type', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            {{-- @else
                <input type="hidden" name="partner_type" value='{{$form_type}}' >
            @endif --}}


            <div class="col-md-6" id = 'business_partner_form_id' >
                <div class="form-group">
                    <label for="business_partner_id">Business Partner</label>
                    <select name="business_partner_id" id="business_partner_id" class="form-control{{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}" style="border-color: red!important;" autofocus required>
                        <option value="">-- Select --</option>
                        @php
                            $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                            $count = $businessPartners->count();
                        @endphp
                        @foreach($businessPartners as $business_partner)
                            <option value="{{ $business_partner->id }}" {{isset($partner) ? $partner->business_partner_id == $business_partner->id ? 'selected' : '': ''}}>{{ $business_partner->name }}</option>
                        @endforeach
                        @if($count == 1)
                            <script>
                                // Automatically select the only option if there's only one business partner
                                document.getElementById('business_partner_id').selectedIndex = 1;
                            </script>
                        @endif
                    </select>
                    {!! $errors->first('business_partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
                {{-- <input type="hidden" name="business_partner_id" id="business_partner_id" value="{{$business_partner->name}}"> --}}
            </div>



        <div class="col-md-6">
            <div class="form-group">
                <label for="name">Name  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" placeholder="Name" name="name" class="form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$partner->name??'')}}" autofocus required>
                {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        {{-- <div class="col-md-6" id="employee_id">
            <div class="form-group">
                <label for="employee_type" >Employee Type</label>
                <select name="employee_type" id="employee_type" class="form-control{{ $errors->has('employee_type') ? ' is-invalid' : '' }}"  autofocus required>
                    <option> Select</option>
                    <option value="driver" selected {{ isset($partner)?$partner->employee_type === 'driver' ? 'selected' : '' : '' }}>Driver</option>
                    <option value="managment" {{ isset($partner)?$partner->employee_type === 'managment' ? 'selected' : '' : '' }}>Managment</option>
                    <option value="office_staff"  {{ isset($partner)?$partner->employee_type === 'office_staff' ? 'selected' : '' : '' }}>Office Staff</option>
                    <option value="agent" {{ isset($partner)?$partner->partner_type === 'agent' ? 'selected' : '' : '' }}>Agent</option>
                </select>
                {!! $errors->first('employee_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

        <div class="col-md-6">
            <div class="form-group">
                <label for="email">Email  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message'></i></span>
                <input type="email" placeholder="Email" name="email" class="form-control {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{old('email',$partner->email??'')}}" autofocus>
                <div class="invalid-feedback" id="email-error">Please enter a valid email address.</div>
                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-6" id = "passport_id">
            <div class="form-group">
                <label for="passport">Passport  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user-circle'></i></span>
                <input type="text" placeholder="Passport" name="passport" class="form-control {{($errors->has('passport') ? ' is-invalid' : '')}}" id="passport" value="{{old('passport',$partner->passport??'')}}" autofocus>
                {!! $errors->first('passport', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no">Phone No</label>
                <div class="input-group">
                     <!-- Country Code Dropdown -->
                     <select class="form-select" id="prefix_phonecus" name="prefix_phone">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                            <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}">
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Phone No" name="phone_no" class="form-control {{($errors->has('phone_no') ? ' is-invalid' : '')}}" id="phone_no" value="{{old('phone_no',$partner->phone_no??'')}}" autofocus>
                {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="whatsapp_no">Whatsapp No</label>
                <div class="input-group">
                     <!-- Country Code Dropdown -->
                     <select class="form-select" id="prefix_whatsappcus" name="prefix_whatsapp">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                            <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_whatsapp == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}">
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Whatsapp No" name="whatsapp_no" class="form-control {{($errors->has('whatsapp_no') ? ' is-invalid' : '')}}" id="whatsapp_no" value="{{old('whatsapp_no',$partner->whatsapp_no??'')}}" autofocus required>
                {!! $errors->first('whatsapp_no', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="cnic">Cnic</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-id-card'></i></span>
                <input type="text" placeholder="Cnic" name="cnic" class="form-control {{($errors->has('cnic') ? ' is-invalid' : '')}}" id="cnic" value="{{old('cnic',$partner->cnic??'')}}" autofocus>
                {!! $errors->first('cnic', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        {{-- added company_name field --}}
        {{-- <div class="col-md-6" id = "company_name_id">
            <div class="form-group">
                <label for="company_name">Company Name  </label>
                <input type="text" placeholder="Company Name" name="company_name" class="form-control {{($errors->has('company_name') ? ' is-invalid' : '')}}" id="company_name" value="{{$partner->company_name??''}}" autofocus >
                {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-12">
            <div class="form-group">
                <label for="address1">Address1</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map'></i></span>
                <input type="text" placeholder="Address1" name="address1" class="form-control {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{old('address1',$partner->address1??'')}}" autofocus>
                {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="address2">Address2</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map'></i></span>
                <input type="text" placeholder="Address2" name="address2" class="form-control {{($errors->has('address2') ? ' is-invalid' : '')}}" id="address2" value="{{old('address2',$partner->address2??'')}}" autofocus>
                {!! $errors->first('address2', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="address3">Address3</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map'></i></span>
                <input type="text" placeholder="Address3" name="address3" class="form-control {{($errors->has('address3') ? ' is-invalid' : '')}}" id="address3" value="{{old('address3',$partner->address3??'')}}" autofocus>
                {!! $errors->first('address3', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="country">Country</label>
                <div class="input-group input-group-sm custom-width">
                <select name="country" class="form-control select2-country {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus>
                    <option value="">-- Select Country --</option>
                    @foreach(App\Models\City::fetchCountry() as $country)
                        <option value="{{ $country }}" {{ (old('country') ?? $partner->country ?? '') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
                </div>
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" class="form-control select2-city {{ $errors->has('city') ? 'is-invalid' : '' }}" id="city" autofocus>
                    <option value="">-- Select City --</option>
                    @if(old('country') || isset($partner->country))
                        @foreach(App\Models\City::getCitiesByCountry(old('country') ?? $partner->country) as $city)
                            <option value="{{ $city->city }}" {{ (old('city') ?? $partner->city ?? '') == $city->city ? 'selected' : '' }}>
                                {{ $city->city }}
                            </option>
                        @endforeach
                    @endif
                </select>
                {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="country">Country</label>
                <input type="text" placeholder="Country" name="country" class="form-control {{($errors->has('country') ? ' is-invalid' : '')}}" id="country" value="{{$partner->country??''}}" autofocus>
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        {{-- <div class="col-md-6 my-3 ">
            <div class="form-group">

                <div class="form-check" id = "create_form">
                    <input type="hidden" name="create_user" value='0'>
                    <input type="checkbox"  name="create_user" value='1' class="form-check-input" id="create_user" {{ isset($partner)?$partner->create_user ? 'checked' : '': '' }} checked>
                    <label class="form-check-label" for="create_user">Create User</label>
                </div>
                {!! $errors->first('create_user', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div></div>
        <div class="col-md-6" id="passwordFields" style="{{ isset($partner)?$partner->create_user ? '' : '':'' }}">
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" class="form-control" id="password" autofocus required>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" autofocus required>
            </div>
        </div> --}}

        </div>
    </div>
</div>

{{-- javascript --}}
@include('layouts.partials.form-email&password-validation');

<script>
    $(document).ready(function() {
        function formatCountry(option) {
                if (!option.id) {
                    return option.text;
                }
                var iso = $(option.element).data('iso');
                var flag = $('<span><span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text + '</span>');
                return flag;
            }

            $('#prefix_phonecus').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '30%'// Adjust width as needed


            })

            $('#prefix_whatsappcus').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '30%'// Adjust width as needed


            })

        $('#phone_no').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            // var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#passport').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            // var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#whatsapp_no').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#name').on('input', function() {
            var inputValue = $(this).val();
            // Remove numeric characters
            var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#cnic').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });
        $('.select2-country').select2({
        width: '100%'
        });

    // Initialize Select2 with tags option for city dropdown
        $('.select2-city').select2({
            width: '100%',
            tags: true,
            ajax: {
                url: '{{ route("get-cities") }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term,
                        country: $('#country').val()
                    };
                },
                processResults: function(data) {
                    return {
                        results: data.map(function(city) {
                            return {
                                id: city.city,
                                text: city.city
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Event listener for country select dropdown
        $('#country').on('change', function() {
            var countryName = $(this).val();
            $('#city').val(null).trigger('change');
            // Trigger the select2 search to update city dropdown
            $('.select2-city').select2('data', null);
        });

        // Set the selected city if editing
        var selectedCity = '{{ old("city", $partner->city ?? '') }}';
        if (selectedCity) {
            var newOption = new Option(selectedCity, selectedCity, true, true);
            $('#city').append(newOption).trigger('change');
        }
    });
</script>


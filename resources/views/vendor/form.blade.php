<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            {{-- @if($form_type == 'all') --}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="partner_type" >Partner Type</label>
                        <select name="partner_type" id="partner_type" class="form-control form-control-sm{{ $errors->has('partner_type') ? ' is-invalid' : '' }}"  disabled>
                            <option> Select</option>
                            <option value="agent" selected {{ isset($partner)?$partner->actors->name === 'vendor' ? 'selected' : '' : '' }}>Vendor</option>
                            {{-- <option value="agent" {{ isset($partner)?$partner->partner_type === 'agent' ? 'selected' : '' : '' }}>Agent</option> --}}
                        </select>
                        {!! $errors->first('partner_type', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            {{-- @else
                <input type="hidden" name="partner_type" value='{{$form_type}}' >
            @endif --}}

            {{-- @if($form_type == 'all')
            <div class="col-md-6" id = 'business_partner_form_id' style = "display:none;">
                <div class="form-group">
                    <label for="business_partner_id">Business Partner</label>
                    <select name="business_partner_id" id="business_partner_id" class="form-control{{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
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
            </div>
            @endif --}}
            {{-- <div class="col-md-6">
                <label for="inputLastName1" class="form-label">First Name</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                    <input type="text" class="form-control border-start-0" id="inputLastName1" placeholder="First Name" />
                </div>
            </div> --}}
            <div class="col-md-4">
                <div class="form-group">
                    <label for="company_id">Company  </label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                    <input type="text" readonly name="company_id" class="form-control form-control-sm {{($errors->has('company_id') ? ' is-invalid' : '')}}" id="company_id" value="{{auth()->user()->active_company_details()->name}}" autofocus required>
                    {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}</div>
                </div>
            </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="name">Name  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                <input type="text" placeholder="Name" name="name" class="form-control form-control-sm {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$partner->name??'')}}" autofocus required>
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
                </select>
                {!! $errors->first('employee_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

        <div class="col-md-4">
            <div class="form-group">
                <label for="email">Email  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                <input type="email" placeholder="Email" name="email" class="form-control form-control-sm {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{old('email',$partner->email??'')}}" autofocus required>
                <div class="invalid-feedback" id="email-error">Please enter a valid email address.</div>
                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label for="price_list_id">Price List</label>
                <select name="price_list_id" id="price_list_id"
                    class="form-control form-control-sm red-border-select2 {{ $errors->has('price_list_id') ? ' is-invalid' : '' }}" autofocus required>
                    <option value="">-- Select --</option>
                    @foreach (App\Models\PriceList::pricelist() as $pricelist)
                        <option value="{{ $pricelist->id }}" data-currency="{{ $pricelist->currency }}"
                            {{  old('price_list_id', isset($partner) ? $partner->price_list_id : '') == $pricelist->id ? 'selected' : '' }}>
                            {{ Str::title($pricelist->name) }}</option>
                    @endforeach
                </select>
                {!! $errors->first('price_list_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6" id = "passport_id">
            <div class="form-group">
                <label for="passport">Passport  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user-circle'></i></span>
                <input type="text" placeholder="Passport" name="passport" class="form-control {{($errors->has('passport') ? ' is-invalid' : '')}}" id="passport" value="{{old('passport',$partner->passport??'')}}" >
                {!! $errors->first('passport', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div> --}}
        <div class="col-md-4">
            <div class="form-group">
                <label for="phone_no">Phone No</label>
                <div class="input-group">
                     <!-- Country Code Dropdown -->
                     <select class="form-select" id="prefix_phonecus" name="prefix_phone">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                        <option value="{{ $country->phonecode }}" {{ (old('prefix_phone', $partner->prefix_phone ?? '+1') == $country->phonecode) ? 'selected' : '' }} data-iso="{{ strtolower($country->iso) }}">
                            {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Phone No" name="phone_no" class="form-control form-control-sm {{($errors->has('phone_no') ? ' is-invalid' : '')}}" id="phone_no" value="{{old('phone_no',$partner->phone_no??'')}}" autofocus>
                {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="whatsapp_no">Whatsapp No</label>
                <div class="input-group">
                    <select class="form-select" id="prefix_whatsappcus" name="prefix_whatsapp">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                        <option value="{{ $country->phonecode }}" {{ (old('prefix_whatsapp', $partner->prefix_whatsapp ?? '+1') == $country->phonecode) ? 'selected' : '' }} data-iso="{{ strtolower($country->iso) }}">
                            {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_whatsapp == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Whatsapp No" name="whatsapp_no" class="form-control form-control-sm {{($errors->has('whatsapp_no') ? ' is-invalid' : '')}}" id="whatsapp_no" value="{{old('whatsapp_no',$partner->whatsapp_no??'')}}" autofocus required>
                {!! $errors->first('whatsapp_no', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="cnic">Cnic</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-id-card'></i></span>
                <input type="text" placeholder="Cnic" name="cnic" class="form-control form-control-sm {{($errors->has('cnic') ? ' is-invalid' : '')}}" id="cnic" value="{{old('cnic',$partner->cnic??'')}}" autofocus required>
                {!! $errors->first('cnic', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        {{-- added company_name field --}}
        <div class="col-md-4" id = "company_name_id">
            <div class="form-group">
                <label for="company_name">Company Name  </label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-factory'></i></span>
                <input type="text" placeholder="Company Name" name="company_name" class="form-control form-control-sm {{($errors->has('company_name') ? ' is-invalid' : '')}}" id="company_name" value="{{old('company_name',$partner->company_name??'')}}" autofocus required>
                {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label for="address1">Address1</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map'></i></span>
                <input type="text" placeholder="Address1" name="address1" class="form-control form-control-sm {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{old('address1',$partner->address1??'')}}" autofocus>
                {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        {{-- <div class="col-md-12">
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
        </div> --}}
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="city">City</label>
                <input type="text" placeholder="City" name="city" class="form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{$partner->city??''}}" autofocus>
                {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}



        <div class="col-md-4">
            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" class="form-control form-control-sm select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus>
                    <option value="">-- Select Country --</option>
                    @foreach(App\Models\City::fetchCountry() as $country)
                        <option value="{{ $country }}" {{ (old('country') ?? $partner->country ?? '') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" class="form-control form-control-sm select2-tags {{ $errors->has('city') ? 'is-invalid' : '' }}" id="city" autofocus>
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
        @if ($form_type=='all')


        <div class="col-md-6 my-3 ">
            <div class="form-group">
                {{-- <label for="create_user">Create User</label> --}}
                <div class="form-check" id = "create_form">
                    <input type="hidden" name="create_user" value='0'>
                    <input type="checkbox"  name="create_user" value='1' class="form-check-input" id="create_user" {{ isset($partner)?$partner->create_user ? 'checked' : '': '' }} checked>
                    <label class="form-check-label" for="create_user">Create User</label>
                </div>
                {!! $errors->first('create_user', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div></div>
        <div class="col-md-6" id="passwordFields"   style="{{ isset($partner)?$partner->create_user ? '' : '':'' }}">

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-lock'></i></span>
                <input type="password" name="password" class="form-control form-control-sm" id="password"
                 pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                 title="Password must be at least 8 characters long and include uppercase, lowercase, and a number."
                 autofocus required>
                 <div class="invalid-feedback" id="password-error">
                     Password must be at least 8 characters long and include uppercase, lowercase, and a number.
                </div>
                </div>
            </div>
            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-show'></i></span>
                <input type="password" name="password_confirmation" class="form-control form-control-sm" id="password_confirmation"
                 pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                 title="Password must be at least 8 characters long and include uppercase, lowercase, and a number."
                autofocus
                required>
                 <div class="invalid-feedback" id="confirm-password-error">
                    Password must be at least 8 characters long and include uppercase, lowercase, and a number.
                </div>
            </div>
            </div>
        </div>
        @endif

        </div>
    </div>
</div>

{{-- javascript --}}
@include('layouts.partials.form-email&password-validation');

<script>
    $(document).ready(function() {

        const createUserCheckbox = $('#create_user');
        const partner_type = $('#partner_type');
        const create_form = $('#create_form');
        const passwordFields = $('#passwordFields');
        const business_partner = $('#business_partner_form_id');
        const company_name = $('#company_name_id');
        const passport_id = $('#passport_id');
        const employee_id = $('#employee_id')
        employee_id.hide();

        partner_type.on('change', function() {
            if (partner_type.val() == 'business') {
                create_form.show();
                employee_id.hide();
                business_partner.hide();
                company_name.show();
                createUserCheckbox.show();
                createUserCheckbox.prop('checked', true);
                passwordFields.show();
                $('#password').prop('required', true);
                $('#password_confirmation').prop('required', true);
            } else if(partner_type.val() == 'employee'){
                employee_id.show();
                create_form.show();
                passport_id.hide();
                business_partner.hide();
                company_name.hide();
                createUserCheckbox.show();
                createUserCheckbox.prop('checked', true);
                passwordFields.show();
                $('#password').prop('required', true);
                $('#password_confirmation').prop('required', true);

            } else {
                employee_id.hide();
                create_form.hide();
                company_name.hide();
                createUserCheckbox.hide();
                passwordFields.hide();
                business_partner.show();
                $('#password').prop('required', false);
                $('#password_confirmation').prop('required', false);
            }
        });

        createUserCheckbox.on('change', function() {
            // Prevent unchecking the checkbox if the partner type is 'business'
            // if (!createUserCheckbox.is(':checked')) {
            //     createUserCheckbox.prop('checked', true);
            //     return false; // Prevent default behavior
            // }

            // Toggle visibility of password fields based on checkbox state
            if (createUserCheckbox.is(':checked')) {
                passwordFields.show();
                $('#password').prop('required', true);
                $('#password_confirmation').prop('required', true);
            } else {
                passwordFields.hide();
                $('#password').prop('required', false);
                $('#password_confirmation').prop('required', false);
            }
        });


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
            $('#name').on('input', function() {
                var inputValue = $(this).val();
                // Remove non-numeric characters
                var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
                // Limit to exactly 11 numbers
                // var elevenDigitValue = numericValue.slice(0, 11);
                // Update the input field value
                $(this).val(numericValue);
            });
             $('#company_name').on('input', function() {
                var inputValue = $(this).val();
                // Remove non-numeric characters
                var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
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
            $('#cnic').on('input', function() {
                var inputValue = $(this).val().trim();
                // Remove non-numeric characters
                var numericValue = inputValue.replace(/\D/g, '');
                // Update the input field value
                $(this).val(numericValue);
            });
            $('.select2').select2();
            $('.select2-tags').select2({
                width: '100%',
                tags: true,
                ajax: {
                    url: '{{ route("get-cities") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            country: $('#country').val()
                        };
                    },
                    processResults: function (data) {
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
                $('.select2-tags').trigger('select2:select', {
                    data: { id: '', text: '' }
                });
            });

            // Set the selected city if editing
            var selectedCity = '{{ old("city", $partner->city ?? '') }}';
            if (selectedCity) {
                var newOption = new Option(selectedCity, selectedCity, true, true);
                $('#city').append(newOption).trigger('change');
            }
            //for country flags
            // function formatState (state) {
            // if (!state.id) {
            //     return state.text;
            // }
            // var flagUrl = "https://flagcdn.com/16x12/" + $(state.element).data('flag') + ".png";
            // var $state = $(
            //     '<span><img src="' + flagUrl + '" class="img-flag" /> ' + state.text + '</span>'
            // );
            // return $state;
            // };

            // $(".select2").select2({
            //     templateResult: formatState,
            //     templateSelection: formatState,
            //     escapeMarkup: function(m) { return m; }
            // });
            //for cities:
            // $('#country').change(function() {
            //     var countryName = $(this).val();

            //     $.ajax({
            //         url: '/get-cities', // Endpoint to fetch cities
            //         method: 'GET',
            //         data: { country_name: countryName },
            //         dataType: 'json',
            //         success: function(response) {
            //             var citySelect = $('#city');
            //             citySelect.empty(); // Clear previous options

            //             // Populate city options
            //             $.each(response, function(index, city) {
            //                 citySelect.append($('<option>').text(city.name).val(city.id));
            //             });

            //             // Trigger select2 refresh
            //             citySelect.trigger('change');
            //         },
            //         error: function(xhr, status, error) {
            //             console.error('Error fetching cities:', error);
            //         }
            //     });
            // });
    });

</script>
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


            });

            $('#prefix_whatsappcus').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '30%'// Adjust width as needed


            });
        });
</script>


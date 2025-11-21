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
            {{-- @if($form_type == 'all') --}}
                <div class="col-md-6 toggle-fields">
                    <div class="form-group">
                        <label for="partner_type" >Partner Type</label>
                        <select name="partner_type" id="partner_type" class="form-control{{ $errors->has('partner_type') ? ' is-invalid' : '' }}"  disabled>
                            <option> Select</option>
                            <option value="driver" selected {{ isset($partner)?$partner->actors->name === 'driver' ? 'selected' : '' : '' }}>Driver</option>
                        </select>
                        {!! $errors->first('partner_type', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            {{-- </div> --}}
            {{-- @else
                <input type="hidden" name="partner_type" value='{{$form_type}}' >
            @endif --}}

            {{-- @if ($form_type == 'all')
            <div class="col-md-6" id = 'business_partner_form_id' style = "display:none;">
                <div class="form-group">
                    <label for="business_partner_id">Business Partner</label>
                    <select name="business_partner_id" id="business_partner_id" class="form-control{{ $errors->has('business_partner_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select --</option>
                        @php
                            $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                            $count = $businessPartners->count();
                        @endphp
                        @foreach ($businessPartners as $business_partner)
                            <option value="{{ $business_partner->id }}" {{isset($partner) ? $partner->business_partner_id == $business_partner->id ? 'selected' : '': ''}}>{{ $business_partner->name }}</option>
                        @endforeach
                        @if ($count == 1)
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


            <div class="col-md-6">
                <div class="form-group">
                    <label for="name">Name </label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-user'></i></span>
                        <input type="text" placeholder="Name" name="name"
                            class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" id="name"
                            value="{{ old('name', $partner->name ?? '') }}" autofocus required>
                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            {{-- <div class="col-md-6" id="employee_id">
            <div class="form-group">
                <label for="employee_type" >Employee Type</label>
                <select name="employee_type" id="employee_type" class="form-control{{ $errors->has('employee_type') ? ' is-invalid' : '' }}"  autofocus required>
                    <option> Select</option>
                    <option value="driver" selected {{ isset($partner)?$partner->employee_type === 'driver' ? 'selected' : '' : '' }}>Driver</option>

                </select>
                {!! $errors->first('employee_type', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email </label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class='bx bxs-message'></i>
                        </span>
                        <input
                            type="email"
                            placeholder="Email"
                            name="email"
                            class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                            id="email"
                            value="{{ old('email', $partner->email ?? '') }}"
                            required
                            autofocus>
                        <div class="invalid-feedback" id="email-error">Please enter a valid email address.</div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 toggle-fields" id = "passport_id">
                <div class="form-group">
                    <label for="passport">Passport </label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-user-circle'></i></span>
                        <input type="text" placeholder="Passport" name="passport"
                            class="form-control {{ $errors->has('passport') ? ' is-invalid' : '' }}" id="passport"
                            value="{{ old('passport', $partner->passport ?? '') }}">
                        {!! $errors->first('passport', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="phone_no">Phone No</label>
                    <div class="input-group">
                        <!-- Country Code Dropdown -->
                        <select class="form-select" id="prefix_phonecus" name="prefix_phone">
                            @foreach (App\Models\CountryCode::phone_codes() as $country)
                                <option value="{{ $country->phonecode }}"
                                    {{ old('prefix_phone', $partner->prefix_phone ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                    data-iso="{{ strtolower($country->iso) }}">
                                    {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                    +{{ $country->iso . '(' . $country->phonecode . ')' }}
                            @endforeach
                        </select>
                        <input type="text" placeholder="Phone No" name="phone_no"
                            class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}" id="phone_no"
                            value="{{ old('phone_no', $partner->phone_no ?? '') }}" autofocus required>
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
                            @foreach (App\Models\CountryCode::phone_codes() as $country)
                                <option value="{{ $country->phonecode }}"
                                    {{ old('prefix_whatsapp', $partner->prefix_whatsapp ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                    data-iso="{{ strtolower($country->iso) }}">
                                    {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_whatsapp == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                    +{{ $country->iso . '(' . $country->phonecode . ')' }}
                            @endforeach
                        </select>
                        <input type="text" placeholder="Whatsapp No" name="whatsapp_no"
                            class="form-control {{ $errors->has('whatsapp_no') ? ' is-invalid' : '' }}"
                            id="whatsapp_no" value="{{ old('whatsapp_no', $partner->whatsapp_no ?? '') }}" autofocus
                            required>
                        {!! $errors->first('whatsapp_no', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
             <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="emergency_contact_no1">Emergency Contact Number1</label>
                    <div class="input-group">
                        <!-- Country Code Dropdown -->
                        <select class="form-select" id="prefix_emergency_contact1" name="prefix_emergency_contact1">
                            @foreach (App\Models\CountryCode::phone_codes() as $country)
                                <option value="{{ $country->phonecode }}"
                                    {{ old('prefix_emergency_contact1', $partner->prefix_emergency_contact1 ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                    data-iso="{{ strtolower($country->iso) }}">
                                    {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                    +{{ $country->iso . '(' . $country->phonecode . ')' }}
                            @endforeach
                        </select>
                        <input type="text" placeholder="Emergency Contact No1" name="emergency_contact_no1"
                            class="form-control {{ $errors->has('emergency_contact_no1') ? ' is-invalid' : '' }}" id="emergency_contact_no1"
                            value="{{ old('emergency_contact_no1', $partner->emergency_contact_no1 ?? '') }}">
                        {!! $errors->first('emergency_contact_no1', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
             <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="emergency_contact_no2">Emergency Contact no2</label>
                    <div class="input-group">
                        <!-- Country Code Dropdown -->
                        <select class="form-select" id="prefix_emergency_contact2" name="prefix_emergency_contact2">
                            @foreach (App\Models\CountryCode::phone_codes() as $country)
                                <option value="{{ $country->phonecode }}"
                                    {{ old('prefix_emergency_contact2', $partner->prefix_emergency_contact2 ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                    data-iso="{{ strtolower($country->iso) }}">
                                    {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                    +{{ $country->iso . '(' . $country->phonecode . ')' }}
                            @endforeach
                        </select>
                        <input type="text" placeholder="Emergency Contact No" name="emergency_contact_no2"
                            class="form-control {{ $errors->has('emergency_contact_no2') ? ' is-invalid' : '' }}" id="emergency_contact_no2"
                            value="{{ old('emergency_contact_no2', $partner->emergency_contact_no2 ?? '') }}">
                        {!! $errors->first('emergency_contact_no2', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="emergency_contact_name">Emergency Contact Name </label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-user'></i></span>
                        <input type="text" placeholder="Emergency Contact Name" name="emergency_contact_name"
                            class="form-control {{ $errors->has('emergency_contact_name') ? ' is-invalid' : '' }}" id="emergency_contact_name"
                            value="{{ old('emergency_contact_name', $partner->emergency_contact_name ?? '') }}">
                        {!! $errors->first('emergency_contact_name', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="cnic">Nic</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-id-card'></i></span>
                        <input type="text" placeholder="Nic" name="cnic"
                            class="form-control {{ $errors->has('cnic') ? ' is-invalid' : '' }}" id="cnic"
                            value="{{ old('cnic', $partner->cnic ?? '') }}" autofocus required>
                        {!! $errors->first('cnic', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
              {{-- <div class="col-md-6">
                <div class="form-group">
                    <label for="nic_no">Nic Number</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-id-card'></i></span>
                        <input type="text" placeholder="Nic Number" name="nic_no"
                            class="form-control {{ $errors->has('nic_no') ? ' is-invalid' : '' }}" id="nic_no"
                            value="{{ old('nic_no', $partner->nic_no ?? '') }}" autofocus required>
                        {!! $errors->first('nic_no', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div> --}}
              <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="nic_expiry_date">Nic Expiry Date</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-id-card'></i></span>
                        <input type="date" placeholder="Nic Expiry Date" name="nic_expiry_date"
                            class="form-control {{ $errors->has('nic_expiry_date') ? ' is-invalid' : '' }}" id="nic_expiry_date"
                            value="{{ old('nic_expiry_date', $partner->nic_expiry_date ?? '') }}">
                        {!! $errors->first('nic_expiry_date', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="age">Age</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-cake'></i>
                        </span>
                        <input type="text" placeholder="Age" name="age"
                            class="form-control {{ $errors->has('age') ? ' is-invalid' : '' }}" id="age"
                            value="{{ old('age', $partner->age ?? '') }}">
                        {!! $errors->first('age', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="experience">Experience</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-briefcase'></i></span>
                        <input type="text" placeholder="Experience" name="experience"
                            class="form-control {{ $errors->has('experience') ? ' is-invalid' : '' }}"
                            id="experience" value="{{ old('experience', $partner->experience ?? '') }}">
                        {!! $errors->first('experience', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="akama">Akama</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-file'></i></span>
                        <input type="text" placeholder="Akama" name="akama"
                            class="form-control {{ $errors->has('akama') ? ' is-invalid' : '' }}" id="akama"
                            value="{{ old('akama', $partner->akama ?? '') }}" >
                        {!! $errors->first('akama', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="akama">Driving License</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-file'></i></span>
                        <input type="text" placeholder="driver_license" name="driver_license"
                            class="form-control {{ $errors->has('driver_license') ? ' is-invalid' : '' }}" id="driver_license"
                            value="{{ old('driver_license', $partner->driver_license ?? '') }}">
                        {!! $errors->first('driver_license', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
             <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="license_country">Driving License Country</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-file'></i></span>
                        <input type="text" placeholder="driver_license_country" name="license_country"
                            class="form-control {{ $errors->has('license_country') ? ' is-invalid' : '' }}" id="license_country"
                            value="{{ old('license_country', $partner->license_country ?? '') }}">
                        {!! $errors->first('license_country', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
             <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="licensee_expiry_date">Driving License Expiry Date</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-file'></i></span>
                        <input type="date" placeholder="licensee_expiry_date" name="licensee_expiry_date"
                            class="form-control {{ $errors->has('licensee_expiry_date') ? ' is-invalid' : '' }}" id="licensee_expiry_date"
                            value="{{ old('licensee_expiry_date', $partner->licensee_expiry_date ?? '') }}" >
                        {!! $errors->first('licensee_expiry_date', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
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
            <div class="col-md-12 toggle-fields">
                <div class="form-group">
                    <label for="address1">Address1</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-map'></i></span>
                        <input type="text" placeholder="Address1" name="address1"
                            class="form-control {{ $errors->has('address1') ? ' is-invalid' : '' }}" id="address1"
                            value="{{ old('address1', $partner->address1 ?? '') }}" >
                        {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12 toggle-fields">
                <div class="form-group">
                    <label for="address2">Address2</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-map'></i></span>
                        <input type="text" placeholder="Address2" name="address2"
                            class="form-control {{ $errors->has('address2') ? ' is-invalid' : '' }}" id="address2"
                            value="{{ old('address2', $partner->address2 ?? '') }}" >
                        {!! $errors->first('address2', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>
            <div class="col-md-12 toggle-fields">
                <div class="form-group">
                    <label for="address3">Address3</label>
                    <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                class='bx bxs-map'></i></span>
                        <input type="text" placeholder="Address3" name="address3"
                            class="form-control {{ $errors->has('address3') ? ' is-invalid' : '' }}" id="address3"
                            value="{{ old('address3', $partner->address3 ?? '') }}" >
                        {!! $errors->first('address3', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
            </div>

            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country"
                        class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country"
                        >
                        <option value="">-- Select Country --</option>
                        @foreach (App\Models\City::fetchCountry() as $country)
                            <option value="{{ $country }}"
                                {{ (old('country') ?? ($partner->country ?? '')) == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 toggle-fields">
                <div class="form-group">
                    <label for="city">City</label>
                    <select name="city"
                        class="form-control select2-tags {{ $errors->has('city') ? 'is-invalid' : '' }}"
                        id="city" >
                        <option value="">-- Select City --</option>
                        @if (old('country') || isset($partner->country))
                            @foreach (App\Models\City::getCitiesByCountry(old('country') ?? $partner->country) as $city)
                                <option value="{{ $city->city }}"
                                    {{ (old('city') ?? ($partner->city ?? '')) == $city->city ? 'selected' : '' }}>
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
                <input type="text" placeholder="Country" name="country" class="form-control {{($errors->has('country') ? ' is-invalid' : '')}}" id="country" value="{{old('country',$partner->country??'')}}" autofocus>
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            @if ($form_type == 'all')
                <div class="col-md-6 my-3 ">
                    <div class="form-group">
                        {{-- <label for="create_user">Create User</label> --}}
                        <div class="form-check" id = "create_form">
                            <input type="hidden" name="create_user" value='0'>
                            <input type="checkbox" name="create_user" value='1' class="form-check-input"
                                id="create_user" {{ isset($partner) ? ($partner->create_user ? 'checked' : '') : '' }}
                                checked>
                            <label class="form-check-label" for="create_user">Create User</label>
                        </div>
                        {!! $errors->first('create_user', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
                <div></div>
                <div class="col-md-6" id="passwordFields"
                    style="{{ isset($partner) ? ($partner->create_user ? '' : '') : '' }}">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                    class='bx bxs-lock'></i></span>
                            <input type="password" name="password" class="form-control" id="password" placeholder="Enter password"
                                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                        title="Password must be at least 8 characters long and include uppercase, lowercase, and a number." autofocus
                                required>
                                <div class="invalid-feedback" id="password-error">
                                    Password must be at least 8 characters long and include uppercase, lowercase, and a number.
                                </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <div class="input-group"> <span class="input-group-text bg-transparent"><i
                                    class='bx bxs-show'></i></span>
                            <input type="password" name="password_confirmation" class="form-control"
                                id="password_confirmation" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$"
                                        title="Password must be at least 8 characters long and include uppercase, lowercase, and a number."  autofocus
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
@include('layouts.partials.form-email&password-validation')

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
            } else if (partner_type.val() == 'employee') {
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
            if (!createUserCheckbox.is(':checked')) {
                createUserCheckbox.prop('checked', true);
                return false; // Prevent default behavior
            }

            // Toggle visibility of password fields based on checkbox state
            if (createUserCheckbox.is(':checked')) {
                createUserCheckbox.prop('checked', true);
                passwordFields.show();
                $('#password').prop('required', true);
                $('#password_confirmation').prop('required', true);
            } else {
                passwordFields.hide();
                $('#password').prop('required', false);
                $('#password_confirmation').prop('required', false);
            }
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

        $('#phone_no,#experience').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Limit to exactly 11 numbers
            // var elevenDigitValue = numericValue.slice(0, 11);
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#age').on('input', function() {
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
        $('#cnic').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });
        $('#akama').on('input', function() {
            var inputValue = $(this).val().trim();
            // Remove non-numeric characters
            var numericValue = inputValue.replace(/\D/g, '');
            // Update the input field value
            $(this).val(numericValue);
        });


        $('.select2').select2({
            width:'100%',
        });
        $('.select2-tags').select2({
            width: '100%',
            tags: true,
            ajax: {
                url: '{{ route('get-cities') }}',
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        q: params.term, // search term
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
            $('.select2-tags').trigger('select2:select', {
                data: {
                    id: '',
                    text: ''
                }
            });
        });

        // Set the selected city if editing
        var selectedCity = '{{ old('city', $partner->city ?? '') }}';
        if (selectedCity) {
            var newOption = new Option(selectedCity, selectedCity, true, true);
            $('#city').append(newOption).trigger('change');
        }
    })



</script>
<script>
    $(document).ready(function() {
        function formatCountry(option) {
            if (!option.id) {
                return option.text;
            }
            var iso = $(option.element).data('iso');
            var flag = $('<span><span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text +
                '</span>');
            return flag;
        }

        $('#prefix_phonecus').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            placeholder: "Code",
            width: '30%' // Adjust width as needed


        });

        $('#prefix_whatsappcus').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            placeholder: "Code",
            width: '30%' // Adjust width as needed


        });
          $('#prefix_emergency_contact1').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            placeholder: "Code",
            width: '30%' // Adjust width as needed


        });
          $('#prefix_emergency_contact2').select2({
            templateResult: formatCountry,
            templateSelection: formatCountry,
            placeholder: "Code",
            width: '30%' // Adjust width as needed


        });

    });
</script>




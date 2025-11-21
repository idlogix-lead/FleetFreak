<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            
            <div class="col-md-6">
                <div class="form-group">
                    <label for="partner_id">Partner</label>
                    <select name="partner_id" id="partner_id" class="form-control {{ $errors->has('partner_id') ? 'is-invalid' : '' }}">
                        <option value="">Select a Partner</option>
                        @php
                        $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                    @endphp
                        @foreach ($businessPartners as $name)
                            <option value="{{ $name->id }}" {{ ($partnerLocation->partner_id == $name->id) ? 'selected' : '' }}>
                                {{ $name->name }}
                            </option>
                        @endforeach
                    </select>
                    {!! $errors->first('partner_id', '<div class="invalid-feedback">:message</div>') !!}
                </div>
            </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address1">Address1</label>
                <input type="text" placeholder="Address1" name="address1" class="form-control {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{$partnerLocation->address1}}">
                {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="address2">Address2</label>
                <input type="text" placeholder="Address2" name="address2" class="form-control {{($errors->has('address2') ? ' is-invalid' : '')}}" id="address2" value="{{$partnerLocation->address2}}">
                {!! $errors->first('address2', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address3">Address3</label>
                <input type="text" placeholder="Address3" name="address3" class="form-control {{($errors->has('address3') ? ' is-invalid' : '')}}" id="address3" value="{{$partnerLocation->address3}}">
                {!! $errors->first('address3', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
        <div class="col-md-6">
            <div class="form-group">
                <label for="primary_contact_person">Primary Contact Person</label>
                <input type="text" placeholder="Primary Contact Person" name="primary_contact_person" class="form-control {{($errors->has('primary_contact_person') ? ' is-invalid' : '')}}" id="primary_contact_person" value="{{$partnerLocation->primary_contact_person}}">
                {!! $errors->first('primary_contact_person', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="secondary_contact_person">Secondary Contact Person</label>
                <input type="text" placeholder="Secondary Contact Person" name="secondary_contact_person" class="form-control {{($errors->has('secondary_contact_person') ? ' is-invalid' : '')}}" id="secondary_contact_person" value="{{$partnerLocation->secondary_contact_person}}">
                {!! $errors->first('secondary_contact_person', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_no">Phone No</label>
                <div class="input-group">
                     <!-- Country Code Dropdown -->
                     <select class="form-select" id="prefix_phonecus" name="prefix_phone">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                        <option value="{{ $country->phonecode }}" {{ (old('prefix_phone', $partnerLocation->prefix_phone ?? '+1') == $country->phonecode) ? 'selected' : '' }} data-iso="{{ strtolower($country->iso) }}">
                            {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_phone == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Phone No" name="phone_no" class="form-control {{($errors->has('phone_no') ? ' is-invalid' : '')}}" id="phone_no" value="{{old('phone_no',$partnerLocation->phone_no??'')}}" autofocus>
                {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
            </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="whatsapp_no">Whatsapp No</label>
                <div class="input-group">
                    <select class="form-select" id="prefix_whatsappcus" name="prefix_whatsapp">
                        @foreach(App\Models\CountryCode::phone_codes() as $country)
                        <option value="{{ $country->phonecode }}" {{ (old('prefix_whatsapp', $partnerLocation->prefix_whatsapp ?? '+1') == $country->phonecode) ? 'selected' : '' }} data-iso="{{ strtolower($country->iso) }}">
                            {{-- <option value="{{ $country->phonecode }}" {{ (isset($partner) && $partner->prefix_whatsapp == $country->phonecode) ? 'selected' : '' }}  data-iso="{{ strtolower($country->iso) }}"> --}}
                                +{{ $country->iso.'('.$country->phonecode.')' }}
                        @endforeach
                    </select>
                <input type="text" placeholder="Whatsapp No" name="whatsapp_no" class="form-control {{($errors->has('whatsapp_no') ? ' is-invalid' : '')}}" id="whatsapp_no" value="{{old('whatsapp_no',$partnerLocation->whatsapp_no??'')}}" autofocus required>
                {!! $errors->first('whatsapp_no', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus>
                    <option value="">-- Select Country --</option>
                    @foreach(App\Models\City::fetchCountry() as $country)
                        <option value="{{ $country }}" {{ (old('country') ?? $partnerLocation->country ?? '') == $country ? 'selected' : '' }}>
                            {{ $country }}
                        </option>
                    @endforeach
                </select>
                {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="city">City</label>
                <select name="city" class="form-control select2-tags {{ $errors->has('city') ? 'is-invalid' : '' }}" id="city" autofocus>
                    <option value="">-- Select City --</option>
                    @if(old('country') || isset($partnerLocation->country))
                        @foreach(App\Models\City::getCitiesByCountry(old('country') ?? $partnerLocation->country) as $city)
                            <option value="{{ $city->city }}" {{ (old('city') ?? $partnerLocation->city ?? '') == $city->city ? 'selected' : '' }}>
                                {{ $city->city }}
                            </option>
                        @endforeach
                    @endif
                </select>
                {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6 my-3 ">
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="ship_address" value='0'>
                    <input type="checkbox"  name="ship_address" value='1' class="form-check-input" id="ship_address" {{ isset($partnerLocation)?$partnerLocation->ship_address ? 'checked' : '': '' }} checked>
                    <label class="form-check-label" for="ship_address">Ship Address</label>
                </div>
                {!! $errors->first('ship_address', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="col-md-6 my-3 ">
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="invoice_address" value='0'>
                    <input type="checkbox"  name="invoice_address" value='1' class="form-check-input" id="invoice_address" {{ isset($partnerLocation)?$partnerLocation->invoice_address ? 'checked' : '': '' }} checked>
                    <label class="form-check-label" for="invoice_address">Invoice Address</label>
                </div>
                {!! $errors->first('invoice_address', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
        {{-- @php
        // Check if there's any partnerLocation that is marked as active against the id
        $isActiveDisabled = \App\Models\PartnerLocation::where('company_id',auth()->user()->active_company())->where('is_default', 1)->exists();
        @endphp

        <div class="col-md-6 my-4">
            <div class="form-group form-check">
                <input type="hidden" value="0" name="is_default" id="is_default_hidden">

                <input type="checkbox" value="1" name="is_default" class="form-check-input" id="is_default"
                    {{ old('is_default', $partnerLocation->is_default) ? 'checked' : '' }}
                    @if($isActiveDisabled && $partnerLocation->is_default== 0) disabled @endif>
                <label class="form-check-label" for="is_default">Is Default</label>
            </div>
        </div> --}}

        <div class="col-md-6 my-3 ">
            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="is_default" value='0'>
                    <input type="checkbox"  name="is_default" value='1' class="form-check-input" id="is_default" {{ isset($partnerLocation)?$partnerLocation->is_default ? 'checked' : '': '' }}>
                    <label class="form-check-label" for="is_default">Is Default</label>
                </div>
                {!! $errors->first('is_default', '<div class="invalid-feedback">:message</div>') !!}
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
    })
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

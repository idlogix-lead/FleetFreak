<head>
    <!-- Other head elements -->
    <link rel="stylesheet" href="{{ asset('assets/css/pending-rides.css') }}">
</head>

{{-- <style>
    .icon-row {
        margin-bottom: 1px;
        display: flex;
        align-items: center;
        justify-content: flex-start; /* or 'space-around' or 'space-evenly' */
    }
    .icon-container {
    color: black;
        display: flex;
        align-items: center;
        margin: 0 20px; /* Adjust spacing between icons */
        margin-bottom: 10px;
    }

    .icon-container svg {
        margin-right: 2px;
    }

    .custom-width {
        width: 120%; /* Or any other specific width like 75%, 400px, etc. */
    }
   .custom-icon-button {
        margin-right: 30px;
        border-radius: 50%; /* Make the button round */
        padding: 10px; /* Small padding to keep the button compact */

        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none; /* Remove default button border */
    }

    .icon-wrapper {
        color: black; /* Icon color */
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .btn-success{
        /* color: black !important; */
        border-color: #233abc !important;
        background-color: #233abc !important;
    }
    .btn-success:hover {
        background-color: #103463 !important;
    }
    .btn-primary{
        font-weight: 500 !important;
        color: black !important;
        border-color: #fbae42 !important;
        background-color: #fbae42 !important;
    }
    .btn-primary:hover {
        background-color: #f8a32c !important;
    }
    .toggle-fields {
        display: none; /* Hide fields by default */
    }
    /* .toggle-icon {
        cursor: pointer;
        font-size: 1.5em;
        color: #007bff;
        margin-left: 10px;
    } */
     .table{
        /* color: white; */
        background-color: #f5f5f5;
        border-radius: 5px;
     }

    table > tbody > tr > td {
        padding: 2px ;
        text-align: center;

    }
    table > tbody > tr > td:focus-within {
        /* Styles for the parent element when a child has focus */
        /* border: 2px solid #8833ff;  Example: add a blue border */

        /* background-color: #eee;  */
    }
    table > tbody > tr > td input,
    table > tbody > tr > td select,
    table > tbody > tr > td textarea
    {
        /* color:green; */
        /* padding: 0px !important; */

        /* margin: 0px !important; */
        /* border-color: transparent !important; */
        border-color: #6F788C !important;
        border-radius: 6px !important;

    }

    table > tbody > tr > td .btn {
        /* background-color:red !important; */
        border-color:transparent !important;



    }
    .input-group {
        display: flex;
        /* align-items: center; */
        flex-wrap: nowrap !important;

    }
    .input-group .btn {
        border: 0px;
        padding: 2px;
        margin: 5px;
    }
    .custom-card {
            width: 97%; /* Fixed width */

            margin: 0 auto 15px auto; /* Center the card horizontally */
            margin-top: 20px;
            /* background-color: #044b8d; */
            background-color: #fafafa;
            /* background-color: #d1d7dc; */
            padding: 15px; /* Padding to give a card-like appearance */
            /* border-radius: 8px;*/
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
            margin-bottom: 15px; /* Space between cards */
            margin-top: 0px; /* Space between cards */
        }
        .vehicle-card {
            width: 97%; /* Fixed width */

            margin: 0 auto 15px auto; /* Center the card horizontally */

            /* background-color: #044b8d; */
            background-color: #fbae42;
            /* background-color: #d1d7dc; */
            padding: 0px; /* Padding to give a card-like appearance */
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
            margin-bottom: 15px; /* Space between cards */
            margin-top: 5px; /* Space between cards */
        }
    .top-card {
        width: 97%; /* Fixed width */

        margin: 0 auto 15px auto; /* Center the card horizontally */
        margin-top: 20px;
        /* background-color: #044b8d; */
        background-color: #1A2E97;
        /* background-color: #d1d7dc; */
        padding: 15px; /* Padding to give a card-like appearance */
        border-top-left-radius: 8px; /* Rounded top-left corner */
        border-top-right-radius: 8px; /* Rounded top-right corner */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
        margin-bottom: 0px; /* Space between cards */
    }

    .form-group{
        color: #1A2E97;
        /* margin-right: 25px; */



    }
    .custom-card-ui{
        color: #737272;
        margin-right: 25px;
    }
    .custom-card .input-group {
        border-radius: 7px; /* Apply border radius to the entire input group */
        /* overflow: hidden; Ensure the border radius is applied uniformly */
    }
    .form-control{

        width: 90%;
        /* color: #6F788C; */
        color: black;
        border-radius: 0px;

    }
    .top-card-form-group{
        color: white !important;

    }
    .top-card-form-group .input-group {
        border-radius: 9px; /* Apply border radius to the entire input group */
        overflow: hidden; /* Ensure the border radius is applied uniformly */
    }
    .top-card-form-group .input-group-text{
        background-color: #FAD198;
        border-radius: 10px 0 0 10px;
    }
    .custom-card .input-group-text{
        background-color: #FAD198;
        border-radius: 10px 0 0 10px;
    } --}}
{{-- /* .custom-card-ui .form-control {
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        transition: box-shadow 0.3s ease-in-out;
    }

    .custom-card-ui .form-control:focus {
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
    } */


    /* .c_cols{

    } */

</style> --}}
{{-- model popup --}}
<!-- Modal -->

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-labelledby="addRowModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addRowModalLabel">Add Trip Details</h5>
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> --}}
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="startDate">Start Date</label>
                    <input type="date" id="startDate" min="{{ date('Y-m-d') }}" class="form-control" {{isset($edit) ? '': 'required'}} >
                </div>
                <div class="form-group">
                    <label for="endDate">End Date</label>
                    <input type="date" id="endDate" min="{{ date('Y-m-d') }}" class="form-control" {{isset($edit) ? '': 'required'}} >
                </div>
                <div class="form-group">
                    <label for="month_rate">Rate</label>
                    <input type="text" id="month_rate" class="form-control" {{isset($edit) ? '': 'required'}} >
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="confirmBtn">Confirm</button>
                <button type="button" class="btn btn-secondary" id="closebtn" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- -------------------- --}}
<div class="box box-info padding-1">
    <div class="box-body">
        <div class="row">
            {{-- top card --}}
            <div class="top-card">
                <div class="row">
                    <h3 style="color: white !important;"><span><svg width="23" height="23"
                                fill= "currentColor"xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd"
                                clip-rule="evenodd">
                                <path
                                    d="M13.403 24h-13.403v-22h3c1.231 0 2.181-1.084 3-2h8c.821.916 1.772 2 3 2h3v9.15c-.485-.098-.987-.15-1.5-.15l-.5.016v-7.016h-4l-2 2h-3.897l-2.103-2h-4v18h9.866c.397.751.919 1.427 1.537 2zm5.097-11c3.035 0 5.5 2.464 5.5 5.5s-2.465 5.5-5.5 5.5c-3.036 0-5.5-2.464-5.5-5.5s2.464-5.5 5.5-5.5zm0 2c1.931 0 3.5 1.568 3.5 3.5s-1.569 3.5-3.5 3.5c-1.932 0-3.5-1.568-3.5-3.5s1.568-3.5 3.5-3.5zm2.5 4h-3v-3h1v2h2v1zm-15.151-4.052l-1.049-.984-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.385zm6.151 1.052h-2v-1h2v1zm2-2h-4v-1h4v1zm-8.151-4.025l-1.049-.983-.8.823 1.864 1.776 3.136-3.192-.815-.808-2.336 2.384zm8.151 1.025h-4v-1h4v1zm0-2h-4v-1h4v1zm-5-6c0 .552.449 1 1 1 .553 0 1-.448 1-1s-.447-1-1-1c-.551 0-1 .448-1 1z" />
                            </svg>
                            @if (isset($edit))
                                Edit Your Ride
                            @else
                                Place Your Ride
                            @endif
                        </span></span></h3>
                    <p style="color: #d0cccc;"><span>Effortlessly Manage Your Rides With Our Intuitive Rides Page</span>
                    </p>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="company_id">Company</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-notepad  pending-rides-icon'></i></span>
                                <input type="text" readonly placeholder="Order No" name="company_id"
                                    class="form-control {{ $errors->has('company_id') ? ' is-invalid' : '' }}"
                                    id="company_id"
                                    value="{{auth()->user()->active_company_details()->name}}">
                                {!! $errors->first('company_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="trip_type">Trip Type <span style="color: red;">*</span></label>
                            <div class="input-group input-group-sm"> <span class="input-group-text "><i
                                        class='bx bxs-car pending-rides-icon'></i></span>
                                <select name="trip_type" id="trip_type"
                                    class="form-control{{ $errors->has('trip_type') ? ' is-invalid' : '' }} ">
                                    <option value="">Select Trip Type
                                    </option>
                                    <option  selected value="monthly_booking"
                                        {{ $order->trip_type === 'monthly_booking' ? 'selected' : '' }}>Monthly Booking
                                    </option>
                                    {{-- <option value="cargo_trip"
                                        {{ $order->trip_type === 'cargo_trip' ? 'selected' : '' }}>Cargo Trip
                                    </option> --}}
                                </select>
                            </div>
                            {!! $errors->first('trip_type', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                        <input type="hidden" id="tripTypeHidden" name="trip_type" />
                    </div>

                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="vehicle_class_id">Vehicle Model <span style="color: red;">*</span></label>
                            <div class="input-group input-group-sm"> <span class="input-group-text "><i
                                        class='bx bxs-car pending-rides-icon'></i></span>
                                <select name="vehicle_class_id" id="vehicle_class_id"
                                    class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}">
                                    <option value="">Select Vehicle Model</option>
                                    {{-- <select name="vehicle_class_id" id="vehicle_class_id"
                                    class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}">
                                    <option value="">Select Vehicle Model</option> --}}

                                    {{-- @foreach (App\Models\Vehicle::VehicleDropdownOrder()->unique('vehicleModel.name') as $v_class)
                                <option value="{{ $v_class->vehicleClass->id }}"
                                        data-seats-allow="{{ $v_class->vehicleClass->seats_allow }}"
                                        data-bags-allow="{{ $v_class->vehicleClass->bags_allow }}"

                                        {{$order->vehicle_class_id == $v_class->vehicleClass->id ? 'selected' : ''}}>
                                    {{ $v_class->vehicleModel->name??null }}
                                </option>
                            @endforeach --}}
                                    @foreach (App\Models\Vehicle::VehicleDropdownOrder()->unique('vehicleModel.name') as $v_class)
                                        <option value="{{ $v_class->vehicleModel->vehicleClass->id }}"
                                            data-seats-allow="{{ $v_class->vehicleModel->vehicleClass->seats_allow }}"
                                            data-bags-allow="{{ $v_class->vehicleModel->vehicleClass->bags_allow }}"
                                            data-model_no="{{ $v_class->vehicle_model_id }}"
                                            {{ $order->vehicle_class_id == $v_class->vehicleModel->vehicleClass->id ? 'selected' : '' }}>
                                            {{ $v_class->vehicleModel->name ?? null }}
                                        </option>
                                    @endforeach

                                </select>
                                <input type="hidden" name="vehicle_model_id" id="vehicle_model_id" value="">
                            </div>
                            {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>

                    {{-- <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="vehicle_class_id">Vehicle Model <span style="color: red;">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">
                                    <i class='bx bxs-car pending-rides-icon'></i>
                                </span>
                                <select name="vehicle_class_id" id="vehicle_class_id"
                                    class="form-control{{ $errors->has('vehicle_class_id') ? ' is-invalid' : '' }}">
                                    <option value="">Select Vehicle Model</option>
                                    @foreach ($vehicleModels as $vehicleModel)
                                        <option value="{{ $vehicleModel->vehicleClass->id }}"
                                            data-seats-allow="{{ $vehicleModel->vehicleClass->seats_allow }}"
                                            data-bags-allow="{{ $vehicleModel->vehicleClass->bags_allow }}"
                                            data-model_no="{{ $vehicleModel->id }}"
                                            {{ $order->vehicle_class_id == $vehicleModel->vehicleClass->id ? 'selected' : '' }}>
                                            {{ $vehicleModel->name }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('vehicle_class_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div> --}}

                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="business_partner_order_id">Built To</label>
                            <div class="input-group input-group-sm ">
                                <span class="input-group-text "><i class='bx bxs-group  pending-rides-icon'></i></span>
                                <select {{ auth()->user()->actor_id != 2 ? 'disabled' : '' }}
                                    name="business_partner_select" id="business_partner_order_id"
                                    class="form-control{{ $errors->has('business_partner_order_id') ? ' is-invalid' : '' }}">
                                    <option value="">-- Select --</option>
                                    @php
                                        $businessPartners = App\Models\Partner::BusinessPartnerDropdown();
                                        $count = $businessPartners->count();
                                    @endphp
                                    @foreach ($businessPartners as $business_partner)
                                        <option value="{{ $business_partner->id }}"
                                            {{ $order->business_partner_id == $business_partner->id ? 'selected' : '' }}>
                                            {{ Str::title($business_partner->company_name??$business_partner->name) }}</option>
                                    @endforeach
                                    @if ($count == 1)
                                        <script>
                                            // Automatically select the only option if there's only one business partner
                                            document.getElementById('business_partner_order_id').selectedIndex = 1;
                                        </script>
                                    @endif
                                </select>
                            </div>

                            <!-- Hidden input to send the selected value -->
                            <input type="hidden" name="business_partner_id" id="hidden_business_partner_id"
                                value="{{ $order->business_partner_id }}">

                            {!! $errors->first('business_partner_order_id', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>


                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="booking_amount">Booking Amount </label>
                            <div class="input-group input-group-sm"> <span
                                    class="input-group-text  pending-rides-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z">
                                        </path>
                                        <path
                                            d="M12 11c-2 0-2-.63-2-1s.7-1 2-1 1.39.64 1.4 1h2A3 3 0 0 0 13 7.12V6h-2v1.09C9 7.42 8 8.71 8 10c0 1.12.52 3 4 3 2 0 2 .68 2 1s-.62 1-2 1c-1.84 0-2-.86-2-1H8c0 .92.66 2.55 3 2.92V18h2v-1.08c2-.34 3-1.63 3-2.92 0-1.12-.52-3-4-3z">
                                        </path>
                                    </svg></span>
                                <input type="text" readonly value="{{ $order->booking_amount }}"
                                    name="booking_amount"
                                    class="form-control {{ $errors->has('booking_amount') ? ' is-invalid' : '' }}"
                                    id="booking_amount" autofocus>
                                {!! $errors->first('booking_amount', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="final_amount">Final Amount </label>
                            <div class="input-group input-group-sm"> <span
                                    class="input-group-text  pending-rides-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z">
                                        </path>
                                        <path
                                            d="M12 11c-2 0-2-.63-2-1s.7-1 2-1 1.39.64 1.4 1h2A3 3 0 0 0 13 7.12V6h-2v1.09C9 7.42 8 8.71 8 10c0 1.12.52 3 4 3 2 0 2 .68 2 1s-.62 1-2 1c-1.84 0-2-.86-2-1H8c0 .92.66 2.55 3 2.92V18h2v-1.08c2-.34 3-1.63 3-2.92 0-1.12-.52-3-4-3z">
                                        </path>
                                    </svg></span>
                                <input type="text" readonly value="{{ $order->final_amount }}"
                                    name="final_amount"
                                    class="form-control {{ $errors->has('final_amount') ? ' is-invalid' : '' }}"
                                    id="final_amount" autofocus>
                                {!! $errors->first('final_amount', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3">
                        <div class="form-group top-card-form-group">
                            <label for="order_no">Order No</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-notepad  pending-rides-icon'></i></span>
                                <input type="text" readonly placeholder="Order No" name="order_no"
                                    class="form-control {{ $errors->has('order_no') ? ' is-invalid' : '' }}"
                                    id="order_no"
                                    value="{{ isset($nextOrderNo) ? $nextOrderNo : $order->order_no }}">
                                {!! $errors->first('order_no', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3 hide">
                        <div class="form-group top-card-form-group">
                            <label for="overall_adult">Adult</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-group  pending-rides-icon'></i></span>
                                <input type="text" placeholder="Add Adults" name="overall_adult"
                                    class="form-control {{ $errors->has('overall_adult') ? ' is-invalid' : '' }}"
                                    id="overall_adult" value="{{ old('overall_adult', $order->overall_adult) }}"
                                    autofocus required>
                                {!! $errors->first('overall_adult', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3 hide">
                        <div class="form-group top-card-form-group">
                            <label for="overall_child">Child</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-user  pending-rides-icon'></i></span>
                                <input type="text" placeholder="Add Childs" name="overall_child"
                                    class="form-control {{ $errors->has('overall_child') ? ' is-invalid' : '' }}"
                                    id="overall_child" value="{{ old('overall_child', $order->overall_child) }}"
                                    autofocus required>
                                {!! $errors->first('overall_child', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3 hide">
                        <div class="form-group top-card-form-group">
                            <label for="overall_bags">Bags</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-briefcase  pending-rides-icon'></i></span>
                                <input type="text" placeholder="Add Bags" name="overall_bags"
                                    class="form-control {{ $errors->has('overall_bags') ? ' is-invalid' : '' }}"
                                    id="overall_bags" value="{{ old('overall_bags', $order->overall_bags) }}"
                                    autofocus required>
                                {!! $errors->first('overall_bags', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3 hide">
                        <div class="form-group top-card-form-group">
                            <label for="direction">Direction <span style="color: red;">*</span></label>
                            <div class="input-group input-group-sm"> <span class="input-group-text "><i
                                        class='bx bxs-car pending-rides-icon'></i></span>
                                <select name="direction" id="direction"
                                    class="form-control{{ $errors->has('direction') ? ' is-invalid' : '' }} ">
                                    <option value="oneway" {{ $order->direction === 'oneway' ? 'selected' : '' }}>One
                                        way
                                    </option>
                                    <option value="return" {{ $order->direction === 'return' ? 'selected' : '' }}>
                                        Return
                                    </option>
                                </select>
                            </div>
                            {!! $errors->first('direction', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>
                    <div class="c_cols col-sm-6 col-md-4 col-lg-3 hide">
                        <div class="form-group top-card-form-group">
                            <label for="description">Description</label>
                            <div class="input-group  input-group-sm"> <span class="input-group-text"><i
                                        class='bx bx-briefcase  pending-rides-icon'></i></span>
                                <input type="text" placeholder="Add Description" name="description"
                                    class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}"
                                    id="description" value="{{ old('description', $order->description) }}" autofocus>
                                {!! $errors->first('description', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="vehicle-card">
                {{-- vehicle class details icon --}}
                {{-- <h3 style="color: white; font-size:20px; margin-left:20px; margin-bottom:0px; ">Facilities</h3> --}}
                <p style="color:#895486 ; margin-left:20px; "><span><i><b>--Maximum Limit--</b></i></span></p>
                <div class="icon-row" style="margin-top: -10px;">
                    {{-- <div class="icon-container">
                        <span> --}}
                    {{-- <h5 style="background-color: #6F788C; border-radius:10px; padding: 5px; color:white;">Adults:{{ $order->vehicle_class_id->seats_allow ?? '0' }}</h5> --}}
                    {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 256 256" xml:space="preserve">

                                <defs>
                                </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                    <path d="M 83.239 64.768 l -6.275 -3.623 l 4.364 -1.301 c 1.059 -0.315 1.661 -1.43 1.346 -2.488 c -0.315 -1.06 -1.433 -1.661 -2.488 -1.346 l -7.99 2.382 l -7.192 -4.152 l 12.723 -3.791 c 1.059 -0.315 1.661 -1.43 1.346 -2.488 c -0.316 -1.06 -1.432 -1.66 -2.488 -1.346 l -16.349 4.871 L 49 45 l 11.234 -6.486 l 16.349 4.872 c 0.19 0.057 0.383 0.084 0.572 0.084 c 0.861 0 1.657 -0.562 1.916 -1.429 c 0.315 -1.059 -0.287 -2.172 -1.346 -2.488 l -12.724 -3.791 l 7.193 -4.153 l 7.991 2.381 c 0.19 0.057 0.383 0.084 0.572 0.084 c 0.861 0 1.657 -0.562 1.916 -1.429 c 0.315 -1.059 -0.287 -2.172 -1.346 -2.488 l -4.365 -1.301 l 6.276 -3.624 c 0.957 -0.552 1.284 -1.775 0.732 -2.732 c -0.554 -0.957 -1.777 -1.287 -2.732 -0.732 l -6.276 3.624 l 1.056 -4.431 c 0.256 -1.074 -0.407 -2.153 -1.481 -2.409 c -1.07 -0.254 -2.152 0.407 -2.409 1.481 l -1.934 8.111 l -7.193 4.153 l 3.078 -12.915 c 0.256 -1.075 -0.407 -2.153 -1.481 -2.409 c -1.07 -0.253 -2.152 0.407 -2.409 1.481 L 58.234 35.05 L 47 41.536 V 28.564 l 12.394 -11.723 c 0.803 -0.759 0.838 -2.025 0.079 -2.828 c -0.76 -0.802 -2.026 -0.837 -2.827 -0.079 L 47 23.058 v -8.305 l 6.058 -5.73 c 0.803 -0.759 0.838 -2.025 0.079 -2.828 c -0.76 -0.802 -2.024 -0.838 -2.827 -0.079 L 47 9.247 V 2 c 0 -1.104 -0.896 -2 -2 -2 s -2 0.896 -2 2 v 7.247 l -3.309 -3.13 c -0.802 -0.759 -2.068 -0.722 -2.828 0.079 c -0.759 0.803 -0.724 2.068 0.079 2.828 L 43 14.753 v 8.305 l -9.645 -9.124 c -0.802 -0.758 -2.069 -0.723 -2.828 0.079 c -0.759 0.803 -0.724 2.068 0.079 2.828 L 43 28.565 v 12.971 L 31.766 35.05 L 27.81 18.455 c -0.256 -1.074 -1.333 -1.737 -2.409 -1.481 c -1.074 0.256 -1.738 1.334 -1.481 2.409 l 3.079 12.915 l -7.193 -4.153 l -1.933 -8.111 c -0.256 -1.074 -1.333 -1.735 -2.409 -1.481 c -1.074 0.256 -1.738 1.334 -1.481 2.409 l 1.056 4.431 l -6.276 -3.624 c -0.957 -0.554 -2.18 -0.225 -2.732 0.732 c -0.552 0.957 -0.225 2.18 0.732 2.732 l 6.276 3.624 l -4.365 1.301 c -1.059 0.315 -1.661 1.429 -1.345 2.488 c 0.258 0.868 1.054 1.429 1.916 1.429 c 0.189 0 0.382 -0.027 0.572 -0.084 l 7.991 -2.381 l 7.193 4.153 l -12.724 3.791 c -1.059 0.315 -1.661 1.429 -1.345 2.488 c 0.258 0.868 1.054 1.429 1.916 1.429 c 0.189 0 0.382 -0.027 0.572 -0.084 l 16.349 -4.872 L 41 45 l -11.233 6.486 l -16.35 -4.871 c -1.059 -0.314 -2.173 0.286 -2.488 1.346 c -0.315 1.059 0.287 2.173 1.345 2.488 l 12.723 3.791 l -7.193 4.153 L 9.814 56.01 c -1.058 -0.315 -2.172 0.286 -2.488 1.346 c -0.316 1.059 0.287 2.173 1.345 2.488 l 4.365 1.301 l -6.275 3.623 C 5.804 65.32 5.477 66.543 6.029 67.5 c 0.37 0.642 1.042 1 1.734 1 c 0.339 0 0.683 -0.086 0.998 -0.268 l 6.276 -3.624 l -1.056 4.43 c -0.256 1.074 0.407 2.153 1.481 2.409 c 0.156 0.037 0.312 0.055 0.465 0.055 c 0.905 0 1.725 -0.618 1.944 -1.536 l 1.933 -8.111 l 7.193 -4.153 l -3.079 12.914 c -0.256 1.074 0.407 2.153 1.481 2.409 c 0.156 0.037 0.312 0.055 0.465 0.055 c 0.905 0 1.725 -0.618 1.944 -1.536 l 3.956 -16.595 L 43 48.464 v 12.971 L 30.606 73.159 c -0.802 0.759 -0.837 2.025 -0.079 2.827 c 0.394 0.416 0.923 0.626 1.454 0.626 c 0.493 0 0.987 -0.182 1.374 -0.547 L 43 66.942 v 8.305 l -6.058 5.731 c -0.802 0.759 -0.837 2.025 -0.079 2.827 c 0.394 0.416 0.923 0.626 1.454 0.626 c 0.493 0 0.987 -0.182 1.374 -0.547 L 43 80.753 V 88 c 0 1.104 0.896 2 2 2 s 2 -0.896 2 -2 v -7.247 l 3.31 3.13 c 0.387 0.365 0.881 0.547 1.374 0.547 c 0.53 0 1.06 -0.21 1.453 -0.626 c 0.759 -0.802 0.724 -2.068 -0.079 -2.827 L 47 75.247 v -8.306 l 9.646 9.124 c 0.387 0.365 0.881 0.547 1.374 0.547 c 0.53 0 1.06 -0.21 1.453 -0.626 c 0.759 -0.802 0.724 -2.068 -0.079 -2.827 L 47 61.436 V 48.464 l 11.234 6.486 l 3.956 16.595 c 0.219 0.918 1.039 1.536 1.943 1.536 c 0.154 0 0.31 -0.018 0.466 -0.055 c 1.074 -0.256 1.737 -1.335 1.481 -2.409 l -3.078 -12.914 l 7.193 4.153 l 1.934 8.111 c 0.219 0.919 1.039 1.536 1.943 1.536 c 0.154 0 0.31 -0.018 0.466 -0.055 c 1.074 -0.256 1.738 -1.335 1.481 -2.409 l -1.056 -4.43 l 6.276 3.624 c 0.315 0.182 0.659 0.268 0.998 0.268 c 0.691 0 1.363 -0.358 1.734 -1 C 84.523 66.543 84.196 65.32 83.239 64.768 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                </g>
                                </svg>
                            </span>: on
                    </div> --}}
                    <div class="icon-container">
                        <span id="seats-icon" class="seat-icons">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 256 256" xml:space="preserve">

                                <defs>
                                </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                    <path d="M 67.046 41.433 h -2.871 V 5.022 C 64.175 2.253 61.921 0 59.152 0 H 30.847 c -2.769 0 -5.022 2.253 -5.022 5.022 v 36.411 h -2.871 c -3.028 0 -5.491 2.463 -5.491 5.491 v 1.956 c 0 3.028 2.463 5.491 5.491 5.491 h 18.15 v 23.669 H 28.219 c -0.095 0 -0.188 0.006 -0.289 0.017 c -0.091 -0.011 -0.182 -0.017 -0.276 -0.017 c -3.298 0 -5.98 2.683 -5.98 5.98 c 0 3.297 2.683 5.98 5.98 5.98 c 2.629 0 4.904 -1.746 5.678 -4.169 h 23.334 C 57.442 88.254 59.716 90 62.345 90 c 3.297 0 5.98 -2.683 5.98 -5.98 c 0 -3.297 -2.683 -5.98 -5.98 -5.98 c -0.091 0 -0.18 0.007 -0.271 0.017 c -0.097 -0.011 -0.194 -0.017 -0.294 -0.017 H 48.896 V 54.37 h 18.15 c 3.028 0 5.492 -2.463 5.492 -5.491 v -1.956 C 72.538 43.896 70.074 41.433 67.046 41.433 z M 61.746 81.005 l 0.263 0.035 l 0.144 -0.009 c 0.071 -0.004 0.142 -0.013 0.237 -0.025 c 1.642 0.024 2.97 1.367 2.97 3.013 c 0 1.661 -1.352 3.013 -3.013 3.013 c -1.559 0 -2.869 -1.228 -2.984 -2.795 l -0.101 -1.374 H 30.74 l -0.101 1.375 c -0.114 1.567 -1.426 2.794 -2.984 2.794 c -1.662 0 -3.013 -1.352 -3.013 -3.013 c 0 -1.661 1.351 -3.012 2.974 -3.012 c 0.001 0 0.001 0 0.002 0 c 0.077 0.011 0.153 0.019 0.231 0.024 l 16.223 -0.025 V 54.37 h 1.858 v 26.636 L 61.746 81.005 z M 69.571 48.88 c 0 1.392 -1.133 2.524 -2.525 2.524 h -18.15 h -7.792 h -18.15 c -1.392 0 -2.524 -1.132 -2.524 -2.524 v -1.956 c 0 -1.392 1.132 -2.524 2.524 -2.524 h 5.839 V 5.022 c 0 -1.133 0.922 -2.055 2.055 -2.055 h 28.305 c 1.133 0 2.055 0.922 2.055 2.055 V 44.4 h 5.838 c 1.392 0 2.525 1.132 2.525 2.524 V 48.88 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                    <rect x="33.15" y="9.91" rx="0" ry="0" width="23.7" height="2.97" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) "/>
                                    <rect x="33.15" y="17.83" rx="0" ry="0" width="23.7" height="2.97" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) "/>
                                    <rect x="33.15" y="25.74" rx="0" ry="0" width="23.7" height="2.97" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) "/>
                                    <rect x="33.15" y="34.64" rx="0" ry="0" width="23.7" height="2.97" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) "/>
                                </g>
                                </svg>:  --}}
                            {{-- <p  style="background-color: #6F788C; border-radius:10px; padding: 5px; color:white;">Adults <h6 style="background-color: #6F788C; border-radius:10px; padding: 5px; color:white;" id="seats-allow"> {{ $order->vehicle_class_id->seats_allow ?? '0' }}</h6></p> --}}
                            Adults: <span class="no-of-seats"
                                id="seats-allow">{{ $order->vehicle_class_id->seats_allow ?? '0' }}</span>
                        </span>
                    </div>

                    <div class="icon-container">
                        <span class="seat-icons">
                            {{-- <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 256 256" xml:space="preserve">

                                <defs>
                                </defs>
                                <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                    <path d="M 71.796 23.427 H 61.343 v -10.49 C 61.343 5.804 55.699 0 48.762 0 h -7.523 c -6.937 0 -12.581 5.804 -12.581 12.937 v 10.49 H 18.204 c -1.933 0 -3.5 1.567 -3.5 3.5 v 47.037 C 14.704 82.807 21.897 90 30.74 90 h 28.52 c 8.843 0 16.036 -7.193 16.036 -16.036 V 26.927 C 75.296 24.994 73.729 23.427 71.796 23.427 z M 35.657 12.937 C 35.657 9.664 38.161 7 41.238 7 h 7.523 c 3.077 0 5.581 2.664 5.581 5.937 v 10.49 H 35.657 V 12.937 z M 68.296 73.964 c 0 4.982 -4.054 9.036 -9.036 9.036 H 30.74 c -4.983 0 -9.037 -4.054 -9.037 -9.036 V 30.427 h 6.954 v 6.018 c 0 1.933 1.567 3.5 3.5 3.5 s 3.5 -1.567 3.5 -3.5 v -6.018 h 18.686 v 6.018 c 0 1.933 1.567 3.5 3.5 3.5 s 3.5 -1.567 3.5 -3.5 v -6.018 h 6.953 V 73.964 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                                </g>
                                </svg>:  --}}
                            Bags: <span class="no-of-seats"
                                id="bags-allow">{{ $order->vehicle_class_id->bags_allow ?? '0' }}</span>

                        </span>
                    </div>
                </div>
            </div>
            {{-- CUSTOMER CARD --}}
            <div class="custom-card">

                <h3 style="color: #1A2E97; font-size:20px; margin-left:20px; margin-top:5px;">Add Your Basic
                    Information
                    <button type="button" class="btn btn-sm float-end btn-primary custom-icon-button"
                        id="toggleIcon" title="Add Details">
                        <span class="icon-wrapper" style="color: white !important">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-plus">
                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                            </svg>
                        </span>
                    </button>
                </h3>
                <p style="color: #OB1653; margin-left:20px; "><span><i><b>Use This Personalized Guide To Get Your
                                Rides</b></i></span></p>

                <div class="row">
                    <div class="col-md-3 col-lg-3 col-sm-6 mx-3">
                        <div class="form-group ">
                            <label for="customer_partner_id">Customer Name </label>
                            <div class="input-group input-group-sm custom-width "> <span
                                    class="input-group-text  pending-rides-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a5 5 0 1 0 5 5 5 5 0 0 0-5-5zm0 8a3 3 0 1 1 3-3 3 3 0 0 1-3 3zm9 11v-1a7 7 0 0 0-7-7h-4a7 7 0 0 0-7 7v1h2v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1z">
                                        </path>
                                    </svg></span>
                                <select name="customer_partner_id" id="customer_partner_id"
                                    class="form-control select2 {{ $errors->has('customer_partner_id') ? ' is-invalid' : '' }}">
                                    <option value="">-- Select --</option>
                                    @if ($order->customer_partner_id)
                                        @foreach (App\Models\Partner::CustomerPartnerDropdown() as $customer_partner)
                                            <option value="{{ $customer_partner->id }}"
                                                {{ $order->customer_partner_id == $customer_partner->id ? 'selected' : '' }}>
                                                {{ Str::title($customer_partner->name) }}</option>
                                        @endforeach
                                    @endif

                                </select>
                                {!! $errors->first('customer_partner_id', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-6 mx-5 ">
                        <div class="form-group">
                            <label for="whatsapp_no">Whatsapp No </label>
                            <div class="input-group input-group-sm custom-width">
                                <!-- Country Code Dropdown -->
                                <select disabled class="form-select" id="prefix_whatsapp" name="prefix_whatsapp">
                                    @foreach (App\Models\CountryCode::phone_codes() as $country)
                                        <option value="{{ $country->phonecode }}"
                                            {{ old('prefix_whatsapp', $order->partner_customer->prefix_whatsapp ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                            data-iso="{{ strtolower($country->iso) }}">
                                            +{{ $country->iso . '(' . $country->phonecode . ')' }}
                                    @endforeach
                                </select>
                                <input readonly type="text"
                                    value="{{ $order->partner_customer->whatsapp_no ?? '' }}"
                                    placeholder=" (555) 555-1234" name="whatsapp_no"
                                    class="form-control {{ $errors->has('whatsapp_no') ? ' is-invalid' : '' }}"
                                    id="whatsapp_no" autofocus>
                                {!! $errors->first('whatsapp_no', '<div class="invalid-feedback">:message</div>') !!}
                            </div>

                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-6 mx-3">
                        <div class="form-group">
                            <label for="email">Email </label>
                            <div class="input-group input-group-sm custom-width"> <span
                                    class="input-group-text   pending-rides-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path
                                            d="M20 4H4c-1.103 0-2 .897-2 2v12c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V6c0-1.103-.897-2-2-2zm0 2v.511l-8 6.223-8-6.222V6h16zM4 18V9.044l7.386 5.745a.994.994 0 0 0 1.228 0L20 9.044 20.002 18H4z">
                                        </path>
                                    </svg></span>
                                <input readonly type="text" placeholder="ali123@gmail.com" name="email"
                                    class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                    id="email" value="{{ old('email', $order->partner_customer->email ?? '') }}"
                                    autofocus>
                                {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-6 my-3 mx-3 toggle-fields" id = "passport_id">
                        <div class="form-group ">
                            <label for="passport">Passport </label>
                            <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><i
                                        class='bx bxs-user-circle  pending-rides-icon'></i></span>
                                <input readonly type="text" placeholder=" C1234567" name="passport"
                                    class="form-control {{ $errors->has('passport') ? ' is-invalid' : '' }}"
                                    id="passport"
                                    value="{{ old('passport', $order->partner_customer->passport ?? '') }}" autofocus>
                                {!! $errors->first('passport', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-6 my-3 mx-5 toggle-fields">
                        <div class="form-group">
                            <label for="phone_no">Phone No</label>
                            <div style="border-radius: 0px;" class="input-group input-group-sm custom-width">
                                <!-- Country Code Dropdown -->
                                <select disabled class="form-select" id="prefix_phone" name="prefix_phone">
                                    @foreach (App\Models\CountryCode::phone_codes() as $country)
                                        <option value="{{ $country->phonecode }}"
                                            {{ old('prefix_phone', $order->partner_customer->prefix_phone ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                            data-iso="{{ strtolower($country->iso) }}">
                                            +{{ $country->iso . '(' . $country->phonecode . ')' }}
                                    @endforeach
                                </select>
                                <!-- Phone Number Input -->
                                <input readonly type="text" placeholder=" (555) 555-1234" name="phone_no"
                                    class="form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                    id="phone_no"
                                    value="{{ old('phone_no', $order->partner_customer->phone_no ?? '') }}" autofocus>
                                {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 my-3 mx-3 toggle-fields">
                    <div class="form-group ">
                        <label for="phone_no">Phone No</label>
                        <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" style="fill: rgba(0, 0, 0, 1);transform: ;msFilter:;"><path d="M17.707 12.293a.999.999 0 0 0-1.414 0l-1.594 1.594c-.739-.22-2.118-.72-2.992-1.594s-1.374-2.253-1.594-2.992l1.594-1.594a.999.999 0 0 0 0-1.414l-4-4a.999.999 0 0 0-1.414 0L3.581 5.005c-.38.38-.594.902-.586 1.435.023 1.424.4 6.37 4.298 10.268s8.844 4.274 10.269 4.298h.028c.528 0 1.027-.208 1.405-.586l2.712-2.712a.999.999 0 0 0 0-1.414l-4-4.001zm-.127 6.712c-1.248-.021-5.518-.356-8.873-3.712-3.366-3.366-3.692-7.651-3.712-8.874L7 4.414 9.586 7 8.293 8.293a1 1 0 0 0-.272.912c.024.115.611 2.842 2.271 4.502s4.387 2.247 4.502 2.271a.991.991 0 0 0 .912-.271L17 14.414 19.586 17l-2.006 2.005z"></path></svg></span>
                        <input type="text" placeholder="Phone No" name="phone_no" class="form-control {{($errors->has('phone_no') ? ' is-invalid' : '')}}" id="phone_no" value="{{old('phone_no',$order->partner_customer->phone_no??'')}}" autofocus>
                        {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div> --}}
                    <div class="col-md-3 col-lg-3 col-sm-6 my-3 mx-3 toggle-fields">
                        <div class="form-group ">
                            <label for="cnic">Cnic</label>
                            <div class="input-group input-group-sm custom-width"> <span
                                    class="input-group-text pending-rides-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 24 24">
                                        <path d="M9.715 12c1.151 0 2-.849 2-2s-.849-2-2-2-2 .849-2 2 .848 2 2 2z">
                                        </path>
                                        <path
                                            d="M20 4H4c-1.103 0-2 .841-2 1.875v12.25C2 19.159 2.897 20 4 20h16c1.103 0 2-.841 2-1.875V5.875C22 4.841 21.103 4 20 4zm0 14-16-.011V6l16 .011V18z">
                                        </path>
                                        <path
                                            d="M14 9h4v2h-4zm1 4h3v2h-3zm-1.57 2.536c0-1.374-1.676-2.786-3.715-2.786S6 14.162 6 15.536V16h7.43v-.464z">
                                        </path>
                                    </svg></span>
                                <input readonly type="text" placeholder="123456789" name="cnic"
                                    class="form-control {{ $errors->has('cnic') ? ' is-invalid' : '' }}"
                                    id="cnic" value="{{ old('cnic', $order->partner_customer->cnic ?? '') }}"
                                    autofocus>
                                {!! $errors->first('cnic', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-lg-3 col-sm-6 mx-3 toggle-fields">
                        <div class="form-group ">
                            <label for="address1">Address1</label>
                            <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><i
                                        class='bx bxs-map  pending-rides-icon'></i></span>
                                <input readonly type="text" placeholder="New York" name="address1"
                                    class="form-control {{ $errors->has('address1') ? ' is-invalid' : '' }}"
                                    id="address1"
                                    value="{{ old('address1', $order->partner_customer->address1 ?? '') }}" autofocus>
                                {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-3 mx-3 toggle-fields">
                    <div class="form-group ">
                        <label for="country">Country</label>
                        <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><i class="fas fa-globe"></i></span>
                        <select name="country" class="form-control select2-country {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus>
                            <option value="">-- Select Country --</option>
                            @foreach (App\Models\City::fetchCountry() as $country)
                                <option value="{{ $country }}" {{ (old('country') ?? $order->partner_customer->country ?? '') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div> --}}
                    <div class="col-md-3 col-lg-3 col-sm-6 mx-5 toggle-fields">
                        <div class="form-group ">
                            <label for="country">Country</label>
                            <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><i
                                        class="fas fa-globe  pending-rides-icon"></i></span>

                                <select disabled name="country"
                                    class="form-control select2-country {{ $errors->has('country') ? 'is-invalid' : '' }}"
                                    id="country1" autofocus>
                                    <option value="">-- Select Country --</option>
                                    @foreach (App\Models\City::fetchCountry() as $country)
                                        <option value="{{ $country }}"
                                            {{ (old('country') ?? ($order->partner_customer->country ?? '')) == $country ? 'selected' : '' }}>
                                            {{ $country }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>

                    <div class="col-md-3 col-lg-3 col-sm-6 mx-3 toggle-fields">
                        <div class="form-group">
                            <label for="city">City</label>
                            <div class="input-group input-group-sm custom-width"> <span class="input-group-text "><i
                                        class="fas fa-globe  pending-rides-icon"></i></span>
                                <select disabled name="city"
                                    class="form-control select2-tags {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                    id="city1" autofocus>
                                    <option value="">-- Select City --</option>
                                    @if (old('country') || isset($order->partner_customer->country))
                                        @foreach (App\Models\City::getCitiesByCountry(old('country') ?? $order->partner_customer->country) as $city)
                                            <option value="{{ $city->city }}"
                                                {{ (old('city') ?? ($order->partner_customer->city ?? '')) == $city->city ? 'selected' : '' }}>
                                                {{ $city->city }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                        </div>
                    </div>
                    {{-- <div class="col-md-3 mx-3 toggle-fields">
                    <div class="form-group ">
                        <label for="city">City</label>
                        <div class="input-group input-group-sm custom-width"> <span class="input-group-text"><i class='bx bxs-map'></i></span>
                            <input type="text" placeholder="City" name="city" class="form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{old('city',$order->partner_customer->city??'')}}" autofocus>

                        {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div> --}}


                </div>
            </div>



            {{-- Hidden fields to store the fetched values --}}
            {{-- dynamic select classes code line below --}}
            <input type="hidden" id="seats_allow_hidden"
                value="{{ $order->vehicle_class ? $order->vehicle_class->seats_allow : '' }}" />
            <input type="hidden" id="bags_allow_hidden"
                value="{{ $order->vehicle_class ? $order->vehicle_class->bags_allow : '' }}" />
            {{-- <input type="hidden" id="seats_allow_hidden" value="{{ 4 }}" />
        <input type="hidden" id="bags_allow_hidden" value="{{ 3 }}" /> --}}
            {{--  --}}
            {{-- <div class="col-md-4" id="customerDropdownContainer">
            <div class="form-group">
                <label for="customer_partner_id">Customer</label>
                <div class="input-group">
                    <select name="customer_partner_id" id="customer_partner_id" class="form-control select2 {{ $errors->has('customer_partner_id') ? ' is-invalid' : '' }}">
                        <option value="">-- Select --</option>
                        @if ($order->customer_partner_id)
                            @foreach (App\Models\Partner::CustomerPartnerDropdown() as $customer_partner)
                                <option value="{{ $customer_partner->id }}" {{$order->customer_partner_id == $customer_partner->id ? 'selected' : '' }}>{{ Str::title($customer_partner->name) }}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary" id="addCustomerBtn">
                            <!-- SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </button>
                    </div>
                </div>
                {!! $errors->first('customer_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

            {{--  --}}
            {{-- <div class="col-md-6" id="customerDropdownContainer"  >
            <div class="form-group">
                <label for="customer_partner_id">Customer</label>
                <select name="customer_partner_id" id="customer_partner_id" class="form-control select2 {{ $errors->has('customer_partner_id') ? ' is-invalid' : '' }}">
                    <option value="">-- Select --</option>
                    @if ($order->customer_partner_id)
                        @foreach (App\Models\Partner::CustomerPartnerDropdown() as $customer_partner)
                            <option value="{{ $customer_partner->id }}" {{$order->customer_partner_id == $customer_partner->id ? 'selected' : '' }}
                                >{{Str::title($customer_partner->name) }}</option>
                        @endforeach
                    @endif

                </select>
                {!! $errors->first('customer_partner_id', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="overall_status">Order Status</label>
                <select name="overall_status" id="overall_status" class="form-control{{ $errors->has('overall_status') ? ' is-invalid' : '' }}">
                    <option value=""> -- Select --</option>
                    <option value="approved" {{ $order->overall_status === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="cancelled" {{ $order->overall_status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="draft" selected {{ $order->overall_status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="completed" {{ $order->overall_status === 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ $order->overall_status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="unapproved" {{ $order->overall_status === 'unapproved' ? 'selected' : '' }}>Unapproved</option>
                </select>
                {!! $errors->first('overall_status', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}

            {{-- <div class="col-md-6">
            <div class="form-group">
                <label for="overall_adult">Adult </label>
                <input type="text" value="{{old('overall_adult',$order->overall_adult)}}" placeholder="adult" name="overall_adult" class="form-control {{($errors->has('overall_adult') ? ' is-invalid' : '')}}" id="overall_adult"  autofocus required>
                {!! $errors->first('overall_adult', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-group">
                <label for="overall_child">Child </label>
                <input type="text" value="{{old('overall_child',$order->overall_child)}}" placeholder="child" name="overall_child" class="form-control {{($errors->has('overall_child') ? ' is-invalid' : '')}}" id="overall_child"  autofocus required>
                {!! $errors->first('overall_child', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div>
         <div class="col-md-6">
            <div class="form-group">
                <label for="overall_bags">Bags </label>
                <input type="text" value="{{old('overall_bags',$order->overall_bags)}}" placeholder="bags" name="overall_bags" class="form-control {{($errors->has('overall_bags') ? ' is-invalid' : '')}}" id="overall_bags"  autofocus required>
                {!! $errors->first('overall_bags', '<div class="invalid-feedback">:message</div>') !!}
            </div>
        </div> --}}
            <input type="hidden" name="overall_status" id="req_status" value=''>
            {{-- <div class="col-md-3">
            <div class="form-group">
                <label for="booking_amount">Booking Amount </label>
                <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-dollar-circle'></i></span>
                <input type="text" readonly value="{{$order->booking_amount}}"  name="booking_amount" class="form-control {{($errors->has('booking_amount') ? ' is-invalid' : '')}}" id="booking_amount"  autofocus>
                {!! $errors->first('booking_amount', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="form-group">
                <label for="final_amount" >Final Amount </label>
                <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-dollar-circle'></i></span>
                <input type="text" readonly value="{{$order->final_amount}}"  name="final_amount" class="form-control {{($errors->has('final_amount') ? ' is-invalid' : '')}}" id="final_amount"  autofocus>
                {!! $errors->first('final_amount', '<div class="invalid-feedback">:message</div>') !!}</div>
            </div>
        </div> --}}

            {{-- Toggle Fields --}}
            {{-- <div class="custom-card toggle-fields">
            <div class="row"> --}}
            {{-- <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="email">Email  </label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-message'></i></span>
                        <input type="text" placeholder="Email" name="email" class="form-control {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{old('email',$order->partner_customer->email??'')}}" autofocus>
                        {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div> --}}
            {{-- <div class="col-md-3 toggle-fields" id = "passport_id">
                    <div class="form-group custom-card-ui">
                        <label for="passport">Passport  </label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-user-circle'></i></span>
                        <input type="text" placeholder="Passport" name="passport" class="form-control {{($errors->has('passport') ? ' is-invalid' : '')}}" id="passport" value="{{old('passport',$order->partner_customer->passport??'')}}" autofocus>
                        {!! $errors->first('passport', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="phone_no">Phone No</label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-phone'></i></span>
                        <input type="text" placeholder="Phone No" name="phone_no" class="form-control {{($errors->has('phone_no') ? ' is-invalid' : '')}}" id="phone_no" value="{{old('phone_no',$order->partner_customer->phone_no??'')}}" autofocus>
                        {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="cnic">Cnic</label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-id-card'></i></span>
                        <input type="text" placeholder="Cnic" name="cnic" class="form-control {{($errors->has('cnic') ? ' is-invalid' : '')}}" id="cnic" value="{{old('cnic',$order->partner_customer->cnic??'')}}" autofocus>
                        {!! $errors->first('cnic', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="address1">Address1</label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-map'></i></span>
                        <input type="text" placeholder="Address1" name="address1" class="form-control {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{old('address1',$order->partner_customer->address1??'')}}" autofocus>
                        {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="country">Country</label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class="fas fa-globe"></i></span>
                        <select name="country" class="form-control select2-country {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus>
                            <option value="">-- Select Country --</option>
                            @foreach (App\Models\City::fetchCountry() as $country)
                                <option value="{{ $country }}" {{ (old('country') ?? $order->partner_customer->country ?? '') == $country ? 'selected' : '' }}>
                                    {{ $country }}
                                </option>
                            @endforeach
                        </select>
                        </div>
                        {!! $errors->first('country', '<div class="invalid-feedback">:message</div>') !!}
                    </div>
                </div>
                <div class="col-md-3 toggle-fields">
                    <div class="form-group custom-card-ui">
                        <label for="city">City</label>
                        <div class="input-group input-group-sm"> <span class="input-group-text"><i class='bx bxs-map'></i></span>
                            <input type="text" placeholder="City" name="city" class="form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{old('city',$order->partner_customer->city??'')}}" autofocus>

                        {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div> --}}
            {{-- </div>
        </div> --}}
            {{-- amount card --}}
            {{-- <div class="custom-card" >
            <h3 style="margin-top: 0px;">Amount</h3>
            <div class="row">
                <div class="col-md-4" >
                    <div class="form-group custom-card-ui">
                        <label for="booking_amount">Booking Amount </label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-dollar-circle'></i></span>
                        <input type="text" readonly value="{{$order->booking_amount}}"  name="booking_amount" class="form-control {{($errors->has('booking_amount') ? ' is-invalid' : '')}}" id="booking_amount"  autofocus>
                        {!! $errors->first('booking_amount', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
                 <div class="col-md-4 mx-5 ">
                    <div class="form-group custom-card-ui">
                        <label for="final_amount" >Final Amount </label>
                        <div class="input-group input-group-sm"> <span class="input-group-text "><i class='bx bxs-dollar-circle'></i></span>
                        <input type="text" readonly value="{{$order->final_amount}}"  name="final_amount" class="form-control {{($errors->has('final_amount') ? ' is-invalid' : '')}}" id="final_amount"  autofocus>
                        {!! $errors->first('final_amount', '<div class="invalid-feedback">:message</div>') !!}</div>
                    </div>
                </div>
            </div>
        </div> --}}

        </div>
        <br>
        <div class="container">
            <div class="">
                {{-- <div class="col-auto"> --}}
                <!-- Add Row Button -->
                <button type="button" class="btn btn-sm btn-primary float-end" id="add-btn" onclick="openModal()"
                    data-toggle="tooltip" data-placement="left" title="Add Row">
                    <svg xmlns="" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-plus-square">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="12" y1="8" x2="12" y2="16"></line>
                        <line x1="8" y1="12" x2="16" y2="12"></line>
                    </svg>
                    Add Trip
                </button>
                {{-- </div> --}}
                <h3>
                    <span><i class='bx bxs-car'></i></span> Rides</h2>

            </div>
            <div class="table-responsive ">
                <table class="table table-montserrat text-center">
                    <thead class="thead t-head-clr">
                        <tr>
                            <th>#</th>
                            {{-- <th>From <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            <th>To <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th> --}}
                            <th>Rate <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            <th>Status <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            {{-- <th id="flight_no_header">Flight No <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            <th id="airline_name_header">Airline Name <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            <th id="weight_header">Weight <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            <th id="unit_header">Unit <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th> --}}
                            {{-- <th id="unit_header">
                                        Unit
                                        <span>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <polyline points="19 12 12 19 5 12"></polyline>
                                            </svg>
                                        </span>
                                        <select id="unit_select">
                                            <option value="kg">Kilograms (kg)</option>
                                            <option value="g">Grams (g)</option>
                                            <option value="lbs">Pounds (lbs)</option>
                                            <option value="oz">Ounces (oz)</option>
                                            <option value="t">Metric Tons (t)</option>
                                        </select>
                                    </th> --}}

                            {{-- <th id="load_type_header">Load Type <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th> --}}
                            {{-- <th>Adult</th> --}}
                            {{-- <th>Child</th> --}}
                            {{-- <th>Function</th>
                            <th>Return</th> --}}
                            {{-- <th>Bags</th> --}}
                            <th>Start Date <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                            {{-- <th>Pickup Time <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th> --}}
                            <th>End Date <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-arrow-down">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <polyline points="19 12 12 19 5 12"></polyline>
                                </svg></span></th>
                            <th>Duration <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round"
                                    class="feather feather-arrow-down">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <polyline points="19 12 12 19 5 12"></polyline>
                                </svg></span></th>
                            {{-- <th>CheckOut Time</th> --}}
                            {{-- <th>Is Ac <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th> --}}
                            {{-- <th id="isReturnHeader">Is Return <span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-down"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg></span></th> --}}
                            <th>Action <span>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="14"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-down">
                                        <line x1="12" y1="5" x2="12" y2="19"></line>
                                        <polyline points="19 12 12 19 5 12"></polyline>
                                    </svg></span></th>
                        </tr>
                    </thead>
                    <tbody style="body-font" id="tableBody">

                    </tbody>
                </table>
            </div>
        </div>



    </div>
    {{-- <div class="box-footer mt20">
        @php
            $model = [
                'notify_btn' => 'Save as Draft',
                'function' => 'Save',
                'body' => 'Please Confirm do you really want to Save?',
                'btn-color' => 'primary',
                'float' => 'end mt-2',
                'id' => 'save',
            ];
        @endphp
        @include('partials.modal', ['data' => $model])
    </div> --}}
    @if (!$order->overall_status || $order->overall_status == 'draft' || $order->overall_status == 'unapproved')
        {{-- <div class="col-md-6">
                <div class="form-group"> --}}
        {{-- <input type="hidden" name="overall_status" id="req_status" value=''> --}}
        @php
            $model = [
                'notify_btn' => 'Save & Submit',
                'function' => 'Submit Request',
                'body' => 'Please Confirm do you really want to Submit Request?',
                'btn-color' => 'success',
                'float' => 'end mt-2',
                'id' => 'submit_req',
            ];
        @endphp
        @include('partials.modal', ['data' => $model])
        {{-- <div class="btn btn-outline-success">Submit Request</div> --}}
        {{-- </div>
                <br>
            </div> --}}
    @endif



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

            $('#prefix_phone').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '45%' // Adjust width as needed


            })

            $('#prefix_whatsapp').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '45%' // Adjust width as needed


            })

            // to display vehicle card on select vehicle class
            // Initially hide the div
            $('.vehicle-card').hide();

            // Show the div when a vehicle is selected
            $('#vehicle_class_id').change(function() {
                if ($(this).val()) {
                    $('.vehicle-card').show();
                } else {
                    $('.vehicle-card').hide();
                }
            });

            const vehicleClassSelect = $('#vehicle_class_id');
            const seatsAllowSpan = $('#seats-allow');
            const bagsAllowSpan = $('#bags-allow');

            function updateSeatsBags() {
                const selectedOption = vehicleClassSelect.find('option:selected');
                const seatsAllow = selectedOption.data('seats-allow');
                const bagsAllow = selectedOption.data('bags-allow');
                const model_id = selectedOption.data('model_no');
                $('#vehicle_model_id').val(model_id);
                console.log("model no ==" + model_id);
                // Update the seats allowed in the icon
                seatsAllowSpan.text(seatsAllow ? seatsAllow : ' ');
                bagsAllowSpan.text(bagsAllow ? bagsAllow : ' ');
            }

            vehicleClassSelect.on('change', function() {
                updateSeatsBags();
            });

            // Trigger change event on page load to set the initial value
            updateSeatsBags();

            // Trigger change event on page load to set the initial value
            vehicleClassSelect.trigger('change');
            $('#toggleIcon').on('click', function() {
                $('.toggle-fields').toggle(); // Toggle the visibility of the fields
                // $(this).toggleClass('fa-eye fa-eye-slash'); // Toggle the icon class between eye and eye-slash
            });


            // $('#customerDropdownContainer').css('display', 'none');
            // $('#business_partner_order_id').change(function() {
            // });

            $('#customer_partner_id').select2({
                // val = ;
                // width: auto,
                width: '100%',

                ajax: {
                    url: function() {
                        return get_host() + '/partner/customer/' + $(
                            '#business_partner_order_id option:selected').val();
                    },
                    dataType: 'json',
                    delay: 250,


                    data: function(params) {
                        return {
                            q: params.term || '', // search term
                            page: params.page,
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data,
                        };
                    },
                },
                minimumInputLength: 0,
                escapeMarkup: function(m) {
                    return m;
                },
                placeholder: {
                    id: "",
                    text: "Select Customer"
                },
                allowClear: true,
                templateResult: function(data) {
                    if (!data.id) {
                        return data.text;
                    }
                    var html = data.name + ' - ' + data.cnic;
                    // var html = '';
                    return html;
                },
                language: {
                    noResults: function() {
                        // return "hello";
                        var name = $('#customer_partner_id')
                            .data('select2')
                            .dropdown.$search.val();

                        //  "test"
                        return (
                            '<button type="button" data-name="' + name +
                            '" class="btn btn-link add_new_supplier">' +
                            '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="8.5" cy="7" r="4"></circle><line x1="20" y1="8" x2="20" y2="14"></line><line x1="23" y1="11" x2="17" y2="11"></line></svg>&nbsp; ' +
                            'Add Customer' +
                            '</button>'
                        );
                    },
                },
            }).on('select2:select', function(e) {
                var data = e.params.data;
                $('#customer_partner_id').empty();
                $('#customer_partner_id').append('<option value="' + data.id + '" selected>' + data.name +
                    '</option>');
                $('#whatsapp_no').val(data.whatsapp_no);
                $('#prefix_whatsapp').prop('disabled', false); // Enable the select
                $('#prefix_whatsapp').val(data.prefix_whatsapp).trigger('change'); // Set value and trigger change event
                $('#prefix_whatsapp').prop('disabled', true); // Disable it again
                $('#email').val(data.email);
            });
            $('#customer_partner_id').on('select2:open', function() {
                // Trigger the AJAX call to load initial results
                let searchData = { q: '' }; // Empty string to get default data
                let $select = $(this);
                let ajaxUrl = get_host() + '/partner/customer/' + $('#business_partner_order_id option:selected').val();

                // Fetch data and add to dropdown
                $.get(ajaxUrl, searchData, function(data) {
                    data.forEach(item => {
                        let newOption = new Option(item.name + ' - ' + item.cnic, item.id, false, false);
                        $select.append(newOption).trigger('change');
                    });
                });
            });

            // hide_customer();
            $(document).on('click', '.add_new_supplier', function() {
                $('#customer_partner_id').select2('close');
                // var name = $(this).data('name');
                // $('.partner_modal')
                //     .find('input#name')
                //     .val(name);
                // $('.partner_modal')
                //     .find('select#contact_type')
                //     .val('supplier')
                //     .closest('div.contact_type_div')
                //     .addClass('hide');
                console.log('enter customer model');
                $('.partner_modal').modal('show');

            });


            $('#vehicle_class_id').change(function() {
                var selectedOption = $(this).find('option:selected');
                var seatsAllow = selectedOption.data('seats-allow') || '';
                var bagsAllow = selectedOption.data('bags-allow') || '';
                console.log(seatsAllow);
                $('#seats_allow_hidden').val(seatsAllow);
                $('#bags_allow_hidden').val(bagsAllow);

                // Update the values of all added rows
                updateRows();
            });

            // Initially hide or show fields based on the selected trip type
            toggleFields();

            // Listen for changes in the trip type dropdown
            $('#trip_type').change(function() {

                toggleFields();


                $(this).prop('disabled', true); // Make the dropdown readonly
            });

            // Function to toggle fields based on the trip type
            function toggleFields() {
                var selectedTripType = $('#trip_type').val();
                $('#tripTypeHidden').val(selectedTripType);
                if (selectedTripType === 'monthly_booking' || selectedTripType === 'cargo_trip') {
                    $('#trip_type').prop('disabled', true);
                }
                console.log($('#tripTypeHidden').val());

                if (selectedTripType === 'monthly_booking') {
                    // Show adult, child, bags fields
                    $('#overall_adult').closest('.c_cols').show();
                    $('#overall_child').closest('.c_cols').show();
                    $('#overall_bags').closest('.c_cols').show();

                    // Hide direction and description fields
                    $('#direction').closest('.c_cols').hide();
                    $('#description').closest('.c_cols').hide();

                    $('#flight_no_header').show();
                    $('#airline_name_header').show();
                    $('[id^=flight_num_]').show();
                    $('[id^=airline_name_]').show();

                    $('[id^=ratelist_id]').show();
                    $('[id^=to_rate_list_id]').show();


                    $('#weight_header').hide();
                    $('#unit_header').hide();
                    $('#load_type_header').hide();
                    $('[id^=weight_]').hide();
                    $('[id^=unit_]').hide();
                    $('[id^=type_of_load_]').hide();
                    $('[id^=ratelist_id_input]').hide();
                    $('[id^=to_rate_list_id_input]').hide();

                } else if (selectedTripType === 'cargo_trip') {
                    // Hide adult, child, bags fields
                    $('#overall_adult').closest('.c_cols').hide();
                    $('#overall_child').closest('.c_cols').hide();
                    $('#overall_bags').closest('.c_cols').hide();

                    // Show direction and description fields
                    $('#direction').closest('.c_cols').show();
                    $('#description').closest('.c_cols').show();
                    $('#flight_no_header').hide();
                    $('#airline_name_header').hide();
                    $('[id^=flight_num_]').hide();
                    $('[id^=airline_name_]').hide();
                    $('[id^=ratelist_id]').hide();
                    $('[id^=to_rate_list_id]').hide();

                    $('#weight_header').show();
                    $('#unit_header').show();
                    $('#load_type_header').show();
                    $('[id^=weight_]').show();
                    $('[id^=unit_]').show();
                    $('[id^=type_of_load_]').show();
                    $('[id^=ratelist_id_input]').show();
                    $('[id^=to_rate_list_id_input]').show();
                }
            }

        });
        // hidden business partner id -----start-----
        // Update the hidden field when the select field changes
        document.getElementById('business_partner_order_id').addEventListener('change', function() {
            document.getElementById('hidden_business_partner_id').value = this.value;
        });

        // Initial set of hidden field if select has a pre-selected value
        document.getElementById('hidden_business_partner_id').value = document.getElementById('business_partner_order_id')
            .value;
        // ----------end---------

        let rows_id = 1; // Initial row number
        let row_function_id = {};


        //     function handleAddRow() {
        //     // Add the first row
        //     let structure = {}; // Initialize an empty structure or pass the necessary data
        //     let firstRowId = addRow(structure);

        //     // Check if the `is_return` field is true and add another row if it is
        //     let isReturn = $('#structure_is_return_' + firstRowId).is(':checked');
        //     if (isReturn) {
        //         let returnStructure = {}; // Initialize the structure for the return row
        //         addRow(returnStructure);
        //     }
        // }
        function updateRows() {
            var seatsAllowed = $('#seats_allow_hidden').val();
            var bagsAllowed = $('#bags_allow_hidden').val();

            $('.added-row').each(function() {
                var row_id = $(this).attr('id').split('_')[1];
                $(`#structure_adult_${row_id}`).val(seatsAllowed);
                $(`#structure_bags_${row_id}`).val(bagsAllowed);
            });
        }


        // default func
        function addRow(structure = {}, selectedRateListId = null, isreturn = false, seatsAllow = '', bagsAllow = '',
            fromValue = null, toValue = null,) {
            // console.log(structure);
            // Check if is_return is true

            row_id = rows_id;
            row_index = rows_id - 1;
            // Check the selected trip type
            // let isBookingTrip = (tripType === 'booking');


            // row_function_id[row_id] = 1;

            let newRow = `
                <tr id="structure_${row_id}"}" class="added-row">
                    <td style = " padding-top:15px;">
                        ${row_id}
                        <input type="hidden"  id="structure_id_${row_id}" value="${structure.id??""}" name="rowIds[${row_index}]">
                        <input type="hidden" id="rate_list_id_${row_id}" name="rate_list_id[${row_index}]" value="${structure.rate_list_id ?? ""}">
                        <input type="hidden" id="structure_from_value_${row_id}" name="from_value[${row_index}]" value="${structure.fromValue ?? ''}">
                        <input type="hidden"  id="with_driver_${row_id}" value="0" name="with_driver[${row_index}]">

                    </td>
                   
                    
                    <td>
                        <input type="text" style='min-width:100px;' value="${structure.rate??""}" oninput="calculateTotal()"  id="rate_${row_id}"  class="form-control actions rate-input"   placeholder ="Rate" name="rate[${row_index}]" autofocus required readonly>
                    </td>


                    <td>
                        <select name='status[${row_index}]' style='min-width:115px;' id='status${row_id}' class='form-control' disabled >
                            <!--  <option value = "">-- Select -- </option>
                            <option value="approved" ${ structure.status == 'approved'?'selected':''}>Approved</option>
                            <option value="cancelled" ${ structure.status == 'cancelled'?'selected':''}>Cancelled</option> -->
                            <option value="draft" selected ${ structure.status == 'draft'?'selected':''}>Draft</option>
                        </select>


                    </td>
                    <td><input style='min-width:100px; font-size:14px;' type="date" value="${structure.date??""}" class="form-control" id="structure_date_${row_id}" placeholder = "Enter Date" name="date[${row_index}]" min="{{ date('Y-m-d') }}" autofocus required></td>
                    <!--<td><input style='min-width:60px; font-size:14px;' type="time" value="${structure.pickup_time??""}" class="form-control" id="structure_pickup_time_${row_id}" placeholder = "Enter Pickup Time no" name="pickup_time[${row_index}]" autofocus required></td>-->
                    <td><input style='min-width:100px; font-size:14px;' type="date" value="${structure.end_date??""}" class="form-control" id="structure_end_date_${row_id}" placeholder = "Enter Date" name="end_date[${row_index}]" min="{{ date('Y-m-d') }}" autofocus required></td>
                    <td><input readonly style='min-width:100px; font-size:14px;' type="text" value="${structure.duration??""}" class="form-control duration-field" id="structure_duration_${row_id}" name="duration[${row_index}]" ></td>

                    <td>
                        <button type="button" class="btn btn-outline-danger  float-end" onclick="deleteRow('${row_id}')" data-toggle="tooltip" data-placement="left" title="Remove Row">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                            </svg>
                        </button>
                    </td>
                </tr>
            `;


            $('#tableBody').append(newRow);
            // $(`#ratelist_id${row_id}`).select2();

            rows_id++;
            calculateTotal();
            return row_id;
        }
        // to update to route according to from
        function updateToField(row_id) {
            let fromValue = $(`#ratelist_id${row_id}`).val();
            $(`#structure_from_value_${row_id}`).val(fromValue);
            let flightNumInput = $(`#flight_num_${row_id}`);
            let airlineNameInput = $(`#airline_name_${row_id}`);
            let toSelect = $(`#to_rate_list_id${row_id}`);
            toSelect.empty();
            toSelect.append('<option value="">-- Select --</option>');

            @foreach (App\Models\RateList::RouteRateDropDown() as $route)

                if ('{{ $route->route->fromLoc->name }}' == fromValue) {
                    toSelect.append(
                        `<option value='{{ $route->route->toLoc->name }}' data-route-id='{{ $route->id }}' data-price='{{ $route->price }}'>{{ $route->route->toLoc->name }}</option>`
                    );

                }
            @endforeach


            // Enable or disable the flight number input based on the 'from' value
            if (fromValue.includes('Airport')) {
                flightNumInput.prop('disabled', false).prop('required', true);
                airlineNameInput.prop('disabled', false).prop('required', true);
                // $('#flight_no_header').show();
                // $('#airline_name_header').show();
            } else {
                flightNumInput.prop('disabled', true);
                airlineNameInput.prop('disabled', true);
                flightNumInput.val(''); // Optionally clear the field if it's disabled
                airlineNameInput.val('');
                // $('#flight_no_header').hide();
                // $('#airline_name_header').hide();
            }
        }

        function updateRate(row_id) {
            let toSelect = $(`#to_rate_list_id${row_id}`);
            let selectedOption = toSelect.find('option:selected');
            let rate = selectedOption.data('price');
            let routeId = selectedOption.data('route-id');
            console.log("this is desired route id: " + routeId);
            var data_route = $(`#ratelist_id${row_id} option:selected`).data('route-id')
            data_route = routeId;
            console.log("Selected from route ID:", data_route);
            $(`#rate_${row_id}`).val(rate);
            $(`#rate_list_id_${row_id}`).val(routeId);

            calculateTotal();
        }
        // end-------------

        function change_route(row_id) {
            // let rateList = $(`#rate_list_id${row_id}`);
            // let selectedOption = rateList.find(':selected');
            var price = $(`#ratelist_id${row_id} option:selected`).attr('price');
            $(`#rate_${row_id}`).val(price);


            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            $('.rate-input').each(function() {
                let value = parseFloat($(this).val());
                if (!isNaN(value)) {
                    total += value;
                }
            });
            $('#booking_amount').val(total.toFixed(2));
        }

        function deleteRow(row_id) {

            if (confirm("Are you sure you want to delete this row?")) {

                let structure_id = $('#structure_id_' + row_id).val();
                console.log(structure_id);
                if (structure_id) {
                    $.post('/delete-route-row/' + structure_id, {
                        "_token": "{{ csrf_token() }}",
                        "_method": "DELETE",
                    }).then(function(data) {
                        console.log(data);
                        $("#structure_" + row_id).remove();
                        toastr.options = {
                            "positionClass": "toast-top-right",
                        }
                        toastr.success("Row Removed Successfully", 'Success');

                    }).fail(function(xhr) {
                        toastr.options = {
                            "positionClass": "toast-top-right",
                        }
                        toastr.error(xhr.responseJSON.error, 'Error');

                    })
                } else {
                    $("#structure_" + row_id).remove();
                    toastr.options = {
                        "positionClass": "toast-top-right",
                    }
                    toastr.success("Row Removed Successfully", 'Success');

                }

            }


        }

        function deleteRow_byReturn(row_id) {

            let structure_id = $('#structure_id_' + row_id).val();
            console.log(structure_id);
            if (structure_id) {
                $.post('/delete-route-row/' + structure_id, {
                    "_token": "{{ csrf_token() }}",
                    "_method": "DELETE",
                }).then(function(data) {
                    console.log(data);
                    $("#structure_" + row_id).remove();
                    toastr.options = {
                        "positionClass": "toast-top-right",
                    }
                    toastr.success("Row Removed Successfully", 'Success');

                }).fail(function(xhr) {
                    toastr.options = {
                        "positionClass": "toast-top-right",
                    }
                    toastr.error(xhr.responseJSON.error, 'Error');

                })
            } else {
                $("#structure_" + row_id).remove();
                toastr.options = {
                    "positionClass": "toast-top-right",
                }
                toastr.success("Row Removed Successfully", 'Success');

            }




        }

        async function load_edit() {
            @if (isset($edit))
                var selectedTripType = $('#trip_type').val();
                @foreach ($order->order_details as $structure)

                    row_id = await addRow({!! $structure !!}, null, false, '', '', null, null, selectedTripType);
                @endforeach
            @endif
        }
        $(document).ready(function() {
            load_edit();
            calculateTotal();

            console.log('Initializing Select2');
            $('#country1').select2({
                width: '100%', // Adjust width as needed
                placeholder: '-- Select Country --'
            });
            console.log('Initializing Select22');
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
                            country: $('#country1').val()
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
            $('#country1').on('change', function() {
                var countryName = $(this).val();
                console.log(countryName);
                $('#city1').val(null).trigger('change');
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
                $('#city1').append(newOption).trigger('change');
            }
        })

        //to restrict fields in numeric:
        // $('#overall_adult').on('input', function() {
        //     var inputValue = $(this).val().trim();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/\D/g, '');
        //     // Limit to exactly 11 numbers
        //     var elevenDigitValue = numericValue.slice(0, 11);
        //     // Update the input field value
        //     $(this).val(elevenDigitValue);
        // });
        // $('#overall_child').on('input', function() {
        //     var inputValue = $(this).val().trim();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/\D/g, '');
        //     // Limit to exactly 11 numbers
        //     var elevenDigitValue = numericValue.slice(0, 11);
        //     // Update the input field value
        //     $(this).val(elevenDigitValue);
        // });
        // $('#overall_bags').on('input', function() {
        //     var inputValue = $(this).val().trim();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/\D/g, '');
        //     // Limit to exactly 11 numbers
        //     var elevenDigitValue = numericValue.slice(0, 11);
        //     // Update the input field value
        //     $(this).val(elevenDigitValue);
        // });

        $('#btn_submit_req').click(function(e) {
            if (rows_id <= 1) {
                msgboxbox.show('Atleast One Orderline Is Required!', 'error', null);
                e.preventDefault();
                e.stopPropagation();
            }
            $('#req_status').val('pending');
            // msgboxbox.show('your request has been submited','success', null);
            // validate_all(e);
            // total_expense();
        });
        $('#btn_save').click(function(e) {
            console.log('enter btn save');
            $('#req_status').val('draft');

            // msgboxbox.show('your request is in draft','success', null);


            if (rows_id <= 1) {
                msgboxbox.show('Atleast One Orderline Is Required!', 'error', null);
                e.preventDefault();
                e.stopPropagation();
            }
            $('#req_status').val('draft');
            // validate_all(e);
            // total_expense();
        });

        let modalOpenedOnce = false;

    function openModal() {
        if (!modalOpenedOnce) {
            $('#addRowModal').modal('show');
            modalOpenedOnce = false; // Ensure modal doesn't reopen unnecessarily
        } else {
            const selectedTripType = $('#trip_type').val();
            const seatsAllow = $('#seats_allow_hidden').val();
            const bagsAllow = $('#bags_allow_hidden').val();

            if (selectedTripType === 'monthly_booking' || selectedTripType === 'cargo_trip') {
                // Call your addRow function with relevant parameters
                addRow({}, null, false, seatsAllow, bagsAllow, null, null, selectedTripType);
            } else {
                msgboxbox.show("Must Select Trip Type Before Adding Trip", 'error', null);
            }
        }
    }
    $('#closebtn').on('click', function () {
        $('#addRowModal').modal('hide');

    })
    $('#confirmBtn').on('click', function () {
    const startDate = new Date($('#startDate').val());
    const endDate = new Date($('#endDate').val());
    let month_rate = $('#month_rate').val();

    if (!startDate || !endDate || isNaN(startDate) || isNaN(endDate) ) {
        alert('Both Start Date and End Date are required.');
        return;
    }
    if (!month_rate || isNaN(month_rate)) {
        alert('Rate is required.');
        return;
    }

    if (startDate > endDate) {
        alert('Start Date cannot be later than End Date.');
        return;
    }

    // Close modal
    $('#addRowModal').modal('hide');

    let currentStartDate = new Date(startDate); // Start of the current row
    let calculatedEndDate;

while (currentStartDate <= endDate) {
    // Get the number of days in the current month
    let daysInCurrentMonth = new Date(
        currentStartDate.getFullYear(),
        currentStartDate.getMonth() + 1,
        0
    ).getDate();

    // Calculate the end date for the current row
    calculatedEndDate = new Date(currentStartDate);
    calculatedEndDate.setDate(calculatedEndDate.getDate() + daysInCurrentMonth-1);

    // Ensure it does not exceed the user's given end date
    if (calculatedEndDate > endDate) {
        calculatedEndDate = new Date(endDate);
    }

    let daysInRow = Math.ceil((calculatedEndDate - currentStartDate) / (1000 * 60 * 60 * 24)) + 1;

    // Calculate the rate for the current row
    let dailyRate = month_rate / daysInCurrentMonth; // Divide the month rate by days in the month
    let rowRate = (dailyRate * daysInRow).toFixed(2); // Multiply daily rate by days in row

    // Format dates as YYYY-MM-DD
    const formattedStartDate = currentStartDate.toISOString().split('T')[0];
    const formattedEndDate = calculatedEndDate.toISOString().split('T')[0];

    // Calculate the duration in months, including partial months
    let durationInMonths = (calculatedEndDate.getFullYear() - currentStartDate.getFullYear()) * 12 +
        calculatedEndDate.getMonth() - currentStartDate.getMonth();

    // If the calculated end date is earlier in the month than the start date, account for a partial month
    if (calculatedEndDate.getDate() < currentStartDate.getDate()) {
        durationInMonths = 1; // Add 1 month for partial overlap
    }

    // Handle edge case: less than a month
    if (durationInMonths === 0) {
        durationInMonths = '1 Months'; // Label for partial months
    } else {
        durationInMonths = durationInMonths + ' Months'; // Proper label
    }
        // if(durationInMonths==0){
        //     durationInMonths = 'not full month'
        // }
        // Call addRow function with the start and end dates
        $('#add-btn').hide();
        addRow({
            date: formattedStartDate,
            end_date: formattedEndDate,
            duration:durationInMonths,
            rate:rowRate
        });

        // Move the currentStartDate to the next day after calculatedEndDate
        currentStartDate = new Date(calculatedEndDate);
        currentStartDate.setDate(currentStartDate.getDate() + 1);
    }
});

    // --------------------------------------------------------------------------------
    </script>

</div>

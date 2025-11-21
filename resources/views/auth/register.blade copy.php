<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/fleet_logo.png" type="image/png" />
    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />

    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>

    <link rel="stylesheet" href="{{ asset('assets/alert/css/alert.min.css') }}" />
    <script src="/assets/alert/js/alert.min.js"></script>

    <!-- Bootstrap CSS -->
    <script src="/assets/js/jquery.min.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/company-registration.css') }}">

    <title>Company Registration Form</title>


</head>
<style>
    /* Loader style */
    #loader {
        display: none;
        /* Hidden by default */
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 1000;
        display: flex;
        /* Center the loader and text */
        justify-content: center;
        /* Center horizontally */
        align-items: center;
        /* Center vertically */
        flex-direction: column;
        /* Stack elements vertically */
    }

    .spinner-border {
        width: 3rem;
        /* Adjust size */
        height: 3rem;
        /* Adjust size */
    }

    /* Loader text style */
    .loader-text {
        color: black;
        /* Text color */
        margin-top: 10px;
        /* Space between loader and text */
        font-size: 1.2rem;
        /* Adjust text size */
    }

    .step1-p {
        margin-top: 5%;
        padding-left: 5%;
        padding-right: 5%;

    }

    #stepDescription {
        margin-bottom: 8%;
        margin-top: 2%;
    }
</style>


<body>
    <div id="msgbox-area" class='msgbox-area'></div>

    <div id="loader" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="loader-text">We are processing your information, please wait...</div> <!-- Loader text -->
    </div>
    {{-- <div id="loader" >
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div> --}}

    <!--wrapper-->
    {{-- <div class="mx-2 text-center">
        <img src="assets/images/fleet_logo.png" width="150" alt="" />
    </div> --}}
    {{-- @dd($_SERVER) --}}
    <div class="wrapper">
        @include('layouts.partials.error')
        @include('layouts.partials.success')
        <div class="col mx-auto">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <h1>Register your Company at FleetFreak
                        </h1>
                        <p id="stepDescription">
                            Let’s provide your basic information to start working in world’s simple and best Fleet
                            Management System
                        </p>
                    </div>
                    <div class="step-indicator">
                        <div class="step active" id="step1-indicator">1</div>
                        <div class="step-line"></div>
                        <div class="step" id="step2-indicator">2</div>
                        <div class="step-line"></div>
                        <div class="step" id="step3-indicator">3</div>
                    </div>

                    <!-- Progress Bar -->
                    {{-- <div class="progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 33%" id="progressBar"></div>
                    </div> --}}

                    <!-- Step 1: Email -->
                    <div id="step1">
                        <p class="text-center step1-p">Provide your active Email ID. Company and Admin user will be
                            registered against this Email ID.</p>
                        <form id="emailForm" method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <label for="email">Email</label>
                                    {{-- <div class="input-group"> --}}
                                    {{-- <span class="input-group-text bg-transparent">
                                            <i class='bx bxs-message'></i>
                                        </span> --}}
                                    <input type="email" placeholder="Ex: johndoe@gmail.com" name="email"
                                        class="form-control" id="email">
                                    <div class="invalid-feedback">Please enter a valid email address.</div>
                                    {{-- </div> --}}
                                </div>
                            </div>
                            <div class="box-footer mt-4">
                                <button type="button" class="btn btn-primary float-end mx-5" id="nextStep">Next
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg>
                                </button>

                            </div>
                        </form>
                    </div>

                    <!-- Step 2: Company Info (Hidden by default) -->
                    <!-- Step 2: Company Info -->
                    <div id="step2" style="display: none;">
                        <p class="text-center" style="margin-top: 5%;">Provide your company legal name</p>
                        <form id="companyForm" method="POST" action="{{ route('register_company') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <div class="col-md-8 my-1" id="company_name_id">
                                    <div class="form-group">
                                        <label for="company_name">Company Name</label>
                                        {{-- <div class="input-group">
                                            <span class="input-group-text bg-transparent"><i
                                                    class='bx bxs-factory'></i></span> --}}
                                        <input type="text" placeholder="Ex: Microsoft" name="company_name"
                                            class="custom-bg form-control" id="company_name"
                                            value="{{ old('company_name') }}" autofocus>
                                        <div class="invalid-feedback">Company name must not be empty.</div>
                                        {{-- {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!} --}}
                                        {{-- </div> --}}
                                    </div>
                                </div>

                                {{-- <div class="col-md-6 my-1">
                                    <div class="form-group mx-3">
                                        <label for="address1">Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent"><i
                                                    class='bx bxs-map'></i></span>
                                            <input type="text" placeholder="Address1" name="address1"
                                                class="custom-bg form-control {{ $errors->has('address1') ? ' is-invalid' : '' }}"
                                                id="address1" value="{{ old('address1', $partner->address1 ?? '') }}"
                                                autofocus required>
                                            {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    </div>
                                </div> --}}

                                {{-- <div class="col-md-6 my-1">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" placeholder="City" name="city"
                                            class="custom-bg form-control {{ $errors->has('city') ? ' is-invalid' : '' }}"
                                            id="city" value="{{ $partner->city ?? '' }}" autofocus required>
                                        {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                                    </div>
                                </div> --}}

                                {{-- <div class="col-md-6 my-1">
                                    <div class="form-group mx-3">
                                        <label for="country">Country</label>
                                        <select name="country"
                                            class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}"
                                            id="country" autofocus required>
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
                                </div> --}}
                            </div>

                            <div class="box-footer mt-4">
                                <button type="button" id="backStep1" class="btn btn-success mx-5">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>Back</button>
                                <button type="button" class="btn btn-primary float-end mx-5" id="nextStep2">Next
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-right">
                                        <line x1="5" y1="12" x2="19" y2="12"></line>
                                        <polyline points="12 5 19 12 12 19"></polyline>
                                    </svg></button>

                            </div>
                        </form>
                    </div>


                    <!-- Step 3: Registration and Password (Hidden by default) -->
                    <!-- Step 3: Personal Information -->
                    <div id="step3" style="display: none;">
                        <p class="text-center" style="margin-top: 5%;">Provide your personal information and Set
                            password for login</p>

                        <form id="personalForm" method="POST" action="{{ route('register_submit_detail') }}">
                            @csrf
                            <div class="row justify-content-center">
                                <input type="hidden" name="register_from" value="{{ Route::currentRouteName() }}">

                                <div class="col-md-8 my-1">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        {{-- <div class="input-group"> --}}
                                        {{-- <span class="input-group-text bg-transparent"><i
                                                    class='bx bxs-user'></i></span> --}}
                                        <input type="text" placeholder="Enter Your Name" name="name"
                                            class="custom-bg form-control {{ $errors->has('name') ? ' is-invalid' : '' }}"
                                            id="name" value="{{ old('name', $partner->name ?? '') }}" autofocus>
                                        {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}
                                        {{-- </div> --}}
                                    </div>
                                </div>

                                <div class="col-md-8 my-1">
                                    <div class="form-group">
                                        <label for="phone_no">Phone No</label>
                                        <div class="input-group">
                                            <select class="form-select" id="prefix_phonecust" name="prefix_phone"
                                                autofocus>
                                                @foreach (App\Models\CountryCode::phone_codes() as $country)
                                                    <option value="{{ $country->phonecode }}"
                                                        {{ old('prefix_phone', $partner->prefix_phone ?? '+1') == $country->phonecode ? 'selected' : '' }}
                                                        data-iso="{{ strtolower($country->iso) }}">
                                                        +{{ $country->iso . '(' . $country->phonecode . ')' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="text" placeholder="" name="phone_no"
                                                class="custom-bg form-control {{ $errors->has('phone_no') ? ' is-invalid' : '' }}"
                                                id="phone_no"
                                                value="{{ old('phone_no', $partner->phone_no ?? '') }}">
                                            {!! $errors->first('phone_no', '<div class="invalid-feedback">:message</div>') !!}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8" id="passwordFields">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        {{-- <div class="input-group">
                                            <span class="input-group-text bg-transparent"><i
                                                    class='bx bxs-lock'></i></span> --}}
                                        <input type="password" name="password" class="form-control" id="password">
                                        {{-- </div> --}}
                                    </div>
                                </div>
                                <div class="col-md-8" id="passwordFields">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password</label>
                                        {{-- <div class="input-group">
                                            <span class="input-group-text bg-transparent"><i
                                                    class='bx bxs-show'></i></span> --}}
                                        <input type="password" name="password_confirmation" class="form-control"
                                            id="password_confirmation">
                                        {{-- </div> --}}
                                    </div>
                                    {{-- hidden fields --}}
                                    <input type="hidden" name="email" id="email"
                                        value="{{ old('email', $email ?? '') }}">
                                    <input type="hidden" name="company_name" id="company_name"
                                        value="{{ old('company_name', $company_name ?? '') }}">
                                    {{-- <input type="hidden" name="address1" id="address1"
                                        value="{{ old('address1', $address1 ?? '') }}">
                                    <input type="hidden" name="city" id="city"
                                        value="{{ old('city', $city ?? '') }}">
                                    <input type="hidden" name="country" id="country"
                                        value="{{ old('country', $country ?? '') }}"> --}}
                                </div>
                            </div>

                            <div class="box-footer mt-4">
                                <button type="button" id="backStep2" class="btn btn-success mx-5"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-arrow-left">
                                        <line x1="19" y1="12" x2="5" y2="12"></line>
                                        <polyline points="12 19 5 12 12 5"></polyline>
                                    </svg>Back
                                </button>

                                <button type="submit" class="btn btn-primary float-end mx-5"
                                    id="submitForm">Submit</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/select2/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            // Function to handle form submission
            function handleFormSubmission() {
                var email = $('#email').val();

                if (email === '' || !validateEmail(email)) {
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').removeClass('is-invalid');

                    // Show the loader
                    $('#loader').fadeIn();

                    // Delay the AJAX call by 3 seconds (3000 milliseconds)
                    setTimeout(function() {
                        // Send form data to the backend using AJAX
                        $.ajax({
                            url: "{{ route('register') }}", // Replace with your route
                            type: 'POST',
                            data: {
                                email: email,
                                _token: "{{ csrf_token() }}" // Include CSRF token
                            },
                            success: function(response) {
                                // Hide the loader after the response is received
                                $('#loader').fadeOut();

                                if (response.success) {
                                    // If the email validation is successful, move to the next step
                                    $('#step1').hide();
                                    $('#step2').show();

                                    // Update step indicators
                                    $('#step1-indicator').addClass('completed');
                                    $('#step2-indicator').addClass('active');
                                } else {
                                    // If email already exists or other validation errors
                                    $('#email').addClass('is-invalid');
                                    alert(response.message); // Show validation message
                                }
                            },
                            error: function(xhr, status, error) {
                                // Hide the loader in case of an error
                                $('#loader').fadeOut();

                                // Handle any errors that occur during the AJAX request
                                console.log(error);
                                alert("An error occurred while submitting the form.");
                            }
                        });
                    }, 3000); // Delay of 3000 milliseconds (3 seconds)
                }
            }

            // Trigger form submission on button click
            $('#nextStep').click(function(e) {
                e.preventDefault(); // Prevent default form submission
                handleFormSubmission();
            });

            // Handle form submission on Enter key press
            $('#email').keypress(function(e) {
                if (e.which === 13) { // 13 is the keycode for the Enter key
                    e.preventDefault(); // Prevent default form submission on Enter
                    handleFormSubmission(); // Call the form submission function
                }
            });

            // Email validation function
            function validateEmail(email) {
                var re = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
                return re.test(String(email).toLowerCase());
            }
            $('#backStep1').click(function() {
                $('#step2').hide(); // Hide step 2
                $('#step1').show(); // Show step 1

                $('#step1-indicator').addClass('completed');
                $('#step2-indicator').addClass('active');
                // $('#progressBar').css('width', '33%'); // Adjust progress bar
            });
            $('#backStep2').click(function() {

                $('#step3').hide(); // Hide step 2
                $('#step2').show(); // Show step 1
                $('#step2-indicator').addClass('completed');
                $('#step3-indicator').addClass('active');
                // $('#progressBar').css('width', '33%'); // Adjust progress bar
            });
            // default code that is working----------------

            // $('#nextStep2').click(function() {
            //     // Validate company info fields
            //     var isValid = true;

            //     // Validate company name
            //     if ($('#company_name').val() === '') {
            //         $('#company_name').addClass('is-invalid');
            //         isValid = false;
            //     } else {
            //         $('#company_name').removeClass('is-invalid');
            //     }

            //     if (isValid) {
            //         $('#loader').fadeIn();
            //         // Collect data for submission
            //         var data = {
            //             company_name: $('#company_name').val(),

            //             _token: "{{ csrf_token() }}" // CSRF token for security
            //         };
            //         setTimeout(function() {
            //         // Make an AJAX request to check company name
            //         $.ajax({
            //             url: "{{ route('register_company') }}", // Replace with your route to check company name
            //             method: 'POST',
            //             data: data,
            //             success: function(response) {
            //                 if (response.success) {
            //                     // Proceed to the next step
            //                     $('#loader').fadeOut();

            //                     $('#step2').hide();
            //                     $('#step3').show();
            //                     $('#step2-indicator').addClass('completed');
            //                     $('#step3-indicator').addClass('active');
            //                     // $('#progressBar').css('width', '100%');
            //                 } else {
            //                     // Show error message
            //                     alert(response
            //                     .message); // Display the error message if company name exists
            //                     $('#company_name').addClass('is-invalid');
            //                 }
            //             },
            //             error: function(xhr) {
            //                 // Handle error response
            //                 console.error('Error:', xhr.responseText);
            //             }
            //         });
            //         },3000);
            //     }
            // });
            // ------------------------------------------

            // Function to handle form submission
            function handleCompanySubmission() {
                // Validate company info fields
                var isValid = true;

                // Validate company name
                if ($('#company_name').val() === '') {
                    $('#company_name').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#company_name').removeClass('is-invalid');
                }

                if (isValid) {
                    $('#loader').fadeIn();

                    // Collect data for submission
                    var data = {
                        company_name: $('#company_name').val(),
                        _token: "{{ csrf_token() }}" // CSRF token for security
                    };

                    // Delay the AJAX request by 3 seconds
                    setTimeout(function() {
                        // Make an AJAX request to check company name
                        $.ajax({
                            url: "{{ route('register_company') }}", // Replace with your route to check company name
                            method: 'POST',
                            data: data,
                            success: function(response) {
                                if (response.success) {
                                    // Proceed to the next step
                                    $('#loader').fadeOut();

                                    $('#step2').hide();
                                    $('#step3').show();
                                    $('#step2-indicator').addClass('completed');
                                    $('#step3-indicator').addClass('active');
                                } else {
                                    // Show error message
                                    alert(response
                                    .message); // Display the error message if company name exists
                                    $('#company_name').addClass('is-invalid');
                                }
                            },
                            error: function(xhr) {
                                // Handle error response
                                console.error('Error:', xhr.responseText);
                            }
                        });
                    }, 3000); // 3-second delay
                }
            }

            // Trigger form submission on button click
            $('#nextStep2').click(function(e) {
                e.preventDefault(); // Prevent default form submission
                handleCompanySubmission();
            });

            // Handle form submission on "Enter" key press
            $('#company_name').keypress(function(e) {
                if (e.which === 13) { // 13 is the keycode for the Enter key
                    e.preventDefault(); // Prevent default form submission on Enter
                    handleCompanySubmission(); // Call the company submission function
                }
            });
            // working code that is running submit form
            // $('#submitForm').click(function(e) {
            //     e.preventDefault(); // Prevent form from submitting right away

            //     var isValid = true;

            //     // Validate name
            //     if ($('#name').val() === '') {
            //         $('#name').addClass('is-invalid');
            //         isValid = false;
            //     } else {
            //         $('#name').removeClass('is-invalid');
            //     }

            //     // Validate phone number
            //     if ($('#phone_no').val() === '') {
            //         $('#phone_no').addClass('is-invalid');
            //         isValid = false;
            //     } else {
            //         $('#phone_no').removeClass('is-invalid');
            //     }

            //     // Validate password
            //     var password = $('#password').val();
            //     var confirmPassword = $('#password_confirmation').val();

            //     if (password === '') {
            //         $('#password').addClass('is-invalid');
            //         isValid = false;
            //     } else {
            //         $('#password').removeClass('is-invalid');
            //     }

            //     if (confirmPassword === '') {
            //         $('#password_confirmation').addClass('is-invalid');
            //         isValid = false;
            //     } else if (password !== confirmPassword) {
            //         $('#password_confirmation').addClass('is-invalid');
            //         alert('Passwords do not match!');
            //         isValid = false;
            //     } else {
            //         $('#password_confirmation').removeClass('is-invalid');
            //     }

            //     if (isValid) {
            //         // Gather data from previous steps
            //         $('#loader').fadeIn();
            //         var email = $('#email').val();
            //         var company_name = $('#company_name').val();


            //         // Append previous step data to the form
            //         $('<input>').attr({
            //             type: 'hidden',
            //             name: 'email',
            //             value: email
            //         }).appendTo('#personalForm');

            //         $('<input>').attr({
            //             type: 'hidden',
            //             name: 'company_name',
            //             value: company_name
            //         }).appendTo('#personalForm');
            //          // Wait for 2 seconds before submitting the form
            //         setTimeout(function() {
            //             // $('.loading-overlay').fadeOut();
            //             $('#loader').fadeOut();
            //             $('#personalForm').submit();
            //         }, 3000); // 2 second delay
            //     }
            // });
            // ----------------------------------------------------------
            function handleSubmitSubmission() {
                var isValid = true;

                // Validate name
                if ($('#name').val() === '') {
                    $('#name').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                }

                // Validate phone number
                if ($('#phone_no').val() === '') {
                    $('#phone_no').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#phone_no').removeClass('is-invalid');
                }

                // Validate password
                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();

                if (password === '') {
                    $('#password').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#password').removeClass('is-invalid');
                }

                if (confirmPassword === '') {
                    $('#password_confirmation').addClass('is-invalid');
                    isValid = false;
                } else if (password !== confirmPassword) {
                    $('#password_confirmation').addClass('is-invalid');
                    alert('Passwords do not match!');
                    isValid = false;
                } else {
                    $('#password_confirmation').removeClass('is-invalid');
                }

                if (isValid) {
                    // Gather data from previous steps
                    $('#loader').fadeIn();
                    var email = $('#email').val();
                    var company_name = $('#company_name').val();

                    // Append previous step data to the form
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'email',
                        value: email
                    }).appendTo('#personalForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'company_name',
                        value: company_name
                    }).appendTo('#personalForm');

                    // Wait for 3 seconds before submitting the form
                    setTimeout(function() {
                        $('#loader').fadeOut();
                        $('#personalForm').submit(); // Submit the form after validation
                    }, 3000); // 3-second delay
                }
            }

            // Trigger form submission on button click
            $('#submitForm').click(function(e) {
                e.preventDefault(); // Prevent form submission by default
                handleSubmitSubmission();
            });

            // Handle form submission on "Enter" key press
            $('#personalForm input').keypress(function(e) {
                if (e.which === 13) { // 13 is the keycode for Enter key
                    e.preventDefault(); // Prevent default form submission on Enter
                    handleSubmitSubmission(); // Call the form submission function
                }
            });

            $('.select2').select2({
                width: '100%',
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


            // Set the selected city if editing
            var selectedCity = '{{ old('city') }}';
            if (selectedCity) {
                var newOption = new Option(selectedCity, selectedCity, true, true);
                $('#city').append(newOption).trigger('change');
            }


        });
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

            $('#prefix_phonecust').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '20%' // Adjust width as needed


            });


        });
    </script>

</body>

</html>

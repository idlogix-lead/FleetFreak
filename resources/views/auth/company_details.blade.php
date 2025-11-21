<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/logo1.png" type="image/png" />
    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <script src="/assets/js/jquery.min.js"></script>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

    <title>Zaroon</title>
</head>
{{-- <style>
      .card{
            
            /* max-width: 200px; */
            margin-left:10%;
            width: 80%;
            border-radius: 12px;
            box-shadow: rgba(0, 0, 0, 0.30) 0px 5px 20px;
            
            
            
            /* background-color: rgb(249, 249, 249,0.9); */
        }
        .input-group{
            background-color: rgb(249, 249, 249,0.9);
        }
        .custom-bg {
            background-color: rgb(249, 249, 249,0.9);

        }
       
</style> --}}
<style>
    /* Adjusting the form size and design */
    body {
        background-color: #f4f6f9;
    }
    
    .wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .card {
        width: 100%;
        margin: 20px auto;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        background-color: #fff;
        min-height: 300px; /* Set a minimum height */
    }

    .card-body {
        padding: 30px;
    }

    h3 {
        font-weight: 500;
        margin-bottom: 20px;
        color: #333;
    }

    p {
        font-size: 14px;
        color: #666;
        margin-bottom: 25px;
    }

    /* .form-control {
        height: 45px;
        border-radius: 8px;
        box-shadow: none;
        border: 1px solid #ddd;
    } */

    .input-group-text {
        background-color: #e9ecef;
        border-radius: 8px 0 0 8px;
        border: 1px solid #ddd;
    }

    .btn-primary {
        padding: 5px 10px; /* Adjust padding to make the button smaller */
        font-size: 14px; /* Optional: reduce font size */
        height: auto; /* Remove the fixed height */
        border-radius: 6px; /* Optionally adjust the border radius */
    }

    .btn-primary:hover {
        background-color: #0069d9;
    }

    .login-separater hr {
        margin: 20px 0;
        border-top: 1px solid #ddd;
    }

    .box-footer {
        text-align: right;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .card {
            width: 90%;
        }
    }

    @media (max-width: 576px) {
        .card {
            width: 100%;
        }

        h3 {
            font-size: 22px;
        }
        #loader{
            display: none;
            position: fixed;
            left: 50%;
            top: 20%;
            transform: translate(-50%, -50%);
            z-index: 999;
        }
    }
</style>
<body style="background-color: white;">
    <div id="msgbox-area" class='msgbox-area'></div>
    <div id="loader" >
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!--wrapper-->
    <div class="wrapper">
        
        @include('layouts.partials.error')
        @include('layouts.partials.success')
        <div class="authentication-header"></div>
        <div class="d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
         
                    <div class="col mx-auto">
                        {{-- <div class="text-center">
                            <img src="assets/images/logo1.png" width="150" alt="" />
                        </div> --}}
                        {{-- <div class="card">
                            <div class="card-body"> --}}
                                <div class="p-4 rounded" style="margin-left: 15%; margin-right:15%;">
                                    <div class="text-center">
                                        <h3 class="">Company Registration Form</h3>
                                    
                                    </div>
                                    <p style="text-align: center">Be our business partner to book rides/trips for your valuable customers. Please fill following details, after admin approval, your account will be activated
                                        .</p>
                                
                                    <div class="login-separater text-center mb-4">
                                        <hr />
                                    </div>
                                    <div class="form-body">
                                      
                                        <form method="POST" action="{{ route('register_company') }}" >
                                            @csrf
                    
                                        
                                            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">

                                            <div class="box box-info padding-1">
                                                <div class="box-body">
                                                    <div class="row">
                                                        {{-- @if($form_type == 'all') --}}
                                                            {{-- <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="partner_type" >Partner Type</label>
                                                                    <select name="partner_type" id="partner_type" class="form-control{{ $errors->has('partner_type') ? ' is-invalid' : '' }}"  disabled>
                                                                        <option> Select</option>
                                                                        <option value="agent" selected {{ isset($partner)?$partner->actors->name === 'agent' ? 'selected' : '' : '' }}>Agent</option>
                                                                     
                                                                    </select>
                                                                    {!! $errors->first('partner_type', '<div class="invalid-feedback">:message</div>') !!}
                                                                </div>
                                                            </div> --}}
                                                      
                                                        
                                                    {{-- <div class="col-md-6 my-1">
                                                        <div class="form-group">
                                                            <label for="name">Name  </label>
                                                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-user'></i></span>
                                                            <input type="text" placeholder="Name" name="name" class="custom-bg form-control {{($errors->has('name') ? ' is-invalid' : '')}}" id="name" value="{{old('name',$partner->name??'')}}" autofocus required>
                                                            {!! $errors->first('name', '<div class="invalid-feedback">:message</div>') !!}</div>
                                                        </div>
                                                    </div> --}}
                                                    
                                                    {{-- <div class="col-md-6 my-1">
                                                        <div class="form-group mx-3">
                                                            <label for="email">Email  </label>
                                                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-message' ></i></span>
                                                            <input type="text" placeholder="Email" name="email" class="custom-bg form-control {{($errors->has('email') ? ' is-invalid' : '')}}" id="email" value="{{old('email',$partner->email??'')}}" autofocus required>
                                                            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}</div>
                                                        </div>
                                                    </div> --}}
                                                    {{-- <div class="row justify-content-center"> <!-- Add row and justify-content-center -->
                                                        <div class="col-md-4 my-1"> <!-- Adjust the column size -->
                                                            <div class="form-group">
                                                                <label for="company_name">Company Name</label>
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-transparent"><i class='bx bxs-factory'></i></span>
                                                                    <input type="text" placeholder="Company Name" name="company_name" 
                                                                           class="custom-bg form-control {{($errors->has('company_name') ? ' is-invalid' : '')}}" 
                                                                           id="company_name" value="{{old('company_name', $partner->company_name ?? '')}}" autofocus required>
                                                                    {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> --}}
                                                  
                                                     
                                                    <div class="col-md-6 my-1" id = "company_name_id">
                                                        <div class="form-group">
                                                            <label for="company_name">Company Name {{$email}}</label>
                                                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-factory'></i></span>
                                                            <input type="text" placeholder="Company Name" name="company_name" class="custom-bg form-control {{($errors->has('company_name') ? ' is-invalid' : '')}}" id="company_name" value="{{old('company_name',$partner->company_name??'')}}" autofocus required>
                                                            {!! $errors->first('company_name', '<div class="invalid-feedback">:message</div>') !!}</div>
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-md-6 my-1">
                                                        <div class="form-group mx-3">
                                                            <label for="address1">Address</label>
                                                            <div class="input-group"> <span class="input-group-text bg-transparent"><i class='bx bxs-map'></i></span>
                                                            <input type="text" placeholder="Address1" name="address1" class="custom-bg form-control {{($errors->has('address1') ? ' is-invalid' : '')}}" id="address1" value="{{old('address1',$partner->address1??'')}}" autofocus required>
                                                            {!! $errors->first('address1', '<div class="invalid-feedback">:message</div>') !!}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 my-1">
                                                        <div class="form-group">
                                                            <label for="city">City</label>
                                                            <input type="text" placeholder="City" name="city" class="custom-bg form-control {{($errors->has('city') ? ' is-invalid' : '')}}" id="city" value="{{$partner->city??''}}" autofocus required>
                                                            {!! $errors->first('city', '<div class="invalid-feedback">:message</div>') !!}
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    <div class="col-md-6 my-1">
                                                        <div class="form-group mx-3">
                                                            <label for="country">Country</label>
                                                            <select name="country" class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" id="country" autofocus required>
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
                                                      <!-- Hidden input for the previous email -->
                                                     <input type="hidden" name="email" value="{{$email}}">
                                             
                                                    <div class="box-footer mt20">
                                                        @php
                                                        $model=[
                                                            'notify_btn' => "Save And Register",
                                                            'function' => "Save",
                                                            'body' => 'Please Confirm do you realy want to Save?',
                                                            'btn-color' => 'primary',
                                                            'float' => "end mt-2",
                                                            'id' => "save"
                                                            ];
                                                        @endphp
                                                        @include('partials.modal', ['data'=>$model])
                                                    </div>
                                                    {{-- @endif --}}

                                                    {{-- </div> --}}
                                                </div>
                                            </div>

                                            {{-- javascript --}}

                          
                                        </form>
                                    </div>
                                </div>
                            {{-- </div>
                        </div> --}}
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js"></script>
    <script src="/assets/select2/js/select2.min.js"></script>
    

    
<script>
    $(document).ready(function() {
        // Listen for the form submit event
   // Listen for the keypress event on any input in the form
   $('form input').on('keypress', function(event) {
        if (event.which === 13) { // Enter key is pressed
            event.preventDefault(); // Prevent default form submission

            // Hide the save button (if you have any)
            $('#btn_save').hide();

            // Show the loader
            $('#loader').show();

            // Simulate a delay (2 seconds)
            setTimeout(() => {
                // Submit the form
                $(this).closest('form').submit();
                $('#loader').hide(); // Submit the form after delay
            }, 2000);
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
        // $('#name').on('input', function() {
        //     var inputValue = $(this).val();
        //     // Remove non-numeric characters
        //     var numericValue = inputValue.replace(/[^a-zA-Z\s]/g, '');
        //     // Limit to exactly 11 numbers
        //     // var elevenDigitValue = numericValue.slice(0, 11);
        //     // Update the input field value
        //     $(this).val(numericValue);
        // });
    
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


        // Set the selected city if editing
        var selectedCity = '{{ old("city") }}';
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
                    var flag = $('<span><span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text + '</span>');
                    return flag;
                }

                $('#prefix_phonecust').select2({
                    templateResult: formatCountry,
                    templateSelection: formatCountry,
                    placeholder: "Code",
                    width: '30%'// Adjust width as needed
                    

                });
                
                $('#prefix_whatsappcust').select2({
                    templateResult: formatCountry,
                    templateSelection: formatCountry,
                    placeholder: "Code",
                    width: '30%'// Adjust width as needed
                    

                });
            });
    </script>


    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>

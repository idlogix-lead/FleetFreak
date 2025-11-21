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
    
    <title>Company Registration Form</title>
    
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

        .form-control {
            /* height: 45px; */
            border-radius: 8px;
            box-shadow: none;
            border: 1px solid #ddd;
        }

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
        }
        #loader{
            display: none;
            position: fixed;
            left: 50%;
            top: 20%;
            transform: translate(-50%, -50%);
            z-index: 999;
        }
    </style>
</head>

<body>
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
        <div class="d-flex align-items-center justify-content-center">
            <div class="col mx-auto">
                <div class="card">
                    <div class="card-body">
                        <div class="text-center">
                            <h3>Company Registration Form</h3>
                        </div>
                        <p class="text-center">
                            Registered your email first:                        
                        </p>

                        <div class="login-separater text-center mb-4">
                            <hr />
                        </div>

                        <div class="form-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <div class="box box-info padding-1">
                                    <div class="box-body">
                                        <div class="row">
                                            <!-- Email Field -->
                                            {{-- <div class="row mb-3"> --}}
                                                <div class="row justify-content-center">
                                                    <div class="col-md-10">
                                                        <label for="email">Email</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-transparent">
                                                                <i class='bx bxs-message'></i>
                                                            </span>
                                                            <input type="text" placeholder="Email" name="email"
                                                                class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}"
                                                                id="email" value="{{ old('email', $partner->email ?? '') }}" autofocus required>
                                                            {!! $errors->first('email', '<div class="invalid-feedback">:message</div>') !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            {{-- </div> --}}

                                            <!-- Save Button -->
                                            {{-- <button type="button" class="btn btn-primary btn-sm float-end mt-2  t modd mx-1 my-3 "id="btn_save">
                                            register
                                            </button> --}}
                                            {{-- <div class="box-footer mt-4">
                                                @php
                                                $model = [
                                                    'notify_btn' => "Save And Register",
                                                    'function' => "Save",
                                                    'body' => 'Please confirm, do you really want to save?',
                                                    'btn-color' => 'primary',
                                                    'float' => "end mt-2",
                                                    'id' => "save"
                                                ];
                                                @endphp
                                                @include('partials.modal', ['data' => $model])
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end wrapper-->

    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/3.0.7/metisMenu.min.js"></script>
    <script src="/assets/select2/js/select2.min.js"></script>
    <script src="assets/js/app.js"></script>
    <script src="assets/js/register.js"></script>

</body>

</html>



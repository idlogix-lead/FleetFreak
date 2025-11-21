<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="assets/images/fleet_logo.png" type="image/png" />
    {{-- <link rel="icon" href="/assets/images/logo1.png" type="image/png" /> --}}

    <!--plugins-->
    <link href="assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="assets/css/pace.min.css" rel="stylesheet" />
    <script src="assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="assets/css/app.css" rel="stylesheet">
    <link href="assets/css/icons.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/alert/css/alert.min.css') }}" />
    <script src="{{ asset('assets/alert/js/alert.min.js') }}"></script>

    <title>{{ env('APP_NAME') }}</title>
    <style>
        .bg {
            background-image: url('assets/images/login_page_final.png');
            /* Replace with your image path */
            background-size: cover;
            /* Ensure the image covers the entire background */
            background-position: center;
            /* Center the image */
            background-repeat: no-repeat;
            /* Prevent image repetition */
        }
    </style>

</head>

<body class="bg">
    <div id="msgbox-area" class='msgbox-area'></div>

    <!--wrapper-->
    <div class="wrapper">

        @include('layouts.partials.error')
        @include('layouts.partials.success')
        {{-- <div class="authentication-header"></div> --}}
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        {{-- <div class="mb-4 text-center">
                            <img src="assets/images/new_logo1.png" width="180" alt="" />
                        </div> --}}
                        <div class="login-card">

                            <div class="card-body pt-2 pb-5">
                                <div class="mb-2  text-center">
                                    {{-- <br>
                                    <br>
                                    <img style="margin-right: 5px;" src="assets/images/login-fleetfreak-logo.png" width="180" alt="" /> --}}
                                    <img style="margin-right: 5px;" src="assets/images/login-fleetfreak-logo1.png"
                                        width="180" alt="" />

                                </div>
                                <div class="p-4 rounded">
                                    <div class="text-center ">
                                        <h3 class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-log-in">
                                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                                <polyline points="10 17 15 12 10 7"></polyline>
                                                <line x1="15" y1="12" x2="3" y2="12">
                                                </line>
                                            </svg>Sign in
                                        </h3>
                                        {{-- Welcome back!</h3> --}}

                                        {{-- <p>Don't have an account yet? <a href="{{ url('register') }}">Sign
                                                up here</a>
                                        </p> --}}
                                    </div>

                                    <br>
                                    <div class="form-body">

                                        <form method="POST" action="{{ route('login') }}" class="row g-3">

                                            @csrf

                                            <div class="col-10 mx-auto">
                                                <label for="email"
                                                    class="form-label">{{ __('Email Address') }}</label>


                                                <input id="email" type="email" placeholder = "Enter Your Email"
                                                    class="form-control form-css @error('email') is-invalid @enderror"
                                                    name="email" value="{{ old('email') }}" autocomplete="email"
                                                    autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror

                                            </div>

                                            <div class="col-10 mx-auto">
                                                <label for="password" class="form-label">{{ __('Password') }}</label>

                                                <div class="input-group " id="show_hide_password">
                                                    <input id="password" type="password"
                                                        class="form-control form-css @error('password') is-invalid @enderror"
                                                        name="password" placeholder = "Enter Your Password"
                                                        autocomplete="current-password"><a href="javascript:;"
                                                        class="input-group-text form-css bg-transparent "><i
                                                            class='bx bx-hide '></i></a>

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="row g-2 checkfrm ">
                                                <div class="col-md-6 ">
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox" name="remember"
                                                            id="remember" {{ old('remember') ? 'checked' : '' }}>
                                                        <label class="form-check-label ms-0"
                                                            for="remember">{{ __('Remember Me') }}</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 link-color ">
                                                    @if (Route::has('password.request'))
                                                        <a href="{{ route('password.request') }}"
                                                            class="me-2 link-color">{{ __('Forgot Password?') }}</a>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-4 mx-auto">
                                                <div class="d-grid">
                                                    <a href="{{ route('register') }}"
                                                        class="btn btn-sm btn-success"><i
                                                            class="bx bxs-lock-open"></i>
                                                        {{ __('Sign Up') }}
                                                    </a>
                                                </div>

                                            </div>
                                            <div class="col-4 mx-auto">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-sm btn-primary"><i
                                                            class="bx bxs-lock-open"></i>
                                                        {{ __('Sign In') }}
                                                    </button>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </div>
    </div>
    <!--end wrapper-->
    <!-- Bootstrap JS -->
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--Password show & hide js -->
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
    <!--app JS-->
    <script src="assets/js/app.js"></script>
</body>

</html>

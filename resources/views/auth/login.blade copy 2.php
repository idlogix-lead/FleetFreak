<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="/assets/images/logo.png" type="image/png" />

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
    <title>{{env('APP_NAME')}}</title>
    <style>
        .login-container {
            display: flex;
            min-height: 100vh;
        }
        .login-image {
            flex: 0 0 60%; /* 70% width */
            background-image: url('assets/images/login_screen1.jpg'); /* Replace with your image path */
            background-size: cover;
            background-position: center;
        }
        .login-form-container {
            flex: 0 0 40%; /* 30% width */
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f7f7f7;
            padding: 2rem;
        }
        .login-form-content {
            width: 100%;
        }
        @media (max-width: 991.98px) {
            .login-image {
                display: none;
            }
            .login-form-container {
                flex: 1 0 100%; /* Full width on smaller screens */
            }
        }
        .checkfrm{
            margin-left: 100px;
        }
    </style>
</head>

<body style="background-color: #666666;">
    <!--wrapper-->
    <div class="wrapper login-container">
        <div class="login-image"></div>
        <div class="login-form-container">
            <div class="login-form-content">
                <div class="mb-4 text-center">
                    <img src="assets/images/new_logo1.png" width="220" alt="" />
                </div>
                <div>
                    <div class="text-center mb-4">
                        <h3 class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                <polyline points="10 17 15 12 10 7"></polyline>
                                <line x1="15" y1="12" x2="3" y2="12"></line>
                            </svg> Sign in
                        </h3>
                    </div>
                    <div class="form-body">
                        <form method="POST" action="{{ route('login') }}" class="row g-3">
                            @csrf
                            <div class="col-8 mx-auto">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" placeholder="Enter Your Email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-8 mx-auto">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group" id="show_hide_password">
                                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Enter Your Password" required autocomplete="current-password">
                                    <a href="javascript:;" class="input-group-text bg-transparent"><i class='bx bx-hide'></i></a>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        
                            <div class="row g-2 checkfrm ">
                                <div class="col-md-4 ">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label ms-2" for="remember">{{ __('Remember Me') }}</label>
                                    </div>
                                </div>
                                <div class="col-md-4  ">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="me-2">{{ __('Forgot Your Password?') }}</a>
                                    @endif
                                </div>
                            </div>
                        
                            <div class="col-4 mx-auto">
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bx bxs-lock-open"></i> {{ __('Sign In') }}
                                    </button>
                                </div>
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

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="iso-8859-1">
    <meta meta name="csrf-token" content="{{ csrf_token() }}" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="/assets/images/logo1.png" type="image/png" />
    <!--plugins-->
    @yield('style')
    <link href="/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="/assets/css/bootstrap-extended.css?v=1.1" rel="stylesheet">
    <link href="/assets/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/app.css?v=1.0.1">

    <link href="/assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    {{-- font awesome --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Theme Style CSS -->
    @if (auth()->user()->theme == 'dark-theme')
        <link rel="stylesheet" href="{{ asset('assets/css/dark-theme.css') }}" />
    @elseif(auth()->user()->theme == 'semi-dark')
        <link rel="stylesheet" href="{{ asset('assets/css/semi-dark.css') }}" />
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/header-colors.css') }}" />
    {{-- select2 css file --}}
    <link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}" />
    {{-- msgbox alert css --}}
    {{-- <link rel="stylesheet" href="{{ asset('assets/alert/css/alert.min.css') }}"/> --}}
    <link rel="stylesheet" href="{{ asset('assets/alert/css/alert.min.css') }}" />
    <script src="/assets/alert/js/alert.min.js"></script>
    {{-- msgbox alert js --}}
    {{-- <script src="/assets/alert/js/alert.min.js"></script> --}}
    <!-- js Toaster -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    <script src="/assets/js/jquery.min.js"></script>
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    {{-- leaflet js map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>



       {{-- --------End--------- --}}

    <title>{{env('APP_NAME')}}</title>
    <style>
        .bg{
            background-image: url('assets/images/login_page_final.png');
            background-size: cover;
            background-position: center;
            /* background-color: black; */
            
        }
        .card{
            margin-left: 10%;
            margin-top: 9%;
            height: 85%;
            width: 83%;
            border-radius: 12px;
            box-shadow: rgba(0, 0, 0, 0.30) 0px 5px 20px;
            
            
            
            background-color: rgb(249, 249, 249,0.9);
        }
       
        .form-css{
            height: 35px;
        }
        .checkfrm{
            margin-left: 25px;
        }
    </style>
</head>

<body class="bg">
    <!--wrapper-->
    <div class="wrapper">
        <div class="authentication-header"></div>
        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
            <div class="container-fluid">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                    <div class="col mx-auto">
                        {{-- <div class="mb-4 text-center">
                            <img src="assets/images/new_logo1.png" width="180" alt="" />
                        </div> --}}
                        <div class="card">
                            
                            <div class="card-body pt-2 pb-5">
                                <div class="mb-2  text-center">
                                    <img style="margin-right: 5px;" src="assets/images/new_logo-01.png" width="180" alt="" />
                                </div>
                                <div class="p-4 rounded">
                                    <div class="text-center ">
                                        <h3 class="">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>  Change Password</h3>
                                        {{-- <p>Don't have an account yet? <a href="{{ url('register') }}">Sign
                                                up here</a>
                                        </p> --}}
                                    </div>
                                   
                                    <br>
                                    <div class="form-body">
                                        <form method="POST" action="{{ route('password.change.update') }}" class="row g-3">
                                            @csrf

                                            <div class="col-10 mx-auto">
                                                <label for="password" class="form-label">{{ __('New Password') }}</label>
                    
                                                <div class="input-group " >
                                                    <input id="password" type="password" class="form-control form-css @error('password') is-invalid @enderror" name="password" placeholder = "Enter Your Password"  autocomplete="current-password"><a href="javascript:;"
                                                    class="input-group-text form-css bg-transparent "><i
                                                        class='bx bx-hide '></i></a>
                    
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                               
                                            </div>
                                            <div class="col-10 mx-auto">
                                                <label for="password" class="form-label">{{ __('Confirm Password') }}</label>

                                                <div class="input-group " >
                                                    <input id="password_confirmation" type="password" class="form-control form-css @error('password_confirmation') is-invalid @enderror" name="password_confirmation" placeholder = "Enter Password Again"  autocomplete="current-password"><a href="javascript:;"
                                                    class="input-group-text form-css bg-transparent "><i
                                                        class='bx bx-hide '></i></a>
                    
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                           
                                            <div class="col-8 mx-auto">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
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

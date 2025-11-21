<!DOCTYPE html>
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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/css/flag-icon.min.css">
        <link rel="stylesheet" href="{{ asset('assets/css/company-registration.css') }}">
    
        <title>Company Registration Form</title>
    
    
    </head>
    <style>
        .success-icon-container {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .success-icon {
            width: 80px;
            height: 80px;
        }

        .circle {
            stroke: green;
            stroke-width: 4;
            fill: none;
            stroke-dasharray: 314; /* Circumference of the circle */
            stroke-dashoffset: 314; /* Initially hide the stroke */
            animation: drawCircle 1s ease-out forwards; /* Animate the circle for 1 second */
        }

        .tick {
            stroke: green;
            stroke-width: 4;
            fill: none;
            stroke-dasharray: 70; /* Length of the tick */
            stroke-dashoffset: 50; /* Initially hide the tick */
            animation: drawTick 0.5s ease-out 1s forwards; /* Animate the tick after 1s (when circle finishes) */
            opacity: 0; /* Initially hidden */
        }

        @keyframes drawCircle {
            to {
                stroke-dashoffset: 0; /* Show the full stroke */
            }
        }

        @keyframes drawTick {
            0% {
                stroke-dashoffset: 50;
                opacity: 0; /* Hide the tick */
            }
            100% {
                stroke-dashoffset: 0;
                opacity: 1; /* Show the tick when animation starts */
            }
        }

        /* Center the content */
        .card {
            text-align: center;
            margin-top: 50px;
        }
    </style>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5">
                    <div class="card-header text-center">
                        <!-- Animated Green Tick Icon with Circle -->
                        <div class="success-icon-container">
                            <svg class="success-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100">
                                <!-- Background Circle -->
                                <circle class="circle" cx="50" cy="50" r="48"></circle>
                                <!-- Animated Tick -->
                                <path class="tick" d="M30 50 L45 65 L70 40"></path>
                            </svg>
                        </div>
                        {{-- <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="100" height="100" viewBox="0 0 64 64">
                            <path fill="#98c900" d="M54,20c0-5.523-4.477-10-10-10H32H20c-5.523,0-10,4.477-10,10v24.008c0,5.523,4.477,10,10,10h12h12 c5.523,0,10-4.477,10-10V20z"></path><ellipse cx="32" cy="61" opacity=".3" rx="20" ry="3"></ellipse><path fill="#fff" d="M14.01,12H14c-2.24,1.69-3.75,4.29-3.97,7.25C10.01,19.49,10,19.75,10,20v12 c2.761,0,5-2.239,5-5v-7c0-0.108,0.003-0.221,0.017-0.38c0.102-1.375,0.778-2.65,1.862-3.525c0.048-0.033,0.095-0.068,0.142-0.103 C17.881,15.343,18.911,15,20,15h5c2.761,0,5-2.239,5-5H20C17.75,10,15.68,10.74,14.01,12z" opacity=".3"></path><path d="M54,44V22c-2.761,0-5,2.238-5,5v17c0,2.757-2.243,5-5,5h-5c-2.761,0-5,2.238-5,5h10 C49.523,54,54,49.523,54,44z" opacity=".15"></path><path fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10" stroke-width="3" d="M13.5,23.5V20c0-0.153,0.005-0.312,0.018-0.459c0.135-1.809,1.003-3.46,2.396-4.594l0.204-0.152"></path><path fill="#edff9c" d="M26.727,42.104l-8.43-8.457c-0.397-0.398-0.397-1.044,0-1.442l2.875-2.884	c0.397-0.398,1.041-0.398,1.438,0l6.135,6.154l12.428-14.13c0.371-0.422,1.014-0.463,1.435-0.09l3.049,2.699	c0.421,0.373,0.461,1.017,0.09,1.439L31.17,41.965C30.007,43.288,27.971,43.352,26.727,42.104z"></path>
                            </svg> --}}
                        <h3>Registration Successful</h3>
                    </div>
                    <div class="card-body text-center">
                        <p>You have been registered successfully!</p>
                        <p>Please click the link below to continue to the login page.</p>
                        <a href="{{ route('login') }}" class="btn btn-sm btn-primary">Go to Login Page</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

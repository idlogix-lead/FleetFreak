<!doctype html>
<html lang="en"
    class="{{ auth()->user()->theme }} {{ auth()->user()->header_color ? 'color-header ' . auth()->user()->header_color : '' }} {{ auth()->user()->sidebar_color ? 'color-sidebar ' . auth()->user()->sidebar_color : '' }}">

<head>

    <!-- Required meta tags -->
    <meta charset="iso-8859-1">
    <meta meta name="csrf-token" content="{{ csrf_token() }}" charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="/assets/images/fleet_logo.png" type="image/png" />
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
    {{-- <link rel="stylesheet" href="/assets/css/app.css?v=1.0.1"> --}}
    <link href="assets/plugins/highcharts/css/highcharts.css" rel="stylesheet" />
	<link href="assets/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet" />
    
    <link href="/assets/css/icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    {{-- font awesome --}}
    {{-- <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    {{-- new-form css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/new-form.css') }}">
    {{-- flatpicker --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_blue.css">

    {{-- {{ calnder }} --}}
    {{-- <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css" />
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script> --}}
    {{-- <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css" />
    <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script> --}}




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
    {{-- datatable css --}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery.dataTables.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/datatable.css') }}" />

    <script src="/assets/alert/js/alert.min.js"></script>
    {{-- msgbox alert js --}}
    {{-- <script src="/assets/alert/js/alert.min.js"></script> --}}
    <!-- js Toaster -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" rel="stylesheet">

    <script src="/assets/js/jquery.min.js"></script>
     {{-- Data Tables js --}}
    {{-- <script src="/assets/js/jquery-3.6.0.min.js"></script> --}}
    <script src="/assets/js/jquery.dataTables.min.js"></script>
    
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}
    {{-- leaflet js map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.css" />
    <script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <title>{{ env('APP_NAME') }}</title>
    {{-- haris index table css --}}
    <style>
        .table-montserrat {
         font-family: 'Montserrat', sans-serif;
         color: #5b676d;
         }
        .t-head-clr{
         /* color: #1A2E97; */
         color:  #640D5F !important;
         font-size: 13px;
         }
         .body-font{
            font-size:13px;
         }

         /* css for btns and model filteration */
        .btn-grey{
                background-color: #ebf2f5;
            }
        .search-container {
            display: flex;
            align-items: center;
            background-color: #ebf2f5;
            padding: 0px 8px;
            /* border-radius: 15px; */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .search-container input {
            border: none;
            background: none;
            outline: none;
            padding-left: 10px;
            font-size: 16px;
            color: #555;
        }

        .search-container i {
            font-size: 18px;
            color: #888;
        }
        .custom-modal-width {
          max-width: 320px;
        }
        label{
            font-family: 'Montserrat', sans-serif;
        }
        .custom-input-width{
            max-width: 250px;
            font-family: 'Montserrat', sans-serif;
        }
        .custom-btn {
            /* padding: 0.1rem 0.1rem; Smaller padding */
            padding-left: 1%;
            padding-right: 1%;
            font-size: 0.75rem; /* Smaller font size */
            width: 60%; /* Full width */
        }
        .custom-btn-filter {
            /* padding: 0.1rem 0.1rem; Smaller padding */
            /* margin-left: 15%; */
            padding-left: 1%;
            padding-right: 1%;
            font-size: 0.75rem; /* Smaller font size */
            width: 70%; /* Full width */
        }



    </style>

       {{-- --------End--------- --}}
</head>

<body>

    <div id="msgbox-area" class='msgbox-area'></div>

    <script>
        // msgboxbox.show("helloooo",'warning', null);
    </script>
    @include('layouts.partials.loader')


    <!--wrapper-->
    <div class="wrapper">

        <!--navigation-->
        @include('layouts.nav')
        <!--end navigation-->

        <!--start header -->
        @include('layouts.header')
        {{-- msgbox messages --}}

        @include('layouts.partials.error')
        @include('layouts.partials.success')
        {{-- @include('layouts.partials.save_request') --}}

        {{-- ------------------------------------ --}}
        <!--end header -->

        <!--start page wrapper -->
        <div class="page-wrapper">
            <div class="page-content">
                @yield('wrapper')
            </div>
        </div>
        <!--end page wrapper -->
        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
                class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">FleetFreak Company © 2024. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->
    <!--start switcher-->
    {{-- @include('layouts.partials.theme') --}}
    <!--end switcher-->
    {{-- select2 js file --}}
    <script src="/assets/select2/js/select2.min.js"></script>

    {{-- <script src="/assets/alert/js/alert.js"></script> --}}

    <!-- Bootstrap JS -->


    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <!--plugins-->
    {{-- <script src="/assets/js/jquery.min.js"></script> --}}
    <script src="/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!--app JS-->
    <script src="/assets/js/app.js"></script>


    <!--Toaster JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

 {{-- <link rel="stylesheet" href="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <script src="https://uicdn.toast.com/tui-code-snippet/latest/tui-code-snippet.min.js"></script>
    <script src="https://uicdn.toast.com/tui-time-picker/latest/tui-time-picker.min.js"></script>
    <script src="https://uicdn.toast.com/tui-date-picker/latest/tui-date-picker.min.js"></script> --}}
    {{-- <script src="https://uicdn.toast.com/tui-calendar/latest/tui-calendar.min.js"></script> --}}
    @yield('script')
    @include('layouts.theme-control')


    {{-- <script type="module">
        // Import the functions you need from the SDKs you need
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
        import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
        import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

        // import "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-sw.js";
        // import { } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-push-scope.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyCzqd535JYGW_RXtv849TeDLA_MT4Q8KOc",
            authDomain: "zaroon-3f2ae.firebaseapp.com",
            projectId: "zaroon-3f2ae",
            storageBucket: "zaroon-3f2ae.appspot.com",
            messagingSenderId: "674096357358",
            appId: "1:674096357358:web:5a959f3e4514f31564f8ff",
            measurementId: "G-NSE3DC50Z9"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);

        // Initialize Firebase Cloud Messaging and get a reference to the service
        const messaging = getMessaging(app);

        console.log(messaging,app);

        const vapidKey  = 'BC6wWY7dgKUzOHI5uMcRVqFTb79L2l3G2DsvuJhpk_VaEd9c8N03mbn-e1h3EWR4Mbdr5JgFmIDfgydTlDqD9zM';


        // var registration = navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' });navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' })
        // var registration;



        if(true){
            navigator.serviceWorker.register('/firebase-messaging-sw.js', { type: 'module' })
            .then(function(registration) {
                console.log('Service worker registration successful:', registration);
                getToken(messaging,{
                    serviceWorkerRegistration: registration,
                    vapidKey: vapidKey
                }).then((currentToken) => {
                    if (currentToken) {
                        // Send the token to your server and update the UI if necessary
                        // ...
                        $.post('/save-firebase-token', {
                            token: currentToken
                        });
                        console.log('Token generated:', currentToken);
                        saveToken(currentToken);
                    } else {
                        // Show permission request UI
                        console.log('No registration token available. Request permission to generate one.');
                        // ...
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                    // ...
                });
                Call useServiceWorker() here using the registration object
            })
            .catch(function(error) {
                console.error('Service worker registration failed:', error);
            });
        }


        // console.log(registration);

        console.log(app, analytics, messaging   )
    </script>
    {{-- app.blade.php


    {{-- <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
        import {
            getMessaging,
            getToken
        } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

        const firebaseConfig = {
            apiKey: "AIzaSyCzqd535JYGW_RXtv849TeDLA_MT4Q8KOc",
            authDomain: "zaroon-3f2ae.firebaseapp.com",
            projectId: "zaroon-3f2ae",
            storageBucket: "zaroon-3f2ae.appspot.com",
            messagingSenderId: "674096357358",
            appId: "1:674096357358:web:5a959f3e4514f31564f8ff",
            measurementId: "G-NSE3DC50Z9"
        };

        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const messaging = getMessaging(app);

        messaging.onMessage((payload) => {
            console.log('Message received. ', payload);
            const notificationTitle = payload.notification.title;
            const notificationOptions = {
                body: payload.notification.body,
                icon: payload.notification.icon
            };
            new Notification(notificationTitle, notificationOptions);
        });

        Notification.requestPermission().then(permission => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');
                getToken(messaging, {
                    vapidKey: 'YOUR_VAPID_KEY'
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log('Token retrieved:', currentToken);
                        $.post('/save-token', {
                            token: currentToken
                        });
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });

        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(function(registration) {
                    console.log('Service Worker registered with scope:', registration.scope);
                }).catch(function(err) {
                    console.log('Service Worker registration failed:', err);
                });
        }

        console.log(app, analytics, messaging )
    </script> --}}


</body>
{{-- <script type="module">



    // Import the functions you need from the SDKs you need
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
    import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
    import { getMessaging, getToken } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

    // firebase service worker
    // import "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-sw.js";

    // import { } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging-push-scope.js";
    // TODO: Add SDKs for Firebase products that you want to use
    // https://firebase.google.com/docs/web/setup#available-libraries

    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional


    const firebaseConfig = {
    apiKey: "AIzaSyBw5V3atE7_EdfCHXc9kKEQK9r-iGSE8Ts",
    authDomain: "tms-zaroon.firebaseapp.com",
    projectId: "tms-zaroon",
    storageBucket: "tms-zaroon.appspot.com",
    messagingSenderId: "403926211854",
    appId: "1:403926211854:web:a51abe3ebdefb6a916cb5f",
    measurementId: "G-7P8VNJEX8S"
  };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);

    // Initialize Firebase Cloud Messaging and get a reference to the service
    const messaging = getMessaging(app);

    console.log(messaging,app);
    const vapidKey = "BN8a3jJdKNw0g8BSNlF4lzjm0hTXtD2XiqBOvjA0AmNoYdQZg-JEq_owlFgyIj8ZTWErgWHzL1OtVqz6ihMr6C8"



    // var registration = navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' });navigator.serviceWorker.register('/public/js/core/firebase/firebase-messaging-sw.js', { type: 'module' })
    // var registration;
    function service_worker(){
        if(true){
            navigator.serviceWorker.register('firebase-messaging-sw.js', { type: 'module' })
            .then(function(registration) {
                // console.log('Service worker registration successful:', registration);
                getToken(messaging,{
                    serviceWorkerRegistration: registration,
                    vapidKey: vapidKey
                }).then((currentToken) => {
                    if (currentToken) {
                        // Send the token to your server and update the UI if necessary
                        // ...
                        console.log(currentToken);
                        // $.post("{{url('/store_fcm')}}",{
                        //     '_token':'{{csrf_token()}}',
                        //     'firebase_web_token':currentToken,
                        // }).then(function(resp){
                            // console.log(resp);
                        // });

                    } else {
                        // Show permission request UI
                        console.log('No registration token available. Request permission to generate one.');
                        // ...
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                    // ...
                });
                // Call useServiceWorker() here using the registration object
            })
            .catch(function(error) {
                console.error('Service worker registration failed:', error);
            });
        }
    }
    $(document).ready(function(){
        // service_worker()
        setInterval(service_worker(), 300000);
    })


    // console.log(registration);


    console.log(app, analytics, messaging   )
</script>  --}}
<script type="module">
    // Import the functions you need from the SDKs you need
    import {
        initializeApp
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-app.js";
    import {
        getAnalytics
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-analytics.js";
    import {
        getMessaging,
        getToken
    } from "https://www.gstatic.com/firebasejs/10.11.1/firebase-messaging.js";

    // Your web app's Firebase configuration
    const firebaseConfig = {
        apiKey: "AIzaSyBw5V3atE7_EdfCHXc9kKEQK9r-iGSE8Ts",
        authDomain: "tms-zaroon.firebaseapp.com",
        projectId: "tms-zaroon",
        storageBucket: "tms-zaroon.appspot.com",
        messagingSenderId: "403926211854",
        appId: "1:403926211854:web:a51abe3ebdefb6a916cb5f",
        measurementId: "G-7P8VNJEX8S"
    };

    // Initialize Firebase
    const app = initializeApp(firebaseConfig);
    const analytics = getAnalytics(app);
    const messaging = getMessaging(app);

    console.log(app, analytics, messaging);

    const vapidKey = "BN8a3jJdKNw0g8BSNlF4lzjm0hTXtD2XiqBOvjA0AmNoYdQZg-JEq_owlFgyIj8ZTWErgWHzL1OtVqz6ihMr6C8";

    function service_worker() {
        navigator.serviceWorker.register('/firebase-messaging-sw.js', {
                type: 'module'
            })
            .then(function(registration) {
                getToken(messaging, {
                    serviceWorkerRegistration: registration,
                    vapidKey: vapidKey
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log(currentToken);
                        $.post("{{ url('/store_fcm') }}", {
                            '_token': '{{ csrf_token() }}',
                            'firebase_web_token': currentToken,
                        }).then(function(resp) {
                            console.log(resp);
                        });
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            })
            .catch(function(error) {
                console.error('Service worker registration failed:', error);
            });
    }

    $(document).ready(function() {
        setInterval(service_worker, 300000);
    });

    console.log(app, analytics, messaging);
</script>

</html>

<!DOCTYPE html>
<html>
    <head>
        <title>{{ config('app.name') }}</title>
        <meta name="keywords" content="HTML, CSS, JavaScript , jQuery">
        <meta name="description" content="School Management System">
        <meta name="author" content="Haris Technical Solutions">
        <meta name="author" content="M Haris Maqsood">
        <meta name="keywords" content="School Management,{{ config('app.name') }}">
        <meta name="description" content="{{ config('app.name') }}">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="{{ csrf_token() }}">
        <link rel="shortcut icon" href="{{ asset('/favicon.ico') }}">
        {{-- <link href="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('assets/fonts/feather-font/css/iconfont.css') }}" rel="stylesheet" />
        {{-- <link href="{{ asset('assets/plugins/flag-icon-css/css/flag-icon.min.css') }}" rel="stylesheet" /> --}}
        <link href="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css') }}" rel="stylesheet" />
        {{-- <link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet"> --}}
        <script src="{{ asset('js/ext/fontawesome.min.js') }}"></script>
        {{-- <link href="{{ asset('css/brands.min.css') }}" rel="stylesheet"> --}}
        <link href="{{ asset('css/solid.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/checkbox.css') }}" rel="stylesheet">

        {{-- <script defer src="{{ asset('js/ext/brands.js') }}"></script> --}}
        <script defer src="{{ asset('js/ext/solid.min.js') }}"></script>
        {{-- msgbox library toaster --}}
        <script src="{{asset('js/alert/alert.min.js')}}"></script>
        <link rel='stylesheet' href="{{asset('css/alert.min.css')}}">

        @stack('plugin-styles')
        <link rel="stylesheet" href="{{asset('css/bootstrap-tagsinput.css')}}">
        {{-- <link href="{{ asset('assets/plugins/mdb/css/mdb.min.css') }}" rel="stylesheet" /> --}}
        {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.min.css" rel="stylesheet" /> --}}

        @if(Auth()->User()->theme == 1)
            <link href="{{ asset('css/app-light.min.css') }}" rel="stylesheet" />
            <link href="{{ asset('css/daterangepicker-light.min.css') }}" rel="stylesheet" />
        @elseif (Auth()->User()->theme == 0)
            <link href="{{ asset('css/app-dark.min.css') }}" rel="stylesheet" />
            <link href="{{ asset('css/daterangepicker-dark.min.css') }}" rel="stylesheet" />
        @endif

        <script src="{{asset('js/moment.min.js')}}"></script>
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <link href="{{ asset('css/print.css') }}" rel="stylesheet"  media="print" >

        @stack('style')
    </head>
    <body data-base-url="{{ url('/home') }}" class='sidebar-dark'>
        {{-- msgbox library toaster placeholder div --}}
        <div id="msgbox-area" class="msgbox-area"></div>
        <script src="{{ asset('assets/js/spinner.js') }}"></script>
        {{-- <script src="{{ asset('assets/js/awsom.js') }}"></script> --}}
        {{-- <script src="{{ asset('js/jquery.print.js') }}"></script> --}}
        
        <style>
            .breadcrumb .breadcrumb-item a{
                color:#727cf5;
            }
            .breadcrumb .breadcrumb-active a {
                color:red;
            }
        </style>

        @if (auth()->user()->superadmin())
            @include('layout.admin.master')
        @elseif (auth()->user()->businesses()->allow_subscription)
            @include('layout.partials.subs_master')
        @else
            @include('layout.partials.non_subs_master')
        @endif


        <script src="{{ asset('js/app.min.js') }}"></script>
        <script src="{{ asset('js/pro.js') }}"></script>
        <script src="{{ asset('assets/plugins/feather-icons/feather.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
        {{-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.2.0/mdb.umd.min.js"></script> --}}
        {{-- <script type="text/javascript" src="{{ asset('assets/plugins/mdb/js/mdb.umd.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('assets/plugins/mdb/js/mdb.helper.js') }}"></script> --}}
        @stack('plugin-scripts')
        <script src="{{ asset('js/bootstrap-tagsinput.min.js')}}"></script>
        {{-- <script src="{{ asset('assets/js/template.min.js') }}"></script> --}}
        <script src="{{ asset('assets/js/template.js') }}"></script>
        @stack('custom-scripts')
        <script src="{{asset('js/dblclick.js')}}"></script>
        {{-- <script src="{{ asset('assets/plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/plugins/promise-polyfill/polyfill.min.js') }}"></script> --}}
        {{-- <script src="{{ asset('assets/js/sweet-alert.min.js') }}"></script> --}}
    </body>
</html>
@extends('layouts.app')

{{-- <link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" /> --}}
{{-- <script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script> --}}
<!-- Bootstrap CSS -->
{{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"> --}}

<!-- jQuery -->
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

<!-- Popper.js (required for dropdowns) -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> --}}

<!-- Bootstrap JS -->
{{-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}

<link href="{{ asset('assets/css/calendar.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/plugins/bootstrap-material-datetimepicker/js/moment.min.js') }}" ></script>


<!-- main content -->
<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
<link rel="stylesheet" href="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.css">
<link rel="stylesheet" href="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.css">

<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.js"></script>
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>



{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<style>
    .toastui-calendar-detail-container .toastui-calendar-section-header{
        margin-bottom: 0% !important;
    }
    .toastui-calendar-template-popupDetailDate{
        color: #640d5f !important;
    }

    /* .checkbox_label{
        padding-left: 25px !important;
    }
    .sidebar-item{
        padding-bottom: 0px;
    }
    .toastui-calendar-popup-container, .toastui-calendar-see-more-container{
        position: fixed !important;
        top:20% !important;
        left:50% !important;
        transform: translate(-50%, -20%) !important;
    }
    .toastui-calendar-popup-arrow.toastui-calendar-left, .toastui-calendar-popup-arrow-border, .toastui-calendar-popup-arrow.toastui-calendar-right, .toastui-calendar-popup-arrow-fill {
        display: none;
    } */
    /* .toastui-calendar-popup-arrow.toastui-calendar-right .toastui-calendar-popup-arrow-border */
    /* .toastui-calendar-detail-container{
        box-shadow: 0 2px 90px 0 rgb(0 0 0 / 48%) !important;
    }
    #lead_created[type="checkbox"]:checked + label:before {
        border-right: 2px solid green !important;
        border-bottom: 2px solid green !important;
    }
    #lead_reminder[type="checkbox"]:checked + label:before {
        border-right: 2px solid red !important;
        border-bottom: 2px solid red !important;
    }
    #calendar{
        position: relative;
        min-height: 700px;
        border: 1px solid rgb(194, 194, 194);
        min-width: 700px;
    }
    .responsive{
        overflow-x: auto;
    } */
    /* .scroll{ */
        /* max-height:300px; */
        /* overflow-y:auto; */
        /* min-width:200px; */
        /* transform: scaleX(-1); */
        /* direction: rtl; */
    /* } */

</style>
@section('wrapper')
    <div class="container-fluid">
        <h3 class="mb-2">Calendar</h3>
        <div class="mb-1">
            <p style="color: #640d5f; display: inline;">Fleet Freak / </p>
            <p style="color: #696b65; display: inline;">Calendar </p>
        </div>



        {{-- @if (Auth::user()->id == ) --}}
            <div class="row">
                <div class="col-sm-12 d-flex align-items-center mb-3 mr-2 ml-2">
                    @php
                        $vehicles = App\Models\Vehicle::VehicleDropdown();
                    @endphp
                     {{-- @dd($vehicles); --}}
                    <!-- Multi-select with Select2 -->
                   <select id="userSelect" class="form-control col-md-8 col-sm-6 mr-2" style="width: 40% !important">
                    <option value="">Select---</option>
                    @foreach ($vehicles as $vehicle)

                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }}</option>
                        @endforeach
                    </select>

                    {{-- <a href="{{ route('calendar.vehicle.filter') }}"> --}}
                    <!-- Filter Button -->
                    {{-- <button id="filterBtn" class="btn btn-primary ml-2 mr-2" style="margin: 0 20px 0 20px !important ">Filter</button> --}}
                    <button type="button" id="filterBtn" class="btn btn-primary ml-2 mr-2" style="margin: 0 20px 0 20px !important">Filter</button>

                    {{-- </a> --}}

                    <!-- Reset Button -->
                    <a href="{{ route('calendar.index') }}">
                        <button type="button" class="btn btn-secondary" id="resetBtn">Reset</button>
                    </a>
                </div>
            </div>
        {{-- @endif --}}



    </div>



    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">


                    <!-- Calendar toolbar -->
                    <div class="calendar-toolbar">
                        <input type="hidden" id="defaultViewType" value="month">

                        <div class="btn-group ml-3 mr-2">
                            <button type="button" class="btn dropdown-toggle monthly-color" id="viewToggleBtn"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                Monthly
                            </button>

                            <div class="dropdown-menu">
                                <button class="dropdown-item" id="dayViewBtn">Daily</button>
                                <button class="dropdown-item" id="weekViewBtn">Weekly</button>
                                <button class="dropdown-item" id="monthViewBtn">Monthly</button>
                            </div>
                        </div>
                        <div>
                            <button id="todayBtn" class="todaybtn btn btn-danger">Today</button>
                            <div class="btn-group ml-2" role="group" aria-label="Basic example"
                                style="margin-left: 10px !important;">
                                <button id="prevBtn" class="btn btn-rounded-x prev-next prev-btn">
                                    < </button>
                                        <button id="nextBtn" class="btn btn-rounded-x prev-next next-btn">></button>
                            </div>
                            <span id="selectedMonth" class="ml-2"></span>
                            {{-- @if ($user)
                                 <button class="btn btn-primary">{{$user->name}} </button>
                                 @endif --}}

                            {{-- <span class="float-right">filter</span> --}}
                            {{-- <button id="filterBtn" class="btn btn-primary">Filter</button> --}}
                        </div>



                    </div>

                    <!-- Calendar container -->
                    <div id="calendar"></div>

                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
      var vehicleId = null;
        document.addEventListener('DOMContentLoaded', function () {
            console.log('DOM fully loaded and parsed');

            const filterBtn = document.getElementById('filterBtn');
            console.log(filterBtn);

            filterBtn.addEventListener('click', function () {
                console.log('Filter button clicked');
                const userSelect = document.getElementById('userSelect'); // Vehicle dropdown
                const vehicleId = userSelect.value; // Get the selected vehicle ID
                console.log(vehicleId);
                // getVehicleId(vehicleId);
                get_data();

                if (!vehicleId) {
                    alert('Please select a vehicle.');
                    return;
                }
            });
        });
        const GET_DATA_URL = "{{route('calendar.get_events')}}";
        const CSRF_TOKEN = "{{csrf_token()}}";
        const ADD_MAINTENANCE_ROUTE = "{{route('inspections.create')}}";
    </script>
    <script src="{{asset('assets/js/tui_calendar_app.js?v='.config('system.versioning'))}}"></script>
    {{-- @include('calendar.calendar_script'); --}}
@endsection

{{-- <script>
    $(document).ready(function() {
        // Initialize the calendar
        console.log(typeof tui.Calendar);
        console.log("!!!!!");
        const Calendar = tui.Calendar;

        const calendar = new Calendar('#calendar', {
            defaultView: 'month',
            useCreationPopup: true,
            useDetailPopup: true,
            template: {
                time: schedule => `<strong>${schedule.title}</strong>`,
            },
        });

        fetch('/calendar/events')
            .then(response => response.json())
            .then(events => {
                // console.log(events);
                // Replace schedules dynamically
                // calendar.setOptions({
                //     schedules: events, // Pass your event array here
                // });
                calendar.setOptions({
                    schedules: [{
                        id: "1",
                        calendarId: "1",
                        title: "Test Event",
                        category: "time",
                        start: new Date("2024-11-22 10:00:00")
                            .toISOString(),
                        end: new Date("2024-11-22 10:00:00")
                            .toISOString()
                    }],
                });
                // calendar.render();
                calendar.render();
            })
            .catch(err => console.error('Error loading events:', err));
        // console.log("Current schedules in calendar:", calendar.getSchedules());
        // const schedules = calendar.getSchedulesByDate(calendar.getDate(), calendar.getDate());
        // console.log("Schedules in calendar:", schedules);


    });
</script> --}}

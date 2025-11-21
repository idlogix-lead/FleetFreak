@extends('layouts.app')

<link rel="stylesheet" href="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.css" />
<script src="https://uicdn.toast.com/calendar/latest/toastui-calendar.min.js"></script>
<link href="{{ asset('css/calendar.css') }}" rel="stylesheet" />
<meta name="csrf-token" content="{{ csrf_token() }}">


{{-- <style>
#calendar {
            width: 100%;
            height: 800px;
            margin: 0 auto;
        }
</style> --}}
@section('wrapper')
<div id="calendar"></div>

<div style="text-align: center; margin-top: 20px;">
    <button id="prev">Previous</button>
    <button id="today">Today</button>
    <button id="next">Next</button>
</div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
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

        fetch('/tui-calendar/events')
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
        const schedules = calendar.getSchedulesByDate(calendar.getDate(), calendar.getDate());
        console.log("Schedules in calendar:", schedules);



        // Navigation buttons
        document.addEventListener('click', function(event) {
            if (event.target.matches('#prev')) calendar.prev();
            if (event.target.matches('#next')) calendar.next();
            if (event.target.matches('#today')) calendar.today();
        });
    });
</script>




{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TUI Calendar</title>


 {{-- <link href="{{ asset('css/calendar.css') }}" rel="stylesheet" /> --}}
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}

<style>
    #calendar {
        width: 100%;
        height: 800px;
        margin: 0 auto;
    }
</style>
{{-- </head>
<body>

    <div class="container-fluid">
        <h3 class="mb-2">Calendar</h3>
        <div class="mb-1">
            <p style="color: #727cf5; display: inline;">AMS  / </p>
            <p style="color: #ff0000; display: inline;">Calendar </p>
        </div>
    </div>
        <div id="calendar"></div>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initialize the calendar
    //         console.log(tui.Calendar); // Should not be `undefined`
    //    try {
    //     // Ensure tui.Calendar is defined
    //     if (typeof tui.Calendar === 'undefined') {
    //         throw new Error('TUI Calendar is not loaded.');
    //     }
    //         const Calendar = tui.Calendar;
    //         const calendar = new Calendar('#calendar', {
    //             defaultView: 'month', // Options: 'month', 'week', 'day'
    //             useDetailPopup: true,
    //             useCreationPopup: true,
    //             template: {
    //                 time: function(schedule) {
    //                     return `<strong>${schedule.title}</strong>`;
    //                 },
    //             },
    //         });

    //         // Fetch events from the Laravel backend
    //         fetch('/tui-calendar/events')
    //             .then(response => response.json())
    //             .then(data => {
    //                 // Add events to the calendar
    //                 calendar.createSchedules(data);
    //             })
    //             .catch(error => console.error('Error fetching events:', error));

     try {
                // Initialize TUI Calendar
                const calendar = new tui.Calendar('#calendar', {
                    defaultView: 'month',
                    useDetailPopup: true,
                    useCreationPopup: true,
                });

                // Add test event
                calendar.createSchedules([
                    {
                        id: '1',
                        calendarId: '1',
                        title: 'Test Event',
                        category: 'time',
                        start: '2024-11-22T10:00:00',
                        end: '2024-11-22T12:00:00',
                    },
                ]);

                console.log('Calendar initialized successfully.');
            } catch (error) {
                console.error('Error initializing TUI Calendar:', error);
            }

            // Navigation buttons
            document.addEventListener('click', function (event) {
                if (event.target.matches('#prev')) calendar.prev();
                if (event.target.matches('#next')) calendar.next();
                if (event.target.matches('#today')) calendar.today();
            });
        });
    </script>

    <div style="text-align: center; margin-top: 20px;">
        <button id="prev">Previous</button>
        <button id="today">Today</button>
        <button id="next">Next</button>
    </div>
</body>
</html> --}}

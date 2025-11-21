
var cal;
var CALENDAR_CSS_PREFIX = 'toastui-calendar-';
let events_data = [];
const Calendar = window.tui.Calendar;
var EVENT_CATEGORIES = ['milestone', 'task'];



var $$ = function (selector) {
    return Array.prototype.slice.call(document.querySelectorAll(selector));
};

function getNavbarRange(tzStart, tzEnd, viewType) {
    var start = tzStart;
    var end = tzEnd;
    var middle;

    if (viewType === 'month') {
        middle = new Date(start.getTime() + (end.getTime() - start.getTime()) / 2);
        return moment(middle.getTime()).format('MMMM-YYYY');
    }
    if (viewType === 'day') {
        return moment(start.getTime()).format('DD/MM/YYYY');
    }
    if (viewType === 'week') {
        return moment(start.getTime()).format('DD/MM/YYYY') + ' ~ ' + moment(end.getTime()).format('DD/MM/YYYY');
    }

    throw new Error('no view type');
}

var cls = function (className) {
    return CALENDAR_CSS_PREFIX + className;
};

// Elements
var navbarRange = document.getElementsByClassName('navbar--range'); //$('.navbar--range');
var prevButton = document.getElementsByClassName('prev'); //$('.prev');
var nextButton = document.getElementsByClassName('next'); //$('.next');
var todayButton = document.getElementsByClassName('today'); //$('.today');
var defaultViewType = document.getElementById('defaultViewType'); //$('.today');

var dropdown = document.getElementsByClassName('dropdown'); //$('.dropdown');
var dropdownTrigger = document.getElementsByClassName('dropdown-trigger'); //  $('.dropdown-trigger');
var dropdownTriggerIcon = document.getElementsByClassName('dropdown-trigger'); //$('.dropdown-icon');
var dropdownContent = document.getElementsByClassName('dropdown-content'); //$('.dropdown-content');
var checkboxCollapse = document.getElementsByClassName('checkbox-collapse'); //$('.checkbox-collapse');
// var sidebar = document.getElementsByClassName('sidebar'); //$('.sidebar');

// var timelineView = document.getElementsByClassName('timeline_view'); //$('.sidebar');
// var schedulerView = document.getElementsByClassName('scheduler_view'); //$('.sidebar');


var appState = {
    isDropdownActive: true,
};

// functions to handle calendar behaviors
function reloadEvents() {
    cal.clear();
    cal.createEvents(events_data);
}

function getReadableViewName(viewType) {
    switch (viewType) {
        case 'month':
            return 'Monthly';
        case 'week':
            return 'Weekly';
        case 'day':
            return 'Daily';
        default:
            return 'Monthly';
    }
}

function displayRenderRange() {
    var rangeStart = cal.getDateRangeStart();
    var rangeEnd = cal.getDateRangeEnd();
    for (i = 0; i < navbarRange.length; i++) {
        navbarRange[i].textContent = getNavbarRange(rangeStart, rangeEnd, cal.getViewName());
    }
}

function setDropdownTriggerText() {
    var viewName = cal.getViewName();
    var buttonText = $('.dropdown .button-text');
    buttonText.html(getReadableViewName(viewName));
    buttonText.textContent = getReadableViewName(viewName);
}

function toggleDropdownState() {
    appState.isDropdownActive = !appState.isDropdownActive;
    for (i = 0; i < dropdown.length; i++) {
        dropdown[i].classList.toggle('is-active', appState.isDropdownActive);
    }
    for (i = 0; i < dropdownTriggerIcon.length; i++) {
        dropdownTriggerIcon[i].classList.toggle(cls('open'), appState.isDropdownActive);
    }
}

function setAllCheckboxes(checked) {
    var checkboxes = $$('.sidebar-item > input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = checked;
    });
}
function setAllUserCheckboxes(checked) {
    var checkboxes = $$('.team_list > input[type="checkbox"]');
    checkboxes.forEach(function (checkbox) {
        checkbox.checked = checked;
    });
}

function update() {
    setDropdownTriggerText();
    displayRenderRange();
    reloadEvents();
}


function setCalViewType(targetViewName) {
    // $(timelineView).addClass('hidden');
    // $(schedulerView).removeClass('hidden');
    $(prevButton).removeClass('hidden');
    $(nextButton).removeClass('hidden');
    $(todayButton).removeClass('hidden');

    cal.changeView(targetViewName);
    checkboxCollapse.disabled = targetViewName === 'month';
    toggleDropdownState();
    update();
}

function bindInstanceEvents() {
    cal.on({
        clickMoreEventsBtn: function (btnInfo) {
            console.log('clickMoreEventsBtn', btnInfo);
        },
        clickEvent: function (eventInfo) {
            console.log('clickEvent', eventInfo);
        },
        clickDayName: function (dayNameInfo) {
            console.log('clickDayName', dayNameInfo);
        },
        selectDateTime: function (dateTimeInfo) {
            console.log('selectDateTime', dateTimeInfo);
        },
        beforeCreateEvent: function (event) {
            console.log('beforeCreateEvent', event);
            event.id = chance.guid();

            cal.createEvents([event]);
            cal.clearGridSelections();
        },
        beforeUpdateEvent: function (eventInfo) {
            var event, changes;

            console.log('beforeUpdateEvent', eventInfo);

            event = eventInfo.event;
            changes = eventInfo.changes;

            cal.updateEvent(event.id, event.calendarId, changes);
        },
        beforeDeleteEvent: function (eventInfo) {
            console.log('beforeDeleteEvent', eventInfo);

            cal.deleteEvent(eventInfo.id, eventInfo.calendarId);
        },
    });
}


function getEventTemplate(event, isAllday) {
    // console.log(event);
    var html = [];
    var start = moment(event.start.toDate().toUTCString());
    if (!isAllday) {
        html.push('<strong>' + start.format('HH:mm') + '</strong> ');
    }
    if (event.isPrivate) {
        html.push('<span class="calendar-font-icon ic-lock-b"></span>');
        html.push(' Private');
    } else {
        if (event.recurrenceRule) {
            html.push('<span class="calendar-font-icon ic-repeat-b"></span>');
        } else if (event.attendees.length > 0) {
            html.push('<span class="calendar-font-icon ic-user-b"></span>');
        } else if (event.location) {
            html.push('<span class="calendar-font-icon ic-location-b"></span>');
        }
        html.push(' ' + event.title);
    }

    return html.join('');
}

cal = new Calendar('#calendar', {

    // calendars: [],
    // calendars: events_data,
    defaultView: 'month',

    useFormPopup: false,
    useDetailPopup: true,
    week: {
        startDayOfWeek: 1,
        workweek: false,
    },
    month: {
        startDayOfWeek: 1,
        workweek: false,
    },
    eventFilter: function (event) {
        var currentView = cal.getViewName();
        if (currentView === 'month') {
            return ['allday', 'time'].includes(event.category) && event.isVisible;
        }

        return event.isVisible;
    },
    template: {

        popupDetailTitle({
            id,
            title,
            raw
        }) {
            // text,
            // alert(title);
            // haris url
            // if (title.includes("Sickness") || title.includes("Holiday"))
            //     return "<a target='blank' href='leaves?leave_type=Overtime&source=list'>" + title +
            //         "</a><br> " + raw;
            // else
            return `<a href='#'>${title}</a><br> ${raw}`;

        },

        // popupDetailLocation({
        //     location
        // }) {
        //     return '<b>' + location + '</b>';
        // },

        popupDetailBody({
            body
        }) {

            // return icon + '<strong style="margin-left:6px;"> Creator:</strong> ' + body[1] + '<br>' + icon_phone + '<strong style="margin-left:6px;">Phone:</strong> ' + body[2] + '<br><b>' + body[0] + '</b>';
            body_content = template(body.event_type, body);
            return body_content;
        },
        popupDetailDate({
            start,
            end
        }) {
            // return `Start Date:${moment(start.getTime()).format('DD/MM/YYYY')}   End Date: ${moment(end.getTime()).format('DD/MM/YYYY')}`;
            return `Start Date:${moment(start.getTime()).format('DD/MM/YYYY')}`;

        },
        // popupDetailAttendees({
        //     attendees = []
        // }) {
        //     return `<strong>Lead Name: </strong>` + attendees.join(', ');
        // },



        allday: function (event) {
            return getEventTemplate(event, true);
        },
        time: function (event) {
            return getEventTemplate(event, true);
        },
        task: function (event) {
            return getEventTemplate(event, true);
        },
    },

});



function bindAppEvents() {
    // console.log(dropdownTrigger,"haris");
    // for (i = 0; i < dropdownTrigger.length; i++) {
    //     dropdownTrigger[i].addEventListener("click", toggleDropdownState);
    // }
    // // dropdownTrigger.addEventListener('click', toggleDropdownState);
    // for (i = 0; i < prevButton.length; i++) {
    //     prevButton[i].addEventListener('click', function() {
    //         cal.prev();
    //         get_data();
    //         setDropdownTriggerText();
    //         displayRenderRange();
    //     });
    // }
    // for (i = 0; i < nextButton.length; i++) {
    //     nextButton[i].addEventListener('click', function() {
    //         cal.next();
    //         get_data();

    //         setDropdownTriggerText();
    //         displayRenderRange();
    //     });
    // }
    // for (i = 0; i < todayButton.length; i++) {
    //     todayButton[i].addEventListener('click', function() {
    //         cal.today();
    //         get_data();
    //         setDropdownTriggerText();
    //         displayRenderRange();
    //     });
    // }
    // for (i = 0; i < dropdownContent.length; i++) {
    //     dropdownContent[i].addEventListener('click', function(e) {
    //         var targetViewName;
    //         if ('viewName' in e.target.dataset) {
    //             targetViewName = e.target.dataset.viewName;
    //             setCalViewType(targetViewName);
    //             get_data();

    //         }
    //     });
    // }

    // Calendar navigation buttons
    document.getElementById('prevBtn').addEventListener('click', function () {
        cal.prev();
        get_data();
        updateMonthDisplay(); // Update after navigating
    });

    document.getElementById('nextBtn').addEventListener('click', function () {
        cal.next();
        get_data();
        updateMonthDisplay(); // Update after navigating
    });

    document.getElementById('todayBtn').addEventListener('click', function () {
        cal.today();
        get_data();
        updateMonthDisplay(); // Update after setting to today
    });

    // View change buttons
    document.getElementById('dayViewBtn').addEventListener('click', function () {
        cal.changeView('day');
        get_data();
        document.getElementById('viewToggleBtn').textContent = 'Daily';
    });
    document.getElementById('weekViewBtn').addEventListener('click', function () {
        cal.changeView('week');
        get_data();
        document.getElementById('viewToggleBtn').textContent = 'Weekly';
    });
    document.getElementById('monthViewBtn').addEventListener('click', function () {
        cal.changeView('month');
        get_data();
        document.getElementById('viewToggleBtn').textContent = 'Monthly';
        updateMonthDisplay(); // Ensure month is updated when switching views
    });


}
// Function to update the month display
function updateMonthDisplay() {
    const currentDate = cal.getDate()
        .toDate(); // Convert the calendar's date to a proper Date object
    const options = {
        month: 'long',
        year: 'numeric'
    }; // Specify options to display only month and year
    const formattedDate = currentDate.toLocaleDateString('default', options); // Format date
    document.getElementById('selectedMonth').textContent = formattedDate; // Display "Month Year"
}



















function get_data() {
    // return null;
    let events = [], users = [];
    // $('.check_list').each(function () {
    //     if (this.checked) {
    //         events.push(this.id);
    //     }
    // });
    // $('.users').each(function () {
    //     if (this.checked) {
    //         users.push(this.value);
    //     }
    // });
    // console.log(users, 'users');
    // if (events.length == 0 || users.length == 0) {
    //     events_data = [];
    //     reloadEvents();
    // } else {
        // $('.preloader').css("display","block");
        request = {
            _token: CSRF_TOKEN,
            events: events,
            users: users,
            start: moment(cal.getDateRangeStart().getTime()).format('YYYY-MM-DD'),
            end: moment(cal.getDateRangeEnd().getTime()).format('YYYY-MM-DD'),
        };
        $.post(GET_DATA_URL, request, function (data) {
            console.log(data, 'events');

            // events_data = JSON.parse(data);
            events_data = (data);
            reloadEvents();
            // $('.preloader').css("display","none");
        }
        ).fail(function (xhr, code, error) {
            // get_data();
        });
    // }
}
function error_(a, b, c) {
    console.log(b);
}

$("#all").click(function () {
    if (this.checked) {
        setAllCheckboxes(true);
    } else {
        setAllCheckboxes(false);
    }
    get_data();
});
$(".check_all_team").click(function () {
    if (this.checked) {
        setAllUserCheckboxes(true);
    } else {
        setAllUserCheckboxes(false);
    }
    get_data();
});
$(".check_list, .users").click(function () {
    get_data();
});

$(document).ready(function () {
    bindInstanceEvents();
    bindAppEvents();
    update();
    setCalViewType(defaultViewType.value);
    updateMonthDisplay();
    var arr = $(".toastui-calendar-ic-dropdown-arrow");
    arr.attr("hidden", true);
    get_data();
});



function template(type, body){
    resp = '';
    switch(type){
        case 'regular_maintenance':
            resp = regular_maintenance(body);
            break;
        case 'ride_events':
            resp=ride_event(body);
            break;
        default:
            resp = 'Invalid event_type';
    }
    return resp;
}

function regular_maintenance(body){
    let icon = `<svg height="11" width="11" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>`;
    let icon_phone = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>`;
    resp = `
        ${body.description}
        <br>
        <a href='${ADD_MAINTENANCE_ROUTE}?vehicle_id=${body.vehicle_id}' target='blank'><div class="btn btn-sm btn-outline-primary float-end" >Add Maintenance</div></a>
    `;
    return resp;
}

function ride_event(body){
    let icon = `<svg height="11" width="11" viewBox="0 0 448 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512H418.3c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304H178.3z"/></svg>`;
    let icon_phone = `<svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-phone-call"><path d="M15.05 5A5 5 0 0 1 19 8.95M15.05 1A9 9 0 0 1 23 8.94m-1 7.98v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>`;
     let route = body.status === 'approved'
        ? '/driver_assignments'
        : `/pending_orders/${body.order_id}/edit`;
    resp = `
         <span style='color: #640d5f !important;'>Rent: ${body.rent}</span><br>
         <span style='color: #640d5f !important;'>Pickup time: ${body.pickup_time}</span><br>
         <span style='color: #696b65 !important;'>Description: ${body.description}</span><br>
          ${body.status !== 'incomplete'
        ? `<a href='${route}' target='_blank'>
            <div class="btn btn-sm btn-outline-primary float-end">Ride Detail</div>
           </a>`
        : `
        <span style='color: #696b65 !important;'>From: ${body.from}</span><br>
        <span style='color: #696b65 !important;'>To: ${body.to}</span><br>
        <span style='color: #696b65 !important;'>Driver: ${body.driver}</span><br>
        <span style='color: #696b65 !important;'>Vehicle Identification No: ${body.vehicle_identification_no}</span><br>

        `}
    `;
    return resp;
}

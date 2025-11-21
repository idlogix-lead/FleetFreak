<script>
    document.addEventListener('DOMContentLoaded', function() {
        //    console.log($formattedRequests);
        const requests = @json($formattedRequests);
        console.log(requests)

         var calendar = new tui.Calendar('#calendar', {
            defaultView: 'month',
            useCreationPopup: false,
            useDetailPopup: true, // Disable default popup
            // useCreationPopup : true,
            taskView: false,
            scheduleView: true,
            template: {
                // Month title customization (optional)
                month: {
                    title: function(date) {
                        return date.toLocaleString('default', {
                            month: 'long'
                        }) + ' ' + date.getFullYear();
                    }
                },

                popupDetailTitle({
                    id,
                    title
                }) {
                    return `
                            <div class="custom-popup">
                             <h6><a href='${id}'>${title}</a></h6><br>
                            </div>
                        `;
                },
                // popupDetailBody({ body, raw }) {
                //        // Check if user_id is not 3 (admin) before displaying the buttons
                //         const showButtons = raw.user_id !== 3;
                //     return `
                //         <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                //             <strong style="color: #ff5722;">Total Expense:</strong> ${raw.total_expense} <br>
                //             <strong style="color: #4caf50;">Approved Expense:</strong> ${raw.approved_expense} <br>
                //             <strong style="color: #2196f3;">Payment Status:</strong> ${raw.payment_status} <br>
                //             <b style="color: #f44336;">Status: ${raw.status}</b><br>
                //             <strong style="font-style: italic;">Description:</strong> ${body} <br>
                //             <!-- Hidden fields to store required data -->
                //             <input type="hidden" id="workflow_activity_id" value="${raw.workflow_activity_id}">
                //             <input type="hidden" id="description" name="description" value=" ${body}">
                //             <input type="hidden" id="csrf_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                //               <!-- Input field for rejection reason, initially hidden -->
                //             <div id="rejectReasonContainer" style="display: none; margin-top: 10px;">
                //                 <label for="rejectReason">Reason for Rejection:</label>
                //                 <input type="text" id="rejectReason" name="message" placeholder="Enter rejection reason" />
                //                 <button class="btn btn-danger reject" data-process='rejected, ${raw.route}'>Reject</button>
                //             </div>
                //             <button class="btn btn-success approver" data-process='approved,${raw.route}'>Approve</button>
                //             <button class="btn btn-danger rejectreason" data-process='rejected, ${raw.route}'>Reject</button>


                //         </div>
                //     `;
                // },

                popupDetailBody({
                    body,
                    raw
                }) {
                    // Check if user_id is not 3 (admin) before displaying the buttons
                    const showButtons = raw.user_id !== 3;

                    return `
                            <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
                                <strong style="color: #ff5722;">Total Expense:</strong> ${raw.total_expense} <br>
                                <strong style="color: #4caf50;">Approved Expense:</strong> ${raw.approved_expense} <br>
                                <strong style="color: #2196f3;">Payment Status:</strong> ${raw.payment_status} <br>
                                <b style="color: #f44336;">Status: ${raw.status}</b><br>
                                <strong style="font-style: italic;">Description:</strong> ${body} <br>

                                <!-- Hidden fields to store required data -->
                                <input type="hidden" id="workflow_activity_id" value="${raw.workflow_activity_id}">
                                <input type="hidden" id="description" name="description" value=" ${body}">
                                <input type="hidden" id="csrf_token" value="${$('meta[name="csrf-token"]').attr('content')}">

                                <!-- If the user is not admin (user_id != 3), show buttons -->
                                ${showButtons ? `
                                    <div id="rejectReasonContainer" style="display: none; margin-top: 10px;">
                                        <label for="rejectReason">Reason for Rejection:</label>
                                        <input type="text" id="rejectReason" name="message" placeholder="Enter rejection reason" />

                                    </div>
                                    <button class="btn btn-success approver" data-process='approved,${raw.route}'>Approve</button>
                                    <button class="btn btn-danger reject" data-process='rejected, ${raw.route}' id="reject" style="display: none;">Reject</button>
                                    <button class="btn btn-danger rejectreason" data-process='rejected, ${raw.route}' id="rejectReasonButton">Reject</button>
                                ` : ''}
                            </div>
                           `;
                },


                popupDetailDate({
                    start,
                    end
                }) {
                    return `<p>Start Date: ${moment(start.getTime()).format('DD/MM/YYYY')}</p>`;
                }

                // Customize the footer buttons (edit and delete)
                // popupDetailFooter() {
                //     return `
                //         <div class="tui-popup-btnbox">
                //             <!-- Modify the Edit button to link to a custom URL -->
                //             <button type="button" class="tui-popup-edit" onclick="window.location.href='your-custom-link';">Edit</button>
                //             <!-- Rename the Delete button to Close -->
                //             <button type="button" class="tui-popup-close">Close</button>
                //         </div>
                //     `;
                // }
            }
        });

        function calanderDataProcess(requests) {
        // console.log('hhhhhhhhhh');
        const calendarData = requests.map(({
            request,
            workflow_activity_created_at,
            workflow_activity_approval_delay_time,
            workflow_activity_id,
            user_id
        }) => ({
            id: request.id, // Assuming `request` has an `id` field
            title: request.request_no, // Assuming `request` has a `request_no` field
            status: request.status, // Assuming `request` has a `status` field
            description: request.description, // A  ssuming `request` has a `description` field
            // start:user_id === 3 ? new Date(workflow_activity_created_at).toISOString() : new Date(workflow_activity_approval_delay_time).toISOString()  , // Use the created_at from workflow_activity
            start: workflow_activity_created_at ? new Date(workflow_activity_created_at)
                .toISOString() : new Date(workflow_activity_approval_delay_time)
            .toISOString(), // Use the created_at from workflow_activity
            end: new Date(request.deleted_at)
                .toISOString(), // Assuming `request` has a `deleted_at` field
            total_expense: request
                .total_expense, // Assuming `request` has a `total_expense` field
            approved_expense: request
                .approved_expense, // Assuming `request` has a `approved_expense` field
            payment_status: request
                .payment_status, // Assuming `request` has a `payment_status` field
            url: user_id === 3 ?
                `${window.location.origin}/requests/timeline/${request.id}?` :
                `${window.location.origin}/pending_requests/${workflow_activity_id}?`,
            // url:`${window.location.origin}/pending_requests/${workflow_activity_id}?`, // Edit URL for the request
            workflow_activity_id: workflow_activity_id,
            user_id: user_id
        }));

        // return calendarData;

        // console.log(calendarData);
        addRequestsToCalendar(calendarData);
         }
        //    calendarData=calanderDataProcess(requests);
        //   addRequestsToCalendar(calendarData);
          calanderDataProcess(requests);

        //    console.log(calanderData);

        // $(document).ready(function() {
            $(document).on('click', '.refer', function(e) {
                let process = $(this).attr('data-process');
                console.log(process);
            })

            $(document).on('click', '.rejectreason', function(e) {
                // Show the rejection reason input field
                $('#rejectReasonContainer').show();

                // Optionally, you can focus on the input field
                $('#rejectReason').focus();
                $('#rejectReasonButton').hide();
                $('#reject').show();
            });
            $(document).on('click', '.approver', function(e) {
                let process = $(this).attr('data-process');
                console.log(process);
                // Split the process string into an array
                let processArray = process.split(',');

                // Assign the first value to the action variable
                let action = processArray[0];

                // Assign the second value to the route variable
                let route = processArray[1];
                // console.log('action  '+action); // 'approved'
                // console.log('route  '+route);  // 'pending_requests/multiple_process'
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                // console.log(csrfToken); // This should log the token value

                const workflowActivityId = document.getElementById('workflow_activity_id')
                    .value;
                const description = document.getElementById('description').value;

                // Perform a check to ensure values are available
                if (!workflowActivityId || !description) {
                    alert("Some required data is missing!");
                    return;
                }

                // Prepare data for sending
                const data = {
                    process: action,
                    WorkflowActivities: [workflowActivityId],
                    description: description,
                };


                // Perform an AJAX request to the backend route
                $.ajax({
                    url: route, // the route provided in the raw data
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Set the CSRF token
                    },
                    success: function(response) {
                        alert("Request processed successfully: " + response
                            .success);
                        // Optionally refresh the page or calendar to reflect changes
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Failed to process request: " + xhr.responseText);
                    }
                });

            })
            $(document).on('click', '.reject', function(e) {
                let process = $(this).attr('data-process');
                console.log(process);
                // Split the process string into an array
                let processArray = process.split(',');

                // Assign the first value to the action variable
                let action = processArray[0];

                // Assign the second value to the route variable
                let route = processArray[1];
                console.log('action  ' + action); // 'approved'
                console.log('route  ' + route); // 'pending_requests/multiple_process'
                let csrfToken = $('meta[name="csrf-token"]').attr('content');
                console.log(csrfToken); // This should log the token value

                const workflowActivityId = document.getElementById('workflow_activity_id')
                    .value;
                const description = document.getElementById('description').value;
                const rejectReason = $('#rejectReason').val(); // Get rejection reason
                console.log(rejectReason);

                // Perform a check to ensure values are available
                if (!workflowActivityId || !description) {
                    alert("Some required data is missing!");
                    return;
                }

                // Prepare data for sending
                const data = {
                    process: action,
                    WorkflowActivities: [workflowActivityId],
                    description: description,
                    message: rejectReason // Send rejection reason
                };


                // Perform an AJAX request to the backend route
                $.ajax({
                    url: route, // the route provided in the raw data
                    type: 'POST',
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Set the CSRF token
                    },
                    success: function(response) {
                        alert("Request processed successfully: " + response
                            .success);
                        // Optionally refresh the page or calendar to reflect changes
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        alert("Failed to process request: " + xhr.responseText);
                    }
                });

            })
        // })
        // console.log(calendarData);


        // console.log(calendarData); // Check the generated calendar data

        // var calendar = new tui.Calendar('#calendar', {
        //     defaultView: 'month',
        //     useCreationPopup: false,
        //     useDetailPopup: true, // Disable default popup
        //     // useCreationPopup : true,
        //     taskView: false,
        //     scheduleView: true,
        //     template: {
        //         // Month title customization (optional)
        //         month: {
        //             title: function(date) {
        //                 return date.toLocaleString('default', {
        //                     month: 'long'
        //                 }) + ' ' + date.getFullYear();
        //             }
        //         },

        //         popupDetailTitle({
        //             id,
        //             title
        //         }) {
        //             return `
        //                     <div class="custom-popup">
        //                      <h6><a href='${id}'>${title}</a></h6><br>
        //                     </div>
        //                 `;
        //         },
        //         // popupDetailBody({ body, raw }) {
        //         //        // Check if user_id is not 3 (admin) before displaying the buttons
        //         //         const showButtons = raw.user_id !== 3;
        //         //     return `
        //         //         <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        //         //             <strong style="color: #ff5722;">Total Expense:</strong> ${raw.total_expense} <br>
        //         //             <strong style="color: #4caf50;">Approved Expense:</strong> ${raw.approved_expense} <br>
        //         //             <strong style="color: #2196f3;">Payment Status:</strong> ${raw.payment_status} <br>
        //         //             <b style="color: #f44336;">Status: ${raw.status}</b><br>
        //         //             <strong style="font-style: italic;">Description:</strong> ${body} <br>
        //         //             <!-- Hidden fields to store required data -->
        //         //             <input type="hidden" id="workflow_activity_id" value="${raw.workflow_activity_id}">
        //         //             <input type="hidden" id="description" name="description" value=" ${body}">
        //         //             <input type="hidden" id="csrf_token" value="${$('meta[name="csrf-token"]').attr('content')}">
        //         //               <!-- Input field for rejection reason, initially hidden -->
        //         //             <div id="rejectReasonContainer" style="display: none; margin-top: 10px;">
        //         //                 <label for="rejectReason">Reason for Rejection:</label>
        //         //                 <input type="text" id="rejectReason" name="message" placeholder="Enter rejection reason" />
        //         //                 <button class="btn btn-danger reject" data-process='rejected, ${raw.route}'>Reject</button>
        //         //             </div>
        //         //             <button class="btn btn-success approver" data-process='approved,${raw.route}'>Approve</button>
        //         //             <button class="btn btn-danger rejectreason" data-process='rejected, ${raw.route}'>Reject</button>


        //         //         </div>
        //         //     `;
        //         // },

        //         popupDetailBody({
        //             body,
        //             raw
        //         }) {
        //             // Check if user_id is not 3 (admin) before displaying the buttons
        //             const showButtons = raw.user_id !== 3;

        //             return `
        //                     <div style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">
        //                         <strong style="color: #ff5722;">Total Expense:</strong> ${raw.total_expense} <br>
        //                         <strong style="color: #4caf50;">Approved Expense:</strong> ${raw.approved_expense} <br>
        //                         <strong style="color: #2196f3;">Payment Status:</strong> ${raw.payment_status} <br>
        //                         <b style="color: #f44336;">Status: ${raw.status}</b><br>
        //                         <strong style="font-style: italic;">Description:</strong> ${body} <br>

        //                         <!-- Hidden fields to store required data -->
        //                         <input type="hidden" id="workflow_activity_id" value="${raw.workflow_activity_id}">
        //                         <input type="hidden" id="description" name="description" value=" ${body}">
        //                         <input type="hidden" id="csrf_token" value="${$('meta[name="csrf-token"]').attr('content')}">

        //                         <!-- If the user is not admin (user_id != 3), show buttons -->
        //                         ${showButtons ? `
        //                             <div id="rejectReasonContainer" style="display: none; margin-top: 10px;">
        //                                 <label for="rejectReason">Reason for Rejection:</label>
        //                                 <input type="text" id="rejectReason" name="message" placeholder="Enter rejection reason" />

        //                             </div>
        //                             <button class="btn btn-success approver" data-process='approved,${raw.route}'>Approve</button>
        //                             <button class="btn btn-danger reject" data-process='rejected, ${raw.route}' id="reject" style="display: none;">Reject</button>
        //                             <button class="btn btn-danger rejectreason" data-process='rejected, ${raw.route}' id="rejectReasonButton">Reject</button>
        //                         ` : ''}
        //                     </div>
        //                    `;
        //         },


        //         popupDetailDate({
        //             start,
        //             end
        //         }) {
        //             return `<p>Start Date: ${moment(start.getTime()).format('DD/MM/YYYY')}</p>`;
        //         }

        //         // Customize the footer buttons (edit and delete)
        //         // popupDetailFooter() {
        //         //     return `
        //         //         <div class="tui-popup-btnbox">
        //         //             <!-- Modify the Edit button to link to a custom URL -->
        //         //             <button type="button" class="tui-popup-edit" onclick="window.location.href='your-custom-link';">Edit</button>
        //         //             <!-- Rename the Delete button to Close -->
        //         //             <button type="button" class="tui-popup-close">Close</button>
        //         //         </div>
        //         //     `;
        //         // }
        //     }
        // });

        function addRequestsToCalendar(requests) {
            const groupedRequests = groupByDate(requests);
            Object.keys(groupedRequests).forEach(date => {
                const dateRequests = groupedRequests[date];
                dateRequests.forEach(req => {

                    const eventData = {
                        id: req.url,
                        title: req.title + ' - ' + req.status,
                        body: req.description ?? 'No description is present',
                        start: req.start,
                        end: req.start,
                        state: '',
                        attendees: '',
                        isAllday: true,
                        category: 'allday',
                        raw: { // Store custom fields like total_expense, approved_expense in raw
                            total_expense: req.total_expense,
                            approved_expense: req.approved_expense,
                            payment_status: req.payment_status,
                            status: req.status,
                            workflow_activity_id: req.workflow_activity_id,
                            route: `pending_requests/multiple_process`, // Example route for buttons
                            user_id: req.user_id
                        }
                    };

                    // calendar.clear();
                    calendar.createEvents([eventData]);
                    // console.log(eventData);
                });
            });
        }

        function groupByDate(requests) {
            return requests.reduce((acc, req) => {
                const date = req.start.split('T')[0]; // Group by date
                if (!acc[date]) acc[date] = [];
                acc[date].push(req);
                return acc;
            }, {});
        }

        // addRequestsToCalendar(calendarData);


        // Function to update the month display
        function updateMonthDisplay() {
            const currentDate = calendar.getDate()
                .toDate(); // Convert the calendar's date to a proper Date object
            const options = {
                month: 'long',
                year: 'numeric'
            }; // Specify options to display only month and year
            const formattedDate = currentDate.toLocaleDateString('default', options); // Format date
            document.getElementById('selectedMonth').textContent = formattedDate; // Display "Month Year"
        }
        // Update the month display on load
        updateMonthDisplay();

        // Calendar navigation



        // Calendar navigation buttons
        document.getElementById('prevBtn').addEventListener('click', function() {
            calendar.prev();
            updateMonthDisplay(); // Update after navigating
        });

        document.getElementById('nextBtn').addEventListener('click', function() {
            calendar.next();
            updateMonthDisplay(); // Update after navigating
        });

        document.getElementById('todayBtn').addEventListener('click', function() {
            calendar.today();
            updateMonthDisplay(); // Update after setting to today
        });

        // View change buttons
        document.getElementById('dayViewBtn').addEventListener('click', function() {
            calendar.changeView('day');
            document.getElementById('viewToggleBtn').textContent = 'Daily';
        });
        document.getElementById('weekViewBtn').addEventListener('click', function() {
            calendar.changeView('week');
            document.getElementById('viewToggleBtn').textContent = 'Weekly';
        });
        document.getElementById('monthViewBtn').addEventListener('click', function() {
            calendar.changeView('month');
            document.getElementById('viewToggleBtn').textContent = 'Monthly';
            updateMonthDisplay(); // Ensure month is updated when switching views
        });

        //     return calendar;
        // }

        $('#userSelect').select2({
            placeholder: "Select users to filter", // Placeholder text
            allowClear: true, // Allows users to clear the selection
            width: '50%', // Ensures the select box fits well within its container
            minimumInputLength: 2 // Start searching after typing 2 characters
        });
        // calanderData(requests)

        // $('#filterBtn').on('click', function() {
        //     var userId = document.getElementById('userSelect').value;
        //     // var status = document.getElementById('statusSelect').value;
        //     // console.log(status);
        //     let url = 'user/filter/calendar';

        //    if(userId){
        //      url += `/${userId}`;
        //    }
        //     // // Check if userId or status exist and append them as route parameters
        //     // if (userId && status) {
        //     //     url += `/${userId}/${status}`; // Both userId and status exist
        //     // } else if (userId) {
        //     //     url += `/${userId}/${status=null}`; // Only userId exists
        //     // } else if (status) {
        //     //     url += `/${userId=null}/${status}`; // Only status exists
        //     // }
        //   console.log(url);
        //     if (userId) {
        //         // console.log(userId)

        //         $.ajax({
        //             // url: 'user/filter/calendar/' + userId, // Use route to fetch unread count
        //             url:url,
        //             type: 'GET',
        //             success: function(data) {
        //                 // console.log('hhhh');
        //                 console.log(data);
        //                 calanderData(data);


        //             },
        //             error: function(xhr) {
        //                 console.error('Error fetching notification count:',
        //                 xhr); // Log error
        //             }
        //         });
        //     } else {
        //         alert('Please select a user to filter.');
        //     }
        // });

        // $('#filterBtn').on('click', function () {
        //     var selectedUserIds = $('#userSelect').val(); // Get selected user IDs as an array

        //     if (selectedUserIds.length > 0) {
        //         let url = 'user/filter/calendar';

        //         $.ajax({
        //             url: url,
        //             type: 'GET',
        //             data: {
        //                 user_ids: selectedUserIds
        //             },
        //             success: function (data) {
        //                 console.log(data);
        //                 calanderData(data); // Update calendar with filtered data
        //             },
        //             error: function (xhr) {
        //                 console.error('Error fetching data:', xhr);
        //             }
        //         });
        //     } else {
        //         alert('Please select at least one user to filter.');
        //     }
        // });
        $('#filterBtn').on('click', function() {
            const selectedUserIds = $('#userSelect').val(); // Get selected user IDs as an array

            if (selectedUserIds.length > 0) {
                const url = 'user/filter/calendar';

                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        user_ids: selectedUserIds
                    },
                    success: function(filteredData) {
                        // Clear existing events from the calendar
                        // const calendar = new tui.Calendar('#calendar');
                        calendar.clear(); // This method removes all existing events

                        // Re-add filtered events

                        // calanderData(filteredData); // Update calendar with filtered data
                        calanderDataProcess(filteredData);
                        // calanderData(filteredData);
                        // addRequestsToCalendar(filteredData);

                        // Force update of the calendar view
                        calendar.render(); // Ensure the calendar view is updated
                    },
                    error: function(xhr) {
                        console.error('Error fetching filtered data:', xhr);
                    }
                });
            } else {
                alert('Please select at least one user to filter.');
            }
        });





        // console.log(requests);

        // const calendarData = requests.map(request => ({
        //     id: request.id,
        //     title: request.request_no,
        //     status: request.status,
        //     description: request.description,
        //     start: new Date(request.created_at).toISOString(),
        //     end: new Date(request.deleted_at).toISOString(),
        //     total_expense: request.total_expense,
        //     approved_expense: request.approved_expense,
        //     payment_status: request.payment_status,
        //     url: `/requests/${request.id}/edit`
        // }));


    });

    //    $('#filterBtn').on('click', function() {
    //     console.log('hhhhhhhhh');
    //     // loadNotifications('unread');
    // });

    //     document.getElementById('filterForm').addEventListener('submit', function(event) {
    //     event.preventDefault(); // Prevent the default form submission
    //     var userId = document.getElementById('userSelect').value;

    //     if (userId) {
    //         // Update the form action to include the selected user ID
    //         var formAction = this.getAttribute('action').replace(':id', userId);
    //         this.setAttribute('action', formAction);
    //         this.submit(); // Submit the form
    //     } else {
    //         alert('Please select a user to filter.');
    //     }
    // });

    // $('#filterBtn').on('click', function() {
    //     var userId = document.getElementById('userSelect').value;

    //     if (userId) {
    //         console.log(userId)

    //         $.ajax({
    //             url: 'user/filter/calendar/' + userId, // Use route to fetch unread count
    //             type: 'GET',
    //             success: function(data) {
    //                 console.log(data);

    //             },
    //             error: function(xhr) {
    //                 console.error('Error fetching notification count:', xhr); // Log error
    //             }
    //         });
    //     } else {
    //         alert('Please select a user to filter.');
    //     }
    // });

    // function process(action, route) {
    //     console.log(action,route);
    //     // Get the values from the hidden input fields
    //     const workflowActivityId = document.getElementById('workflow_activity_id').value;
    //     const description = document.getElementById('description').value;

    //     // Perform a check to ensure values are available
    //     if (!workflowActivityId || !description) {
    //         alert("Some required data is missing!");
    //         return;
    //     }

    //     // Prepare data for sending
    //     const data = {
    //         process: action,
    //         WorkflowActivities: [workflowActivityId],
    //         description: description,
    //     };

    //     // Perform an AJAX request to the backend route
    //     $.ajax({
    //         url: route, // the route provided in the raw data
    //         type: 'POST',
    //         data: data,
    //         success: function(response) {
    //             alert("Request processed successfully: " + response.success);
    //             // Optionally refresh the page or calendar to reflect changes
    //         },
    //         error: function(xhr) {
    //             console.error(xhr.responseText);
    //             alert("Failed to process request: " + xhr.responseText);
    //         }
    //     });
    // }
</script>

   $(document).ready(function() {
            $('#nextStep').click(function(e) {
                e.preventDefault(); // Prevent default form submission
                var email = $('#email').val();

                if (email === '' || !validateEmail(email)) {
                    $('#email').addClass('is-invalid');
                } else {
                    $('#email').removeClass('is-invalid');

                    // Send form data to the backend using AJAX
                    $.ajax({
                        url: "{{ route('register') }}", // Replace with your route
                        type: 'POST',
                        data: {
                            email: email,
                            _token: "{{ csrf_token() }}" // Include CSRF token
                        },
                        success: function(response) {
                            if (response.success) {
                                // If the email validation is successful, move to the next step
                                $('#step1').hide();
                                $('#step2').show();
                                // Update step indicators
                                $('#step1-indicator').addClass('completed');
                                $('#step2-indicator').addClass('active');
                                // $('#progressBar').css('width', '66%');
                            } else {
                                // If email already exists or other validation errors
                                $('#email').addClass('is-invalid');
                                alert(response.message); // Show validation message
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle any errors that occur during the AJAX request
                            console.log(error);
                            alert("An error occurred while submitting the form.");
                        }
                    });
                }
            });

            // Email validation function
            function validateEmail(email) {
                var re = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
                return re.test(String(email).toLowerCase());
            }
            $('#backStep1').click(function() {
                $('#step2').hide(); // Hide step 2
                $('#step1').show(); // Show step 1
                $('#step1-indicator').addClass('completed');
                $('#step2-indicator').addClass('active');
                // $('#progressBar').css('width', '33%'); // Adjust progress bar
            });
            $('#backStep2').click(function() {
                $('#step3').hide(); // Hide step 2
                $('#step2').show(); // Show step 1
                $('#step2-indicator').addClass('completed');
                $('#step3-indicator').addClass('active');
                // $('#progressBar').css('width', '33%'); // Adjust progress bar
            });

            $('#nextStep2').click(function() {
                // Validate company info fields
                var isValid = true;

                // Validate company name
                if ($('#company_name').val() === '') {
                    $('#company_name').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#company_name').removeClass('is-invalid');
                }

                // Validate address
                if ($('#address1').val() === '') {
                    $('#address1').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#address1').removeClass('is-invalid');
                }

                // Validate city
                if ($('#city').val() === '') {
                    $('#city').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#city').removeClass('is-invalid');
                }

                // Validate country
                if ($('#country').val() === '') {
                    $('#country').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#country').removeClass('is-invalid');
                }

                if (isValid) {
                    // Collect data for submission
                    var data = {
                        company_name: $('#company_name').val(),
                        address1: $('#address1').val(),
                        city: $('#city').val(),
                        country: $('#country').val(),
                        _token: "{{ csrf_token() }}" // CSRF token for security
                    };

                    // Make an AJAX request to check company name
                    $.ajax({
                        url: "{{ route('register_company') }}", // Replace with your route to check company name
                        method: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.success) {
                                // Proceed to the next step
                                $('#step2').hide();
                                $('#step3').show();
                                $('#step2-indicator').addClass('completed');
                                $('#step3-indicator').addClass('active');
                                // $('#progressBar').css('width', '100%');
                            } else {
                                // Show error message
                                alert(response
                                .message); // Display the error message if company name exists
                                $('#company_name').addClass('is-invalid');
                            }
                        },
                        error: function(xhr) {
                            // Handle error response
                            console.error('Error:', xhr.responseText);
                        }
                    });
                }
            });

            $('#submitForm').click(function(e) {
                e.preventDefault(); // Prevent form from submitting right away

                var isValid = true;

                // Validate name
                if ($('#name').val() === '') {
                    $('#name').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#name').removeClass('is-invalid');
                }

                // Validate phone number
                if ($('#phone_no').val() === '') {
                    $('#phone_no').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#phone_no').removeClass('is-invalid');
                }

                // Validate password
                var password = $('#password').val();
                var confirmPassword = $('#password_confirmation').val();

                if (password === '') {
                    $('#password').addClass('is-invalid');
                    isValid = false;
                } else {
                    $('#password').removeClass('is-invalid');
                }

                if (confirmPassword === '') {
                    $('#password_confirmation').addClass('is-invalid');
                    isValid = false;
                } else if (password !== confirmPassword) {
                    $('#password_confirmation').addClass('is-invalid');
                    alert('Passwords do not match!');
                    isValid = false;
                } else {
                    $('#password_confirmation').removeClass('is-invalid');
                }

                if (isValid) {
                    // Gather data from previous steps
                    var email = $('#email').val();
                    var company_name = $('#company_name').val();
                    var address1 = $('#address1').val();
                    var city = $('#city').val();
                    var country = $('#country').val();

                    // Append previous step data to the form
                    $('<input>').attr({
                        type: 'hidden',
                        name: 'email',
                        value: email
                    }).appendTo('#personalForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'company_name',
                        value: company_name
                    }).appendTo('#personalForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'address1',
                        value: address1
                    }).appendTo('#personalForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'city',
                        value: city
                    }).appendTo('#personalForm');

                    $('<input>').attr({
                        type: 'hidden',
                        name: 'country',
                        value: country
                    }).appendTo('#personalForm');

                    // All validations passed, proceed to submit the form
                    $('#personalForm').submit();
                }
            });

            $('.select2').select2({
                width: '100%',
            });
            $('.select2-tags').select2({
                width: '100%',
                tags: true,
                ajax: {
                    url: '{{ route('get-cities') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term, // search term
                            country: $('#country').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(city) {
                                return {
                                    id: city.city,
                                    text: city.city
                                };
                            })
                        };
                    },
                    cache: true
                }
            });


            // Set the selected city if editing
            var selectedCity = '{{ old('city') }}';
            if (selectedCity) {
                var newOption = new Option(selectedCity, selectedCity, true, true);
                $('#city').append(newOption).trigger('change');
            }


        });

    $(document).ready(function() {
            function formatCountry(option) {
                if (!option.id) {
                    return option.text;
                }
                var iso = $(option.element).data('iso');
                var flag = $('<span><span class="flag-icon flag-icon-' + iso + '"></span> ' + option.text +
                    '</span>');
                return flag;
            }

            $('#prefix_phonecust').select2({
                templateResult: formatCountry,
                templateSelection: formatCountry,
                placeholder: "Code",
                width: '40%' // Adjust width as needed


            });


        });

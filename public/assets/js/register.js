
$(document).ready(function() {
    
        $('#email').on('keypress', function(event) {
            if (event.which === 13) { // Enter key
                event.preventDefault(); // Prevent form submission

                // Hide the save button
                $('#btn_save').hide();

                // Show the loader
                $('#loader').show();

                // Simulate a delay (2 seconds)
                setTimeout(function() {
                    // Submit the form
                    $('form').submit();
                }, 2000);
            }
        });





});

<script>
     document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');

    emailInput.addEventListener('input', function () {
        if (emailInput.validity.valid) {
            emailError.style.display = 'none';
        } else {
            emailError.style.display = 'block';
        }
    });
    const passwordInput = document.getElementById('password');
    const passwordError = document.getElementById('password-error');
    const confirmPasswordInput = document.getElementById('password_confirmation');
    const confirmPasswordError = document.getElementById('confirm-password-error');

    passwordInput.addEventListener('input', function () {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (regex.test(passwordInput.value)) {
            passwordError.style.display = 'none'; // Hide error if valid
        } else {
            passwordError.style.display = 'block'; // Show error if invalid
        }
    });
    confirmPasswordInput.addEventListener('input', function () {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
        if (regex.test(confirmPasswordInput.value)) {
            confirmPasswordError.style.display = 'none'; // Hide error if valid
        } else {
            confirmPasswordError.style.display = 'block'; // Show error if invalid
        }
    });
});
</script>

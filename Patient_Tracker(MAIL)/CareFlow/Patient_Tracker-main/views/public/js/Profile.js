document.addEventListener('DOMContentLoaded', function() {
    const changePasswordForm = document.getElementById('change-password-form');
    if (changePasswordForm) {
        changePasswordForm.addEventListener('submit', function(event) {
            // Clear previous error messages
            document.getElementById('repeat-new-password-error').innerText = '';
            const newPasswordError = document.getElementById('new-password-error');
            newPasswordError.innerText = ''; // Clear new password error

            // Get the values of the new password fields
            const newPassword = document.getElementById('new-password').value;
            const repeatNewPassword = document.getElementById('repeat-new-password').value;

            // Check if the new password meets the length requirement
            if (newPassword.length < 8) {
                event.preventDefault(); // Prevent form submission
                newPasswordError.innerText = 'Password must be at least 8 characters long.';
            }

            // Check if the new password and repeat password match
            if (newPassword !== repeatNewPassword) {
                event.preventDefault(); // Prevent form submission
                document.getElementById('repeat-new-password-error').innerText = 'Passwords do not match.';
            }
        });
    }
});


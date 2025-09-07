const changePasswordForm = document.getElementById('change-password-form');
const changePasswordBtn = document.getElementById('signUpBtn');

// Display error message below the input field
function displayError(inputField, message) {
    const errorMessage = inputField.parentElement.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.textContent = message; // Set the error message text
    }
}

// Clear existing error messages from the input field
function clearErrorMessages(inputField) {
    const errorMessage = inputField.parentElement.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.textContent = ''; // Clear the error message text
    }
}

document.addEventListener('DOMContentLoaded', function () {
    changePasswordBtn.addEventListener('click', (e) => {
        if (!validateChangePassword()) {
            e.preventDefault(); // Prevent form submission when validation fails
        }
    });

    function validateChangePassword() {
        let isValid = true;

        // Clear previous error states
        const inputs = changePasswordForm.querySelectorAll(".input-field");
        inputs.forEach(input => {
            input.classList.remove('input-error');
            clearErrorMessages(input);
        });

        // Validate Current Password
        const currentPasswordInput = document.getElementById('current-password');
        if (!currentPasswordInput.value.trim()) {
            isValid = false;
            currentPasswordInput.classList.add('input-error');
            displayError(currentPasswordInput, "Current password is required.");
        }

        // Validate New Password
        const newPasswordInput = document.getElementById('new-password');
        if (newPasswordInput.value.trim().length < 8) {
            isValid = false;
            newPasswordInput.classList.add('input-error');
            displayError(newPasswordInput, "New password must be at least 8 characters long.");
        }

        // Validate Repeat New Password
        const repeatNewPasswordInput = document.getElementById('repeat-new-password');
        if (newPasswordInput.value !== repeatNewPasswordInput.value) {
            isValid = false;
            repeatNewPasswordInput.classList.add('input-error');
            displayError(repeatNewPasswordInput, "New password and Repeat new password must match.");
        }

        return isValid;
    }
});






















function calculateBMI() {
    // Get the height and weight from the input fields
    const height = parseFloat(document.getElementById('height').value);
    const weight = parseFloat(document.getElementById('weight').value);

    // Check if inputs are valid
    if (isNaN(height) || isNaN(weight) || height <= 0 || weight <= 0) {
        alert("Please enter valid height and weight.");
        return;
    }

    // Convert height from cm to meters
    const heightInMeters = height / 100;

    // Calculate BMI
    const bmi = weight / (heightInMeters * heightInMeters);

    // Determine the health status based on BMI value
    let healthStatus = '';
    if (bmi < 18.5) {
        healthStatus = 'Underweight';
    } else if (bmi >= 18.5 && bmi < 24.9) {
        healthStatus = 'Fit (Normal weight)';
    } else if (bmi >= 25 && bmi < 29.9) {
        healthStatus = 'Overweight';
    } else if (bmi >= 30) {
        healthStatus = 'Obesity';
    }

    // Display the result
    document.getElementById('bmiResult').innerText = bmi.toFixed(2);
    document.getElementById('healthStatus').innerText = healthStatus;
}

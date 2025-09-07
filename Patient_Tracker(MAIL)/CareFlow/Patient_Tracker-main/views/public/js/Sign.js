const loginBtn = document.querySelector("#login");

const registerBtn = document.querySelector("#register");
const loginForm = document.querySelector(".login-form");
const registerForm = document.querySelector(".register-form");
const signUpForm = document.querySelector("#signup");
const signUpBtn = signUpForm.querySelector("button.input-submit");

const signInForm = document.querySelector("#signin");
const signInBtn = signInForm.querySelector("button.input-submit");

// Prevent the form from submitting if validation fails
signUpBtn.addEventListener("click", (e) => {
  // Check if the form is valid
  if (!validateSignUp()) {
    e.preventDefault(); // Prevent form submission when validation fails

    // Slide the forms accordingly
    loginForm.style.left = "150%";
    registerForm.style.left = "50%";
  } else {
    signUpForm.submit(); // Submit the form if validation passes
  }
});
signInBtn.addEventListener("click", (e) => {
  // Check if the form is valid
  if (!validateSignIn()) {
    e.preventDefault(); // Prevent form submission when validation fails
  } else {
    signInForm.submit(); // Submit the form if validation passes
  }
});

function validateSignIn() {
  let isValid = true;

  // Clear previous error states
  const inputs = signInForm.querySelectorAll(".input-field");
  inputs.forEach((input) => {
    input.classList.remove("input-error");
    clearErrorMessages(input); // Clear existing error messages
  });

  // Validate Email
  const emailInput = signInForm.querySelector("input[name='Email']");
  if (!emailInput.value.trim().match(/^\S+@\S+\.\S+$/)) {
    isValid = false;
    emailInput.classList.add("input-error");
    displayError(emailInput, "Invalid email format.");
    console.log("Email validation failed.");
  }

  // Validate Password
  const passwordInput = signInForm.querySelector("input[name='Password']");
  if (passwordInput.value.trim().length < 8) {
    isValid = false;
    passwordInput.classList.add("input-error");
    displayError(passwordInput, "Password must be at least 8 characters long.");
    console.log("Password validation failed.");
  }

  const captchaResponse = grecaptcha.getResponse(); // Get reCAPTCHA response
  const captchaErrorElement = document.getElementById("captchaError"); // Make sure this matches your error message element's ID
  if (captchaResponse.length === 0) {
    isValid = false;
    captchaErrorElement.textContent = "Please complete the CAPTCHA."; // Set error message
    captchaErrorElement.classList.add("input-error"); // Add error class for styling
    console.log("Captcha Error: Please complete the CAPTCHA.");
  } else {
    captchaErrorElement.textContent = ""; // Clear any previous error message
    captchaErrorElement.classList.remove("input-error"); // Remove error class
  }

  console.log("Validation result:", isValid);
  return isValid; // Return the final validation state
}
function validateSignUp() {
  let isValid = true;

  // Clear previous error states
  const inputs = signUpForm.querySelectorAll(".input-field");
  inputs.forEach((input) => {
    input.classList.remove("input-error");
    clearErrorMessages(input); // Clear existing error messages
  });

  // Validate Name
  const nameInput = signUpForm.querySelector("input[name='Name']");
  if (nameInput.value.trim() === "") {
    isValid = false;
    nameInput.classList.add("input-error");
    displayError(nameInput, "Name is required.");
  }

  // Validate Email
  const emailInput = signUpForm.querySelector("input[name='Email']");
  if (!emailInput.value.trim().match(/^\S+@\S+\.\S+$/)) {
    isValid = false;
    emailInput.classList.add("input-error");
    displayError(emailInput, "Invalid email format.");
  }

  // Validate Password
  const passwordInput = signUpForm.querySelector("input[name='Password']");
  if (passwordInput.value.trim().length < 8) {
    isValid = false;
    passwordInput.classList.add("input-error");
    displayError(passwordInput, "Password must be at least 8 characters long.");
  }

  // Validate Confirm Password
  const confirmPasswordInput = signUpForm.querySelector(
    "input[name='CPassword']"
  );
  if (passwordInput.value !== confirmPasswordInput.value) {
    isValid = false;
    confirmPasswordInput.classList.add("input-error");
    displayError(confirmPasswordInput, "Passwords do not match.");
  }

  // Validate Phone Number
  const phoneInput = signUpForm.querySelector("input[name='PhoneNumber']");
  if (!phoneInput.value.match(/^[0-9]{10,15}$/)) {
    isValid = false;
    phoneInput.classList.add("input-error");
    displayError(phoneInput, "Phone number must be between 10 to 15 digits.");
  }

  // Validate Address
  const addressInput = signUpForm.querySelector("input[name='Address']");
  if (addressInput.value.trim() === "") {
    isValid = false;
    addressInput.classList.add("input-error");
    displayError(addressInput, "Address is required.");
  }

  return isValid; // Return the final validation state
}

// Display error message below the input field
function displayError(inputField, message) {
  // Check if an error message already exists
  if (!inputField.parentElement.querySelector(".error-message")) {
    const errorMessage = document.createElement("div");
    errorMessage.className = "error-message"; // Add a class for styling
    errorMessage.style.color = "red"; // Set error message color
    errorMessage.textContent = message; // Set the error message text
    inputField.parentElement.appendChild(errorMessage); // Append error message to input field
  }
}

// Clear existing error messages from the input field
function clearErrorMessages(inputField) {
  const errorMessage = inputField.parentElement.querySelector(".error-message");
  if (errorMessage) {
    errorMessage.remove(); // Remove the error message element
  }
}

// Toggle between Sign In and Sign Up forms
loginBtn.addEventListener("click", () => {
  toggleForms(true);
});
registerBtn.addEventListener("click", () => {
  toggleForms(false);
});
function toggleForms(isLogin) {
  if (isLogin) {
    loginBtn.style.backgroundColor = "#21264D";
    registerBtn.style.backgroundColor = "rgba(255,255,255,0.2)";
    loginForm.style.left = "50%";
    registerForm.style.left = "-50%";
    loginForm.style.opacity = 1;
    registerForm.style.opacity = 0;
  } else {
    loginBtn.style.backgroundColor = "rgba(255,255,255,0.2)";
    registerBtn.style.backgroundColor = "#21264D";
    loginForm.style.left = "150%";
    registerForm.style.left = "50%";
    loginForm.style.opacity = 0;
    registerForm.style.opacity = 1;
  }
  document.querySelector(".col-1").style.borderRadius = isLogin
    ? "25px 30% 20% 25px"
    : "25px 20% 30% 25px";
}

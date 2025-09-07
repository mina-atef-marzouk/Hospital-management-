
<?php
include("../includes/dbh.inc.php");

// Initialize error variables for Sign Up-IN
$nameError = $emailError = $passwordError = $cpasswordError = $phoneError = $addressError =$signin_emailError=$signin_passwordError = "";
$captchaError = ""; // Initialize captcha error variable
$recaptcha_secret = "6LfzJGgqAAAAAIspbMTLlRxVcnCLCPBSk29HlkzS";
// Process form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'signup') {
            // Validation for Sign Up
            if (empty($_POST["Name"])) {
                $nameError = "Name is required";
            }
            if (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
                $emailError = "Valid email is required";
            }
            if (strlen($_POST["Password"]) < 8) {
                $passwordError = "Password must be at least 8 characters long";
            }
            if ($_POST["Password"] !== $_POST["CPassword"]) {
                $cpasswordError = "Passwords must match";
            }
            if (empty($_POST["PhoneNumber"]) || !preg_match("/^[0-9]{10,15}$/", $_POST["PhoneNumber"])) {
                $phoneError = "Valid phone number is required";
            }
            if (empty($_POST["Address"])) {
                $addressError = "Address is required";
            }
            // If no validation errors
            if (empty($nameError) && empty($emailError) && empty($passwordError) && empty($cpasswordError) && empty($phoneError) && empty($addressError)) {
                // Check if email already exists
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $_POST["Email"]);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $emailError = "Email already exists. Please use a different email.";
                    echo "<script>alert('$emailError');</script>"; // Alert the user with the error message
                } else {
                    $hashedPassword = password_hash($_POST["Password"], PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type_id, phone_number, address, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())");
                    $userTypeId = 2; // Assuming user_type_id is 2 for regular users
                    $stmt->bind_param("sssiss", $_POST["Name"], $_POST["Email"], $hashedPassword, $userTypeId, $_POST["PhoneNumber"], $_POST["Address"]);
                    if ($stmt->execute()) {
                        header("Location: Login.php?signup=success");
                        exit();
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }
                }
            }
        } elseif ($_POST['action'] == 'signin') {
            // reCAPTCHA Validation
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $response_keys = json_decode($response, true);
            // Check reCAPTCHA success
            if (intval($response_keys["success"]) !== 1) {
                $captchaError = "Please complete the CAPTCHA verification.";
            } else {
                // Proceed with email and password validation only if reCAPTCHA is successful
                if (!filter_var($_POST["Email"], FILTER_VALIDATE_EMAIL)) {
                    $signin_emailError = "Valid email is required";
                }
                if (strlen($_POST["Password"]) < 8) {
                    $signin_passwordError = "Password must be at least 8 characters long";
                }
                // If no validation errors
                if (empty($signin_emailError) && empty($signin_passwordError)) {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->bind_param("s", $_POST["Email"]);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        if (password_verify($_POST["Password"], $row["password"])) {
                             // Update the updated_at column
                            $updateStmt = $conn->prepare("UPDATE users SET updated_at = NOW() WHERE user_id = ?");
                            $updateStmt->bind_param("i", $row["user_id"]);
                            $updateStmt->execute();
                            session_start();
                            $_SESSION["user_id"] = $row["user_id"];
                            $_SESSION["name"] = $row["name"];
                            $_SESSION["email"] = $row["email"];
                            $_SESSION["user_type_id"] = $row["user_type_id"]; // Store user_type_id in session
                            echo "user_type_id " .$row["user_type_id"];
                            switch ($row["user_type_id"]) {
                                case 1:
                                    header("Location: ../doctors/DoctorAppointments.php");
                                    break;
                                case 2:
                                    header("Location: ../users/index.php");
                                    break;
                                case 3:
                                    header("Location: ../admin/dashboard.php");
                                    break;
                                default:
                                    header("Location: ../users/index.php");
                                    break;
                            }
                            exit();
                        } else {

                            $signin_passwordError = "Invalid password";
                        }
                    } else {
                        $signin_emailError = "No account found with that email";
                    }
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="../public/css/login.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <style>
        #captchaError{
            border: 0px  orange;

        }
        .input-error {
            border: 1px solid red;
        }
   
        .error-message {
            color: red;
        }

    </style>
</head>
<body>

    <div class="form-container">
        <div class="col col-1">
            <div class="image-layer">
                <img src="../public/img/white-outline.png" class="form-image-main">
                <img src="../public/img/Medical Care Logo.png" class="form-image LOGO">
            </div>
            <p class="featured-words">Tracking health data today for a <span>healthier</span> tomorrow.</p>
        </div>
        <div class="col col-2">
            <div class="btn-box">
                <button class="btn btn-1" id="login">Sign In</button>
                <button class="btn btn-2" id="register">Sign Up</button>
            </div>



            <!-- Sign In Form -->
            <form method="post" action="Login.php" id="signin">
                <input type="hidden" name="action" value="signin">
                <div class="login-form">
                    <div class="form-tittle"><span>Sign In</span></div>

                    <div class="form-inputs">
                    <div class="input-box">
    <input type="text" class="input-field" placeholder="Email" name="Email">
    <i class="bx bx-user icon"></i>
    <div class="error-message"><?php echo $signin_emailError; ?></div>
</div>
<div class="input-box">
    <input type="password" id="signinPasswordInput" class="input-field" placeholder="Password" name="Password">
    <i class="bx bx-lock-alt icon" id="ToggleSigninPassword" style="cursor: pointer;"></i>
    <div class="error-message"><?php echo $signin_passwordError; ?></div>
</div>

                        <div class="forget-pass"><a href="./forgetPassword.php">Forgot Password?</a></div>
                        <br>
                       <div class="input-box">
                        <div class="g-recaptcha" data-sitekey="6LfzJGgqAAAAAErZ0_ejEIj5WRCHYXnMeGxICA88"></div>
                        <div class="error-message" id="captchaError"><?php echo $captchaError; ?></div>
</div>


                        <div class="input-box">
                            <button class="input-submit">
                                <span>Sign In</span>
                                <i class="bx bx-right-arrow-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Sign Up Form -->
            <form method="post" action="Login.php" id="signup">
                <input type="hidden" name="action" value="signup">
                <div class="register-form">
                    <div class="form-tittle"><span>Create Account</span></div>

                    <div class="form-inputs">
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Name" name="Name">
                            <i class="bx bx-user icon"></i>
                            <div class="error-message"><?php echo $nameError; ?></div>
                        </div>
                        <div class="input-box">
                            <input type="email" class="input-field" placeholder="Email" name="Email">
                            <i class="bx bx-envelope icon"></i>
                            <div class="error-message"><?php echo $emailError; ?></div>
                        </div>
<!-- Sign-up Password Input -->
<div class="input-box">
    <input type="password" id="signupPasswordInput" class="input-field" placeholder="Password" name="Password">
    <i class="bx bx-lock-alt icon" id="ToggleSignupPassword" style="cursor: pointer;"></i>
    <div class="error-message"><?php echo $passwordError; ?></div>
</div>

<!-- Confirm Password Input -->
    <div class="input-box">
        <input type="password" id="confirmPasswordInput" class="input-field" placeholder="Confirm Password" name="CPassword">
        <i class="bx bx-lock-alt icon" id="toggleConfirmPassword" style="cursor: pointer;"></i>
        <div class="error-message"><?php echo $cpasswordError; ?></div>
    </div>
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Phone Number" name="PhoneNumber">
                            <i class="bx bx-phone icon"></i>
                            <div class="error-message"><?php echo $phoneError; ?></div>
                        </div>
                        <div class="input-box">
                            <input type="text" class="input-field" placeholder="Address" name="Address">
                            <i class="bx bx-location-plus icon"></i>
                            <div class="error-message"><?php echo $addressError; ?></div>
                        </div>
                        <div class="input-box">
                            <button class="input-submit">
                                <span>Create Account</span>
                                <i class="bx bx-right-arrow-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script>
    document.getElementById('ToggleSigninPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('signinPasswordInput');
        const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', passwordType);
        this.classList.toggle('bx-show'); // Change icon
    });

    document.getElementById('ToggleSignupPassword').addEventListener('click', function () {
        const passwordInput = document.getElementById('signupPasswordInput');
        const passwordType = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', passwordType);
        this.classList.toggle('bx-show'); // Change icon
    });

    document.getElementById('toggleConfirmPassword').addEventListener('click', function () {
        const confirmPasswordInput = document.getElementById('confirmPasswordInput');
        const confirmPasswordType = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPasswordInput.setAttribute('type', confirmPasswordType);
        this.classList.toggle('bx-show'); // Change icon
    });
</script>

<script src="../public/js/NavFooter.js"></script>

<script src="../public/js/sign.js"></script>
</body>
</html>


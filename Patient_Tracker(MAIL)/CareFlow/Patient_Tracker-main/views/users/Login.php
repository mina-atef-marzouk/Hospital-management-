<?php
include("../includes/dbh.inc.php");
require '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$nameError = $emailError = $passwordError = $cpasswordError = $phoneError = $addressError = $signin_emailError = $signin_passwordError = $captchaError = "";
$recaptcha_secret = "6LfzJGgqAAAAAIspbMTLlRxVcnCLCPBSk29HlkzS";

function sendVerificationEmail($email, $name, $otp) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'careflow.tracker@gmail.com';
        $mail->Password = 'kdwp rabc tfde wjvw';
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('careflow.tracker@gmail.com', 'CareFlow');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "<p>Hello $name,</p><p>Your OTP for login is: <strong>$otp</strong></p><p>This code is valid for 10 minutes.</p>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("OTP email error: {$mail->ErrorInfo}");
        return false;
    }
}
// Inside your signin block
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['action'])) {
        if ($_POST['action'] == 'signup') {
            // Your signup logic (already correctly handled)
        } elseif ($_POST['action'] == 'signin') {
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptcha_secret&response=$recaptcha_response");
            $response_keys = json_decode($response, true);

            if (intval($response_keys["success"]) !== 1) {
                $captchaError = "Please complete the CAPTCHA verification.";
            } else {
                $email = trim($_POST["Email"]);
                $password = $_POST["Password"];

                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $signin_emailError = "Valid email is required";
                } elseif (strlen($password) < 8) {
                    $signin_passwordError = "Password must be at least 8 characters";
                } else {
                    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows === 1) {
                        $row = $result->fetch_assoc();

                        if (!password_verify($password, $row["password"])) {
                            $signin_passwordError = "Incorrect password.";
                        } elseif ($row["is_verified"] == 0) {
                            // Generate OTP
                            $otp = rand(100000, 999999);
                            $expiry = date("Y-m-d H:i:s", time() + 300); // 5 mins

                            // Store OTP and expiry
                            $updateOtpStmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
                            $updateOtpStmt->bind_param("sss", $otp, $expiry, $email);

                            if ($updateOtpStmt->execute()) {
                                // Send OTP
                                if (sendVerificationEmail($email, $row['name'], $otp)) {
                                    session_start();
                                    $_SESSION['email'] = $email;
                                    $_SESSION['user_id'] = $row['user_id'];
                                    header("Location: verify_otp.php");
                                    exit();
                                } else {
                                    $signin_emailError = "Failed to send OTP email.";
                                }
                            } else {
                                error_log("DB ERROR: " . $updateOtpStmt->error);
                                $signin_emailError = "Something went wrong. Try again.";
                            }
                        } else {
                            // User is verified — proceed to login
                            session_start();
                            $_SESSION['user_id'] = $row['user_id'];
                            $_SESSION['email'] = $row['email'];
                            $_SESSION['name'] = $row['name'];
                            $_SESSION['user_type_id'] = $row['user_type_id'];

                            if (password_verify($password, $row["password"])) {
                                // Password is correct, start session
                                $_SESSION["user_id"] = $row["user_id"];
                                $_SESSION["name"] = $row["name"];
                                $_SESSION["user_type_id"] = $row["user_type_id"]; // Store user_type_id in session

                                // Redirect based on user type
                                switch ($row["user_type_id"]) {
                                    case 1:
                                        // Doctor
                                        header("Location: ../doctors/index.php");
                                        break;
                                    case 2:
                                        // Patient
                                        header("Location: ../users/index.php");
                                        break;
                                    case 3:
                                        // Admin
                                        header("Location: ../admin/dashboard.php");
                                        break;
                                    default:
                                        // Default redirection, could be to a general user page
                                        header("Location: ../users/index.php");
                                        break;
                                }
                                exit();
                            }
                        }
                    } else {
                        $signin_emailError = "No account with that email.";
                    }
                }
            }
        }
    }
}

?>

<!-- Your HTML code remains the same -->


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
<script src="../public/js/sign.js"></script>
</body>
</html>


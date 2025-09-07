<?php
include("../includes/dbh.inc.php");
session_start();

$otpError = $otpSuccess = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Concatenate the 6 input boxes into one string
    $enteredOTP = trim(
        $_POST["otp1"] . $_POST["otp2"] . $_POST["otp3"] .
        $_POST["otp4"] . $_POST["otp5"] . $_POST["otp6"]
    );

    // Fetch user with the entered OTP
    $stmt = $conn->prepare("SELECT * FROM users WHERE otp = ?");
    $stmt->bind_param("s", $enteredOTP);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Check OTP expiration
        if (strtotime($user['otp_expiry']) > time()) {
            // OTP is valid, update the user's verification status
            $update = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE user_id = ?");
            $update->bind_param("i", $user['user_id']);
            $update->execute();

            $otpSuccess = "✅ Your email has been verified successfully!";

            // Store user session details
            $_SESSION["user_id"] = $user["user_id"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["name"] = $user["name"];
            $_SESSION["user_type_id"] = $user["user_type_id"];

            // Redirect user based on user type
            switch ($user["user_type_id"]) {
                case 1:
                    header("Location: ../doctors/index.php");
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
            $otpError = "❌ OTP expired. Please sign in again to get a new code.";
        }
    } else {
        $otpError = "❌ Invalid OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .otp-container {
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
            max-width: 100%;
            text-align: center;
        }
        .otp-container h2 {
            margin-bottom: 30px;
            font-size: 24px;
            color: #333;
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .otp-inputs input {
            width: 50px;
            height: 50px;
            text-align: center;
            font-size: 24px;
            border: 2px solid #ccc;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.3s ease;
        }
        .otp-inputs input:focus {
            border-color: #3498db;
        }
        .otp-inputs input:disabled {
            background-color: #f0f0f0;
        }
        .otp-container button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .otp-container button:hover {
            background-color: #2980b9;
        }
        .message {
            margin-top: 20px;
            font-weight: bold;
        }
        .error {
            color: #e74c3c;
        }
        .success {
            color: #2ecc71;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #aaa;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="otp-container">
        <h2>Verify Your OTP</h2>
        <form method="POST" oninput="moveToNextInput(event)">
            <div class="otp-inputs">
                <input type="text" name="otp1" maxlength="1" required autofocus>
                <input type="text" name="otp2" maxlength="1" required>
                <input type="text" name="otp3" maxlength="1" required>
                <input type="text" name="otp4" maxlength="1" required>
                <input type="text" name="otp5" maxlength="1" required>
                <input type="text" name="otp6" maxlength="1" required>
            </div>
            <button type="submit">Verify</button>
        </form>
        
        <div class="message <?php echo $otpError ? 'error' : 'success'; ?>">
            <?php echo $otpError ?: $otpSuccess; ?>
        </div>
        
        <div class="footer">
            <p>Didn't receive the code? <a href="#">Resend OTP</a></p>
        </div>
    </div>

    <script>
        // JavaScript to auto-focus on the next input field after a user types a digit
        function moveToNextInput(event) {
            const currentInput = event.target;
            if (currentInput.value.length === 1) {
                const nextInput = currentInput.nextElementSibling;
                if (nextInput) {
                    nextInput.focus();
                }
            }
        }
    </script>

</body>
</html>

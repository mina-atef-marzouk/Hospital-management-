<?php
session_start();
include("../includes/dbh.inc.php");

if (!isset($_SESSION['email'])) {
    header("Location: forgot-password.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['Cpassword']);
    $email = $_SESSION['email'];
    $code = $_SESSION['code'];
    $user_code = $conn->real_escape_string($_POST['code']);

    // Validate code
    if ($user_code != $code) {
        $error = "Invalid code!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Update password
        $update = "UPDATE users SET password = '$hashed_password', code = NULL WHERE email = '$email'";
        $conn->query($update);

        // Clear session
        unset($_SESSION['email'], $_SESSION['code']);

        $success = "Password updated successfully!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/forgetpassword.css">
    <title>Create New Password</title>
</head>
<body>
    <form method="POST" action="">
        <h2>Create New Password</h2>
        <p>Enter the verification code sent to your email:</p>
        <input type="text" name="code" placeholder="CODE OTP"required>
        <input type="password" name="password" placeholder="New Password" required>
        <input type="password" name="Cpassword" placeholder="Confirm Password" required>
        <button type="submit">Submit</button>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    </form>
</body>
</html>

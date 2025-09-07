<?php

session_start();
include("../includes/dbh.inc.php");
//echo realpath('../../../vendor/autoload.php');
require '../../../vendor/autoload.php';
// Include PHPMailer at the top

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Autoload PHPMailer classes

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    // Check if email exists
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Generate a verification code
        $code = rand(100000, 999999);
        $_SESSION['email'] = $email;
        $_SESSION['code'] = $code;

        // Update code in database
        $update = "UPDATE users SET code = '$code' WHERE email = '$email'";
        $conn->query($update);

        // Send the email using PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'careflow.tracker@gmail.com'; // SMTP username
            $mail->Password = 'kdwp rabc tfde wjvw'; // SMTP password
            $mail->SMTPSecure = 'ssl'; // Or 'ssl'
            $mail->Port = 465; // Typically 587 for TLS

            // Recipients
            $mail->setFrom('careflow.tracker@gmail.com', 'CareFlow'); // From address
            $mail->addAddress($email); // Recipient's email

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Code';
            $mail->Body = "Your verification code is: <b>$code</b>";

            $mail->send();
            header("Location: newpassword.php");
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Email not found!";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../public/css/forgetpassword.css">
</head>
<body>
    <form method="POST" action="">
        <h2>Forgot Password</h2>
        <p>Enter your email address:</p>
        <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <input type="email" name="email" required>
        <button type="submit">Continue</button>
    </form>
</body>
</html>

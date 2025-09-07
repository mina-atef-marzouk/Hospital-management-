<?php
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

    checkUserSession([2]);

// Start session

// Redirect to login if the user is not logged in


// Include PHPMailer
require '../../../vendor/autoload.php';

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    // Retrieve the user's email from the session
    $userEmail = $_SESSION['email'];

    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Gmail SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'careflow.tracker@gmail.com'; // Your email
        $mail->Password = 'kdwp rabc tfde wjvw'; // Your app password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Secure with SSL
        $mail->Port = 465;

        // Email settings
        $mail->setFrom($userEmail, 'User Feedback'); // Sender (user's email from session)
        $mail->addAddress('careflow.tracker@gmail.com
'); // Fixed recipient
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body);

        // Send email
        $mail->send();
        $message = "Your message has been sent to careflow@gmail.com.";
    } catch (Exception $e) {
        $message = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="../public/css/emailP.css"> 
</head>
<body>
<?php include("../includes/user-header.php");?>

    <div class="containerMESSAGE">
        <form method="POST" action="">
            <h1>Contact Us</h1>

            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" placeholder="Enter subject" required>

            <label for="body">Message:</label>
            <textarea id="body" name="body" placeholder="Write your message here..." required></textarea>

            <button type="submit" id="button">Send Email</button>
        </form>
    </div>

    <?php if (!empty($message)): ?>
        <script>
            alert("<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>");
        </script>
    <?php endif; ?>
</body>
</html>

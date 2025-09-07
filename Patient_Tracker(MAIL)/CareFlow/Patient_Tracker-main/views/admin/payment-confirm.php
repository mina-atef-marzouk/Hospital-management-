<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Confirmation</title>
    <link rel="stylesheet" href="../public/css/confirmation.css">
</head>

<body>
    <div class="container">
        <div class="content">
            <h1>Your payment has been successfully processed.</h1>
            <p>We’ve sent a confirmation email to your registered email address.</p>
            <div class="payment-summary">
                <h3>Payment Summary</h3>
                <p><strong>Payment Number:</strong> #123456</p>
                <p><strong>Total:</strong> $150.00</p>
            </div>
            <button class="home-btn" onclick="window.location.href='./dashboard.php'">
                Back to Home
            </button>
        </div>
    </div>
</body>

</html>

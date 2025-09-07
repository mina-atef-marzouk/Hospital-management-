<?php
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");
include("../includes/NotificationSystem.php");

checkUserSession([2]);

$userId = $_SESSION['user_id'];

// Initialize notification system (Publisher)
$notificationSystem = new NotificationSystem();

// Check if the user wants to subscribe/unsubscribe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientEmail = $_POST['patient_email'];
    $action = $_POST['action'];  // Subscribe or Unsubscribe

    // Update subscription status in the database
    $status = ($action === 'subscribe') ? 1 : 0;
    // Update subscription status in the database (assuming `patients` table with `email` and `subscribed` columns)
    $stmt = $conn->prepare("UPDATE patients SET subscribed = ? WHERE user_id = (SELECT user_id FROM users WHERE email = ?)");
    $stmt->execute([$status, $patientEmail]);

    // Notify patient if they are subscribed
    if ($status === 1) {
        $patientObserver = new PatientObserver($patientEmail, $status);
        $notificationSystem->addObserver($patientObserver);
        echo "Patient $patientEmail is subscribed and will receive notifications.<br>";
    } else {
        echo "Patient $patientEmail is unsubscribed and will not receive notifications.<br>";
        // Remove observer if unsubscribed
        $patientObserver = new PatientObserver($patientEmail, $status);
        $notificationSystem->removeObserver($patientObserver);
    }
}

// Retrieve the logged-in user's patient data
$stmt = $conn->prepare("SELECT u.email, p.subscribed 
                        FROM users u 
                        INNER JOIN patients p ON u.user_id = p.user_id 
                        WHERE u.user_id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$patient = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Subscription</title>
    <link rel="stylesheet" href="">
</head>
<body>
<?php include("../includes/user-header.php");?>

    <div class="containerSUB">
    <h2>Patient Subscription</h2>

        <?php if ($patient): ?>
            <form method="POST" action="">
                <input type="hidden" name="patient_email" value="<?php echo $patient['email']; ?>">
                <label><?php echo $patient['email']; ?></label>
                <button type="submit" name="action" value="<?php echo $patient['subscribed'] ? 'unsubscribe' : 'subscribe'; ?>">
                    <?php echo $patient['subscribed'] ? 'Unsubscribe' : 'Subscribe'; ?>
                </button>
                <br>
                <hr>
                <br>
                <a href="./send_notifications.php" id="sendNotificationLink">contact-us</a>

                </form>

        <?php else: ?>
            <p>No patient found for this account.</p>
        <?php endif; ?>

        
    </div>

    <style>
          #sendNotificationLink {
        display: inline-block;
        background-color: #28a745;  /* Green background */
        color: white;  /* White text color */
        padding: 12px 24px;  /* Padding for space around the text */
        font-size: 18px;  /* Larger font size */
        border-radius: 8px;  /* Rounded corners */
        text-decoration: none;  /* Remove underline */
        text-align: center;  /* Center text */
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);  /* Subtle shadow for depth */
        transition: all 0.3s ease;  /* Smooth transition for all properties */
    }

    /* Hover effect */
    #sendNotificationLink:hover {
        background-color: #218838;  /* Darker green */
        transform: translateY(-2px);  /* Slight lift on hover */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);  /* More shadow on hover */
    }

    /* Focus effect for accessibility */
    #sendNotificationLink:focus {
        outline: none;  /* Remove default focus outline */
        box-shadow: 0 0 10px 2px rgba(40, 167, 69, 0.6);  /* Green glow on focus */
    }
/* General container styling */
.containerSUB {
    width: 80%;
    max-width: 600px;
margin-top: 150px;
margin-left: 250px;

    padding: 30px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    text-align: center;
}

/* Styling the h2 */
h2 {
    color: #333;
    font-size: 32px;
    margin-bottom: 20px;
}

/* Styling the form labels */
label {
    font-size: 18px;
    color: #555;
    margin-bottom: 10px;
    display: block;
}

/* Styling the subscribe/unsubscribe button */
button {
    background-color: #007bff;
    color: white;
    padding: 12px 24px;
    font-size: 18px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    margin-top: 15px;
}

button:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

/* Styling the error message */
p {
    color: #e74c3c;
    font-size: 18px;
    margin-top: 20px;
}

/* Horizontal line style */
hr {
    margin-top: 40px;
    border-top: 1px solid #ddd;
    width: 80%;
    margin-left: 10%;
}

/* Add some spacing to the page */
body {
    font-family: 'Arial', sans-serif;
    background-color: #f4f7fa;
    margin: 0;
    padding: 0;
}
.button-link {
        background-color: #007bff;
        color: white;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .button-link:hover {
        background-color: #0056b3;
    }
</style>   
</body>
</html>

<?php
include("../includes/Dbh.inc.php");
include("../includes/NotificationSystem.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notificationSystem = new NotificationSystem();
    $subject = $_POST['subject'];
    $body = $_POST['body'];

    // Notify All Patients
    if (isset($_POST['notify_all_patients'])) {
        $patientEmails = fetchEmails($conn, 2); // Patients
        foreach ($patientEmails as $emailData) {
            $patient = new PatientObserver($emailData['email'], $emailData['subscribed']);
            $notificationSystem->addObserver($patient);
        }
        $notificationSystem->setMessage($subject, $body);
    }

    // Notify All Doctors (No subscription status check needed)
    if (isset($_POST['notify_all_doctors'])) {
        $doctorEmails = fetchEmails($conn, 1); // Doctors
        foreach ($doctorEmails as $email) {
            $doctor = new DoctorObserver($email); // No need for subscription status
            $notificationSystem->addObserver($doctor);
        }
                // Use notifyObservers() to send the message to all observers

        $notificationSystem->setMessage($subject, $body);
    }

    // Notify Specific Patient
    if (isset($_POST['notify_specific_patient']) && isset($_POST['recipient_email'])) {
        $patientEmails = fetchEmails($conn, 2);
        foreach ($patientEmails as $emailData) {
            if ($emailData['email'] == $_POST['recipient_email']) {
                $patient = new PatientObserver($emailData['email'], $emailData['subscribed']);
                $patient->update($subject, $body);
            }
        }
    }

    // Notify Specific Doctor
    if (isset($_POST['notify_specific_doctor']) && isset($_POST['recipient_email'])) {
        $doctor = new DoctorObserver($_POST['recipient_email']);
        $doctor->update($subject, $body);
    }
}
?>

<!-- HTML Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notify Patients and Doctors</title>
    <link rel="stylesheet" href="../public/css/email.css"> <!-- Include your external CSS -->
</head>
<body>
    <div class="container">
        <!-- Notify Patients Section -->
        <h1>Notify Patients</h1>
        <form method="POST" action="">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="body">Message:</label>
            <textarea id="body" name="body" required></textarea>

            <div class="button-group">
                <button type="submit" name="notify_all_patients" class="notify-btn">Notify All Patients (Subscribers)</button>
                <button type="submit" name="notify_specific_patient" class="notify-btn">Notify Specific Patient</button>
            </div>

            <label for="recipient_email">Select Patient:</label>
            <select id="recipient_email" name="recipient_email">
                <option value="">Select a patient</option>
                <?php
                    $patientEmails = fetchEmails($conn, 2);
                    foreach ($patientEmails as $emailData) {
                        echo "<option value=\"{$emailData['email']}\">{$emailData['email']}</option>";
                    }
                ?>
            </select>
        </form>

        <hr>

        <!-- Notify Doctors Section -->
        <h1>Notify Doctors</h1>
        <form method="POST" action="">
            <label for="subject">Subject:</label>
            <input type="text" id="subject" name="subject" required>

            <label for="body">Message:</label>
            <textarea id="body" name="body" required></textarea>

            <div class="button-group">
                <button type="submit" name="notify_all_doctors" class="notify-btn">Notify All Doctors</button>
                <button type="submit" name="notify_specific_doctor" class="notify-btn">Notify Specific Doctor</button>
            </div>

            <label for="recipient_email">Select Doctor:</label>
            <select id="recipient_email" name="recipient_email">
                <option value="">Select a doctor</option>
                <?php
                    $doctorEmails = fetchEmails($conn, 1);
                    foreach ($doctorEmails as $email) {
                        echo "<option value=\"$email\">$email</option>";
                    }
                ?>
            </select>
        </form>
    </div>
</body>
</html>

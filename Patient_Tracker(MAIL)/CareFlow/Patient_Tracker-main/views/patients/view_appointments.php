<?php
// Start the session at the top of the script
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

// Ensure the user is logged in and is a doctor (session check)
checkUserSession([2]); // Assuming 1 is the role for doctors


// Check if the user is logged in and the session has `user_id`
if (!isset($_SESSION['user_id'])) {
    echo "<p class='error'>Error: You must log in to view your appointments.</p>";
    exit;
}

// Fetch the logged-in user's ID from the session
$userId = $_SESSION['user_id'];

// Fetch the corresponding patient ID for the logged-in user
$patientId = null;
$sql = "SELECT patient_id FROM patients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patientId = $row['patient_id'];
} else {
    echo "<p class='error'>Error:FILL YOUR PROFILE INFORMATION .</p>";
    echo "<a href='../patients/Profile.php'>profile information page/a>";    exit;
}

// Fetch appointments for the patient
$appointments = [];
$sql = "SELECT a.appointment_id, a.appointment_date, a.status, 
               d.specialization AS doctor_specialization 
        FROM appointments a 
        INNER JOIN doctors d ON a.doctor_id = d.doctor_id 
        WHERE a.patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patientId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
} else {
    echo "<p class='info'>No appointments found.</p>";
    $conn->close();
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../public/css/view_appointments.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointments</title>
</head>
<?php include("../includes/user-header.php"); ?>

<body>
    <div class="containerNOUR">
        <h1>Your Appointments</h1>
        <?php if (!empty($appointments)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Doctor</th>
                        <th>Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($appointments as $appointment): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['appointment_id']); ?></td>
                            <td><?= htmlspecialchars($appointment['doctor_specialization']); ?></td>
                            <td><?= htmlspecialchars($appointment['appointment_date']); ?></td>
                            <td><?= htmlspecialchars($appointment['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="info">You have no appointments booked.</p>
        <?php endif; ?>
    </div>
</body>
</html>

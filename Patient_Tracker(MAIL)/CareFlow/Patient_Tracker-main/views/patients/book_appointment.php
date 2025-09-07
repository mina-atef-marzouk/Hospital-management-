<?php
// Start the session at the top of the script
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

// Ensure the user is logged in and is a doctor (session check)
checkUserSession([2]); // Assuming 1 is the role for doctors

// Check if the user is logged in and the session has `user_id` 
if (!isset($_SESSION['user_id'])) {
    echo "<p class='error'>Error: You must log in to book an appointment.</p>";
    exit;
}

// Fetch the logged-in user's ID from the session
$userId = $_SESSION['user_id'];

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
    echo "<a href='../patients/Profile.php'>profile information page/a>";

    exit;
}

// Fetch doctors from the database
$doctors = [];
$sql = "SELECT doctor_id, specialization FROM doctors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }
}

// Handle appointment submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($patientId) { // Ensure a valid patient ID is retrieved
        $doctorId = $_POST['doctor_id'];
        $appointmentDate = $_POST['appointment_date'];
        $status = "scheduled";

        $insertSql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("iiss", $patientId, $doctorId, $appointmentDate, $status);

        if ($stmt->execute()) {
            echo "<p class='success'>Appointment booked successfully!</p>";
        } else {
            echo "<p class='error'>Error: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p class='error'>Error: Invalid patient data.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="../public/css/book_appointment.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
</head>
<body>
<?php include("../includes/user-header.php"); ?>

    <div class="containerNOUR">
        <h1>Book an Appointment</h1>
        <form method="POST" action="">
            <label for="doctor_id">Select Doctor:</label>
            <select id="doctor_id" name="doctor_id" required>
                <option value="">--Select a Doctor--</option>
                <?php foreach ($doctors as $doctor): ?>
                    <option value="<?= $doctor['doctor_id']; ?>">
                        <?= "Dr. " . $doctor['specialization']; ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="appointment_date">Appointment Date:</label>
            <input type="date" id="appointment_date" name="appointment_date" required>

            <button type="submit">Book Appointment</button>
        </form>
    </div>
    
</body>
</html>

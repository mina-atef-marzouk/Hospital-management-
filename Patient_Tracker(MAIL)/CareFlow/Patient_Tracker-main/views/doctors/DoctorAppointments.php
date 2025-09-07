<?php
include("../includes/Dbh.inc.php");
include("../doctors/SessionManager.php");
include("../doctors/Doctor.php");
include("../doctors/Appointment.php");
include("../includes/doctor-header.php");

SessionManager::checkUserSession([1]); // Ensure only doctors can access this page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Appointments</title>
    <link rel="stylesheet" href="../public/css/doctorPage.css">
</head>
<body>

<header>
    Doctor Appointments
</header>

<div class="containerr">
    <?php
    if (isset($_SESSION["user_id"]) && $_SESSION["user_type_id"] == 1) {
        $user_id = $_SESSION["user_id"];
        
        $doctor = new Doctor($conn, $user_id);
        $doctor_id = $doctor->getDoctorId();
        
        $appointment = new Appointment($conn);
        $appointment_result = $appointment->getAppointmentsByDoctorId($doctor_id);

        if ($appointment_result && $appointment_result->num_rows > 0) {
            // Display appointment statement
            echo "<p class='doctor-appointments-statement'>Here are your upcoming appointments:</p>";

            echo "<table>";
            echo "<tr><th>Appointment ID</th><th>Appointment Date</th><th>Status</th></tr>";
            while ($row = $appointment_result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['appointment_id'] . "</td>";
                echo "<td>" . $row['appointment_date'] . "</td>";
                echo "<td class='doctor-appointments-status'>" . $row['status'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p class='message no-appointments'>No appointments found.</p>";
        }
    } else {
        echo "<p class='message'>You must be logged in as a doctor to view appointments.</p>";
    }
    ?>
</div>

</body>
</html>

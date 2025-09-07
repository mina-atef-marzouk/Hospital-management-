<?php
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

    checkUserSession([1]);


if (isset($_SESSION["user_id"]) && $_SESSION["user_type_id"] == 1) { // user_type_id 1 is for doctors

    // Get user_id from session
    $user_id = $_SESSION["user_id"];

    // Prepare query to get doctor_id based on user_id
    $sql = "SELECT doctor_id FROM doctors WHERE user_id = ?";
    $query = $conn->prepare($sql);
    if (!$query) {
        die("SQL prepare failed: " . $conn->error);  // Display the error if the query fails
    }

    $query->bind_param("i", $user_id);
    if (!$query->execute()) {
        die("SQL execution failed: " . $query->error);  // Display error if execution fails
    }

    $result = $query->get_result(); // Execute the query and get the result

    // Check if the doctor_id exists for the logged-in user
    if ($result->num_rows > 0) {
        // Fetch the doctor_id from the result
        $doctor_data = $result->fetch_assoc();
        $doctor_id = $doctor_data['doctor_id'];
        
        // Prepare the query to get appointments for the logged-in doctor
        $appointment_sql = "SELECT appointment_id, appointment_date, status FROM appointments WHERE doctor_id = ?";
        $appointment_query = $conn->prepare($appointment_sql);
        if (!$appointment_query) {
            die("SQL prepare failed: " . $conn->error);  // Display the error if the query fails
        }

        $appointment_query->bind_param("i", $doctor_id);
        if (!$appointment_query->execute()) {
            die("SQL execution failed: " . $appointment_query->error);  // Display error if execution fails
        }

        $appointment_result = $appointment_query->get_result(); // Execute the query and get the result
    } else {
        die("Doctor not found for the logged-in user.");
    }

    // Display the page
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Doctor Appointments</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../public/css/doctorPage.css">  <!-- Link to external CSS -->
    </head>
    <body>
    <?php include("../includes/doctor-header.php");?>
        <div class="containerr">
            <h1>Doctor Appointments</h1>
            <?php
            // Check if there are any appointments
            if ($appointment_result->num_rows > 0) {
                echo '
                <table>
                    <tr>
                        <th>Appointment ID</th>
                        <th>Appointment Date</th>
                        <th>Status</th>
                    </tr>';
                // Loop through and display each appointment
                while ($appointment = $appointment_result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$appointment['appointment_id']}</td>
                            <td>" . date('d M Y, H:i', strtotime($appointment['appointment_date'])) . "</td>
                            <td>{$appointment['status']}</td>
                            </tr>";
                }

                echo '</table>';
            } else {
                echo '<p class="no-appointments">No appointments found.</p>';
            }
            ?>
        </div>
    </body>
    </html>

<?php

} else {
    echo '<p>You must be logged in as a doctor to view appointments.</p>';
}
?>

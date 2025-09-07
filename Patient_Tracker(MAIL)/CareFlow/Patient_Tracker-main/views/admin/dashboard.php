<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$DB = "patient_tracker";
$conn = mysqli_connect($servername, $username, $password, $DB);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize variables to read from database 
$totalPatients = $totalDoctors = $newAppointments = $appointmentsToday = $scheduledAppointments = $completedAppointments = $canceledAppointments = 0;

try {
    // Total Patients
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM patients");
    if ($query) {
        $totalPatients = mysqli_fetch_assoc($query)['count'];
    }

    // Total Doctors
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM doctors");
    if ($query) {
        $totalDoctors = mysqli_fetch_assoc($query)['count'];
    }

  // Overall Bookings
  $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments");
  if ($query) {
      $overallBookings = mysqli_fetch_assoc($query)['count'];
  }


    // Appointments Today (scheduled for today)
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE DATE(appointment_date) = CURDATE()");
    if ($query) {
        $appointmentsToday = mysqli_fetch_assoc($query)['count'];
    }

    // Scheduled Appointments
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status = 'scheduled'");
    if ($query) {
        $scheduledAppointments = mysqli_fetch_assoc($query)['count'];
    }

    // Completed Appointments
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status = 'completed'");
    if ($query) {
        $completedAppointments = mysqli_fetch_assoc($query)['count'];
    }

    // Canceled Appointments
    $query = mysqli_query($conn, "SELECT COUNT(*) as count FROM appointments WHERE status = 'canceled'");
    if ($query) {
        $canceledAppointments = mysqli_fetch_assoc($query)['count'];
    }
} catch (Exception $e) {
    echo "Error fetching statistics: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Tracker Layout</title>
    <!-- <link rel="stylesheet" href="../public/css/layout1.css"> -->
    <link rel="stylesheet" href="../public/css/ex-styles.css">
    <link rel="stylesheet" href="../public/css/animations.css"> 
 
</head>
<body>
    <div class="container">
<?php include("../includes/admin-header.php");?>
        <!-- Main Content -->
        <div class="main">
            <!-- Stats Section -->
            <div class="stats">
                <div class="stat"><h3>Total Doctors</h3><span><?php echo $totalDoctors; ?></span></div>
                <div class="stat"> <h3>Overall Bookings</h3><span><?php echo $overallBookings; ?></span></div>
                <div class="stat"><h3>Total Patients</h3><span><?php echo $totalPatients; ?></span></div>
                <div class="stat"><h3>Scheduled Appointments</h3><span><?php echo $scheduledAppointments; ?></span></div>
                <div class="stat"><h3>Completed Appointments</h3><span><?php echo $completedAppointments; ?></span></div>
                <div class="stat"><h3>Canceled Appointments</h3><span><?php echo $canceledAppointments; ?></span></div>
            </div>

            <!-- Patient List Section -->
            <div class="patient-list">
                <h3 class="heading-main12">Patients List</h3>
                <button onclick="window.location.href='add.php'" class="btn-primary-soft btn button-icon btn-add" >Add user</button>
                <table class="sub-table" >
                    <thead>
                        <tr>
                            <th class="table-headin">Patient ID</th>
                            <th class="table-headin">Date of Birth</th>
                            <th class="table-headin">Gender</th>
                            <th class="table-headin">Medical History</th>
                            <th class="table-headin">Allergies</th>
                            <th class="table-headin">Emergency Contact</th>
                            <th class="table-headin">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                       include("../includes/dbh.inc.php");

                        // Fetch patient data
                        $sql = "SELECT * FROM patients";
                        $result = mysqli_query($conn, $sql);

                        // Loop through and display patient data
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td style=\"font-weight:600;text-align:center;\">{$row['patient_id']}</td>
                                        <td style=\"text-align:center;\">{$row['dob']}</td>
                                        <td style=\"text-align:center;\">{$row['gender']}</td>
                                        <td style=\"text-align:center;\">{$row['medical_history']}</td>
                                        <td style=\"text-align:center;\">{$row['allergies']}</td>
                                        <td style=\"text-align:center;\">{$row['emergency_contact']}</td>
                                        <td>
                                            <form action='../admin/Delete.php' method='POST' >
                                                <input type='hidden' name='patient_id' value='{$row['patient_id']}'>
                                                <input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this patient?\")' class='btn-primary-soft btn button-icon btn-delete'>
                                            </form>
                                            <form action='../admin/Edit.php' method='GET' >
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'> <!-- Changed to user_id -->
                                        <input type='submit' value='Edit' class='btn-primary-soft btn button-icon btn-edit'>
                                    </form>
                                        </td>
                                    </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='10'>No patients found</td></tr>";
                        }

                        mysqli_close($conn);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>

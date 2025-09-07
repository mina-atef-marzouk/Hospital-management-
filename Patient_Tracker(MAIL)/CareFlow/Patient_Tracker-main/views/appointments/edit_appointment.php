<?php
include("../includes/dbh.inc.php");

$appointment_id = '';
$appointment_date = '';
$patient_id = '';
$doctor_id = '';
$status = '';

if (isset($_GET['appointment_id'])) {
    $appointment_id = $_GET['appointment_id'];

    // Get the current appointment data
    $stmt = $conn->prepare("
        SELECT appointments.appointment_id, patients.patient_id, users.name AS patient_name, doctors.doctor_id, users_doctor.name AS doctor_name, 
               appointments.appointment_date, appointments.status
        FROM appointments
        INNER JOIN patients ON appointments.patient_id = patients.patient_id
        INNER JOIN doctors ON appointments.doctor_id = doctors.doctor_id
        INNER JOIN users AS users_doctor ON doctors.user_id = users_doctor.user_id
        INNER JOIN users ON patients.user_id = users.user_id
        WHERE appointments.appointment_id = ?
    ");
    $stmt->bind_param("i", $appointment_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $appointment_date = $row['appointment_date'];
        $patient_id = $row['patient_id'];
        $doctor_id = $row['doctor_id'];
        $status = $row['status'];
    } else {
        echo "<script>alert('Appointment not found.'); window.location.href='appointments.php';</script>";
        exit();
    }
    $stmt->close();
}
//Update the appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $appointment_id = $_POST['appointment_id'] ?? null;
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("
        UPDATE appointments 
        SET patient_id = ?, doctor_id = ?, appointment_date = ?, status = ?
        WHERE appointment_id = ?
    ");
    $stmt->bind_param("iissi", $patient_id, $doctor_id, $appointment_date, $status, $appointment_id);

    if ($stmt->execute()) {
        header("Location: appointments.php");
    } else {
        echo "<script>alert('Error updating appointment: " . $stmt->error . "');</script>";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../public/css/edit_appointment.css" rel="stylesheet">

    <title>Edit Appointment</title>
</head>
<body>
<div class="container">
        <?php
    include("../includes/admin-header.php");
    ?>

    <div>
        <div class="form-container">
            <h2 style="color: #0A76D8; margin:40px;">Edit Patient Info.</h2>
            <form action="edit_appointment.php" method="POST">
                <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointment_id); ?>">

                <label class="form-label" for="patient_id">Select Patient:</label>
                <select name="patient_id" class="input-text filter-container-items" required>
                    <?php
                    $patientsList = $conn->query("
                        SELECT patients.patient_id, users.name AS patient_name 
                        FROM patients
                        INNER JOIN users ON patients.user_id = users.user_id
                    ");

                    while ($patient = $patientsList->fetch_assoc()) {
                        $selected = ($patient['patient_id'] == $patient_id) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($patient['patient_id']) . "' $selected>" . htmlspecialchars($patient['patient_name']) . "</option>";
                    }
                    ?>
                </select>

                <label class="form-label" for="doctor_id">Select Doctor:</label>
                <select name="doctor_id" class="input-text filter-container-items" required>
                    <?php
                    $doctorsList = $conn->query("
                        SELECT doctors.doctor_id, users.name AS doctor_name 
                        FROM doctors
                        INNER JOIN users ON doctors.user_id = users.user_id
                    ");

                    while ($doctor = $doctorsList->fetch_assoc()) {
                        $selected = ($doctor['doctor_id'] == $doctor_id) ? 'selected' : '';
                        echo "<option value='" . htmlspecialchars($doctor['doctor_id']) . "' $selected>" . htmlspecialchars($doctor['doctor_name']) . "</option>";
                    }
                    ?>
                </select>

                <label class="form-label" for="appointment_date">Appointment Date:</label>
                <input type="date" name="appointment_date" style="width: 95%" value="<?php echo htmlspecialchars($appointment_date); ?>" class="input-text filter-container-items" required>

                <label class="form-label" for="status">Status:</label>
                <select name="status" class="input-text filter-container-items" required>
                    <option value="scheduled" <?php echo ($status == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                    <option value="completed" <?php echo ($status == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="cancelled" <?php echo ($status == 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                </select>

                <input class="login-btn btn-primary-soft btn" type="submit" value="Update Appointment">
            </form>
        </div>
    </div>
</div>
</body>
</html>

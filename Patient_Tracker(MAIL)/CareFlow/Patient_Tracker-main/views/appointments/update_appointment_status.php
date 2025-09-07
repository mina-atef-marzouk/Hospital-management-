<?php
include("../includes/dbh.inc.php");

if (isset($_POST['Appointment_ID']) && isset($_POST['status'])) {
    $Appointment_ID = $_POST['Appointment_ID']; // Keep the same casing
    $new_status = $_POST['status'];

    $sql = "UPDATE appointments SET status = ? WHERE appointment_id = ?";
   
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('si', $new_status, $Appointment_ID);

        if ($stmt->execute()) {
            echo "<script>alert('Appointment successfully updated!'); window.location.href = 'appointments.php';</script>";
        } else {
            echo "<script>alert('Error updating appointment: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    } else {
        echo "<script>alert('Error preparing statement: " . $conn->error . "');</script>";
    }
}

$conn->close();

header("Location: appointments.php");
exit();
?>
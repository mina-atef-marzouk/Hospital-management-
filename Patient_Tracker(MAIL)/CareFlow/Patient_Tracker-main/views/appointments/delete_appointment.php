<?php
include("../includes/dbh.inc.php");

// Check for Appointment ID
if (isset($_GET['Appointment_ID'])) {
    $appointment_id = intval($_GET['Appointment_ID']); 
} else {
    header("Location: appointments.php");
    exit();
}

// Handle delete action
if (isset($_POST['delete'])) {
    $sql = "DELETE FROM appointments WHERE appointment_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $appointment_id);

    if ($stmt->execute()) {
        header("Location: appointments.php");
    } else {
        echo "<script>alert('Error: Could not delete the appointment.'); window.location.href = 'appointments.php';</script>";
    }

    $stmt->close();
    exit();
}

// Handle cancel action
if (isset($_POST['cancel_appo'])) {
    // Redirect to the appointments page
    header("Location: appointments.php");
    exit();
}
?>

<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Delete Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <link href="../public/css/delete_appoitment.css" rel="stylesheet">
</head>
<body>

<!-- Modal -->
<div id="myModal" class="modal fade">
    <div class="modal-dialog modal-confirm">
        <div class="modal-content">
            <div class="modal-header flex-column">
                <h4 class="modal-title w-100">Are you sure?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <p>Do you really want to delete this appointment? This process cannot be undone.</p>
            </div>
            <div class="modal-footer justify-content-center">
                <form method="POST" id="deleteForm">
                    <button type="submit" name="cancel_appo" class="btn btn-secondary">Cancel</button>
                    <button type="submit" name="delete" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Script to show modal on page load -->
<script type='text/javascript'>
    $(document).ready(function() {
        $('#myModal').modal('show');
    });
</script>

</body>
</html>

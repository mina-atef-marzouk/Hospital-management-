<?php
include("../includes/dbh.inc.php");
include("../includes/admin-header.php");
include("../includes/session_check.php");
    checkUserSession([3]);


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['patient_id'])) {
    $patient_id = intval(mysqli_real_escape_string($conn, $_POST['patient_id'])); // Sanitize input

    // select patient by ID to delete it 
    $sql = "DELETE FROM patients WHERE patient_id = $patient_id";
    
    if (mysqli_query($conn, $sql)) {
        // after executing the delete function it returns to the table 
        header("Location: display.php");
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    echo "No patient ID provided.";
}

// Close connection
mysqli_close($conn);
?>

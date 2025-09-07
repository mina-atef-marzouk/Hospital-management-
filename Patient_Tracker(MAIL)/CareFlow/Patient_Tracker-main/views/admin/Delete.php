<?php
// Database connection
include("../includes/dbh.inc.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = intval(mysqli_real_escape_string($conn, $_POST['user_id'])); // Sanitize input

    // First, fetch the user_type_id based on user_id
    $sqlUserType = "SELECT user_type_id FROM users WHERE user_id = $user_id";
    $resultUserType = mysqli_query($conn, $sqlUserType);

    if (mysqli_num_rows($resultUserType) > 0) {
        $rowUserType = mysqli_fetch_assoc($resultUserType);
        $user_type_id = $rowUserType['user_type_id'];

        // Check user_type_id and delete from the corresponding table
        if ($user_type_id == 2) { // Patient
            $sqlDelete = "DELETE FROM patients WHERE user_id = $user_id";
        } elseif ($user_type_id == 1) { // Doctor
            $sqlDelete = "DELETE FROM doctors WHERE user_id = $user_id";
        } else {
            echo "Unknown user type. Unable to delete.";
            exit();
        }

        // Execute the delete query
        if (mysqli_query($conn, $sqlDelete)) {
            // Redirect back to the appropriate display page
            header("Location: ../admin/dashboard.php"); // You can change this to the specific display page
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }
    } else {
        echo "No user found with the provided ID.";
    }
} else {
    echo "No user ID provided.";
}

// Close connection
mysqli_close($conn);
?>

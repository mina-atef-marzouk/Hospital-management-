<?php

include("../includes/dbh.inc.php");

// Start session
session_start();

// Check if user_id is set in the session before proceeding
if (!isset($_SESSION['user_id'])) {
    echo "User ID is not set in the session.";
    exit;
}

// Prepare and execute the update query
$stmt = $conn->prepare("UPDATE users SET is_verified = 0 WHERE user_id = ? AND user_type_id = 2");
$stmt->bind_param("i", $_SESSION['user_id']);

if ($stmt->execute()) {
    // Successfully updated is_verified
    echo "User is_verified status updated to 0.";
} else {
    // Error during update
    echo "Error: " . $stmt->error;
}

// Unset session and destroy after the update
session_unset();
session_destroy();

// Redirect to login page
header("Location: login.php");
exit();

?>

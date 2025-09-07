<?php
include("../includes/dbh.inc.php");
include("../includes/admin-header.php");
include("../includes/session_check.php");
    checkUserSession([3]);

$patientData = null;


if (isset($_GET['patient_id'])) {
    $patient_id = intval($_GET['patient_id']);  

    // Fetch patient data
    $sql = "SELECT * FROM patients WHERE patient_id = $patient_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $patientData = mysqli_fetch_assoc($result);
    } else {
        echo "No patient found with the provided ID.";
        exit;  
    }
} else {
    echo "No patient ID provided.";
    exit;  
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = intval($_POST['user_id']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $medical_history = $_POST['medical_history'];
    $allergies = $_POST['allergies'];
    $emergency_contact = $_POST['emergency_contact'];

    // Update patient data
    $sql = "UPDATE patients SET user_id='$user_id', dob='$dob', gender='$gender', 
            medical_history='$medical_history', allergies='$allergies', emergency_contact='$emergency_contact' 
            WHERE patient_id = $patient_id";

    if (mysqli_query($conn, $sql)) {
        echo "Patient updated successfully.";
        header("Location: display.php");
        exit;  
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
    <link rel="stylesheet" href="layout.css">
</head>
<body>
    <div class="container">
        <h2>Edit Patient</h2>
        <form action="edit.php?patient_id=<?php echo $patient_id; ?>" method="POST">
            <label for="user_id">User ID:</label>
            <input type="number" name="user_id" value="<?php echo $patientData['user_id']; ?>" required>

            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob" value="<?php echo $patientData['dob']; ?>" required>

            <label for="gender">Gender:</label>
            <select name="gender" required>
                <option value="male" <?php if($patientData['gender'] == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if($patientData['gender'] == 'female') echo 'selected'; ?>>Female</option>
                <option value="other" <?php if($patientData['gender'] == 'other') echo 'selected'; ?>>Other</option>
            </select>

            <label for="medical_history">Medical History:</label>
            <textarea name="medical_history" required><?php echo $patientData['medical_history']; ?></textarea>

            <label for="allergies">Allergies:</label>
            <textarea name="allergies"><?php echo $patientData['allergies']; ?></textarea>

            <label for="emergency_contact">Emergency Contact:</label>
            <input type="text" name="emergency_contact" value="<?php echo $patientData['emergency_contact']; ?>" required>

            <input type="submit" value="Update Patient">
        </form>
    </div>
</body>
</html>

<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../includes/dbh.inc.php");

// Initialize user data variable
$userData = null;

// Check if 'user_id' is passed via GET
if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);  

    // Fetch user data to determine user type
    $sqlUser = "SELECT * FROM users WHERE user_id = $user_id";
    $resultUser = mysqli_query($conn, $sqlUser);
    
    if (mysqli_num_rows($resultUser) > 0) {
        $userData = mysqli_fetch_assoc($resultUser);
    } else {
        echo "No user found with the provided ID.";
        exit;  
    }
} else {
    echo "No user ID provided.";
    exit;  
}

// Determine user type
$user_type_id = $userData['user_type_id'];
$patientData = null;
$doctorData = null;

if ($user_type_id == 2) {
    // Fetch patient data
    $sqlPatient = "SELECT * FROM patients WHERE user_id = $user_id";
    $resultPatient = mysqli_query($conn, $sqlPatient);
    
    if (mysqli_num_rows($resultPatient) > 0) {
        $patientData = mysqli_fetch_assoc($resultPatient);
    } else {
        echo "No patient data found.";
        exit;  
    }
} elseif ($user_type_id == 1) {
    // Fetch doctor data
    $sqlDoctor = "SELECT * FROM doctors WHERE user_id = $user_id";
    $resultDoctor = mysqli_query($conn, $sqlDoctor);
    
    if (mysqli_num_rows($resultDoctor) > 0) {
        $doctorData = mysqli_fetch_assoc($resultDoctor);
    } else {
        echo "No doctor data found.";
        exit;  
    }
}

// Handle the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Update user data
    $user_id = intval($_POST['user_id']);
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sqlUpdateUser = "UPDATE users SET name='$name', email='$email' WHERE user_id = $user_id";
    mysqli_query($conn, $sqlUpdateUser);

    // Update patient or doctor data based on user_type_id
    if ($user_type_id == 2) { // For patients
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $medical_history = $_POST['medical_history'];
        $allergies = $_POST['allergies'];
        $emergency_contact = $_POST['emergency_contact'];

        $sqlUpdatePatient = "UPDATE patients SET dob='$dob', gender='$gender', 
                             medical_history='$medical_history', allergies='$allergies', emergency_contact='$emergency_contact' 
                             WHERE user_id = $user_id";
        if (mysqli_query($conn, $sqlUpdatePatient)) {
            header("Location: ../patients/display_patients.php");
            exit;  
        } else {
            echo "Error updating patient record: " . mysqli_error($conn);
        }
    } elseif ($user_type_id == 1) { // For doctors
        $specialization = $_POST['specialization'];
        $years_of_experience = $_POST['years_of_experience'];
        $license_number = $_POST['license_number'];

        $sqlUpdateDoctor = "UPDATE doctors SET specialization='$specialization', years_of_experience='$years_of_experience', 
                            license_number='$license_number' WHERE user_id = $user_id";
        if (mysqli_query($conn, $sqlUpdateDoctor)) {
            header("Location: ../doctors/display_doctors.php");
            exit;  
        } else {
            echo "Error updating doctor record: " . mysqli_error($conn);
        }
    }
}

// Close connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <!-- <link rel="stylesheet" href="../public/css/users_manag.css"> -->
         <link rel="stylesheet" href="../public/css/add-user.css">

</head>
<body>
<div class="container">
    <?php include("../includes/admin-header.php")?>
    <div class="cont">
        <div class="first">
    <h2 style="color:  #0A76D8; margin:30px;">Edit User</h2>
    <form action="edit.php?user_id=<?php echo $user_id; ?>" method="POST">
        <label for="user_id">User ID:</label>
        <input type="number" name="user_id"   value="<?php echo $userData['user_id']; ?>" readonly required>

        <label for="name">Name:</label>
        <input type="text" name="name"   value="<?php echo htmlspecialchars($userData['name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email"   value="<?php echo htmlspecialchars($userData['email']); ?>" required>
        </div>
        <?php if ($user_type_id == 2): // Patient-specific fields ?>
            <div class="second">
            <h3>Patient Details</h3>
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob"   value="<?php echo $patientData['dob']; ?>" required>
            <br>
            <label for="gender">Gender:</label>
            <select name="gender" style="width: 93%;"  required>
                <option value="male" <?php if($patientData['gender'] == 'male') echo 'selected'; ?>>Male</option>
                <option value="female" <?php if($patientData['gender'] == 'female') echo 'selected'; ?>>Female</option>
            </select>
            <br>
            <label for="medical_history">Medical History:</label>
            <br>
            <textarea name="medical_history"   required><?php echo htmlspecialchars($patientData['medical_history']); ?></textarea>
            <br>
            <label for="allergies">Allergies:</label>
            <br>
            <textarea name="allergies"  ><?php echo htmlspecialchars($patientData['allergies']); ?></textarea>
            <br>
            <label for="emergency_contact">Emergency Contact:</label>
            <br>
            <input type="text" name="emergency_contact"   value="<?php echo htmlspecialchars($patientData['emergency_contact']); ?>" required>
            <br>
            <input type="submit" value="Update User" >

            </div>
        <?php elseif ($user_type_id == 1): // Doctor-specific fields ?>
            <div class="second">
            <h3>Doctor Details</h3>
            <label for="specialization">Specialization:</label>
            <input type="text" name="specialization"   value="<?php echo htmlspecialchars($doctorData['specialization']); ?>" required>

            <label for="years_of_experience">Years of Experience:</label>
            <input type="number" name="years_of_experience"   value="<?php echo htmlspecialchars($doctorData['years_of_experience']); ?>" min="0" required>

            <label for="license_number">License Number:</label>
            <input type="text" name="license_number"   value="<?php echo htmlspecialchars($doctorData['license_number']); ?>" required>
            <br>
            <input type="submit" value="Update User" >
            </div>

        <?php endif; ?>

    </form>
</div>
</div>
</body>
</html>

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

// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $user_type_id = intval($_POST['user_type_id']);

    // Insert user data into the users table
    $sqlUser = "INSERT INTO users (name, email, password, user_type_id) VALUES ('$name', '$email', '$password', '$user_type_id')";

    if (mysqli_query($conn, $sqlUser)) {
        $user_id = mysqli_insert_id($conn); // Get the last inserted user ID

        // Handle additional tables based on user type
        if ($user_type_id == 2) { // Patient
            $dob = $_POST['dob'] ?: null;
            $gender = $_POST['gender'] ?: null;
            $medical_history = mysqli_real_escape_string($conn, $_POST['medical_history'] ?: '');
            $allergies = mysqli_real_escape_string($conn, $_POST['allergies'] ?: '');
            $emergency_contact = mysqli_real_escape_string($conn, $_POST['emergency_contact'] ?: '');
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $sqlPatient = "INSERT INTO patients (user_id, dob, gender, medical_history, allergies, emergency_contact, created_at, updated_at) 
                           VALUES ('$user_id', '$dob', '$gender', '$medical_history', '$allergies', '$emergency_contact', '$created_at', '$updated_at')";

            if (mysqli_query($conn, $sqlPatient)) {
                header("Location: ../patients/display_patients.php");
                exit;
            } else {
                echo "Error adding patient record: " . mysqli_error($conn);
            }
        } elseif ($user_type_id == 1) { // Doctor
            $specialization = mysqli_real_escape_string($conn, $_POST['specialization'] ?: '');
            $years_of_experience = intval($_POST['years_of_experience'] ?: 0);
            $license_number = mysqli_real_escape_string($conn, $_POST['license_number'] ?: '');
            $created_at = date('Y-m-d H:i:s');
            $updated_at = date('Y-m-d H:i:s');

            $sqlDoctor = "INSERT INTO doctors (user_id, specialization, years_of_experience, license_number, created_at, updated_at) 
                          VALUES ('$user_id', '$specialization', '$years_of_experience', '$license_number', '$created_at', '$updated_at')";

            if (mysqli_query($conn, $sqlDoctor)) {
                header("Location: ../doctors/display_doctors.php");
                exit;
            } else {
                echo "Error adding doctor record: " . mysqli_error($conn);
            }
        } else {
            // Admin or other user types
            header("Location: ../admin/dashboard.php");
            exit;
        }
    } else {
        echo "Error adding user: " . mysqli_error($conn);
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
    <title>Add User</title>
    <link rel="stylesheet" href="../public/css/add-user.css">
</head>
<body>
<div class="container">
<?php include("../includes/admin-header.php");?>
    <div class="page">
    <h2 style="color: #0A76D8; margin:40px;">Add New User</h2>
    <form action="add.php" method="POST">
        <label for="name" >Name:</label>
        <input type="text" name="name" required>

        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <br>
        <label for="user_type_id">User Type:</label>
        <select name="user_type_id" required>
            <option value="3">Admin</option>
            <option value="2">Patient</option>
            <option value="1">Doctor</option>
        </select>
        <br>

        <!-- Patient specific fields -->
        <div id="patientFields">
            <label for="dob">Date of Birth:</label>
            <input type="date" name="dob">
            <br>

            <label for="gender">Gender:</label>
            <select name="gender">
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>

            <br>
            <label for="medical_history">Medical History:</label>
            <br>
            <textarea name="medical_history"></textarea>

            <br>
            <label for="allergies">Allergies:</label>
            <br>
            <textarea name="allergies"></textarea>

            <br>
            <label for="emergency_contact">Emergency Contact:</label>
            <br>
            <input type="text" name="emergency_contact">
        </div>

        <!-- Doctor specific fields -->
        <div id="doctorFields">
            <label for="specialization">Specialization:</label>
            <input type="text" name="specialization">

            <br>
            <label for="years_of_experience">Years of Experience:</label>
            <input type="number" name="years_of_experience" min="0">

            <br>
            <label for="license_number">License Number:</label>
            <input type="text" name="license_number">
            <br>
        </div>

        <input type="submit" value="Add User">
    </form>
</div>
</div>
<script>
    // Show or hide patient-specific fields based on user type selection
    document.querySelector('[name="user_type_id"]').addEventListener('change', function () {
        var patientFields = document.getElementById('patientFields');
        var doctorFields = document.getElementById('doctorFields');
        if (this.value == '2') {
            patientFields.style.display = 'block';
            doctorFields.style.display = 'none';
        } else if (this.value == '1') {
            doctorFields.style.display = 'block';
            patientFields.style.display = 'none';
        } else {
            patientFields.style.display = 'none';
            doctorFields.style.display = 'none';
        }
    });

    // Initially hide patient and doctor fields
    document.getElementById('patientFields').style.display = 'none';
    document.getElementById('doctorFields').style.display = 'none';
</script>
</body>
</html>
<?php
include("../includes/dbh.inc.php");
include("../includes/session_check.php");
    checkUserSession([2]);
$user_id = $_SESSION['user_id'];

// Initialize messages
$success_message = '';
$error_message = '';
$formSubmitted = false;
// Default values to prevent warnings
$emergency_contact = $emergency_contact ?? '';
$dob = $dob ?? '';
$gender = $gender ?? '';
$allergies = $allergies ?? '';
$medical_history = $medical_history ?? '';

// Separate logic for handling profile update and password change
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formSubmitted = true; // Form has been submitted

    if (isset($_POST['current-password'])) {
        // Password change form was submitted
        $currentPassword = $_POST['current-password'];
        $newPassword = $_POST['new-password'];
        $repeatNewPassword = $_POST['repeat-new-password'];
    
        // Check if the new password meets the length requirement
        if (strlen($newPassword) < 8) {
            $error_message = "New password must be at least 8 characters long.";
        } elseif ($newPassword === $repeatNewPassword) {
            // Logic to change the password
            $checkPasswordSql = "SELECT password FROM users WHERE user_id = ?";
            $stmtPassword = $conn->prepare($checkPasswordSql);
            $stmtPassword->bind_param("i", $user_id);
            $stmtPassword->execute();
            $stmtPassword->bind_result($hashedPassword);
            $stmtPassword->fetch();
            $stmtPassword->close();

    
            if (password_verify($currentPassword, $hashedPassword)) {
                // Update password
                $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePasswordSql = "UPDATE users SET password = ? WHERE user_id = ?";
                $stmtUpdatePassword = $conn->prepare($updatePasswordSql);
                $stmtUpdatePassword->bind_param("si", $newHashedPassword, $user_id);
                if ($stmtUpdatePassword->execute()) {
                    $success_message = "Password updated successfully.";
                } else {
                    $error_message = "Error updating password: " . $stmtUpdatePassword->error;
                }
                $stmtUpdatePassword->close();
            } else {
                $error_message = "Current password is incorrect.";
            }
        } else {
            $error_message = "New password and repeat password do not match.";
        }
    }
     else {
        // General profile update form was submitted
        $emergency_contact = $_POST['emergency_contact'];
        $dob = $_POST['DOB'];
        $gender = $_POST['Gender'];
        $allergies = $_POST['allergies'];
        $medical_history = $_POST['medical_history'];

        // Check if the record exists, then update or insert
        $checkSql = "SELECT patient_id FROM patients WHERE user_id = ?";
        $stmtCheck = $conn->prepare($checkSql);
        $stmtCheck->bind_param("i", $user_id);
        $stmtCheck->execute();
        $stmtCheck->store_result();

        if ($stmtCheck->num_rows > 0) {
            // Record exists, update it
            $updateSql = "UPDATE patients SET emergency_contact = ?, dob = ?, gender = ?, allergies = ?, medical_history = ?, updated_at = NOW() WHERE user_id = ?";
            $stmtUpdate = $conn->prepare($updateSql);
            $stmtUpdate->bind_param("sssssi", $emergency_contact, $dob, $gender, $allergies, $medical_history, $user_id);
            if ($stmtUpdate->execute()) {
                $success_message = "Patient information updated successfully.";
            } else {
                $error_message = "Error updating patient information: " . $stmtUpdate->error;
            }
            $stmtUpdate->close();
        } else {
            // Record does not exist, insert it
            $insertSql = "INSERT INTO patients (user_id, dob, gender, emergency_contact, allergies, medical_history, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())";
            $stmtInsert = $conn->prepare($insertSql);
            $stmtInsert->bind_param("isssss", $user_id, $dob, $gender, $emergency_contact, $allergies, $medical_history);
            if ($stmtInsert->execute()) {
                $success_message = "Patient information added successfully.";
            } else {
                $error_message = "Error adding patient information: " . $stmtInsert->error;
            }
            $stmtInsert->close();
        }

        $stmtCheck->close();
    }
}

// Fetch patient information for form repopulation
$fetchSql = "SELECT emergency_contact, dob, gender, allergies, medical_history FROM patients WHERE user_id = ?";
$stmtFetch = $conn->prepare($fetchSql);
$stmtFetch->bind_param("i", $user_id);
$stmtFetch->execute();
$stmtFetch->bind_result($emergency_contact, $dob, $gender, $allergies, $medical_history);
$stmtFetch->fetch();
$stmtFetch->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="../public/css/Profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .alert {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 10px;
        }
        .alert-success {
            background-color: #dff0d8;
            color: #3c763d;
            border-color: #d6e9c6;
        }
        .alert-danger {
            background-color: #f2dede;
            color: #a94442;
            border-color: #ebccd1;
        }
    </style>

</head>
<body>
<?php include("../includes/user-header.php"); ?>

<div class="containerProfile">
    <h1>Patient Profile</h1>
    <?php if ($success_message): ?>
        <div class="alert alert-success"><?= $success_message ?></div>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <div class="alert alert-danger"><?= $error_message ?></div>
    <?php endif; ?>

    <div class="tab-buttons">
        <button class="tab-button active" onclick="showTab('change-password')"><i class="fas fa-lock"></i> Change Password</button>
        <button class="tab-button" onclick="showTab('patient-info')"><i class="fas fa-user-md"></i> Patient Information</button>
    </div>

    <div id="change-password" class="tab-content active">
        <form id="change-password-form" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Change Password</h2>
            <div class="form-group">
                <label for="current-password">Current Password:</label>
                <input type="password" id="current-password" name="current-password" required>
            </div>
            <div class="form-group">
                <label for="new-password">New Password:</label>
                <input type="password" id="new-password" name="new-password" required>
            </div>
            <div class="form-group">
                <label for="repeat-new-password">Repeat New Password:</label>
                <input type="password" id="repeat-new-password" name="repeat-new-password" required>
                <span id="repeat-new-password-error" class="error-message"></span>
            </div>
            <button type="submit"><i class="fas fa-arrow-right"></i> Change Password</button>
        </form>
    </div>

    <div id="patient-info" class="tab-content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <h2>Patient Information</h2>
            <div class="form-group">
                <label for="emergency_contact">Emergency Contact:</label>
                <input type="text" id="emergency_contact" placeholder="N/A for nothing" name="emergency_contact" value="<?= htmlspecialchars($emergency_contact) ?>" required>
            </div>
            <div class="form-group">
                <label for="DOB">Date of Birth:</label>
                <input type="date" id="DOB" name="DOB" value="<?= htmlspecialchars($dob) ?>" required>
            </div>
            <div class="form-group">
                <label for="Gender">Gender:</label>
                <select id="Gender" name="Gender" required>
                    <option value="">Select</option>
                    <option value="Male" <?= $gender === 'Male' ? 'selected' : '' ?>>Male</option>
                    <option value="Female" <?= $gender === 'Female' ? 'selected' : '' ?>>Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="allergies">Allergies:</label>
                <textarea id="allergies" name="allergies" rows="3" placeholder="List any allergies here..."><?= htmlspecialchars($allergies) ?></textarea>
            </div>
            <div class="form-group">
                <label for="medical_history">Medical History:</label>
                <textarea id="medical_history" name="medical_history" rows="3" placeholder="List any medical history here..."><?= htmlspecialchars($medical_history) ?></textarea>
            </div>

            <!-- Height and Weight Input with Horizontal Flexbox -->
            <div class="bmi-calculation">
                <div class="form-group">
                    <label for="height">Height (cm):</label>
                    <input type="number" id="height" name="height" placeholder="Enter height" value="<?= htmlspecialchars($height ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label for="weight">Weight (kg):</label>
                    <input type="number" id="weight" name="weight" placeholder="Enter weight" value="<?= htmlspecialchars($weight ?? '') ?>" required>
                </div>
                <button type="button" onclick="calculateBMI()">Calculate BMI</button>
            </div>

            <!-- BMI Result Display -->
            <div class="bmi-result">
                <p id="bmi-result"></p>
                <p id="bmi-category"></p>
            </div>

            <button type="submit"><i class="fas fa-save"></i> Update Profile</button>
        </form>
    </div>
</div>

<style>
    .bmi-calculation {
        display: flex;
        align-items: center; /* Center items vertically */
        gap: 1rem; /* Space between inputs */
        margin-bottom: 1rem; /* Space below the BMI calculation section */
    }

    .bmi-result {
        margin-top: 1rem; /* Space above the result display */
    }
</style>

<script>
function calculateBMI() {
    const height = parseFloat(document.getElementById('height').value);
    const weight = parseFloat(document.getElementById('weight').value);
    if (height > 0 && weight > 0) {
        const bmi = weight / ((height / 100) ** 2); // BMI formula

        // Display BMI result and category
        const bmiResultElement = document.getElementById('bmi-result');
        bmiResultElement.innerText = `Your BMI is: ${bmi.toFixed(2)}`; // Display BMI
        let category;
        if (bmi < 18.5) {
            category = 'Category: Underweight';
        } else if (bmi >= 18.5 && bmi < 24.9) {
            category = 'Category: Normal weight';
        } else if (bmi >= 25 && bmi < 29.9) {
            category = 'Category: Overweight';
        } else {
            category = 'Category: Obese';
        }
        document.getElementById('bmi-category').innerText = category; // Display BMI category
    } else {
        alert("Please enter valid height and weight values.");
    }
}

    function exitPage() {
        window.location.href = 'index.php'; 
    }
        function showTab(tabName) {
            const tabs = document.querySelectorAll('.tab-content');
            const buttons = document.querySelectorAll('.tab-button');

            // Hide all tabs
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Remove active class from all buttons
            buttons.forEach(button => {
                button.classList.remove('active');
            });

            // Show the selected tab
            document.getElementById(tabName).classList.add('active');

            // Add active class to the selected button
            event.target.classList.add('active');
        }

    </script>
</body>
</html>
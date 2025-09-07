<?php 
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

// Ensure the user is logged in and is a doctor (session check)
checkUserSession([1]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Information</title>
    <link rel="stylesheet" href="../public/css/doctorPage.css">
    <script>
        function filterPatients() {
            var input = document.getElementById('searchInput');
            var filter = input.value.toLowerCase();
            var patientCards = document.getElementsByClassName('patient-card');
            for (var i = 0; i < patientCards.length; i++) {
                var card = patientCards[i];
                var patientName = card.getElementsByClassName('patient-name')[0].textContent.toLowerCase();
                if (patientName.indexOf(filter) > -1) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            }
        }
    </script>
</head>
<body>
    <div class="containerdiv">
        <h3>List of Patients</h3>
        <input type="text" id="searchInput" placeholder="Search for patients..." onkeyup="filterPatients()" class="search-input">

        <?php
        // Get the doctor_id from the logged-in user
        $sql = "SELECT doctor_id FROM doctors WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $_SESSION['user_id']); // Assuming user_id is stored in session
        $stmt->execute();
        $stmt->bind_result($doctor_id);
        
        // Fetch the result
        if ($stmt->fetch()) {
            // doctor_id found
            // echo "Doctor ID: " . $doctor_id; // You can remove or display as needed
        } else {
            echo "No doctor found for the given user ID.";
        }
        $stmt->close();
        
        include("../includes/doctor-header.php");

        // Query to fetch patient information
        $sql = "SELECT users.name, patients.patient_id, patients.gender, patients.allergies, patients.medical_history 
                FROM patients
                JOIN users ON patients.user_id = users.user_id";
        $query = $conn->prepare($sql);
        $query->execute();
        $user_result = $query->get_result();

         // Handle form submission for prescriptions and medical records
         if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_prescription'])) {
            $patient_id = $_POST['patient_id'];
            $medication = $_POST['medication'];
            $dosage = $_POST['dosage'];
            $instructions = $_POST['instructions'];
            $diagnosis = $_POST['diagnosis'];
            $treatment_plan = $_POST['treatment_plan'];
            $lab_results = $_POST['lab_results'];
            $prescription_date = date("Y-m-d");

            // Insert the prescription into the database
            $sql_insert_prescription = "INSERT INTO prescriptions (patient_id, doctor_id, prescription_date, medication, dosage, instructions)
            VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert_prescription);
            if ($stmt === false) {
                die('Error preparing the prescription statement: ' . htmlspecialchars($conn->error));
            }
            $stmt->bind_param("iissss", $patient_id, $doctor_id, $prescription_date, $medication, $dosage, $instructions);

            // Insert the medical record into the database
            $sql_insert_medical_record = "INSERT INTO medical_records (patient_id, doctor_id, diagnosis, treatment_plan, lab_results)
            VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql_insert_medical_record);
            if ($stmt2 === false) {
                die('Error preparing the medical record statement: ' . htmlspecialchars($conn->error));
            }
            $stmt2->bind_param("iisss", $patient_id, $doctor_id, $diagnosis, $treatment_plan, $lab_results);

            if ($stmt->execute() && $stmt2->execute()) {
                $success_message = "Prescription and medical record saved successfully.";
            } else {
                $error_message = "Error saving prescription or medical record: " . $stmt->error . " / " . $stmt2->error;
            }
            $stmt->close(); // Close the statement
            $stmt2->close(); // Close the second statement
        }
        ?>

        <div class="patient-list">
            <?php if ($user_result->num_rows > 0) { ?>
                <?php while ($user_row = $user_result->fetch_assoc()) { ?>
                    <div class="patient-card">
                        <h4 class="patient-name"><?php echo $user_row['name']; ?></h4>
                        <p><strong>Gender:</strong> <?php echo $user_row['gender']; ?></p>
                        <p><strong>Allergies:</strong> <?php echo $user_row['allergies']; ?></p>
                        <p><strong>Medical History:</strong> <?php echo $user_row['medical_history']; ?></p>

                        <!-- Add a "Write Prescription" Button -->
                        <form action="DoctorPrescription.php" method="get">
                            <input type="hidden" name="patient_id" value="<?php echo $user_row['patient_id']; ?>">
                            <button type="submit" name="write_prescription" class="prescribe-button">Write Prescription</button>
                        </form>

                        <!-- Show prescription form when "Write Prescription" is clicked -->
                        <?php 
                        if (isset($_GET['write_prescription']) && $_GET['patient_id'] == $user_row['patient_id']) { ?>
                            <form action="DoctorPrescription.php" method="post" class="prescription-form">

                            
                                <h3>Write Prescription for <?php echo $user_row['name']; ?></h3>

                                <div class="form-group">
                                    <label for="medication">Medication:</label>
                                    <input type="text" name="medication" required>
                                </div>

                                <div class="form-group">
                                    <label for="dosage">Dosage:</label>
                                    <input type="text" name="dosage" required>
                                </div>
                                <div class="form-group">
                                    <label for="instructions">Instructions:</label>
                                    <textarea name="instructions" rows="4" required></textarea>
                                </div>
                                <div class="form-group">
                                            <label for="diagnosis">Diagnosis:</label>
                                            <textarea name="diagnosis" rows="2" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="treatment_plan">Treatment Plan:</label>
                                            <textarea name="treatment_plan" rows="2" required></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="lab_results">Lab Results:</label>
                                            <textarea name="lab_results" rows="2" required></textarea>
                                        </div>
                                
                                <input type="hidden" name="patient_id" value="<?php echo $user_row['patient_id']; ?>">
                                <button type="submit" name="submit_prescription">Save Prescription</button>
                            </form>
                        <?php
                     } ?>

                        <!-- Success/Error Messages -->
                        <?php if (isset($success_message)) { ?>
                            <div class="success-message"><?php echo $success_message; ?></div>
                        <?php } elseif (isset($error_message)) { ?>
                            <div class="error-message"><?php echo $error_message; ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p>No patients found in the database.</p>
            <?php } ?>
        </div>
    </div>
</body>
</html>

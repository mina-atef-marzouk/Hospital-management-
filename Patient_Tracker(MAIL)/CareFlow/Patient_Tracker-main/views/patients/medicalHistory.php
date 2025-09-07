<?php

include("../includes/dbh.inc.php");
include("../includes/session_check.php");

// Check if user is logged in and if they are a patient (user_type_id = 2)
checkUserSession([2]);

// Get the logged-in user's ID
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Check if patient_id is set in the session
    if (isset($_SESSION['patient_id'])) {
        $patient_id = $_SESSION['patient_id'];
    } else {
        // If patient_id is not set in session, fetch it from the database
        $query = "SELECT patient_id FROM patients WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // If patient_id is found in the database, store it in the session
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $patient_id = $row['patient_id'];
            $_SESSION['patient_id'] = $patient_id;  // Store patient_id in session for future use
        }
    }
} else {
    echo "You are not signed in.";
    exit;
}

// Prepare the SQL query to fetch medical records for the specific patient
$query = "SELECT * FROM medical_records WHERE patient_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "SQL preparation error: " . $conn->error;
    exit;
}

$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Medical History</title>
    <link rel="stylesheet" type="text/css" href="../public/css/MedicalHistory.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
<?php include("../includes/user-header.php"); ?>

    <main class="table" id="customers_table">
        <section class="table__header">
            <h1>Medical History</h1>
            <div class="input-group">
                <input type="search" placeholder="Search Data...">
            </div>
            <div class="export__file">
                <label for="export-file" class="export__file-btn" title="Export File">
                    <i class="fas fa-download"></i>
                </label>
                <input type="checkbox" id="export-file">
                <div class="export__file-options">
                    <label>
                        <i class="fas fa-download"></i> Export As &nbsp; &#10140;
                    </label>
                    <label for="toPDF" id="toPDF">
                        PDF <i class="fa-solid fa-file-pdf"></i>
                    </label>
                </div>
            </div>
        </section>

        <section class="table__body">
            <table>
                <thead>
                    <tr>
                        <th>Count</th>
                        <th>Diagnosis</th>
                        <th>Treatment Plan</th>
                        <th>Lab Results</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Check if the query returned any results
                    if ($result && $result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $count++ . "</td>";
                            echo "<td>" . htmlspecialchars($row['diagnosis']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['treatment_plan']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['lab_results']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No records found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
    <script src="../public/js/medicalHistory.js"></script>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>
<?php
include("../includes/Dbh.inc.php");
include("../includes/session_check.php");

// Ensure the user is logged in and is a doctor (session check)
checkUserSession([2]); // Assuming 1 is the role for doctors


// Retrieve user_id from the session
if (!isset($_SESSION['user_id'])) {
    die("No prescription for you. Please log in.");
}

$userId = $_SESSION['user_id']; // Get user ID from the session

// Query to fetch the patient_id based on user_id
$sql = "SELECT patient_id FROM patients WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $patient_id = $row['patient_id'];
} else {
    echo "<p class='error'>Error:FILL YOUR PROFILE INFORMATION .</p>";
    echo "<a href='../patients/Profile.php'>profile information page/a>";}

// Query to fetch medication, dosage, and instructions for the patient
$sql = "SELECT medication, dosage, instructions 
    FROM prescriptions 
    WHERE patient_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Patient Prescriptions</title>
    <link rel="stylesheet" href="../public/css/user.css">
    <link rel="stylesheet" href="../public/css/NavFooter.css">

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #eef2f7; /* Light background for a clean look */
            margin: 0;
            padding: 0;
        }

        .containerNOUR {
            margin-top: 200px;
            margin-left: 200px;
            width: 80%;
            max-width: 1000px;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
        }

        h2 {
            text-align: center;
            color: #003366; /* Dark blue for headings */
            font-size: 28px;
        }

        p {
            text-align: center;
            color: #555;
            font-size: 16px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #003366; /* Dark blue for table header */
            color: white;
            text-transform: uppercase;
        }

        td {
            color: #333;
        }

        /* Hover Effects */
        tr:hover td {
            background-color: #f0f8ff; /* Light blue hover effect */
        }
    </style>
</head>
<body>
<?php include("../includes/user-header.php");?>

    <div class="containerNOUR">
        <h2>Patient Prescriptions</h2>
        <p>Below are your prescriptions with details about the medications, dosage, and instructions.</p>

        <?php
        // Check if there are any prescriptions
        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<thead>
                    <tr>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Instructions</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            // Fetch and display each prescription
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['medication']) . "</td>";
                echo "<td>" . htmlspecialchars($row['dosage']) . "</td>";
                echo "<td>" . htmlspecialchars($row['instructions']) . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>No prescriptions found.</p>";
        }

        // Close the connection
        $stmt->close();
        $conn->close();
        ?>
    </div>
    <script src="../public/js/NavFooter.js"></script>

</body>
</html>

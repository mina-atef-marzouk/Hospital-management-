<?php
include("../includes/dbh.inc.php");
include("../includes/admin-header.php");

// Query to get all patients
$sql = "SELECT patients.*, users.name AS user_name, users.email FROM patients
        JOIN users ON patients.user_id = users.user_id"; // Change 'users.id' to 'users.user_id' if needed
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Output data for each patient
    echo "<table>";
    echo "<tr><th>Name</th><th>Email</th><th>Date of Birth</th><th>Gender</th><th>Medical History</th><th>Allergies</th><th>Emergency Contact</th></tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
        echo "<td>" . htmlspecialchars($row['gender']) . "</td>";
        echo "<td>" . htmlspecialchars($row['medical_history']) . "</td>";
        echo "<td>" . htmlspecialchars($row['allergies']) . "</td>";
        echo "<td>" . htmlspecialchars($row['emergency_contact']) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "No patients found.";
}
$conn->close();
echo "Database populated successfully.";
?>
<head>
    <style>

        body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    padding: 20px;
}

.container {
    margin: 0 auto;
    width: 80%;
    background-color: #fff;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #4a56e2;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

table th, table td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: center;
}

table th {
    background-color: #4a56e2;
    color: white;
}

table tr:nth-child(even) {
    background-color: #f2f2f2;
}

table tr:hover {
    background-color: #ddd;
}
    </style>
</head>
<?php
include("../includes/dbh.inc.php");
// include("../includes/session_check.php");
//     checkUserSession([3]);

// Fetch patients from the database, joining with the users table for more details
$sql = "SELECT p.*, u.name, u.email FROM patients p
        JOIN users u ON p.user_id = u.user_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient List</title>
    <!-- <link rel="stylesheet" href="../public/css/users_manag.css"> -->
    <!-- <link rel="stylesheet" href="../public/css/layout1.css"> -->
    <link rel="stylesheet" href="../public/css/ex-styles.css">
    <link rel="stylesheet" href="../public/css/animations.css"> 


</head>
<body>
<div class="container">
    <?php include("../includes/admin-header.php");?>
<div style="margin: 20px;">
        <h1>Patient List</h1>
        <button onclick="window.location.href='../admin/add.php'" class="btn-primary-soft btn button-icon btn-add">Add Patient</button>
        <br>
        <br>
        <table class="sub-table">
            <thead>
                <tr>
                    <th class="table-headin">Patient ID</th>
                    <th class="table-headin">Name</th>
                    <th class="table-headin">Email</th>
                    <th class="table-headin">Date of Birth</th>
                    <th class="table-headin">Gender</th>
                    <th class="table-headin">Medical History</th>
                    <th class="table-headin">Allergies</th>
                    <th class="table-headin">Emergency Contact</th>
                    <th class="table-headin">Actions</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the table
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['patient_id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['dob']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['medical_history']}</td>
                                <td>{$row['allergies']}</td>
                                <td>{$row['emergency_contact']}</td>
                                <td>
                                    <form action='../admin/Delete.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'> <!-- Use user_id for deletion -->
                                        <input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this patient?\")'  class='btn-primary-soft btn button-icon btn-delete'>
                                    </form>
                                    
                                    <form action='../admin/Edit.php' method='GET' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'> <!-- For editing -->
                                        <input type='submit' value='Edit' class='btn-primary-soft btn button-icon btn-edit'>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='12'>No patients found</td></tr>"; // Adjusted colspan to match table headers
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>

    <?php mysqli_close($conn); // Close the connection ?>
</body>
</html>

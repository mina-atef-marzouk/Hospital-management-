<?php
include("../includes/dbh.inc.php");
// include("../includes/session_check.php");
//     checkUserSession([3]);

// Fetch doctors from the database
$sql = "SELECT d.user_id, d.specialization, d.years_of_experience, d.license_number, u.name, u.email 
        FROM doctors d
        JOIN users u ON d.user_id = u.user_id";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors List</title>
    <!-- <link rel="stylesheet" href="../public/css/users_manag.css"> -->
    <!-- <link rel="stylesheet" href="../public/css/layout1.css"> -->
    <link rel="stylesheet" href="../public/css/ex-styles.css">
    <link rel="stylesheet" href="../public/css/animations.css"> 


</head>
<body>
    <div class="container">
        <?php include("../includes/admin-header.php");?>
<div>
        <h1>Doctors List</h1>
        <button onclick="window.location.href='../admin/add.php'" class="btn-primary-soft btn button-icon btn-add">Add user</button>
        <br>
        <br>
        <table class="sub-table" >
            <thead>
                <tr>
                    <th class="table-headin">User ID</th>
                    <th class="table-headin">Name</th>
                    <th class="table-headin">Email</th>
                    <th class="table-headin">Specialization</th>
                    <th class="table-headin">Years of Experience</th>
                    <th class="table-headin">License Number</th>
                    <th class="table-headin">Actions</th> 
                </tr>
            </thead>
            <tbody>
                <?php
                if (mysqli_num_rows($result) > 0) {
                    // Output data for each doctor
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>
                                <td>{$row['user_id']}</td> <!-- Corrected to user_id -->
                                <td>{$row['name']}</td>
                                <td>{$row['email']}</td>
                                <td>{$row['specialization']}</td>
                                <td>{$row['years_of_experience']}</td>
                                <td>{$row['license_number']}</td>
                                <td>
                                    <form action='../admin/Delete.php' method='POST' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'> 
                                        <input type='submit' value='Delete' onclick='return confirm(\"Are you sure you want to delete this doctor?\")' class='btn-primary-soft btn button-icon btn-delete'>
                                    </form>
                                    <form action='../admin/Edit.php' method='GET' style='display:inline;'>
                                        <input type='hidden' name='user_id' value='{$row['user_id']}'>
                                        <input type='submit' value='Edit' class='btn-primary-soft btn button-icon btn-edit'>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No doctors found</td></tr>"; // Adjusted colspan to 7
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
    <?php
    // Close connection
    mysqli_close($conn);
    ?>
</body>
</html>

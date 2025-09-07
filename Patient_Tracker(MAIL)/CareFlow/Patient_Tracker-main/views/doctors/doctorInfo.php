<?php
include("../includes/dbh.inc.php");
include("../includes/session_check.php");

checkUserSession([1]);

// Get the doctor_id from the logged-in user
$sql = "SELECT doctor_id FROM doctors WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']); // Assuming user_id is stored in session
$stmt->execute();
$stmt->bind_result($doctor_id);

// Fetch the result
if (!$stmt->fetch()) {
    echo "<p style='color:red;'>No doctor found for the given user ID.</p>";
    $stmt->close();
    exit;
}
$stmt->close();


// Fetch doctor details
$query = "SELECT d.Name, d.Specialty, 
                 da1.AttributeValue AS fb_link,
                 da2.AttributeValue AS insta_link,
                 da3.AttributeValue AS vesta_link,
                 da4.AttributeValue AS photo
          FROM doctordivhome d
          LEFT JOIN dynamicattributevalues da1 ON d.DoctorID = da1.DoctorID AND da1.AttributeID = 3
          LEFT JOIN dynamicattributevalues da2 ON d.DoctorID = da2.DoctorID AND da2.AttributeID = 2
          LEFT JOIN dynamicattributevalues da3 ON d.DoctorID = da3.DoctorID AND da3.AttributeID = 4
          LEFT JOIN dynamicattributevalues da4 ON d.DoctorID = da4.DoctorID AND da4.AttributeID = 1
          WHERE d.DoctorID = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "<p style='color:red;'>Failed to prepare statement: " . $conn->error . "</p>";
    exit;
}

$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<p style='color:red;'>Doctor not found for the provided ID.</p>";
    $stmt->close();
    exit;
}

$data = $result->fetch_assoc();
$name = $data['Name'];
$specialization = $data['Specialty'];
$fb_link = $data['fb_link'] ?? '';
$insta_link = $data['insta_link'] ?? '';
$vesta_link = $data['vesta_link'] ?? '';
$photo = $data['photo'] ?? '';  // If photo exists
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Doctor</title>
    <link rel="stylesheet" href="../public/css/ex-styles.css">
</head>
<style>
        /* General Reset */
        body, h1, h2, p, table, form {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Container Styling */
        .containerINN {
            width: 80%;
            margin-top: 150px;
            margin-left: 100px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Heading Styles */
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }

        h2 {
            font-size: 20px;
            color: #333;
            margin-bottom: 15px;
        }

        /* Form Styling */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-size: 16px;
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="url"],
        input[type="file"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="file"] {
            padding: 5px;
        }

        button.btn-primary-soft {
            padding: 12px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            margin-top: 20px;
        }

        button.btn-primary-soft:hover {
            background-color: #45a049;
        }

        /* Table Styling */
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px;
            text-align: left;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        /* Image Styling */
        img {
            max-width: 100px;
            border-radius: 5px;
        }

        /* Error Message */
        p.error {
            color: red;
            font-size: 16px;
            margin-top: 20px;
            text-align: center;
        }
    </style>
</style>
<body>
<?php include("../includes/doctor-header.php");?>

    <div class="containerINN">
        <h1>Edit Doctor Information</h1>
        <form action="UpdateDoctor.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden field to store doctor_id -->
            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_id); ?>">

            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="specialization">Specialization</label>
            <input type="text" id="specialization" name="specialization" value="<?php echo htmlspecialchars($specialization); ?>" required>

            <label for="fb_link">Facebook Link</label>
            <input type="url" id="fb_link" name="fb_link" value="<?php echo htmlspecialchars($fb_link); ?>">

            <label for="insta_link">Instagram Link</label>
            <input type="url" id="insta_link" name="insta_link" value="<?php echo htmlspecialchars($insta_link); ?>">

            <label for="vesta_link">Vesta Link</label>
            <input type="url" id="vesta_link" name="vesta_link" value="<?php echo htmlspecialchars($vesta_link); ?>">

            <label for="photo">Upload Photo</label>
            <input type="file" id="photo" name="photo" accept="image/*">

        <!--    <?php if ($photo) { ?>
                <p>Current Photo: <img src="<?php echo htmlspecialchars($photo); ?>" alt="Doctor Photo" width="100"></p>
            <?php } ?>-->

            <button type="submit" class="btn-primary-soft btn">Update</button>
        </form>

        <h2>Doctor Details</h2>
        <table border="1" cellpadding="10" cellspacing="0">
            <thead>
                <tr>
                    <th>Doctor ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($doctor_id); ?></td>
                    <td><?php echo htmlspecialchars($name); ?></td>
                    <td><?php echo htmlspecialchars($specialization); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php mysqli_close($conn); ?>
</body>
</html>

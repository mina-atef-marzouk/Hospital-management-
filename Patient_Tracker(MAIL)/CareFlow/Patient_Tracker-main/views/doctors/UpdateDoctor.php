<?php
include("../includes/dbh.inc.php");
include("../includes/session_check.php");

checkUserSession([1]);

// Check if the form is submitted and validate the required fields
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctor_id = $_POST['doctor_id']; // Assuming you're passing DoctorID in the form
    $fb_link = $_POST['fb_link'];
    $insta_link = $_POST['insta_link'];
    $vesta_link = $_POST['vesta_link'];

    // Initialize photo URL as null in case there's no upload
    $photo_url = null;

    // Check if there's a file uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        // Define the target directory (use absolute path)
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/Patient_Tracker(MAIL)/CareFlow/Patient_Tracker-main/views/doctors/uploads/';  // Absolute path to uploads folder
        $file_name = preg_replace("/[^a-zA-Z0-9\-_\.]/", "_", basename($_FILES['photo']['name']));
        $target_file = $target_dir . $file_name;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if the file is an actual image
        if (getimagesize($_FILES['photo']['tmp_name']) === false) {
            echo "<p style='color:red;'>File is not an image.</p>";
            exit;
        }

        // Check file size (limit to 2MB)
        if ($_FILES['photo']['size'] > 2000000) {
            echo "<p style='color:red;'>Sorry, your file is too large.</p>";
            exit;
        }

        // Allow certain file formats (e.g., JPG, PNG, JPEG)
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
            echo "<p style='color:red;'>Sorry, only JPG, JPEG, & PNG files are allowed.</p>";
            exit;
        }

        // Attempt to upload the file
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            $photo_url = '/Patient_Tracker(MAIL)/CareFlow/Patient_Tracker-main/views/doctors/uploads/' . $file_name;  // Save the relative path to the photo
        } else {
            echo "<p style='color:red;'>Sorry, there was an error uploading your file.</p>";
            exit;
        }
    }

    // Prepare the attributes to be updated (assuming AttributeID 1 is for the photo)
    $attributes = [
        1 => $photo_url,  // Photo (AttributeID = 1)
        2 => $fb_link,    // Facebook Link (AttributeID = 2)
        3 => $insta_link, // Instagram Link (AttributeID = 3)
        4 => $vesta_link  // Vesta Link (AttributeID = 4)
    ];

    // Update the attributes in the dynamicattributevalues table for the specific doctor
    foreach ($attributes as $attribute_id => $attribute_value) {
        if ($attribute_value !== null) {
            $query = "INSERT INTO dynamicattributevalues (DoctorID, AttributeID, AttributeValue) 
                      VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE AttributeValue = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param('iiss', $doctor_id, $attribute_id, $attribute_value, $attribute_value);

            if ($stmt->execute()) {
                echo "Attribute ID $attribute_id updated successfully.";
            } else {
                echo "Error updating attribute ID $attribute_id.";
            }
        }
    }

    // Redirect or return success
    header("Location: update_doctor_success.php"); // Redirect to a success page
    exit;
} else {
    echo "<p style='color:red;'>No form data submitted.</p>";
}
?>

<?php
include("../includes/dbh.inc.php");
include("../includes/session_check.php");
    checkUserSession([3]);

// Predefined user data for 12 users
$users = [
    [
        'name' => 'Ahmed El-Sayed', 
        'email' => 'ahmed.sayed@example.com', 
        'password' => password_hash('ahmed123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567890', 
        'address' => 'Cairo, Egypt'
    ],
    [
        'name' => 'Fatma Mohamed', 
        'email' => 'fatma.mohamed@example.com', 
        'password' => password_hash('fatma123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567891', 
        'address' => 'Giza, Egypt'
    ],
    [
        'name' => 'Mohamed Ali', 
        'email' => 'mohamed.ali@example.com', 
        'password' => password_hash('mohamed123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567892', 
        'address' => 'Alexandria, Egypt'
    ],
    [
        'name' => 'Sara Hassan', 
        'email' => 'sara.hassan@example.com', 
        'password' => password_hash('sara123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567893', 
        'address' => 'Luxor, Egypt'
    ],
    [
        'name' => 'Omar Abdel', 
        'email' => 'omar.abdel@example.com', 
        'password' => password_hash('omar123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567894', 
        'address' => 'Aswan, Egypt'
    ],
    [
        'name' => 'Amina Nour', 
        'email' => 'amina.nour@example.com', 
        'password' => password_hash('amina123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567895', 
        'address' => 'Mansoura, Egypt'
    ],
    [
        'name' => 'Ibrahim Khaled', 
        'email' => 'ibrahim.khaled@example.com', 
        'password' => password_hash('ibrahim123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567896', 
        'address' => 'Tanta, Egypt'
    ],
    [
        'name' => 'Yasmin Fathy', 
        'email' => 'yasmin.fathy@example.com', 
        'password' => password_hash('yasmin123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567897', 
        'address' => 'Port Said, Egypt'
    ],
    [
        'name' => 'Hossam Zaki', 
        'email' => 'hossam.zaki@example.com', 
        'password' => password_hash('hossam123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567898', 
        'address' => 'Suez, Egypt'
    ],
    [
        'name' => 'Nadia Kamal', 
        'email' => 'nadia.kamal@example.com', 
        'password' => password_hash('nadia123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567899', 
        'address' => 'Ismailia, Egypt'
    ],
    [
        'name' => 'Tamer Salama', 
        'email' => 'tamer.salama@example.com', 
        'password' => password_hash('tamer123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567900', 
        'address' => 'Zagazig, Egypt'
    ],
    [
        'name' => 'Reem Magdy', 
        'email' => 'reem.magdy@example.com', 
        'password' => password_hash('reem123', PASSWORD_DEFAULT), 
        'user_type_id' => 2, 
        'phone_number' => '01234567901', 
        'address' => 'Mansoura, Egypt'
    ],
    // Doctors
    [
        'name' => 'Dr. Leila Hossam',
        'email' => 'leila.hossam@example.com',
        'password' => password_hash('leila123', PASSWORD_DEFAULT),
        'user_type_id' => 1, // user_type_id = 1 for doctors
        'phone_number' => '01234567892',
        'address' => 'Cairo, Egypt'
    ],
    [
        'name' => 'Dr. Khaled Omar',
        'email' => 'khaled.omar@example.com',
        'password' => password_hash('khaled123', PASSWORD_DEFAULT),
        'user_type_id' => 1, // user_type_id = 1 for doctors
        'phone_number' => '01234567893',
        'address' => 'Alexandria, Egypt'
    ]
];

// fill users and patients into the database
foreach ($users as $user) {
    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM users WHERE email = '{$user['email']}'";
    $result = $conn->query($checkEmailQuery);
    
    if ($result->num_rows == 0) { // If no existing entry
        $sql = "INSERT INTO users (name, email, password, user_type_id, phone_number, address) VALUES ('{$user['name']}', '{$user['email']}', '{$user['password']}', {$user['user_type_id']}, '{$user['phone_number']}', '{$user['address']}')";
        
        if ($conn->query($sql) === TRUE) {
            $user_id = $conn->insert_id; // Get the last inserted user_id

            // add  patient data
            switch ($user['name']) {
                case 'Ahmed El-Sayed':
                    $dob = '1990-01-01';
                    $gender = 'Male';
                    $medical_history = 'Diabetes';
                    $allergies = 'None';
                    break;
                case 'Fatma Mohamed':
                    $dob = '1985-05-15';
                    $gender = 'Female';
                    $medical_history = 'Hypertension';
                    $allergies = 'Peanuts';
                    break;
                case 'Mohamed Ali':
                    $dob = '1992-03-20';
                    $gender = 'Male';
                    $medical_history = 'Asthma';
                    $allergies = 'Dust';
                    break;
                case 'Sara Hassan':
                    $dob = '1988-08-25';
                    $gender = 'Female';
                    $medical_history = 'None';
                    $allergies = 'None';
                    break;
                case 'Omar Abdel':
                    $dob = '1995-12-30';
                    $gender = 'Male';
                    $medical_history = 'Heart Disease';
                    $allergies = 'Shellfish';
                    break;
                case 'Amina Nour':
                    $dob = '1993-02-14';
                    $gender = 'Female';
                    $medical_history = 'Migraine';
                    $allergies = 'None';
                    break;
                case 'Ibrahim Khaled':
                    $dob = '1991-07-10';
                    $gender = 'Male';
                    $medical_history = 'None';
                    $allergies = 'Latex';
                    break;
                case 'Yasmin Fathy':
                    $dob = '1994-09-05';
                    $gender = 'Female';
                    $medical_history = 'Allergies';
                    $allergies = 'Pollen';
                    break;
                case 'Hossam Zaki':
                    $dob = '1990-11-11';
                    $gender = 'Male';
                    $medical_history = 'Epilepsy';
                    $allergies = 'None';
                    break;
                case 'Nadia Kamal':
                    $dob = '1989-06-17';
                    $gender = 'Female';
                    $medical_history = 'None';
                    $allergies = 'Milk';
                    break;
                case 'Tamer Salama':
                    $dob = '1996-04-22';
                    $gender = 'Male';
                    $medical_history = 'Anxiety';
                    $allergies = 'None';
                    break;
                case 'Reem Magdy':
                    $dob = '1992-10-08';
                    $gender = 'Female';
                    $medical_history = 'None';
                    $allergies = 'Wheat';
                    break;
                default:
                    $dob = '1990-01-01';
                    $gender = 'Other';
                    $medical_history = 'None';
                    $allergies = 'None';
            }

            $emergency_contact = '01123456789'; //  emergency contact

            // filling the patients data into the patients table
            $sql_patient = "INSERT INTO patients (user_id, dob, gender, medical_history, allergies, emergency_contact) 
                            VALUES ($user_id, '$dob', '$gender', '$medical_history', '$allergies', '$emergency_contact')";
            
            if ($conn->query($sql_patient) !== TRUE) {
                echo "Error inserting patient: " . $conn->error;
            }
            elseif ($user['user_type_id'] == 1) { // Doctor
                $specialization = 'Dermatologist';
                $years_of_experience = rand(5, 15); // Random experience between 5 and 15 years
                $license_number = 'LIC-' . rand(1000, 9999); // Random license number
                $created_at = date('Y-m-d H:i:s');
                $updated_at = date('Y-m-d H:i:s');

                $sql_doctor = "INSERT INTO doctors (user_id, specialization, years_of_experience, license_number, created_at, updated_at) 
                               VALUES ($user_id, '$specialization', $years_of_experience, '$license_number', '$created_at', '$updated_at')";

                if ($conn->query($sql_doctor) !== TRUE) {
                    echo "Error inserting doctor: " . $conn->error;
                }
            }
        } else {
            echo "Error inserting user: " . $conn->error;
        }
    } else {
        echo "User with email {$user['email']} already exists. Skipping insertion.<br>";
    }
}
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
// Query to get all doctors for display
$sql_doctors = "SELECT doctors.*, users.name AS doctor_name, users.email FROM doctors
                JOIN users ON doctors.user_id = users.user_id";
$result_doctors = $conn->query($sql_doctors);

if ($result_doctors->num_rows > 0) {
    // Output data for each doctor
    echo "<h2>Doctor List</h2>";
    echo "<table border='1'><tr><th>Name</th><th>Email</th><th>Specialization</th><th>Experience (Years)</th><th>License Number</th></tr>";
    while ($row = $result_doctors->fetch_assoc()) {
        echo "<tr>
                <td>" . htmlspecialchars($row['doctor_name']) . "</td>
                <td>" . htmlspecialchars($row['email']) . "</td>
                <td>" . htmlspecialchars($row['specialization']) . "</td>
                <td>" . htmlspecialchars($row['years_of_experience']) . "</td>
                <td>" . htmlspecialchars($row['license_number']) . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "No doctors found.";
}

$conn->close();
echo "Database populated successfully.";
?>

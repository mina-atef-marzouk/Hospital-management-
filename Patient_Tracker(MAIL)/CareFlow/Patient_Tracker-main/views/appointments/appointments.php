<?php
include("../includes/dbh.inc.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../public/css/animations.css">  
    <link rel="stylesheet" href="../public/css/ex-styles.css">   
 

    <title>Appointments</title>


</head>
<body>
    <div class="container">
        <?php include("../includes/admin-header.php");?>
        
 <div class="dash-body">
            <table>
                <tr>
                    <td>
                    <a href="appointments.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p class="tableTitle">Appointment Manager</p>
                                           
                    </td>
                    <td>
                        <p class="todayDate">
                            Today's Date
                        </p>
                        <p class="heading-sub12" >
                            <?php 

                                date_default_timezone_set('Africa/Cairo');
                                $today = date('Y-m-d');
                                echo $today;
                                $list110 = $conn->query("select  * from  appointments;");
                            ?>
                        </p>
                    </td>
                    <td >
                        <button  class="btn-label"><img src="../public/img/calendar.svg" width="100%"></button>
                    </td>


                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="left:0;">All Appointments (<?php echo $list110->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4">
                        <table class="filter-container" border="0" >
                        <tr>
                           <td>

                           </td> 
                        <td>
                        Date:
                        </td>
                        <td >
                        <form action="appointments.php" method="post">
                            
                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" >

                        </td>
                        <td  >
                        Doctor:
                        </td>
                        <td>
                        <select name="doctor_id"  class="box filter-container-items " >
                            <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>
                            <?php 
                                $query = "
                                    SELECT 
                                        users.name AS doctor_name, 
                                        doctors.doctor_id 
                                    FROM 
                                        doctors
                                    INNER JOIN 
                                        users 
                                    ON 
                                        doctors.user_id = users.user_id
                                ";

                                $list11 = $conn->query($query);

                                if ($list11 && $list11->num_rows > 0) {
                                    while ($row = $list11->fetch_assoc()) {
                                        $sn = htmlspecialchars($row["doctor_name"]);
                                        $id00 = htmlspecialchars($row["doctor_id"]);
                                        echo "<option value='$id00'>$sn</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No doctors available</option>";
                                }
                            ?>

      </select>
                    </td>
                    <td>
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter">
                        </form>
                    </td>

                    </tr>
                            </table>
                          
                        
                    </td>
                    
                </tr>   
                                     
<?php
if ($_POST) {
    $sqlpt1 = "";
    if (!empty($_POST["sheduledate"])) {
        $sheduledate = $_POST["sheduledate"];
        $sqlpt1 = "appointments.appointment_date = '$sheduledate'";
    }

    $sqlpt2 = "";
    if (!empty($_POST["doctor_id"])) {
        $doctor_id = $_POST["doctor_id"];
        $sqlpt2 = "doctors.doctor_id = $doctor_id"; 
    }

    // Base SQL query
    $sqlmain = "SELECT 
                appointments.appointment_id, 
                patient_user.name AS appointment_patient_name, 
                doctor_user.name AS doctor_name, 
                appointments.appointment_date, 
                appointments.status, 
                appointments.created_at, 
                appointments.updated_at
            FROM appointments 
            INNER JOIN patients ON patients.patient_id = appointments.patient_id
            INNER JOIN users AS patient_user ON patients.user_id = patient_user.user_id
            INNER JOIN doctors ON doctors.doctor_id = appointments.doctor_id
            INNER JOIN users AS doctor_user ON doctors.user_id = doctor_user.user_id";

    // Build WHERE clause if filters are applied
    $sqllist = array($sqlpt1, $sqlpt2);
    $sqlkeywords = array(" WHERE ", " AND ");
    $key2 = 0;
    foreach ($sqllist as $key) {
        if (!empty($key)) {
            $sqlmain .= $sqlkeywords[$key2] . $key;
            $key2++;
        }
    }

} else {
    // Default query without any filter
    $sqlmain = "SELECT 
                appointments.appointment_id, 
                patient_user.name AS appointment_patient_name, 
                doctor_user.name AS doctor_name, 
                appointments.appointment_date, 
                appointments.status, 
                appointments.created_at, 
                appointments.updated_at
            FROM appointments 
            INNER JOIN patients ON patients.patient_id = appointments.patient_id 
            INNER JOIN users AS patient_user ON patients.user_id = patient_user.user_id 
            INNER JOIN doctors ON doctors.doctor_id = appointments.doctor_id 
            INNER JOIN users AS doctor_user ON doctors.user_id = doctor_user.user_id 
            ORDER BY appointments.appointment_date DESC;";
}

?>
   <!--------------- Add Appoitment ----------------->

  
        <td>
            <button id="add-appointment-btn" class="btn-primary-soft btn button-icon btn-add" onclick="toggleAppointmentForm()">
                <font class="tn-in-text">Add Appointment</font>
            </button>
        </td>
        <td>
        <a href="../admin/email.php" id="add-appointment-btn" class="btn-primary-soft btn button-icon btn-add">
    <font class="tn-in-text">Verify Email</font>
</a>

        </td>
<tr>
    <td colspan="4">
            <div class="abc scroll">
                <table class="sub-table" >
                    <thead>
                        <tr>
                            <th class="table-headin">Patient Name</th>
                            <th class="table-headin">Doctor Name</th>
                            <th class="table-headin">Date</th>
                            <th class="table-headin">Status</th>
                            <th class="table-headin">Actions</th> 
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    // Execute the query based on the filters or defaults
                    $result1 = $conn->query($sqlmain);

                    if ($result1->num_rows == 0) {
                        echo '<tr>
                            <td colspan="5">
                                <br><br><br><br>
                                <center>
                                <img src="../public/img/notfound.svg" width="25%">
                                <br>
                                <p class="heading-main12">We couldn\'t find anything related to your keywords!</p>
                                <a class="non-style-link" href="appointments.php">
                                    <button class="login-btn btn-primary-soft btn" >
                                        &nbsp; Show all Appointments &nbsp;
                                    </button>
                                </a>
                                </center>
                                <br><br><br><br>
                            </td>
                        </tr>';
                    } else {
                        while ($row = $result1->fetch_assoc()) {
                            $Appointment_ID = $row["appointment_id"];
                            $patient_Name = $row["appointment_patient_name"];
                            $Doctor_Name = $row["doctor_name"]; 
                            $appodate = $row["appointment_date"]; 
                            $status = $row["status"]; 

                            echo '<tr>
                                <td style="font-weight:600;text-align:center;"> &nbsp;' . substr($patient_Name, 0, 25) . '</td>
                                <td style="text-align:center;font-weight:500; color: var(--btnnicetext);">' . substr($Doctor_Name, 0, 25) . '</td>
                                <td style="text-align:center;">' . $appodate . '</td>
                                <td style="text-align:center;">
                                    <!-- Dropdown to change status -->
                                    <form action="update_appointment_status.php" method="POST">
                                        <input type="hidden" name="Appointment_ID" value="' . $Appointment_ID . '">
                                        <select name="status" onchange="this.form.submit()" style="width:90% ;height: 37px;margin: 0; margin-top:18px; text-align:center;">
                                            <option value="scheduled" ' . ($status == "scheduled" ? "selected" : "") . '>Scheduled</option>
                                            <option value="completed" ' . ($status == "completed" ? "selected" : "") . '>Completed</option>
                                            <option value="canceled" ' . ($status == "canceled" ? "selected" : "") . '>Canceled</option>
                                        </select>
                                    </form>
                                </td>

                                <td>
                                        <div style="display:flex;justify-content: center;">
                                        
                                        <a href="./edit_appointment.php?appointment_id=' . $Appointment_ID . '" class="non-style-link">
                                            <button class="btn-primary-soft btn button-icon btn-edit">
                                                <font class="tn-in-text">Edit</font>
                                            </button>
                                        </a>
                                        &nbsp;&nbsp;&nbsp;    
                                        <a href="./delete_appointment.php?Appointment_ID=' . $Appointment_ID . '" class="non-style-link">
                                            <button class="btn-primary-soft btn button-icon btn-delete">
                                                <font class="tn-in-text">Remove</font>
                                            </button>
                                        </a>

                                        </div>
                                </td>

                            </tr>';
                        }
                    }
                    ?>
                    </tbody>


            <tr>
               
     
                    

                </table>
            </div>

        </center>
    </td>
</tr>
    </table>
</div>
</div>
    </div>


<div id="appointment-popup" class="overlay" style="display: none;">
    <div class="popupAddAppo">
        <center>
            <a class="close" href="javascript:void(0)" onclick="toggleAppointmentForm()">&times;</a>
            <div style="display: flex; justify-content: center; margin-top:20px;">
                <div class="abc">
                    <table class="sub-table scrolldown add-doc-form-container" border="0">
                        <tr>
                            <td colspan="2">
                                <p class="btn-title" >Add New Appointment</p><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <form action="appointments.php" method="POST" class="input-text filter-container-items" >
                                    <label for="patient_id" class="form-label">Select Patient:</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <select name="patient_id" class="input-text filter-container-items" required>
                                    <option value="" disabled selected hidden style="margin:0 20px;">Choose Patient</option>
                                    
                                    <?php
                                        // Query to get patients
                                        $patientsList = $conn->query("SELECT patients.patient_id, users.name AS patient_name 
                                                                        FROM patients 
                                                                        INNER JOIN users ON patients.user_id = users.user_id;");
                                        while ($row = $patientsList->fetch_assoc()) {
                                            echo "<option class=\"input-text filter-container-items\" value=\"" . $row["patient_id"] . "\">" . $row["patient_name"] . "</option>";                                        }
                                    ?>

                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="doctor_id" class="form-label">Select Doctor:</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <select name="doctor_id" class="input-text filter-container-items" required>
                                    <option value="" disabled selected hidden>Choose Doctor</option>
                                   <?php
                                        // Query to get doctors
                                        $doctorsList = $conn->query("SELECT doctors.doctor_id, users.name AS doctor_name 
                                                                    FROM doctors 
                                                                    INNER JOIN users ON doctors.user_id = users.user_id;");
                                        while ($row = $doctorsList->fetch_assoc()) {
                                            echo "<option class=\"input-text filter-container-items\"  value=\"" . $row["doctor_id"] . "\">" . $row["doctor_name"] . "</option>";                                        }
                                    ?>

                                </select><br><br>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <label for="appointment_date" class="form-label" >Appointment Date:</label>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-td" colspan="2">
                                <input type="date" name="appointment_date" class="input-text filter-container-items"  required><br>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="display:flex; margin:10px 20px">
                                <input type="reset" value="Reset" class="login-btn btn-primary-soft btn">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="submit" value="Place Appointment" class="login-btn btn-primary btn" name="addappointment">
                            </td>
                        </tr>
                        </form>
                    </table>
                </div>
            </div>
        </center>
        <br><br>
    </div>
</div>
                                        

<?php

    if (isset($_POST['addappointment'])) {
        $patient_id = $_POST['patient_id']; 
        $doctor_id = $_POST['doctor_id'];
        $appointment_date = $_POST['appointment_date'];
    
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) 
            VALUES ($patient_id, $doctor_id , '$appointment_date', 'scheduled')";
    
        if (mysqli_query($conn, $sql)) {
            header("Location: appointments.php");
            exit(); 
        } else {
            echo "<script>alert('Error: Could not add appointment.');</script>";
        }
    }
        
?>



<script>
function toggleAppointmentForm() {
    var popup = document.getElementById("appointment-popup");
    if (popup.style.display === "none" || popup.style.display === "") {
        popup.style.display = "block";
    } else {
        popup.style.display = "none";
    }
}

// Handle clicks outside the popup to close it
window.onclick = function(event) {
    const overlay = document.getElementById('appointment-popup');
    const popup = overlay.querySelector('.popupAddAppo');

    if (event.target === overlay) {
        overlay.style.display = 'none';
    }
};

</script>


</body>
</html>
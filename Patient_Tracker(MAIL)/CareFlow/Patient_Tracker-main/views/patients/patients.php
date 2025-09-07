<?php
include("../includes/dbh.inc.php");
include("../includes/admin-header.php");
include("../includes/session_check.php");
    checkUserSession([3]);
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
?>

<head>
    <link rel="stylesheet" href="../public/css/patients.css">
</head>
<body>
     <table border="0" width="100%" style=" border-spacing: 0;margin:0;padding:0;margin-top:25px; ">
                <tr >
                    <td width="13%" >
                    <a href="appointments.php" ><button  class="login-btn btn-primary-soft btn btn-icon-back"  style="padding-top:11px;padding-bottom:11px;margin-left:20px;width:125px"><font class="tn-in-text">Back</font></button></a>
                    </td>
                    <td>
                        <p style="font-size: 23px;padding-left:12px;font-weight: 600;">Appointment Manager</p>
                                           
                    </td>
                    <td width="15%">
                        <p style="font-size: 14px;color: rgb(119, 119, 119);padding: 0;margin: 0;text-align: right;">
                            Today's Date
                        </p>
                        <p class="heading-sub12" style="padding: 0;margin: 0;">
                            <?php 

                        date_default_timezone_set('Africa/Cairo');

                        $today = date('Y-m-d');
                        echo $today;

                        $list110 = $conn->query("select  * from  appointments;");

                        ?>
                        </p>
                    </td>
                    <td width="10%">
                        <button  class="btn-label"  style="display: flex;justify-content: center;align-items: center;"><img src="../public/img/calendar.svg" width="100%"></button>
                    </td>


                </tr>

                <tr>
                    <td colspan="4" style="padding-top:10px;width: 100%;" >
                    
                        <p class="heading-main12" style="margin-left: 45px;font-size:18px;color:rgb(49, 49, 49)">All Appointments (<?php echo $list110->num_rows; ?>)</p>
                    </td>
                    
                </tr>
                <tr>
                    <td colspan="4" style="padding-top:0px;width: 100%;" >
                        <center>
                        <table class="filter-container" border="0" >
                        <tr>
                           <td width="10%">

                           </td> 
                        <td width="5%" style="text-align: center;">
                        Date:
                        </td>
                        <td width="30%">
                        <form action="" method="post">
                            
                            <input type="date" name="sheduledate" id="date" class="input-text filter-container-items" style="width:90% ;height: 37px;margin: 0; margin-top:15px;">

                        </td>
                        <td width="5%" style="text-align: center;">
                        Doctor:
                        </td>
                        <td width="30%">
                        <select name="doctor_id" id="" class="box filter-container-items" style="width:90% ;height: 37px;margin: 0;" >
                            <option value="" disabled selected hidden>Choose Doctor Name from the list</option><br/>
                              <?php 
                             
                                $list11 = $conn->query("SELECT users.name AS doctor_name
                                                        FROM doctors
                                                        INNER JOIN users ON doctors.user_id = users.user_id
                                                        WHERE doctors.doctor_id = 1");

                                for ($y=0;$y<$list11->num_rows;$y++){
                                    $row00=$list11->fetch_assoc();
                                    $sn=$row00["doctor_name"];
                                    $id00=$row00["doctor_id"];
                                    echo "<option value=".$id00.">$sn</option><br/>";
                                };


                                ?>

      </select>
                    </td>
                    <td width="12%">
                        <input type="submit"  name="filter" value=" Filter" class=" btn-primary-soft btn button-icon btn-filter"  style="padding: 15px; margin :0;width:100%">
                        </form>
                    </td>

                    </tr>
 </table>
</body>

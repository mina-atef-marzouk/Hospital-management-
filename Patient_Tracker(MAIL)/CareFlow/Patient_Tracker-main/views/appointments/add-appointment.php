<html>
    <head>
        <title>Add Appointment</title><meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../public/css/animations.css">  
        <!-- <link rel="stylesheet" href="../public/css/layout.css">   -->
        <link rel="stylesheet" href="../public/css/ex-styles.css"> 

    </head>
    <body>
        <!-- style="display: none;" -->
    <div id="appointment-popup" class="overlay" >
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
                                            // Make sure to set the value to the patient_id
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

    // Debugging output
        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, status) 
            VALUES ($patient_id, $doctor_id , '$appointment_date', 'scheduled')";

    	$result=mysqli_query($conn,$sql);

	if($result)	{
        echo "<script>alert('Appointment successfully added!');";
	}
    else {
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
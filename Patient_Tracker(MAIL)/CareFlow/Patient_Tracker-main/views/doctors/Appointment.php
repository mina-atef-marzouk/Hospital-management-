<?php
class Appointment  {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getAppointmentsByDoctorId($doctor_id) {
        $sql = "SELECT appointment_id, appointment_date, status FROM appointments WHERE doctor_id = ?";
        $query = $this->conn->prepare($sql);
       

        $query->bind_param("i", $doctor_id);
        $query->execute();

        return $query->get_result();
    }
}
?> 
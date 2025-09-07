<?php
class Doctor {
    private $conn;
    private $doctor_id;

    public function __construct($conn, $user_id) {
        $this->conn = $conn;
        $this->setDoctorId($user_id);
    }

    private function setDoctorId($user_id) {
        $sql = "SELECT doctor_id FROM doctors WHERE user_id = ?";
        $query = $this->conn->prepare($sql);
        

        $query->bind_param("i", $user_id);
       $query->execute();
        $result = $query->get_result();
        if ($result->num_rows > 0) {
            $doctor_data = $result->fetch_assoc();
            $this->doctor_id = $doctor_data['doctor_id'];
        } else {
            die("Doctor not found for the logged-in user.");
        }
    }

    public function getDoctorId() {
        return $this->doctor_id;
    }
}
?>
<?php
require '../../../vendor/autoload.php';
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

// Observer Interface
interface Observer {
    public function update($subject, $body);
}

// Concrete Observer (Patient)
class PatientObserver implements Observer {
    private $email;
    private $subscribed;

    public function __construct($email, $subscribed) {
        $this->email = $email;
        $this->subscribed = $subscribed;
    }

    public function update($subject, $body) {
        if ($this->subscribed) {
            $this->sendEmail($subject, $body);
        } else {
            echo "Patient {$this->email} is not subscribed and will not receive notifications.<br>";
        }
    }

    private function sendEmail($subject, $body) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'careflow.tracker@gmail.com';
            $mail->Password = 'kdwp rabc tfde wjvw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('careflow.tracker@gmail.com', 'CareFlow');
            $mail->addAddress($this->email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo "Message sent to patient: $this->email<br>";
        } catch (Exception $e) {
            echo "Failed to send email to patient: $this->email. Error: {$mail->ErrorInfo}<br>";
        }
    }
}

// Concrete Observer (Doctor)
class DoctorObserver implements Observer {
    private $email;

    public function __construct($email) {
        $this->email = $email;
    }

    public function update($subject, $body) {
        $this->sendEmail($subject, $body);
    }

    private function sendEmail($subject, $body) {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'careflow.tracker@gmail.com';
            $mail->Password = 'kdwp rabc tfde wjvw';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('careflow.tracker@gmail.com', 'CareFlow');
            $mail->addAddress($this->email);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;

            $mail->send();
            echo "Message sent to doctor: $this->email<br>";
        } catch (Exception $e) {
            echo "Failed to send email to doctor: $this->email. Error: {$mail->ErrorInfo}<br>";
        }
    }
}

// Notification System (Subject)
class NotificationSystem {
    private $observers = [];
    private $subject;
    private $body;

    public function addObserver(Observer $observer) {
        $this->observers[] = $observer;
    }

    public function removeObserver(Observer $observer) {
        $key = array_search($observer, $this->observers);
        if ($key !== false) {
            unset($this->observers[$key]);
        }
    }

    public function notifyObservers() {
        foreach ($this->observers as $observer) {
            $observer->update($this->subject, $this->body);
        }
    }

    public function setMessage($subject, $body) {
        $this->subject = $subject;
        $this->body = $body;
        $this->notifyObservers();
    }
}

// Fetch emails and subscription status from the database
function fetchEmails($conn, $userTypeId) {
    $emails = [];
    if ($userTypeId == 2) {
        $sql = "SELECT u.email, p.subscribed FROM users u INNER JOIN patients p ON u.user_id = p.user_id WHERE u.user_type_id = ?";
    } else {
        $sql = "SELECT u.email FROM users u WHERE u.user_type_id = ?";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userTypeId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($userTypeId == 2) {
        while ($row = $result->fetch_assoc()) {
            $emails[] = ['email' => $row['email'], 'subscribed' => $row['subscribed']];
        }
    } else {
        while ($row = $result->fetch_assoc()) {
            $emails[] = $row['email'];
        }
    }
    
    $stmt->close();
    return $emails;
}
?>
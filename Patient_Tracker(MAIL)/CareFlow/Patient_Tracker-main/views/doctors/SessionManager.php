<?php
class SessionManager {
    public static function checkUserSession($allowed_user_types) {
   
        if (!isset($_SESSION["user_id"]) || !in_array($_SESSION["user_type_id"], $allowed_user_types)) {
            header("Location: ../login.php");
            exit();
        }
    }
}
?>
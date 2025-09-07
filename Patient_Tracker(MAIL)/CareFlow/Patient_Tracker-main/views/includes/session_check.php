<?php
function checkUserSession($allowed_types = []) {
    session_start();
    if (!isset($_SESSION["user_id"]) || !isset($_SESSION["user_type_id"])) {
        header("Location: ../users/Login.php");
        exit();
    }

    if (!empty($allowed_types) && !in_array($_SESSION["user_type_id"], $allowed_types)) {
        header("Location: ../unauthorized.php");
        exit();
    }
}
?>
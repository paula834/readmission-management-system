<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function require_student() {
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: /Readmission/login.php");
        exit;
    }
}

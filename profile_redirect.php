<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* Check role */
if ($conn->query("SELECT * FROM Patient WHERE id='$user_id'")->num_rows > 0) {
    header("Location: patient_dashboard.php");
    exit();
}

if ($conn->query("SELECT * FROM Therapist WHERE therapist_id='$user_id'")->num_rows > 0) {
    header("Location: therapist_dashboard.php");
    exit();
}

if ($conn->query("SELECT * FROM Admin WHERE admin_id='$user_id'")->num_rows > 0) {
    header("Location: admin_dashboard.php");
    exit();
}

/* Fallback */
header("Location: home.php");

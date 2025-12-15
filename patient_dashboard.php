<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <div class="sidebar">
        <h2>Patient Panel</h2>

        <a href="appointment.php">Book Appointment</a>
        <a href="#">Appointment History</a>
        <a href="#">Personal Details</a>
        <a href="#">Self Assessment Test</a>
        <a href="#">Progress Report</a>
        <a href="#">Feedback</a>

        <hr style="margin:20px 0; border-color:#ffffff55;">

        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
        <p>Select an option from the left menu.</p>
    </div>

</div>

</body>
</html>

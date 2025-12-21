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
    <title>Therapist Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <div class="sidebar">
        <h2>Therapist Panel</h2>

        <a href="#">Profile</a>
        <a href="therapist_personal.php">Personal Details</a>
        <a href="#">Today's Appointments</a>
        <a href="#">Emergency Service Request</a>
        <a href="therapist_progress.php">Patient Progress Reports</a>
        <a href="therapist_feedback_view.php">View Feedbacks</a>

        <hr style="margin:20px 0; border-color:#ffffff55;">

        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
        <p>Therapist dashboard (features coming soon).</p>
    </div>

</div>

</body>
</html>

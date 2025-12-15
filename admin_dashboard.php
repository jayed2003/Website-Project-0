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
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <div class="sidebar">
        <h2>Admin Panel</h2>

        <a href="#">Emergency Service Request</a>
        <a href="#">Appointments</a>
        <a href="#">Patient List</a>
        <a href="#">Therapist List</a>

        <hr style="margin:20px 0; border-color:#ffffff55;">

        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
        <p>Admin dashboard (features coming soon).</p>
    </div>

</div>

</body>
</html>

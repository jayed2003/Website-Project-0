<?php
session_start();
include("DBconnect.php");
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

        <?php include("admin_sidebar.php"); ?>


<div class="main-content">
	<h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>
    <h2>🛠 Admin Overview</h2>

    <div style="display:flex; gap:20px; flex-wrap:wrap;">

        <?php
            $u = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) AS n FROM user"))['n'];
            $t = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) AS n FROM therapist"))['n'];
            $a = mysqli_fetch_assoc(mysqli_query($conn, "SELECT count(*) AS n FROM appointment"))['n'];
        ?>

        <div class="dashboard-card" style="flex:1;">
            <h3>👥 Users</h3>
            <p><strong><?php echo $u; ?></strong> total registered</p>
        </div>

        <div class="dashboard-card" style="flex:1;">
            <h3>🧑‍⚕️ Therapists</h3>
            <p><strong><?php echo $t; ?></strong> verified therapists</p>
        </div>

        <div class="dashboard-card" style="flex:1;">
            <h3>📅 Appointments</h3>
            <p><strong><?php echo $a; ?></strong> scheduled</p>
        </div>

        <div class="dashboard-card" style="flex:1;">
            <h3>🔔 Recent Activity</h3>
            <p>🕒 System running smoothly 👍</p>
        </div>
    </div>
</div>

</div>

</body>
</html>

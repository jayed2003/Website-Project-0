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
    <title>Therapist Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <?php include("therapist_sidebar.php"); ?>

    
<div class="main-content">
	<h1>Welcome, <?php echo $_SESSION['user_name']; ?></h1>

    <h2>👨‍⚕️ Therapist Dashboard</h2>

    <div style="display:flex; gap:20px; flex-wrap:wrap;">

        <!-- Today Stats -->
        <div class="dashboard-card" style="flex:1;">
            <h3>📆 Today’s Appointments</h3>
            <?php
                $tid = $_SESSION['user_id'];
                $todayQ = mysqli_query($conn, "SELECT count(*) AS total FROM appointment WHERE therapist_id='$tid' AND date = CURDATE()");
                $t = mysqli_fetch_assoc($todayQ)['total'];
                echo "<p><strong>$t</strong> appointments today</p>";
            ?>
        </div>



        <!-- Pending Approvals -->
        <div class="dashboard-card" style="flex:1;">
            <h3>🕑 Pending Requests</h3>
            <?php
                $pending = mysqli_query($conn, "SELECT * FROM appointment WHERE therapist_id='$tid' AND status='Pending'");
                if(mysqli_num_rows($pending)==0){
                    echo "<p class='empty-text'>No pending requests!</p>";
                } else {
                    echo "<ul>";
                    while($p = mysqli_fetch_assoc($pending)){
                        echo "<li> ".$p['date']." ".$p['time']." - <strong>".$p['patient_name']."</strong></li>";
                    }
                    echo "</ul>";
                }
            ?>
        </div>
    </div>
</div>


</div>

</body>
</html>

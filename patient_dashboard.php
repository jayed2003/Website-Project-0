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
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <?php include("patient_sidebar.php"); ?>

    
    <div class="main-content">
    <h2>👋 Welcome Back! <?php echo $_SESSION['user_name']; ?></h2>
    
    <div style="display:flex; gap:20px; flex-wrap:wrap;">

        <!-- Upcoming Appointment -->
        <div class="dashboard-card" style="flex:1;">
            <h3>📅 Next Appointment</h3>
            <?php
                $pid = $_SESSION['user_id'];
                $nextAppt = mysqli_query($conn, "SELECT * FROM appointment WHERE patient_id='$pid' AND date >= CURDATE() ORDER BY date,time LIMIT 1");
                if(mysqli_num_rows($nextAppt)>0){
                    $ap = mysqli_fetch_assoc($nextAppt);
                    echo "<p><strong>Date:</strong> ".$ap['date']."</p>";
                    echo "<p><strong>Time:</strong> ".$ap['time']."</p>";
                    echo "<p><strong>Status:</strong> <span class='status ".$ap['status']."'>".$ap['status']."</span></p>";
                } else {
                    echo "<p class='empty-text'>No appointments scheduled.</p>";
                }
            ?>
        </div>

        <!-- Quick Actions -->
        <div class="dashboard-card" style="flex:1;">
            <h3>⚡ Quick Actions</h3>
            <a href="appointment.php"><button class="btn-primary">📌 Book Appointment</button></a><br><br>
            <a href="self_test.php"><button class="btn">🧠 Mental Health Test</button></a><br><br>
            <a href="patient_feedback.php"><button class="btn">📝 Give Feedback</button></a>
        </div>

        <!-- Profile Progress -->
        <div class="dashboard-card" style="flex:1;">
            <h3>🙋 Profile Progress</h3>
            <?php
                $userId = $_SESSION['user_id'];
                $q = mysqli_query($conn, "SELECT phone, street, city FROM user WHERE id='$userId'");
                $user = mysqli_fetch_assoc($q);
                $filled = 0; $total = 3;

                if($user['phone']) $filled++;
                if($user['street']) $filled++;
                if($user['city']) $filled++;

                $percent = round(($filled/$total)*100);
            ?>
            <p>Completion: <strong><?php echo $percent; ?>%</strong></p>
            <div style="height:10px; width:100%; background:#eee; border-radius:10px;">
                <div style="height:10px; width:<?php echo $percent; ?>%; background:#1abc9c; border-radius:10px;"></div>
            </div>
        </div>
    </div>
</div>


</div>

</body>
</html>

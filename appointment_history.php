<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("DBconnect.php");

$patient_id = $_SESSION['user_id'];
$user_name  = $_SESSION['user_name'];

$sql = "SELECT * FROM appointment 
        WHERE patient_id = $patient_id
        ORDER BY date DESC, time DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Appointment History</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }
        th, td {
            padding: 14px;
            text-align: center;
        }
        th {
            background: #1bb6a3;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2f2f2;
        }
        .status {
            padding: 6px 12px;
            border-radius: 20px;
            color: white;
            font-size: 14px;
        }
        .Pending {
            background: orange;
        }
        .Approved {
            background: green;
        }
        .Cancelled {
            background: red;
        }
    </style>
</head>

<body>

<div class="wrapper">

    
    <div class="sidebar">
        <h2>Patient Panel</h2>
        <a href="appointment.php">Book Appointment</a>
        <a href="appointment_history.php" class="active">Appointment History</a>
        <a href="self_assessment.php">Self Assessment Test</a>
        <a href="logout.php">Logout</a>
    </div>

    
    <div class="content">
        <h1>Appointment History</h1>
        <p>Here are your appointment records, <?php echo $user_name; ?>.</p>

        <?php if ($result->num_rows > 0) { ?>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Therapist</th>
                    <th>Status</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['date']; ?></td>
                        <td><?php echo date("h:i A", strtotime($row['time'])); ?></td>
                        <td><?php echo $row['therapist_name']; ?></td>
                        <td>
                            <span class="status <?php echo $row['status']; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                <?php } ?>
            </table>
        <?php } else { ?>
            <p>No appointments found.</p>
        <?php } ?>
    </div>

</div>

</body>
</html>

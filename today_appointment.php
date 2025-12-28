<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("DBconnect.php");

$therapist_id   = $_SESSION['user_id'];
$therapist_name = htmlspecialchars($_SESSION['user_name'] ?? 'Therapist');
$today = date("Y-m-d");

/* =========================
   ACCEPT APPOINTMENT LOGIC - FIXED SECURITY ISSUE
========================= */
if (isset($_POST['accept']) && isset($_POST['appointment_id'])) {
    $appointment_id = intval($_POST['appointment_id']); // Security fix
    
    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE appointment 
                           SET status='Approved'
                           WHERE appointment_id=? 
                           AND therapist_id=?");
    $stmt->bind_param("ii", $appointment_id, $therapist_id);
    $stmt->execute();
    $stmt->close();
    
    // Refresh page to show updated status
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

/* =========================
   FETCH DATA WITH PREPARED STATEMENTS
========================= */

// All appointments with prepared statement
$all_stmt = $conn->prepare("
    SELECT * FROM appointment
    WHERE therapist_id=?
    ORDER BY date DESC, time ASC
");
$all_stmt->bind_param("i", $therapist_id);
$all_stmt->execute();
$allAppointments = $all_stmt->get_result();

// Accepted appointments
$accepted_stmt = $conn->prepare("
    SELECT * FROM appointment
    WHERE therapist_id=?
    AND status='Approved'
    ORDER BY date DESC, time ASC
");
$accepted_stmt->bind_param("i", $therapist_id);
$accepted_stmt->execute();
$acceptedAppointments = $accepted_stmt->get_result();

// Today's appointments
$today_stmt = $conn->prepare("
    SELECT * FROM appointment
    WHERE therapist_id=?
    AND date=?
    ORDER BY FIELD(status, 'Approved') DESC, time ASC
");
$today_stmt->bind_param("is", $therapist_id, $today);
$today_stmt->execute();
$todayAppointments = $today_stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Therapist Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .box {
            background: white;
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .box h2 {
            margin-bottom: 15px;
            color: #1bb6a3;
        }
        .appointment {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f9f9f9;
            transition: all 0.3s ease;
        }
        .appointment:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        .approved {
            border-left: 6px solid #28a745;
        }
        .pending {
            border-left: 6px solid #ffc107;
        }
        .cancelled {
            border-left: 6px solid #dc3545;
        }
        .status {
            font-weight: bold;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.9em;
            display: inline-block;
            margin-top: 5px;
        }
        .status.approved {
            background: #d4edda;
            color: #155724;
        }
        .status.pending {
            background: #fff3cd;
            color: #856404;
        }
        .status.cancelled {
            background: #f8d7da;
            color: #721c24;
        }
        button {
            padding: 8px 15px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: #1bb6a3;
            color: white;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        button:hover {
            background: #17a293;
            transform: scale(1.05);
        }
        button:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }
        .appointment-info {
            flex-grow: 1;
        }
        .appointment-actions {
            margin-left: 20px;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!-- SIDEBAR -->
    <div class="sidebar">
        <h2>Therapist Panel</h2>
        <a href="#">Profile</a>
        <a href="therapist_personal.php">Personal Details</a>
        <a href="today_appointment.php">Today's Appointments</a>
        <a href="#">Emergency Service Request</a>
        <a href="therapist_progress.php">Patient Progress Reports</a>
        <a href="therapist_feedback_view.php">View Feedbacks</a>
    </div>

    <!-- CONTENT -->
    <div class="content">
        <h1>Welcome, <?php echo $therapist_name; ?></h1>
        
        <!-- Display today's date -->
        <p style="color: #666; margin-bottom: 20px;">
            📅 Today is: <?php echo date('F j, Y'); ?>
        </p>

        <!-- TODAY'S APPOINTMENTS -->
        <div class="box">
            <h2>📅 Today's Appointments</h2>

            <?php if ($todayAppointments->num_rows > 0) { ?>
                <?php while ($row = $todayAppointments->fetch_assoc()) { 
                    $status_class = strtolower($row['status']);
                ?>
                    <div class="appointment <?php echo $status_class; ?>">
                        <div class="appointment-info">
                            <p><strong>Patient:</strong> <?php echo htmlspecialchars($row['patient_name']); ?></p>
                            <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($row['time'])); ?></p>
                            <p><strong>Session Type:</strong> <?php echo htmlspecialchars($row['session_type'] ?? 'Regular'); ?></p>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <?php if (strtolower(trim($row['status'])) == 'pending') { ?>
                            <div class="appointment-actions">
                                <form method="POST" onsubmit="return confirm('Accept this appointment?');">
                                    <input type="hidden" name="appointment_id" 
                                           value="<?php echo $row['appointment_id']; ?>">
                                    <button type="submit" name="accept">Accept</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p style="color: #666; font-style: italic;">No appointments scheduled for today.</p>
            <?php } ?>
        </div>

        <!-- ACCEPTED APPOINTMENTS -->
        <div class="box">
            <h2>✅ Accepted Appointments</h2>

            <?php if ($acceptedAppointments->num_rows > 0) { ?>
                <?php while ($row = $acceptedAppointments->fetch_assoc()) { ?>
                    <div class="appointment approved">
                        <div class="appointment-info">
                            <p><strong>Patient:</strong> <?php echo htmlspecialchars($row['patient_name']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($row['date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($row['time'])); ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p style="color: #666; font-style: italic;">No accepted appointments yet.</p>
            <?php } ?>
        </div>

        <!-- ALL APPOINTMENTS -->
        <div class="box">
            <h2>📋 All Appointments</h2>

            <?php if ($allAppointments->num_rows > 0) { ?>
                <?php while ($row = $allAppointments->fetch_assoc()) { 
                    $status_class = strtolower($row['status']);
                ?>
                    <div class="appointment <?php echo $status_class; ?>">
                        <div class="appointment-info">
                            <p><strong>Patient:</strong> <?php echo htmlspecialchars($row['patient_name']); ?></p>
                            <p><strong>Date:</strong> <?php echo date('M j, Y', strtotime($row['date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($row['time'])); ?></p>
                            <span class="status <?php echo $status_class; ?>">
                                <?php echo htmlspecialchars($row['status']); ?>
                            </span>
                        </div>

                        <?php if (strtolower(trim($row['status'])) == 'pending') { ?>
                            <div class="appointment-actions">
                                <form method="POST" onsubmit="return confirm('Accept this appointment?');">
                                    <input type="hidden" name="appointment_id" 
                                           value="<?php echo $row['appointment_id']; ?>">
                                    <button type="submit" name="accept">Accept</button>
                                </form>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p style="color: #666; font-style: italic;">No appointments found.</p>
            <?php } ?>
        </div>

    </div>
</div>

<?php 
// Close database connections
$all_stmt->close();
$accepted_stmt->close();
$today_stmt->close();
$conn->close();
?>

<script>
// Add confirmation for accepting appointments
document.addEventListener('DOMContentLoaded', function() {
    const acceptForms = document.querySelectorAll('form[method="POST"]');
    acceptForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to accept this appointment?')) {
                e.preventDefault();
            }
        });
    });
});
</script>

</body>
</html>
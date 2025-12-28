<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// DELETE Appointment Action
if (isset($_POST['delete_appointment'])) {
    $appointment_id = $_POST['appointment_id'];
    $conn->query("DELETE FROM appointment WHERE appointment_id = $appointment_id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | All Appointments</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<div class="wrapper">
<?php include("admin_sidebar.php"); ?>

<div class="main-content">
    <h2 class="page-title">📅 All Appointments</h2>

    <?php
    $query = "
        SELECT 
            appointment.appointment_id,
            appointment.date,
            appointment.time,
            appointment.status,
            u_patient.name AS patient_name,
            u_therapist.name AS therapist_name
        FROM appointment
        JOIN patient p ON appointment.patient_id = p.id
        JOIN user u_patient ON p.id = u_patient.id
        JOIN therapist t ON appointment.therapist_id = t.therapist_id
        JOIN user u_therapist ON t.therapist_id = u_therapist.id
        ORDER BY appointment.date DESC, appointment.time DESC
    ";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
    ?>

    <div class="card">
        <p><strong>Patient:</strong> <?php echo $row['patient_name']; ?></p>
        <p><strong>Therapist:</strong> <?php echo $row['therapist_name']; ?></p>
        <p><strong>Date:</strong> <?php echo date("M d, Y", strtotime($row['date'])); ?></p>
        <p><strong>Time:</strong> <?php echo date("h:i A", strtotime($row['time'])); ?></p>
        <p><strong>Status:</strong> 
            <span class="status <?php echo strtolower($row['status']); ?>">
                <?php echo ucfirst($row['status']); ?>
            </span>
        </p>

        <form method="post">
            <input type="hidden" name="appointment_id" value="<?php echo $row['appointment_id']; ?>">
            <button type="submit" name="delete_appointment" 
                class="btn btn-delete">Delete</button>
        </form>
    </div>

    <?php 
        }
    } else {
        echo "<p class='no-data'>No appointments found.</p>";
    }
    ?>

</div>

</div>

</body>
</html>

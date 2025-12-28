<?php
include("DBconnect.php");
session_start();

/* Patient must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

/* Fetch patient info from USER table */
$patientStmt = $conn->prepare("
    SELECT name, phone, gender 
    FROM User 
    WHERE id = ?
");
$patientStmt->bind_param("i", $patient_id);
$patientStmt->execute();
$patient = $patientStmt->get_result()->fetch_assoc();

/* Fetch AVAILABLE therapists */
$therapistStmt = $conn->prepare("
    SELECT t.therapist_id, u.name 
    FROM Therapist t
    JOIN User u ON t.therapist_id = u.id
    WHERE t.availability_status = 'Available'
");
$therapistStmt->execute();
$therapists = $therapistStmt->get_result();

/* Handle emergency request submission */
if (isset($_POST['send_request'])) {
    $therapist_id = $_POST['therapist_id'];
    $description = $_POST['description'];
    $location = $_POST['location'];

    $insertStmt = $conn->prepare("
        INSERT INTO emergency_service_request
        (patient_id, therapist_id, patient_name, phone, gender, description, location)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $insertStmt->bind_param(
        "iisssss",
        $patient_id,
        $therapist_id,
        $patient['name'],
        $patient['phone'],
        $patient['gender'],
        $description,
        $location
    );

    $insertStmt->execute();

    header("Location: appointment_history.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Emergency Service Request</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">
    <?php include("patient_sidebar.php"); ?>

    <div class="main-content">
        <h2>🚨 Emergency Service Request</h2>

        <form method="POST" class="card">

            <label>Select Therapist</label>
            <select name="therapist_id" required>
                <option value="">-- Select Available Therapist --</option>
                <?php while ($row = $therapists->fetch_assoc()) { ?>
                    <option value="<?= $row['therapist_id']; ?>">
                        <?= htmlspecialchars($row['name']); ?>
                    </option>
                <?php } ?>
            </select>

            <label>Description</label>
            <textarea name="description" required></textarea>

            <label>Location</label>
            <input type="text" name="location" required>

            <button type="submit" name="send_request" class="btn-emergency">
				🚨 Send Emergency Request
			</button>


        </form>
    </div>
</div>

</body>
</html>

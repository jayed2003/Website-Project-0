<?php
include("DBconnect.php");
session_start();

/* user has to be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$patient_name = $_SESSION['user_name'];

/* checking if user is patient or not*/
$check_patient = $conn->query(
    "SELECT * FROM Patient WHERE id = '$patient_id'"
);

if ($check_patient->num_rows == 0) {
    // user not patient then sent to home page
    header("Location: home.php");
    exit();
}

$message = "";
$therapists = [];

/* check if date availabe */
if (isset($_POST['check'])) {
    $date = $_POST['date'];

    $sql = "
        SELECT t.therapist_id, u.name
        FROM Therapist t
        JOIN User u ON t.therapist_id = u.id
        WHERE t.therapist_id NOT IN (
            SELECT therapist_id
            FROM Appointment
            WHERE date = '$date'
        )
    ";

    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $therapists[] = $row;
    }
}

/* booking appointment here */
if (isset($_POST['book'])) {
    $date = $_POST['date'];
    $time = $_POST['time'];
    $therapist_id = $_POST['therapist_id'];

    $tname_query = $conn->query(
        "SELECT name FROM User WHERE id = '$therapist_id'"
    );
    $therapist_name = $tname_query->fetch_assoc()['name'];

    $sql = "INSERT INTO Appointment 
            (date, time, status, patient_name, therapist_name, patient_id, therapist_id)
            VALUES
            ('$date', '$time', 'Pending', '$patient_name', '$therapist_name', '$patient_id', '$therapist_id')";

    if ($conn->query($sql)) {
		$message = "<p class='success'>
			Appointment booked successfully!<br>
			Status: Pending<br>
			Redirecting to dashboard </p>";
    header("refresh:2;url=patient_dashboard.php");
	}

    else {
        $message = "<p class='error'>".$conn->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Appointment</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="logo">
        <a href="home.php" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
            <img src="images/logo.png" alt="Logo">
            <span>PsychHelp</span>
        </a>
    </div>
</div>

<!-- Appointment Card -->
<div class="container">
    <h2>Book Appointment</h2>

    <p style="text-align:center; margin-bottom:15px;">
        Logged in as <b><?php echo $patient_name; ?></b>
    </p>

    <?php echo $message; ?>

    <!-- Select Date -->
    <form method="POST">
        <label>Select Date</label>
        <input type="date" name="date" required>
        <button type="submit" name="check">Check Availability</button>
    </form>

    <br>

    <!-- Show therapists -->
    <?php if (!empty($therapists)) { ?>
        <form method="POST">
            <input type="hidden" name="date" value="<?php echo $_POST['date']; ?>">

            <label>Select Time</label>
            <input type="time" name="time" required>

            <label>Select Therapist</label>
            <select name="therapist_id" required>
                <?php foreach ($therapists as $t) { ?>
                    <option value="<?php echo $t['therapist_id']; ?>">
                        <?php echo $t['name']; ?>
                    </option>
                <?php } ?>
            </select>

            <button type="submit" name="book">Book Appointment</button>
        </form>
    <?php } elseif (isset($_POST['check'])) { ?>
        <p style="text-align:center;">No therapists available on this date.</p>
    <?php } ?>

</div>

</body>
</html>

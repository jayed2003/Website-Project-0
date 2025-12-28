<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$success = "";
$error = "";

$sql = "
    SELECT DISTINCT a.therapist_id, u.name AS therapist_name
    FROM appointment a
    JOIN user u ON u.id = a.therapist_id
    WHERE a.patient_id = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    echo "No therapists found for this patient.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $therapist_id   = $_POST['therapist_id'];
    $therapist_name = $_POST['therapist_name'];
    $comment        = $_POST['comment'];
    $rating         = $_POST['rating'];

    if (!empty($comment)) {
        $stmt2 = $conn->prepare(
            "INSERT INTO feedback (patient_id, therapist_id, therapist_name, comment, rating)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
             comment = VALUES(comment),
             rating = VALUES(rating)"
        );

        $stmt2->bind_param(
            "iissi",
            $patient_id,
            $therapist_id,
            $therapist_name,
            $comment,
            $rating
        );

        if ($stmt2->execute()) {
            $success = "Feedback submitted successfully.";
        } else {
            $error = "Failed to submit feedback: " . $stmt2->error;
        }
        $stmt2->close();
    } else {
        $error = "Feedback cannot be empty.";
    }
}

$current_page = basename($_SERVER['PHP_SELF']); 


?>
<!DOCTYPE html>

<html>
<head>
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
<div class="wrapper">
    <div class="sidebar">
        <h2>Patient Panel</h2>
        <a href="appointment.php">Book Appointment</a>
        <a href="#">Appointment History</a>
        <a href="patient_personal.php">Personal Details</a>
        <a href="#">Self Assessment Test</a>
        <a href="patient_progress.php">Progress Report</a>
        <a href="patient_feedback.php">Feedback</a>
        <hr style="margin:20px 0; border-color:#ffffff55;">
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
        <h1>Therapist Feedback</h1>

        <div class="feedback-box">

            <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>

            <form method="POST">
                <label>Select Therapist</label>
                <select name="therapist_id" required onchange="setTherapistName(this)">
                    <option value="">-- Select --</option>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <option value="<?= $row['therapist_id'] ?>"
                                data-name="<?= htmlspecialchars($row['therapist_name']) ?>">
                            <?= htmlspecialchars($row['therapist_name']) ?> (ID: <?= $row['therapist_id'] ?>)
                        </option>
                    <?php } ?>
                </select>

                <input type="hidden" name="therapist_name" id="therapist_name">

                <label>Feedback</label>
                <textarea name="comment" rows="4" required></textarea>

                <label>Rating (1–5)</label>
                <input type="number" name="rating" min="1" max="5">

                <button type="submit">Submit Feedback</button>
            </form>

        </div>
    </div>
</div>

<script>
function setTherapistName(select) {
    const name = select.options[select.selectedIndex].getAttribute('data-name');
    document.getElementById('therapist_name').value = name;
}
</script>

</body>
</html>

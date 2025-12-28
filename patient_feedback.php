<?php
include("DBconnect.php");
session_start();

/* patient must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];
$success = "";
$error = "";

/* Fetch therapists for this patient */
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

$noDataMessage = "";
if (mysqli_num_rows($result) == 0) {
    $noDataMessage = "You haven’t booked any appointments yet.";
}

/* Handle feedback submission */
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

$current_page = basename($_SERVER['PHP_SELF']); // gets current file name


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

    <div class="content-card">
        <h1>Therapist Feedback</h1>

        <div class="feedback-box">

            <?php if ($success) echo "<p style='color:green;'>$success</p>"; ?>
            <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
			<?php if (!empty($noDataMessage)): ?>
				<p style="color:#b30000; font-weight:bold; margin-bottom:10px;">
					<?= $noDataMessage ?>
				</p>
			<?php endif; ?>
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
				
				<button type="submit" class="btn-primary">Submit Feedback</button>
            </form>

        </div>
    <div>
</div>

<script>
function setTherapistName(select) {
    const name = select.options[select.selectedIndex].getAttribute('data-name');
    document.getElementById('therapist_name').value = name;
}
</script>

</body>
</html>

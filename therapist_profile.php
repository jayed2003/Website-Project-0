<?php
include("DBconnect.php");
session_start();

/* therapist must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$therapist_id = $_SESSION['user_id'];

/* ================= FETCH THERAPIST INFO ================= */
$stmt = $conn->prepare("
    SELECT t.therapist_id, t.license_no, t.availability_status, t.profile_image, u.name
    FROM therapist t
    JOIN user u ON u.ID = t.therapist_id
    WHERE t.therapist_id = ?
");
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$therapist = $stmt->get_result()->fetch_assoc();

/* ================= TOGGLE AVAILABILITY ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_status'])) {
    $new_status = $_POST['new_status'];

    $update = $conn->prepare(
        "UPDATE therapist SET availability_status = ? WHERE therapist_id = ?"
    );
    $update->bind_param("si", $new_status, $therapist_id);
    $update->execute();

    header("Location: therapist_profile.php");
    exit();
}


/* ================= UPLOAD PROFILE IMAGE ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['profile_image'])) {
    $targetDir = "uploads/";
    if (!is_dir($targetDir)) mkdir($targetDir);

    $fileName = time() . "_" . basename($_FILES["profile_image"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["profile_image"]["tmp_name"], $targetFile)) {
        $imgUpdate = $conn->prepare(
            "UPDATE therapist SET profile_image = ? WHERE therapist_id = ?"
        );
        $imgUpdate->bind_param("si", $targetFile, $therapist_id);
        $imgUpdate->execute();
    }

    header("Location: therapist_profile.php");
    exit();
}

/* ================= SAVE DEGREE & SPECIALIZATION ================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_professional'])) {
    $degree = trim($_POST['degree']);
    $specialization = trim($_POST['specialization']);

    if ($degree !== "") {
        $conn->query("DELETE FROM therapist_degree WHERE therapist_id = $therapist_id");
        $deg = $conn->prepare(
            "INSERT INTO therapist_degree (therapist_id, degree) VALUES (?, ?)"
        );
        $deg->bind_param("is", $therapist_id, $degree);
        $deg->execute();
    }

    if ($specialization !== "") {
        $conn->query("DELETE FROM specialization WHERE therapist_id = $therapist_id");
        $spec = $conn->prepare(
            "INSERT INTO specialization (therapist_id, specialization) VALUES (?, ?)"
        );
        $spec->bind_param("is", $therapist_id, $specialization);
        $spec->execute();
    }

    header("Location: therapist_profile.php");
    exit();
}

/* ================= FETCH DEGREE & SPECIALIZATION ================= */
$degRow = $conn->query(
    "SELECT degree FROM therapist_degree WHERE therapist_id = $therapist_id"
)->fetch_assoc();

$specRow = $conn->query(
    "SELECT specialization FROM specialization WHERE therapist_id = $therapist_id"
)->fetch_assoc();

/* ================= CALENDAR SETUP ================= */
$today = date('j');
$monthDays = cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
$holidays = [5, 18, 25]; // example holidays
?>

<!DOCTYPE html>
<html>
<head>
    <title>Therapist Profile</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">

    <!-- ================= SIDEBAR ================= -->
    <?php include("therapist_sidebar.php"); ?>

    <!-- ================= MAIN CONTENT ================= -->
    <div class="main-content">
        <h1>Therapist Profile</h1>

        <!-- ===== CALENDAR ===== -->
        <div class="card">
            <h3>Calendar</h3>
            <div class="calendar-grid">
                <?php for ($d = 1; $d <= $monthDays; $d++): 
                    $class = "day";
                    if ($d == $today) $class .= " today";
                    if (in_array($d, $holidays)) $class .= " holiday";
                ?>
                    <div class="<?= $class ?>"><?= $d ?></div>
                <?php endfor; ?>
            </div>

            <form method="POST">
				<input type="hidden" name="new_status"
					value="<?= $therapist['availability_status'] === 'Available' ? 'Unavailable' : 'Available' ?>">

				<label class="switch">
					<input type="checkbox"
						onchange="this.form.submit()"
						<?= $therapist['availability_status'] === 'Available' ? 'checked' : '' ?>>
					<span class="slider"></span>
				</label>

				<span class="status-text">
					Status: <?= htmlspecialchars($therapist['availability_status']) ?>
				</span>
			</form>

        </div>

        <!-- ===== PROFILE CARD ===== -->
        <div class="card profile-card">
            <img class="profile-pic"
                 src="<?= $therapist['profile_image'] ?: 'assets/default.png' ?>">

            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="profile_image">
                <button class="btn">Upload Image</button>
            </form>

            <p><strong>Name:</strong> <?= htmlspecialchars($therapist['name']) ?></p>
            <p><strong>License:</strong> <?= htmlspecialchars($therapist['license_no']) ?></p>


                <strong>Status:</strong> <?= $therapist['availability_status'] ?>
            </form>
        </div>

        <!-- ===== PROFESSIONAL DETAILS ===== -->
        <div class="card">
            <h3>Professional Details</h3>
            <form method="POST">
                <input type="text" name="degree"
                       placeholder="Degree"
                       value="<?= htmlspecialchars($degRow['degree'] ?? '') ?>">
                <input type="text" name="specialization"
                       placeholder="Specialization"
                       value="<?= htmlspecialchars($specRow['specialization'] ?? '') ?>">
                <button class="btn" name="save_professional">Save</button>
            </form>
        </div>

    </div>
</div>

</body>
</html>

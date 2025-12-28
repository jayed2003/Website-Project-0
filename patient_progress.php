<?php
include("DBconnect.php");
session_start();

/* Patient must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

$sql = "
    SELECT patient_name, improvement_score, severity, date, summary
    FROM progress_report
    WHERE patient_id = ?
    ORDER BY date DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $no_reports = "No progress reports available yet.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Progress Reports</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>

<div class="wrapper">
	<?php include("patient_sidebar.php"); ?>

	<div class="content">
		<h1>Your Progress Reports</h1>

		<?php if(isset($no_reports)) { ?>
			<p style="font-style:italic; color:#555; margin-top:20px;"><?= $no_reports; ?></p>
		<?php } else { ?>

			<?php while($row = $result->fetch_assoc()) { ?>
				<div class="report-card">
					<p><strong>Date:</strong> <?= date("d M Y", strtotime($row['date'])); ?></p>
					<p><strong>Improvement Score:</strong> <?= $row['improvement_score']; ?>/10</p>
					<p><strong>Severity:</strong> <?= htmlspecialchars($row['severity']); ?></p>
					<p><strong>Summary:</strong><br><?= nl2br(htmlspecialchars($row['summary'])); ?></p>
				</div>
			<?php } ?>

		<?php } ?>
</div>

<style>
.report-card {
    background: #e0fcf9;
    border-left: 6px solid #1bb6a3;
    padding: 20px 25px;
    border-radius: 12px;
    margin-bottom: 20px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.report-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.1);
}

.report-card p {
    margin: 8px 0;
    color: #333;
    line-height: 1.5;
}

.report-card strong {
    color: #1bb6a3;
}
</style>


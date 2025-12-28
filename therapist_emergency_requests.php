<?php
include("DBconnect.php");
session_start();

/* therapist must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$therapist_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Emergency Requests</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>

<div class="wrapper">

    <!-- Therapist Sidebar -->
    <?php include("therapist_sidebar.php"); ?>

    <!-- Main Content -->
    <div class="main-content">

        <h1 class="page-title">🚨 Emergency Service Requests</h1>

        <div class="card">

            <?php
            $stmt = $conn->prepare("
                SELECT 
                    e.request_datetime,
                    e.patient_name,
                    e.phone,
                    e.gender,
                    e.description,
                    e.location,
                    e.status
                FROM emergency_service_request e
                WHERE e.therapist_id = ?
                ORDER BY e.request_datetime DESC
            ");

            $stmt->bind_param("i", $therapist_id);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
            ?>

            <table class="appointment-table emergency-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Patient</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>

                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= date("M d, Y", strtotime($row['request_datetime'])) ?></td>
                        <td><?= htmlspecialchars($row['patient_name']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= htmlspecialchars($row['location']) ?></td>
                        <td class="status <?= strtolower($row['status']) ?>">
                            <?= ucfirst($row['status']) ?>
                        </td>
                    </tr>
                <?php } ?>

                </tbody>
            </table>

            <?php
            } else {
                echo "<p class='empty-text'>No emergency requests.</p>";
            }
            ?>

        </div>
    </div>
</div>

</body>
</html>

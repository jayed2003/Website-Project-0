<?php
include("DBconnect.php");
session_start();

/* therapist must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$therapist_id = $_SESSION['user_id']; // get therapist ID from session

/* Fetch feedbacks for this therapist */
$sql = "SELECT comment, rating FROM feedback WHERE therapist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $therapist_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $no_feedback = "No feedback available for you yet.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Therapist Feedback View</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .feedback-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            margin-top: 20px;
        }
        th, td {
            padding: 14px 16px;
            text-align: left;
        }
        th {
            background: #1bb6a3;
            color: white;
        }
        tr:nth-child(even) {
            background: #f2fefe;
        }
    </style>
</head>
<body>
<div class="wrapper">


    <?php include("therapist_sidebar.php"); ?>

    <div class="content">
        <h1>Anonymous Feedback Received</h1>

        <?php if(isset($no_feedback)) { 
            echo "<p>$no_feedback</p>"; 
        } else { ?>

            <table class="feedback-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Comment</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $counter = 1;
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>".$counter."</td>";
                        echo "<td>".htmlspecialchars($row['comment'])."</td>";
                        echo "<td>".htmlspecialchars($row['rating'])."</td>";
                        echo "</tr>";
                        $counter++;
                    }
                    ?>
                </tbody>
            </table>

        <?php } ?>
    </div>
</div>
</body>
</html>

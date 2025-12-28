<?php
include("DBconnect.php");
session_start();

/* Patient must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$patient_id = $_SESSION['user_id'];

/* Fetch patient details from `user` table */
$stmt = $conn->prepare("SELECT name, email, phone, gender, street, city, zipcode, date_time FROM user WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    $error = "Patient details not found.";
} else {
    $row = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Personal Details</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .card {
            background: white;
            padding: 30px;
            border-radius: 14px;
            max-width: 700px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #1bb6a3;
        }

        .details p {
            margin: 10px 0;
            font-size: 16px;
            color: #333;
        }

        .details p span {
            font-weight: 600;
            color: #1bb6a3;
        }

        .msg-error {
            background: #fdecec;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="wrapper">
	<?php include("patient_sidebar.php"); ?>


    <div class="content">
        <h1>My Personal Details</h1>

        <div class="card">
            <h2>Account Information</h2>

            <?php if(isset($error)) { echo "<div class='msg-error'>$error</div>"; } else { ?>
            <div class="details">
                <p><span>Name:</span> <?= htmlspecialchars($row['name']) ?></p>
                <p><span>Email:</span> <?= htmlspecialchars($row['email']) ?></p>
                <p><span>Phone:</span> <?= htmlspecialchars($row['phone']) ?></p>
                <p><span>Gender:</span> <?= htmlspecialchars($row['gender']) ?></p>
                <p><span>Address:</span> <?= htmlspecialchars($row['street']) ?>, <?= htmlspecialchars($row['city']) ?>, <?= htmlspecialchars($row['zipcode']) ?></p>
                <p><span>Registered On:</span> <?= htmlspecialchars($row['date_time']) ?></p>
            </div>
            <?php } ?>
        </div>
    </div>

</div>
</body>
</html>

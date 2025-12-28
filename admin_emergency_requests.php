<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Handle Approve/Delete
if (isset($_POST['approve'])) {
    $request_id = $_POST['request_id'];
    $conn->query("UPDATE emergency_service_request SET status='Approved' WHERE request_id=$request_id");
}
if (isset($_POST['delete'])) {
    $request_id = $_POST['request_id'];
    $conn->query("UPDATE emergency_service_request SET status='Cancelled' WHERE request_id=$request_id");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Emergency Requests</title>
    <link rel="stylesheet" href="css/dashboard.css">
</head>
<body>
<div class="wrapper">
<?php include("admin_sidebar.php"); ?>

<div class="main-content">
    <h2 class="page-title">🚨 Emergency Service Requests</h2>

<?php
$query = "
    SELECT e.*, u.name AS patient_name, u.phone, u.gender 
    FROM emergency_service_request e
    JOIN user u ON e.patient_id = u.id
    ORDER BY e.request_datetime DESC
";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
?>

    <div class="card">
        <p><strong><?php echo $row['patient_name']; ?></strong></p>
        <p><strong>Phone:</strong> <?php echo $row['phone']; ?></p>
        <p><strong>Gender:</strong> <?php echo $row['gender']; ?></p>
        <p><strong>Issue:</strong> <?php echo $row['description']; ?></p>
        <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
        <p><strong>Status:</strong> <span class="status <?php echo strtolower($row['status']); ?>">
            <?php echo ucfirst($row['status']); ?>
        </span></p>

        <form method="post">
            <input type="hidden" name="request_id" value="<?php echo $row['request_id']; ?>">
            <button type="submit" name="approve" class="btn btn-approve">Approve</button>
            <button type="submit" name="delete" class="btn btn-delete">Delete</button>
        </form>
    </div>

<?php
    }
} else {
    echo "<p class='no-data'>No emergency requests found.</p>";
}
?>

</div>
</div>
</body>
</html>

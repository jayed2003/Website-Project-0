<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// DELETE Logic
if (isset($_GET['delete_id'])) {
    $user_id = intval($_GET['delete_id']);

    // Delete related appointments first
     $delete_appt = mysqli_query($conn, "DELETE FROM appointment WHERE patient_id = '$user_id'");

    // Delete patient from user table
    $delete_user = mysqli_query($conn, "DELETE FROM user WHERE id = '$user_id'");

    // Delete patient from patient table
    $delete_patient = mysqli_query($conn, "DELETE FROM patient WHERE id = '$user_id'");

    if ($delete_user) {
        header("Location: admin_patients_list.php?msg=deleted");
        exit();
    } else {
        echo "<script>alert('Delete Failed');</script>";
    }
}

// Search filter
$search = "";
if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

$query = "
    SELECT u.id, u.name, u.email, u.phone, u.gender, u.city, u.street, u.zipcode
    FROM User u
    JOIN Patient p ON u.id = p.id
";

if (!empty($search)) {
    $searchLike = "%$search%";
    $query .= " WHERE u.name LIKE ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $searchLike);
} else {
    $stmt = $conn->prepare($query);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Patient List</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .search-bar {
            width: 400px;
            margin: 20px auto;
            text-align: center;
        }
        .patient-card {
            background: #ffffff;
            padding: 18px;
            margin: 18px 0;
            border-radius: 12px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.09);
            width: 85%;
        }
        .delete-btn {
            background-color: #e53935;
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
        }
        .delete-btn:hover {
            background-color: #c62828;
        }
    </style>
</head>
<body>

<div class="wrapper">
<?php include("admin_sidebar.php"); ?>

<div class="main-content">
    <h2>👥 Patient List</h2>

    <!-- Search bar -->
    <form method="GET" class="search-bar">
        <input type="text" name="search" placeholder="Search by name..." value="<?= htmlspecialchars($search) ?>"
               style="padding:10px; width:70%; border-radius:6px; border:1px solid #ccc;">
        <button type="submit" 
                style="padding:10px 16px; background:#008D99; color:#fff; border:none; border-radius:6px; cursor:pointer;">
            Search
        </button>
    </form>

    <?php if ($result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="patient-card">
                <strong><?= $row['name'] ?></strong><br>
                <span><b>Email:</b> <?= $row['email'] ?></span><br>
                <span><b>Phone:</b> <?= $row['phone'] ?></span><br>
                <span><b>Gender:</b> <?= $row['gender'] ?></span><br>
                <span><b>Address:</b> <?= $row['street'] ?>, <?= $row['city'] ?> - <?= $row['zipcode'] ?></span><br>

                <br>
                <a href="admin_patients_list.php?delete_id=<?php echo $row['id']; ?>" 
					onclick="return confirm('Delete this patient and all appointments?');"
					class="delete-btn">Delete</a>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No patients found.</p>
    <?php } ?>
</div>

</div>

</body>
</html>

<?php $stmt->close(); ?>

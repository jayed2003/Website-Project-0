<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$admin_id = $_SESSION['user_id'];

// Delete feedback
if (isset($_GET['delete'])) {
    $patient_id = $_GET['delete'];
    $conn->query("DELETE FROM Feedback WHERE patient_id = $patient_id");
    header("Location: admin_feedback_list.php");
    exit();
}

// Search
$search = isset($_GET['search']) ? $_GET['search'] : "";

// Fetch feedback with user join
$sql = "
    SELECT 
        f.patient_id,
        f.therapist_id,
        u1.name AS patient_name,
        u2.name AS therapist_name,
        f.rating,
        f.comment
    FROM Feedback f
    JOIN User u1 ON f.patient_id = u1.id
    JOIN User u2 ON f.therapist_id = u2.id
    WHERE u2.name LIKE ?
";

$stmt = $conn->prepare($sql);
$search_param = "%" . $search . "%";
$stmt->bind_param("s", $search_param);
$stmt->execute();
$result = $stmt->get_result();
?>

<link rel="stylesheet" href="css/dashboard.css">

<div class="wrapper">
    <?php include("admin_sidebar.php"); ?>

    <div class="main-content">

        <h2 class="page-title">⭐ Feedback List</h2>

        <!-- Search Container -->
        <form method="GET" class="search-bar">
            <input type="text" name="search" class="search-input" placeholder="Search by Therapist name..." 
                value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>

        <table class="appointment-table">
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Therapist</th>
                    <th>Rating</th>
                    <th>Feedback</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0) { ?>
                    <?php while ($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td><?= $row['patient_name']; ?></td>
                            <td><?= $row['therapist_name']; ?></td>
                            <td><?= $row['rating']; ?>/5</td>
                            <td><?= $row['comment']; ?></td>
                            <td>
                                <a href="admin_feedback_list.php?delete=<?= $row['patient_id']; ?>" 
                                   class="delete-btn"
                                   onclick="return confirm('Are you sure you want to delete this feedback?')">
                                   Delete</a>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="5" style="text-align:center;">No feedback available</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
</div>

<style>
/* Center table and make it full width inside content */
.appointment-table {
    width: 95%;
    margin: 20px auto;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    font-size: 15px;
}

/* Header styling */
.appointment-table thead {
    background: #009879;
    color: white;
}

.appointment-table th, 
.appointment-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #e2e2e2;
}

/* Hover effect */
.appointment-table tbody tr:hover {
    background-color: #f2fffc;
}

/* Alternate row shading */
.appointment-table tbody tr:nth-child(even) {
    background-color: #f9f9f9;
}

/* Delete button styling (fixed margin + alignment) */
.delete-btn {
    background: #e74c3c;
    padding: 6px 10px;
    color: white;
    border-radius: 6px;
    font-size: 13px;
    text-decoration: none;
    display: inline-block;
    transition: 0.2s;
}

.delete-btn:hover {
    background: #c0392b;
}

/* Search bar layout cleanup */
.search-bar {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-bottom: 20px;
}

.search-input {
    width: 70%;
    padding: 10px;
    border-radius: 10px;
    border: 2px solid #009879;
    outline: none;
}

.search-btn {
    background: #009879;
    border: none;
    padding: 10px 20px;
    margin-left: 10px;
    border-radius: 10px;
    color: white;
    cursor: pointer;
    font-size: 14px;
}

.search-btn:hover {
    background: #007b70;
}

/* Fix page title */
.page-title {
    margin-bottom: 20px;
}

</style>

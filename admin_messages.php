<?php
include("DBconnect.php");
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$admin_id = $_SESSION['user_id'];
?>

<link rel="stylesheet" href="css/dashboard.css">

<div class="wrapper">
    <?php include("admin_sidebar.php"); ?>

    <div class="main-content">
        <h2>📩 User Messages</h2>

        <table class="message-table">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Message</th>
                <th>Received At</th>
            </tr>

            <tbody>

            <?php
            $result = mysqli_query($conn, "SELECT * FROM messages ORDER BY created_at DESC");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['name']}</td>
                        <td>{$row['email']}</td>
                        <td>{$row['message']}</td>
                        <td>{$row['created_at']}</td>
                    </tr>";
            }
            ?>

            </tbody>
        </table>

    </div>
</div>

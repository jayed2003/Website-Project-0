<?php
include("DBconnect.php");
session_start();

$message = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM User WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];

        // Redirect to home page (we'll create later)
        header("Location: home.php");
        exit();
    } else {
        $message = "<p class='error'>Invalid email or password</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Login</title>
    <link rel="stylesheet" href="css/register.css">
</head>
<body>

<!-- Header -->
<div class="header">
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
        <span>PsychHelp</span>
    </div>
</div>

<!-- Login Form -->
<div class="container">
    <h2>User Login</h2>

    <?php echo $message; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit" name="login">Login</button>
    </form>

    <p style="text-align:center; margin-top:15px;">
        Don’t have an account?
        <a href="register.php" style="color:#1bb6a3; font-weight:600;">Register</a>
    </p>
</div>

</body>
</html>

<?php
session_start();
include("DBconnect.php");

$step = 1; 
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // STEP 1: Verify user by Email + Name
    if (isset($_POST['check_user'])) {
        $email = $_POST['email'];
        $name = $_POST['name'];

        $query = mysqli_query($conn, "SELECT * FROM user WHERE email='$email' AND name='$name'");
        
        if (mysqli_num_rows($query) > 0) {
            $_SESSION['reset_email'] = $email;
            $step = 2;
        } else {
            $message = "❌ Invalid Email or Name!";
        }
    }

    // STEP 2: Update password
    if (isset($_POST['update_password'])) {
        $password = $_POST['password'];
        $confirm = $_POST['confirm_password'];

        if ($password !== $confirm) {
            $message = "⚠ Passwords do not match!";
            $step = 2;
        } else {
            $email = $_SESSION['reset_email'];
            mysqli_query($conn, "UPDATE user SET password='$password' WHERE email='$email'");
            unset($_SESSION['reset_email']);
            // After password update
			unset($_SESSION['reset_email']);
			header("Location: login.php?reset=success");
			exit();
        }
    }
}
?>

<link rel="stylesheet" href="css/register.css">
<div class="header">
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
        <span>PsychHelp</span>
    </div>
</div>
<div class="container">
    <h2>Forgot Password</h2>

    <?php if ($message) echo "<div class='success'>$message</div>"; ?>

    <form method="POST">

    <?php if ($step === 1) { ?>
        <!-- STEP 1: Enter Email + Name -->
        <label>Registered Email</label>
        <input type="email" name="email" required>

        <label>Full Name</label>
        <input type="text" name="name" required>

        <button type="submit" name="check_user">Verify</button>
		
    <?php } elseif ($step === 2) { ?>
        <!-- STEP 2: Password Reset -->
        <label>New Password</label>
        <input type="password" name="password" required minlength="4">

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required minlength="4">

        <button type="submit" name="update_password">Update Password</button>
	<?php } ?>
    

    </form>

    <div style="text-align:center; margin-top:12px;">
        <a href="login.php" style="color:#159c8c;">⬅ Back to Login</a>
    </div>

</div>

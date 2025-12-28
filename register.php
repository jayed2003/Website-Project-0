<?php
include("DBconnect.php");

$message = "";

if (isset($_POST['register'])) {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $password = $_POST['password'];
    $phone    = $_POST['phone'];
    $gender   = $_POST['gender'];
    $street   = $_POST['street'];
    $city     = $_POST['city'];
    $zipcode  = $_POST['zipcode'];
    $role     = $_POST['role']; // Patient or Therapist

    // Insert into User table
    $sql_user = "INSERT INTO user (name, email, password, phone, gender, date_time, street, city, zipcode)
            VALUES ('$name', '$email', '$password', '$phone', '$gender', CURDATE(), '$street', '$city', '$zipcode')";

    if ($conn->query($sql_user)) {

        // Get newly created user ID
        $user_id = $conn->insert_id;

        // If Patient
        if ($role == "Patient") {

            $sql_patient = "INSERT INTO Patient (id) VALUES ('$user_id')";
            $conn->query($sql_patient);

            $message = "<p class='success'>
				Registered successfully as Patient!<br>
				Redirecting to login page... </p>";
			header("refresh:2;url=login.php");

        }

        // If Therapist
        if ($role == "Therapist") {

            $license_no = "LIC-" . rand(10000, 99999);
            $fee = 1500;
            $status = "Available";

            $sql_therapist = "INSERT INTO Therapist 
                (therapist_id, license_no, consultation_fee, availability_status)
                VALUES 
                ('$user_id', '$license_no', '$fee', '$status')";

            $conn->query($sql_therapist);

            $message = "<p class='success'>
			Registered successfully as Therapist!<br>
			License No: <b>$license_no</b><br>
			Redirecting to login page... </p>";
			header("refresh:2;url=login.php");

        }

    } else {
        $message = "<p class='error'>".$conn->error."</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
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

<!-- Registration Form -->
<div class="container">
    <h2>User Registration</h2>

    <?php echo $message; ?>

    <form method="POST">

        <label>Register As</label>
        <select name="role" required>
            <option value="">-- Select Role --</option>
            <option value="Patient">Patient</option>
            <option value="Therapist">Therapist</option>
        </select>

        <label>Name</label>
        <input type="text" name="name" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <label>Phone</label>
        <input type="text" name="phone">

        <label>Gender</label>
        <select name="gender">
            <option>Male</option>
            <option>Female</option>
        </select>

        <label>Street</label>
        <input type="text" name="street">

        <label>City</label>
        <input type="text" name="city">

        <label>Zipcode</label>
        <input type="text" name="zipcode">

        <button type="submit" name="register">Register</button>
    </form>
</div>

</body>
</html>

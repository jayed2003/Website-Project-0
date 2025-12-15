<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>PsychHelp - Home</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<!-- HEADER -->
<header>
    <div class="logo">
        <img src="images/logo.png" alt="Logo">
        <span>PsychHelp</span>
    </div>

    <nav>
		<ul>
			<li><a href="#">Home</a></li>
			<li><a href="#">About</a></li>
			<li><a href="#">Therapist</a></li>
			<li><a href="#">Blog</a></li>
			<li><a href="#">Contact</a></li>
			<li><a href="profile_redirect.php"><b>My Profile</b></a></li>
		</ul>
	</nav>

</header>

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <h1>Complete Healthcare Solution</h1>
        <p>
            We provide professional mental healthcare services with trusted
            therapists to support your well-being and personal growth.
        </p>
        <a href="">Learn More</a>
    </div>
</section>

<!-- FOOTER -->
<footer>
    <div class="footer-container">

        <div class="footer-box">
            <h3>PsychHelp</h3>
            <p>Email: support@PsychHelp.com</p>
            <p>Phone: +880 1234 567890</p>
        </div>

        <div class="footer-box">
            <h3>Quick Links</h3>
            <a href="#">About</a>
            <a href="#">Therapist</a>
            <a href="#">Blog</a>
            <a href="#">Contact</a>
        </div>

        <div class="footer-box">
            <h3>Services</h3>
            <a href="#">Mental Health Support</a>
            <a href="#">Online Therapy</a>
            <a href="#">Emergency Help</a>
        </div>

    </div>

    <div class="footer-bottom">
        © 2025 PsychHelp. All rights reserved.
    </div>
</footer>

</body>
</html>

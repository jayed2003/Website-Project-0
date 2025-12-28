<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | PsychHelp</title>
    <link rel="stylesheet" href="css/home.css">
</head>
<body>

<header class="top-header animate-fade">
    <div class="left-nav">
        <a href="home.php" class="logo">
            <img src="images/logo.png" alt="PsychHelp">
            <span>PsychHelp</span>
        </a>
    </div>

    <nav class="nav-menu">
        <a href="home.php">Home</a>
        <a href="about.php">About Us</a>
        <a href="blog.php">Blog</a>
        <a href="therapist_catalog.php">Therapist</a>
        <a href="contact.php">Contact</a>
    </nav>

    <div class="right-nav">
        <a href="profile_redirect.php" class="profile-btn">My Profile</a>
    </div>
</header>


<section class="hero-section animate-up">
    <div class="hero-content">
        <h1>Your Wellness Matters 💚</h1>
        <p>
            Connect with trusted mental health professionals and begin a happier
            journey — anytime, anywhere.
        </p>
        <a href="appointment.php" class="btn-start">Start Your Healing</a>
    </div>
</section>


<!-- FOOTER -->
<footer class="footer-area animate-fade">
    <div class="footer-container">

        <div class="footer-column">
            <h3>PsychHelp</h3>
            <p>📧 Email: support@psychhelp.com</p>
            <p>📞 Phone: +880 1234 567890</p>
			<p><a href="https://facebook.com" target="_blank">👍 Facebook</a></p>
            <p><a href="https://instagram.com" target="_blank">📸 Instagram</a></p>
            <p><a href="https://wa.me/8801234567890" target="_blank">💬 WhatsApp: +880 1234 567890</a></p>
        </div>

        <div class="footer-column">
            <h3>Quick Links</h3>
            <a href="about.php">About Us</a>
            <a href="therapist_catalog.php">Therapist</a>
            <a href="blog.php">Blog</a>
            <a href="contact.php">Contact</a>
        </div>

        <div class="footer-column">
            <h3>Services</h3>
            <a href="self_assessment.php">Self Assesment Test</a>
            <a href="patient_emergency_request">Emergency Help</a>
        </div>

    </div>

    <div class="footer-bottom">
        © 2025 PsychHelp. All rights reserved.
    </div>
</footer>

</body>
</html>

<?php
include("header.php");
include("DBconnect.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $query = "INSERT INTO messages (name, email, message) VALUES ('$name', '$email', '$message')";
    mysqli_query($conn, $query);

    echo "<script>alert('Message sent successfully! Thank you for reaching out.');</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>

    <style>
        body {
            background: linear-gradient(to bottom, #4fb9af 0%, #b3e0dc 40%);
            font-family: 'Segoe UI', sans-serif;
        }

        .contact-section {
            width: 85%;
            max-width: 1200px;
            margin: 120px auto;
            text-align: center;
        }

        .contact-title {
            font-size: 38px;
            font-weight: bold;
            color: #005f56;
        }

        .contact-container {
            display: flex;
            gap: 40px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .contact-box {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            width: 350px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .contact-box h3 {
            color: #00645a;
            margin-bottom: 20px;
        }

        .contact-info p {
            text-align: left;
            font-size: 15px;
            margin: 10px 0;
        }

        .contact-info a {
            text-decoration: none;
            color: #005f56;
            font-weight: bold;
        }

        .contact-form input, .contact-form textarea {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #999;
            margin-top: 10px;
        }

        .contact-btn {
            width: 100%;
            background: #006c66;
            color: white;
            font-size: 17px;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            margin-top: 10px;
        }

        .contact-btn:hover {
            background: #004f49;
        }

        .google-map {
            width: 100%;
            height: 300px;
            border-radius: 12px;
            margin-top: 50px;
        }

    </style>
</head>

<body>

<div class="contact-section">
    <h1 class="contact-title">Contact Us</h1>
    <p style="color: #004a45;">We are here to listen & help 💚</p>

    <div class="contact-container">

        <!-- Reach Us -->
        <div class="contact-box contact-info">
            <h3>📬 Reach Us</h3>
            <p>📧 Email: <strong>support@PsychHelp.com</strong></p>
            <p>📞 Phone: <strong>+880 1234 567890</strong></p>
            <p>📍 Address: <strong>42 Green Avenue, Dhaka, Bangladesh</strong></p>

            <br><h3>🌐 Find Us</h3>
            <p><a href="https://facebook.com" target="_blank">👍 Facebook</a></p>
            <p><a href="https://instagram.com" target="_blank">📸 Instagram</a></p>
            <p><a href="https://wa.me/8801234567890" target="_blank">💬 WhatsApp</a></p>
        </div>

        <!-- Contact Form -->
        <div class="contact-box contact-form">
            <h3>Send a Message</h3>
            <form method="POST">
                <input type="text" name="name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
                <textarea name="message" placeholder="Write your message..." required></textarea>
                <button class="contact-btn" type="submit">Send Message</button>
            </form>
        </div>

    </div>

    <iframe class="google-map"
        src="https://maps.google.com/maps?q=Dhaka%20Bangladesh&t=&z=12&ie=UTF8&iwloc=&output=embed">
    </iframe>

</div>

<?php include("footer.php"); ?>
</body>
</html>

<?php include("header.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mental Wellness Blog</title>
    <style>
        body {
            background: linear-gradient(to bottom, #4fb9af 0%, #b3e0dc 40%);
        }

        .blog-container {
            width: 85%;
            max-width: 1200px;
            margin: 120px auto 80px auto;
            text-align: center;
        }

        .blog-heading {
            font-size: 32px;
            font-weight: bold;
            color: #005f56;
            margin-bottom: 10px;
        }

        .blog-desc {
            font-size: 16px;
            color: #004a45;
            margin-bottom: 40px;
        }

        .blog-cards {
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .blog-card {
            width: 350px;
            background: white;
            border-radius: 12px;
            padding: 15px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.12);
            transition: 0.3s ease-in-out;
            text-align: left;
        }

        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 8px 20px rgba(0,0,0,0.2);
        }

        .blog-card img {
            width: 100%;
            height: 210px;
            border-radius: 10px;
            object-fit: cover;
        }

        .blog-title {
            font-size: 20px;
            color: #00645a;
            margin-top: 12px;
            font-weight: bold;
        }

        .blog-text {
            color: #333;
            font-size: 14px;
            margin: 8px 0 15px;
        }

        .read-more-btn {
            display: inline-block;
            padding: 10px 16px;
            background-color: #00796b;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .read-more-btn:hover {
            background-color: #00564d;
        }
    </style>
</head>
<body>

<div class="blog-container">
    <h1 class="blog-heading">Mental Wellness Blog</h1>
    <p class="blog-desc">Guidance, knowledge & inspiration for a healthier, happier mind 🌱</p>

    <div class="blog-cards">

        <!-- Card 1 -->
        <div class="blog-card">
            <img src="images/mental_health.jpg" alt="Mental Health Awareness">
            <h3 class="blog-title">Understanding Mental Health</h3>
            <p class="blog-text">Learn why prioritizing mental wellness is crucial in everyday life & how small changes create huge impacts.</p>
            <a href="blog_post_mhaware.php" class="read-more-btn">Read More</a>
        </div>

        <!-- Card 2 -->
        <div class="blog-card">
            <img src="images/mindfulness.jpg" alt="Mindfulness Meditation">
            <h3 class="blog-title">The Power of Mindfulness</h3>
            <p class="blog-text">Mindfulness helps reduce stress, improve emotional control & strengthen mental resilience.</p>
            <a href="blog_post_mindfulness.php" class="read-more-btn">Read More</a>
        </div>

        <!-- Card 3 -->
        <div class="blog-card">
            <img src="images/stress.jpg" alt="Stress Management">
            <h3 class="blog-title">Managing Stress Effectively</h3>
            <p class="blog-text">Identify stress triggers & follow expert strategies to regain calm and focus in your day-to-day life.</p>
            <a href="blog_post_stress.php" class="read-more-btn">Read More</a>
        </div>

    </div>
</div>

<?php include("footer.php"); ?>
</body>
</html>

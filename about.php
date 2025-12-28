<?php include("header.php"); ?>

<style>
    body {
        background: linear-gradient(to bottom, #4fb9af 0%, #b3e0dc 40%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #003f3d;
        line-height: 1.6;
    }

    .about-section {
        max-width: 1000px;
        margin: 80px auto;
        background: rgba(255, 255, 255, 0.9);
        padding: 40px 50px;
        border-radius: 14px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        text-align: center;
    }

    .about-section h1 {
        font-size: 36px;
        font-weight: 700;
        color: #00796b;
        margin-bottom: 15px;
    }

    .about-section p {
        font-size: 18px;
        margin-bottom: 20px;
    }

    .mission-cards {
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        margin-top: 40px;
    }

    .mission-card {
        width: 30%;
        min-width: 250px;
        margin: 10px;
        background: #ffffff;
        border-radius: 14px;
        padding: 20px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.12);
        transition: transform 0.3s ease;
    }

    .mission-card:hover {
        transform: translateY(-8px);
    }

    .mission-card h3 {
        color: #00897b;
        font-size: 20px;
        margin-bottom: 12px;
    }

    .mission-card p {
        font-size: 15px;
        color: #004743;
    }

    .team-section {
        margin-top: 60px;
    }

    .team-section h2 {
        color: #00695c;
        margin-bottom: 20px;
    }

    .team-members {
        display: flex;
        justify-content: space-evenly;
        flex-wrap: wrap;
        margin-top: 20px;
    }

    .team-member {
        width: 200px;
        text-align: center;
        margin: 20px;
    }

    .team-member img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 3px solid #00897b;
    }

    .team-member h4 {
        margin-top: 10px;
        color: #004d40;
        font-weight: 600;
    }

    .team-member p {
        font-size: 14px;
        color: #00695c;
    }
</style>


<div class="about-section">
    <h1>About PsychHelp</h1>
    <p>
        At PsychHelp, we are dedicated to providing accessible, reliable, and compassionate 
        mental health care. Our mission is to make emotional well-being a priority for everyone, 
        everywhere. Whether you're dealing with stress, anxiety, depression, or simply looking 
        to better understand yourself — we’re here for you.
    </p>

    <p>
        PsychHelp connects individuals with licensed mental health therapists who offer 
        professional guidance with complete confidentiality, empathy, and expertise.
    </p>

    <!-- Mission Boxes -->
    <div class="mission-cards">
        <div class="mission-card">
            <h3>🔒 Confidential Care</h3>
            <p>We ensure private and trusted sessions between you and your therapist.</p>
        </div>

        <div class="mission-card">
            <h3>💚 Heart-Focused Support</h3>
            <p>We believe mental health deserves attention filled with compassion.</p>
        </div>

        <div class="mission-card">
            <h3>🌍 Accessible to Everyone</h3>
            <p>Therapy from the comfort of your home — anytime you need it.</p>
        </div>
    </div>

    <!-- Team Section -->
    <div class="team-section">
        <h2>Meet Our Founders</h2>
        <div class="team-members">
            <div class="team-member">
                <img src="images/founder.png" alt="Founder Image">
                <h4>Jayed Hossain</h4>
                <p>Founder & Director</p>
            </div>

            <div class="team-member">
                <img src="images/CEO.jfif" alt="Co-Founder Image">
                <h4>Tabassum Subah</h4>
                <p>Chief Executive Officer (CEO)</p>
            </div>
			
			<div class="team-member">
                <img src="images/COO.jpg" alt="Founder Image">
                <h4>Tasfia Shahrin Rimjhim</h4>
                <p>Chief Operating Officer (COO)</p>
            </div>
        </div>
    </div>

</div>


<?php include("footer.php"); ?>

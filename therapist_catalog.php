<?php
include("DBconnect.php");
include("header.php");
?>

<div class="page-container">
    <h2 class="page-title">Meet Our Counselors</h2>

    <div class="top-book-btn">
        <a href="appointment.php" class="book-btn">Book Appointment</a>
    </div>

    <div class="therapist-grid">

    <?php
    $query = "
    SELECT 
        u.name AS full_name,
        t.therapist_id,
        t.profile_image,
        GROUP_CONCAT(DISTINCT td.degree SEPARATOR ', ') AS degrees,
        GROUP_CONCAT(DISTINCT s.specialization SEPARATOR ', ') AS specializations,
        AVG(f.rating) AS avg_rating,
        COUNT(f.rating) AS review_count
    FROM therapist t
    JOIN user u ON t.therapist_id = u.id
    LEFT JOIN therapist_degree td ON t.therapist_id = td.therapist_id
    LEFT JOIN specialization s ON t.therapist_id = s.therapist_id
    LEFT JOIN feedback f ON t.therapist_id = f.therapist_id
    GROUP BY t.therapist_id, u.name, t.profile_image
    ";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        $profile_image = $row['profile_image'] ? $row['profile_image'] : "images/default_user.png";
        $rating = $row['avg_rating'] ? number_format($row['avg_rating'],1) : "No rating";
        $reviews = $row['review_count'];
    ?>

        <div class="therapist-card">
            <img src="<?php echo $profile_image; ?>" class="therapist-img">

            <h3><?php echo $row['full_name']; ?></h3>
            <p><strong>Designation:</strong> Counselor</p>
            <p><strong>Degrees:</strong> <?php echo $row['degrees'] ?: "Not added"; ?></p>
            <p><strong>Specializations:</strong> <?php echo $row['specializations'] ?: "Not specified"; ?></p>
            <p><strong>Rating:</strong> ⭐ <?php echo $rating; ?> (<?php echo $reviews; ?> reviews)</p>
            <p><strong>Session:</strong> 50 minutes</p>
            <p><strong>Fee:</strong> 1500.00 BDT</p>
        </div>

    <?php } ?>

    </div>
</div>

<?php include("footer.php"); ?>

<?php
session_start();
include("DBconnect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";
$user_id = $_SESSION['user_id'];

// TIME OPTIONS 9 AM - 5 PM
$timeOptions = "";
$start = strtotime("09:00");
$end = strtotime("17:00");
while ($start <= $end) {
    $value = date("H:i", $start);
    $label = date("h:i A", $start);
    $timeOptions .= "<option value='$value'>$label</option>";
    $start = strtotime("+1 hour", $start);
}

// Get selected date + time (if chosen)
$date = isset($_POST['date']) ? $_POST['date'] : null;
$time = isset($_POST['time']) ? $_POST['time'] : null;

// Trigger showing therapists only if both chosen
$showTherapists = ($date && $time);

// Handle final booking
if (isset($_POST['therapist'])) {

    $therapist_id = $_POST['therapist'];

    // Fetch user name
    $uQuery = mysqli_query($conn, "SELECT name FROM user WHERE id='$user_id'");
    $uRow = mysqli_fetch_assoc($uQuery);
    $patient_name = $uRow['name'];

    // Fetch therapist name
    $tQuery = mysqli_query($conn, "SELECT name FROM user WHERE id='$therapist_id'");
    $tRow = mysqli_fetch_assoc($tQuery);
    $therapist_name = $tRow['name'];

    // Check if slot already booked
    $check = mysqli_query($conn, 
        "SELECT * FROM appointment 
         WHERE date='$date' 
         AND time='$time' 
         AND therapist_id='$therapist_id'"
    );

    if (mysqli_num_rows($check) > 0) {
        $message = "⚠ Selected therapist is already booked at this time!";
    } 
    else {
        mysqli_query($conn,
            "INSERT INTO appointment 
             (patient_id, patient_name, therapist_id, therapist_name, date, time, status) 
             VALUES ('$user_id', '$patient_name', '$therapist_id', '$therapist_name', '$date', '$time', 'Pending')"
        );

        $message = "✔ Appointment Successfully Booked!";
    }
}
?>


<link rel="stylesheet" href="css/dashboard.css">

<style>
/* Gradient Background */
.main-content {
    background: white;
    min-height: 100vh;
    padding-top: 40px;
}

/* Card */
.appointment-container {
    background: linear-gradient(to bottom right, #4fb9af 0%, #b3e0dc 100%);
    padding: 30px;
    border-radius: 12px;
    width: 75%;
    margin: 0 auto 40px auto;
    box-shadow: 0px 4px 10px rgba(0,0,0,0.12);
}

/* Input fields */
.form-input {
    padding: 12px;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 16px;
    margin-bottom: 18px;
}

.btn-submit {
    background: #0e8578;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 16px;
}
.btn-submit:hover {
    background: #0c6e63;
}

/* Alerts */
.alert-box {
    background: #e0f7f5;
    border-left: 6px solid #0e8578;
    padding: 10px;
    font-size: 16px;
    margin-bottom: 20px;
    border-radius: 6px;
}
</style>

<div class="wrapper">
<?php include("patient_sidebar.php"); ?>

<div class="main-content">

    <div class="appointment-container">
        <h2>📅 Book Appointment</h2>

        <?php if ($message) echo "<p class='alert-box'>$message</p>"; ?>

        <form method="POST">

            <!-- Disable Fridays & Sundays using JS -->
            <script>
            function disableFriSun(input){
                var day = new Date(input.value).getDay();
                if(day == 0 || day == 5){ // 0 = Sunday, 5 = Friday
                    alert("⚠ Clinic remains closed on Fridays & Sundays!");
                    input.value = "";
                } else {
                    input.form.submit();
                }
            }
            </script>

            <label><strong>Select Date</strong></label>
            <input class="form-input" type="date" name="date"
                min="<?php echo date('Y-m-d'); ?>"
                value="<?php echo $date; ?>"
                onchange="disableFriSun(this)" required>

            <label><strong>Select Time</strong></label>
            <select class="form-input" name="time"
                onchange="this.form.submit()" required>
                <option disabled selected>Select Time</option>
                <?php echo $timeOptions; ?>
            </select>

            <?php if ($showTherapists) { ?>
                
                <label><strong>Available Therapists</strong></label>
                <select class="form-input" name="therapist" required>
                <?php
                $sql = "
                SELECT t.therapist_id, u.name 
                FROM therapist t 
                JOIN user u ON t.therapist_id = u.id
                WHERE t.therapist_id NOT IN (
                    SELECT therapist_id FROM appointment
                    WHERE date='$date' AND time='$time'
                )";

                $result = mysqli_query($conn, $sql);
                if (mysqli_num_rows($result) == 0) {
                    echo "<option disabled>No therapist available!</option>";
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row['therapist_id']."'>".$row['name']."</option>";
                    }
                }
                ?>
                </select>

                <button class="btn-submit" type="submit">Confirm Appointment</button>

            <?php } ?>

        </form>
    </div>
</div>
</div>

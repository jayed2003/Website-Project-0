<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("DBconnect.php");

$patient_id = $_SESSION['user_id'];
$user_name  = $_SESSION['user_name'];

$resultMessage = "";
$severity = "";
$score = null;

if (isset($_POST['submit_test'])) {

    $score = 0;

    // Count answers (20 questions)
    for ($i = 1; $i <= 20; $i++) {
        if (isset($_POST["q$i"])) {
            $score += (int)$_POST["q$i"];
        }
    }

    // Decision logic
    if ($score > 15) {
        $severity = "Low";
        $resultMessage = "You are doing well 😊<br>No need to book a therapist right now.";
    } elseif ($score < 10) {
        $severity = "High";
        $resultMessage = "Your mental health needs attention ⚠️<br>We strongly recommend booking a therapist.";
    } else {
        $severity = "Moderate";
        $resultMessage = "You are doing okay 🙂<br>Some lifestyle improvements are recommended.";
    }

    // Save to database
    $date = date("Y-m-d");
    $time = date("H:i:s");

    $sql = "INSERT INTO self_assessment_test
            (name, test_time, score, date, severity, patient_id)
            VALUES
            ('$user_name', '$time', $score, '$date', '$severity', $patient_id)
            ON DUPLICATE KEY UPDATE
                test_time = VALUES(test_time),
                score = VALUES(score),
                severity = VALUES(severity)";

    if (!$conn->query($sql)) {
        die("Database Error: " . $conn->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Self Assessment Test</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .question {
            background: #ffffff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 12px;
        }
        .question label {
            font-weight: 600;
        }
        .result-box {
    background-color: #1bb6a3;   /* teal */
    color: white;
    padding: 25px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.result-box h2 {
    margin-bottom: 10px;
}

.result-box ul {
    margin-top: 10px;
}

.result-box button {
    background: #e74c3c;
}

        button {
            padding: 12px 25px;
            background: #1bb6a3;
            border: none;
            color: white;
            font-size: 16px;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="wrapper">

    <!-- Sidebar -->
    <?php include("patient_sidebar.php"); ?>

    <!-- Content -->
    <div class="content">
        <h1>Mental Health Self-Assessment</h1>
    

     <?php if ($resultMessage != "") { ?>
            <div class="result-box">
                <h2>Your Result</h2>
                <p><b>Score:</b> <?php echo $score; ?>/20</p>
                <p><b>Severity:</b> <?php echo $severity; ?></p>
                <p><?php echo $resultMessage; ?></p>

                <?php if ($severity == "Moderate") { ?>
                    <ul>
                        <li>Exercise regularly</li>
                        <li>Maintain healthy sleep habits</li>
                        <li>Practice meditation or breathing</li>
                        <li>Talk to friends or family</li>
                    </ul>
                <?php } ?>

                <?php if ($severity == "High") { ?>
                    <form action="appointment.php" method="get" style="margin-top:15px;">
                        <button style="background:#e74c3c;">Book Appointment</button>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>

        <form method="POST">

            <?php
            $questions = [
                "I feel calm and relaxed most of the day",
                "I am able to sleep well at night",
                "I feel motivated to do daily tasks",
                "I feel hopeful about my future",
                "I can concentrate on my work or studies",
                "I feel supported by people around me",
                "I enjoy activities I usually like",
                "I manage stress effectively",
                "I feel confident about myself",
                "I feel emotionally stable",
                "I can control my worries",
                "I feel energetic during the day",
                "I feel satisfied with my life",
                "I do not feel overwhelmed easily",
                "I feel safe and secure",
                "I rarely feel anxious",
                "I handle challenges positively",
                "I feel mentally strong",
                "I have a positive outlook",
                "I feel balanced emotionally"
            ];

            for ($i = 0; $i < 20; $i++) {
                $qno = $i + 1;
                echo "
                <div class='question'>
                    <label>Q$qno. {$questions[$i]}</label><br><br>
                    <input type='radio' name='q$qno' value='1' required> Yes
                    &nbsp;&nbsp;
                    <input type='radio' name='q$qno' value='0' required> No
                </div>
                ";
            }
            ?>

            <button type="submit" name="submit_test">Submit Test</button>
        </form>

        

    </div>
</div>

</body>
</html>

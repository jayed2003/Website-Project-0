<?php
include("DBconnect.php");
session_start();

/* Therapist must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$therapist_id = $_SESSION['user_id'];

/* Handle form submission */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id        = $_POST['patient_id'];
    $patient_name      = $_POST['patient_name'];
    $appointment_id    = $_POST['appointment_id'];
    $improvement_score = $_POST['improvement_score'];
    $severity          = $_POST['severity'];
    $summary           = $_POST['summary'];
    $date              = $_POST['date'] ?? date("Y-m-d");

    $stmt = $conn->prepare(
        "INSERT INTO progress_report 
        (patient_name, improvement_score, severity, date, summary, patient_id, therapist_id, appointment_id)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "sisssiii",
        $patient_name,
        $improvement_score,
        $severity,
        $date,
        $summary,
        $patient_id,
        $therapist_id,
        $appointment_id
    );

    if ($stmt->execute()) {
        $success = "Progress report submitted successfully.";
    } else {
        $error = "Failed to submit progress report.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Create Progress Report</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        .card {
            background: #e0fcf9;
            padding: 30px;
            border-radius: 14px;
            max-width: 700px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
            margin-top: 20px;
            border-left: 6px solid #1bb6a3;
        }

        .card h2 {
            margin-bottom: 20px;
            color: #1bb6a3;
        }

        form label {
            display: block;
            margin: 15px 0 6px;
            font-weight: 600;
            color: #333;
        }

        form input,
        form select,
        form textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 15px;
        }

        form input:focus,
        form select:focus,
        form textarea:focus {
            outline: none;
            border-color: #1bb6a3;
            box-shadow: 0 0 0 2px #1bb6a320;
        }

        form textarea {
            resize: vertical;
        }

        .btn-submit {
            margin-top: 25px;
            padding: 12px;
            width: 100%;
            background: #1bb6a3;
            border: none;
            border-radius: 10px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }

        .btn-submit:hover {
            background: #159c8c;
        }

        .msg-success {
            background: #e7fbf8;
            color: #0f766e;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .msg-error {
            background: #fdecec;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="wrapper">

	<!-- SIDEBAR -->
    <?php include("therapist_sidebar.php"); ?>

    <div class="content">
        <h1>Create Progress Report</h1>

        <div class="card">
            <h2>Patient Progress Summary</h2>

            <?php if(isset($success)) echo "<div class='msg-success'>$success</div>"; ?>
            <?php if(isset($error)) echo "<div class='msg-error'>$error</div>"; ?>

            <form method="POST">
                <label>Report Updated On</label>
                <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>

                <label>Select Appointment</label>
                <select name="appointment_id" id="appointmentSelect" required onchange="fillPatientData(this)">
                    <option value="">-- Select Appointment --</option>
                    <?php
                    $q = "SELECT appointment_id, patient_id, patient_name, date, time
                          FROM appointment
                          WHERE therapist_id = ?
                          ORDER BY date DESC";
                    $s = $conn->prepare($q);
                    $s->bind_param("i", $therapist_id);
                    $s->execute();
                    $r = $s->get_result();

                    while ($row = $r->fetch_assoc()) {
                        echo "<option 
                                value='{$row['appointment_id']}'
                                data-patient='{$row['patient_id']}'
                                data-name='{$row['patient_name']}'>
                                {$row['patient_name']} — {$row['date']} {$row['time']}
                              </option>";
                    }
                    ?>
                </select>

                <input type="hidden" name="patient_id" id="patient_id">
                <input type="hidden" name="patient_name" id="patient_name">

                <label>Improvement Score (0–10)</label>
                <input type="number" name="improvement_score" min="0" max="10" required>

                <label>Severity</label>
                <select name="severity" required>
                    <option value="Mild">Mild</option>
                    <option value="Moderate">Moderate</option>
                    <option value="Severe">Severe</option>
                </select>

                <label>Summary</label>
                <textarea name="summary" rows="4" required></textarea>

                <button class="btn-submit" type="submit">Submit Report</button>
            </form>

        </div>
    </div>

</div>

<script>
function fillPatientData(select) {
    const option = select.options[select.selectedIndex];
    document.getElementById("patient_id").value = option.getAttribute("data-patient");
    document.getElementById("patient_name").value = option.getAttribute("data-name");
}

// Auto-fill first appointment if exists
window.onload = function() {
    const select = document.getElementById("appointmentSelect");
    if(select.options.length > 1) {
        select.selectedIndex = 1;
        fillPatientData(select);
    }
}
</script>

</body>
</html>

<?php
// appointment.php - Appointments Page
include 'config.php';
include 'functions.php';
redirectIfNotLoggedIn();
$pageTitle = 'Appointments';

$user_role = getUserRole();
$user_id = $_SESSION['user_id'];

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_role === 'patient') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        $doctor_id = $_POST['doctor_id'];
        $date = $_POST['date'];
        $time = $_POST['time'];
        $reason = sanitizeInput($_POST['reason']);

        $stmt = $conn->prepare("INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $user_id, $doctor_id, $date, $time, $reason);
        
        if ($stmt->execute()) {
            $patient_stmt = $conn->prepare("SELECT email, username FROM users WHERE id = ?");
            $patient_stmt->bind_param("i", $user_id);
            $patient_stmt->execute();
            $patient_stmt->bind_result($patient_email, $patient_username);
            $patient_stmt->fetch();
            $patient_stmt->close();

            $doctor_stmt = $conn->prepare("SELECT email, username FROM users WHERE id = ?");
            $doctor_stmt->bind_param("i", $doctor_id);
            $doctor_stmt->execute();
            $doctor_stmt->bind_result($doctor_email, $doctor_username);
            $doctor_stmt->fetch();
            $doctor_stmt->close();

            require 'vendor/autoload.php';
            use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = EMAIL_FROM;
                $mail->Password = EMAIL_PASS;
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom(EMAIL_FROM, 'Med Hub System');
                
                $mail->addAddress($patient_email);
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "<p>Dear $patient_username,</p><p>Your appointment has been booked:</p><ul><li>Date: $date</li><li>Time: $time</li><li>Reason: $reason</li><li>Doctor: $doctor_username</li></ul>";
                $mail->send();
                $mail->clearAddresses();

                $mail->addAddress($doctor_email);
                $mail->Subject = 'New Appointment Notification';
                $mail->Body = "<p>Dear $doctor_username,</p><p>A new appointment has been booked:</p><ul><li>Date: $date</li><li>Time: $time</li><li>Reason: $reason</li><li>Patient: $patient_username</li></ul>";
                $mail->send();

                $success = 'Appointment booked successfully! Confirmation emails sent.';
            } catch (Exception $e) {
                $error = 'Appointment booked, but email sending failed: ' . $mail->ErrorInfo;
            }
        } else {
            $error = 'Failed to book appointment: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$result = null;
if ($user_role === 'patient') {
    $result = $conn->query("SELECT a.*, u.username AS doctor_name FROM appointments a JOIN users u ON a.doctor_id = u.id WHERE patient_id = $user_id");
} elseif ($user_role === 'doctor') {
    $result = $conn->query("SELECT a.*, u.username AS patient_name FROM appointments a JOIN users u ON a.patient_id = u.id WHERE doctor_id = $user_id");
}

$doctors = $conn->query("SELECT id, username FROM users WHERE role = 'doctor'");

include 'header.php';
?>
<?php if ($success): ?>
    <div class="alert alert-success animate__animated animate__bounceIn"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger animate__animated animate__shakeX"><?php echo $error; ?></div>
<?php endif; ?>
<h2 class="animate__animated animate__fadeIn">Appointments</h2>
<?php if ($user_role === 'patient'): ?>
    <h3 class="fade-in-section">Book an Appointment</h3>
    <form method="POST" class="animate__animated animate__fadeIn">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <div class="mb-3">
            <label for="doctor_id" class="form-label">Doctor</label>
            <select class="form-select" id="doctor_id" name="doctor_id" required>
                <option value="">Select Doctor</option>
                <?php while ($doc = $doctors->fetch_assoc()): ?>
                    <option value="<?php echo $doc['id']; ?>"><?php echo sanitizeInput($doc['username']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" class="form-control" id="time" name="time" required>
        </div>
        <div class="mb-3">
            <label for="reason" class="form-label">Reason</label>
            <textarea class="form-control" id="reason" name="reason" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Book Appointment <span class="spinner"></span></button>
    </form>
<?php endif; ?>
<h3 class="fade-in-section">Your Appointments</h3>
<table class="table table-striped animate__animated animate__fadeIn">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo $user_role === 'patient' ? 'Doctor' : 'Patient'; ?></th>
            <th>Date</th>
            <th>Time</th>
            <th>Reason</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result): while ($row = $result->fetch_assoc()): ?>
            <tr class="fade-in-section">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo sanitizeInput($user_role === 'patient' ? $row['doctor_name'] : $row['patient_name']); ?></td>
                <td><?php echo $row['appointment_date']; ?></td>
                <td><?php echo $row['appointment_time']; ?></td>
                <td><?php echo sanitizeInput($row['reason']); ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
        <?php endwhile; endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
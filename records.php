<?php
// records.php - Patient Records Page
include 'config.php';
include 'functions.php';
redirectIfNotLoggedIn();
$pageTitle = 'Patient Records';

$user_role = getUserRole();
$user_id = $_SESSION['user_id'];

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $user_role === 'doctor') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        $patient_id = $_POST['patient_id'];
        $date = $_POST['date'];
        $diagnosis = sanitizeInput($_POST['diagnosis']);
        $prescription = sanitizeInput($_POST['prescription']);
        $notes = sanitizeInput($_POST['notes']);

        $stmt = $conn->prepare("INSERT INTO patient_records (patient_id, doctor_id, record_date, diagnosis, prescription, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iissss", $patient_id, $user_id, $date, $diagnosis, $prescription, $notes);
        if ($stmt->execute()) {
            $success = 'Record added successfully!';
        } else {
            $error = 'Failed to add record: ' . $stmt->error;
        }
        $stmt->close();
    }
}

$result = null;
if ($user_role === 'patient') {
    $result = $conn->query("SELECT r.*, u.username AS doctor_name FROM patient_records r JOIN users u ON r.doctor_id = u.id WHERE patient_id = $user_id");
} elseif ($user_role === 'doctor') {
    $result = $conn->query("SELECT r.*, u.username AS patient_name FROM patient_records r JOIN users u ON r.patient_id = u.id WHERE doctor_id = $user_id");
}

$patients = $user_role === 'doctor' ? $conn->query("SELECT id, username FROM users WHERE role = 'patient'") : null;

include 'header.php';
?>
<?php if ($success): ?>
    <div class="alert alert-success animate__animated animate__bounceIn"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger animate__animated animate__shakeX"><?php echo $error; ?></div>
<?php endif; ?>
<h2 class="animate__animated animate__fadeIn">Patient Records</h2>
<?php if ($user_role === 'doctor'): ?>
    <h3 class="fade-in-section">Add Record</h3>
    <form method="POST" class="animate__animated animate__fadeIn">
        <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
        <div class="mb-3">
            <label for="patient_id" class="form-label">Patient</label>
            <select class="form-select" id="patient_id" name="patient_id" required>
                <option value="">Select Patient</option>
                <?php while ($pat = $patients->fetch_assoc()): ?>
                    <option value="<?php echo $pat['id']; ?>"><?php echo sanitizeInput($pat['username']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" class="form-control" id="date" name="date" required>
        </div>
        <div class="mb-3">
            <label for="diagnosis" class="form-label">Diagnosis</label>
            <textarea class="form-control" id="diagnosis" name="diagnosis" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="prescription" class="form-label">Prescription</label>
            <textarea class="form-control" id="prescription" name="prescription" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="notes" class="form-label">Notes</label>
            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Record <span class="spinner"></span></button>
    </form>
<?php endif; ?>
<h3 class="fade-in-section">Records</h3>
<table class="table table-striped animate__animated animate__fadeIn">
    <thead>
        <tr>
            <th>ID</th>
            <th><?php echo $user_role === 'patient' ? 'Doctor' : 'Patient'; ?></th>
            <th>Date</th>
            <th>Diagnosis</th>
            <th>Prescription</th>
            <th>Notes</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result): while ($row = $result->fetch_assoc()): ?>
            <tr class="fade-in-section">
                <td><?php echo $row['id']; ?></td>
                <td><?php echo sanitizeInput($user_role === 'patient' ? $row['doctor_name'] : $row['patient_name']); ?></td>
                <td><?php echo $row['record_date']; ?></td>
                <td><?php echo sanitizeInput($row['diagnosis']); ?></td>
                <td><?php echo sanitizeInput($row['prescription']); ?></td>
                <td><?php echo sanitizeInput($row['notes']); ?></td>
            </tr>
        <?php endwhile; endif; ?>
    </tbody>
</table>
<?php include 'footer.php'; ?>
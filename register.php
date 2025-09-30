<?php
// register.php - Registration Page
include 'config.php';
include 'functions.php';
$pageTitle = 'Register';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        $username = sanitizeInput($_POST['username']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role = 'patient';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } else {
            $stmt = $conn->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $username, $password, $role, $email);
            if ($stmt->execute()) {
                $success = 'Registration successful! Please login.';
            } else {
                $error = 'Registration failed: ' . $stmt->error;
            }
            $stmt->close();
        }
    }
}

include 'header.php';
?>
<?php if ($success): ?>
    <div class="alert alert-success animate__animated animate__bounceIn"><?php echo $success; ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger animate__animated animate__shakeX"><?php echo $error; ?></div>
<?php endif; ?>
<form method="POST" class="animate__animated animate__fadeIn">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
    <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Register <span class="spinner"></span></button>
</form>
<?php include 'footer.php'; ?>
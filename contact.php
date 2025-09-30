<?php
// contact.php - Contact Page
include 'config.php';
include 'functions.php';
$pageTitle = 'Contact Us';

$error = '';
$success = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        $name = sanitizeInput($_POST['name']);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $message = sanitizeInput($_POST['message']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Invalid email format';
        } else {
            $success = 'Thank you for your message!';
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
<h2 class="animate__animated animate__fadeIn">Contact Us</h2>
<form method="POST" class="animate__animated animate__fadeIn">
    <input type="hidden" name="csrf_token" value="<?php echo generateCsrfToken(); ?>">
    <div class="mb-3">
        <label for="name" class="form-label">Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">Message</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    <button type="submit" class="btn btn-primary">Send <span class="spinner"></span></button>
</form>
<?php include 'footer.php'; ?>
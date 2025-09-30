<?php
// login.php - Login Page
include 'config.php';
include 'functions.php';
$pageTitle = 'Login';

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $error = 'Invalid CSRF token';
    } else {
        $username = sanitizeInput($_POST['username']);
        $password = $_POST['password'];

        $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($id, $hashed_password, $role);
        if ($stmt->fetch() && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            header("Location: index.php");
            exit;
        } else {
            $error = 'Invalid credentials';
        }
        $stmt->close();
    }
}

include 'header.php';
?>
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
        <label for="password" class="form-label">Password</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    <button type="submit" class="btn btn-primary">Login <span class="spinner"></span></button>
</form>
<?php include 'footer.php'; ?>
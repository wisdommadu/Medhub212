<!-- header.php - Common Header with Animation Libraries -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Med Hub - <?php echo isset($pageTitle) ? $pageTitle : 'Home'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#007bff">
</head>
<body>
    <header class="bg-primary text-white text-center py-3 animate__animated animate__fadeInDown">
        <h1>Med Hub</h1>
    </header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark animate__animated animate__fadeIn">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Med Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <?php if (!isLoggedIn()): ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="visitor.php">Visitors</a></li>
                    <?php if (isLoggedIn()): ?>
                        <?php if (getUserRole() === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="admin.php">Admin</a></li>
                        <?php elseif (getUserRole() === 'patient'): ?>
                            <li class="nav-item"><a class="nav-link" href="client.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="appointment.php">Appointments</a></li>
                            <li class="nav-item"><a class="nav-link" href="records.php">Records</a></li>
                        <?php elseif (getUserRole() === 'doctor'): ?>
                            <li class="nav-item"><a class="nav-link" href="appointment.php">Appointments</a></li>
                            <li class="nav-item"><a class="nav-link" href="records.php">Patient Records</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container mt-4 fade-in-section">
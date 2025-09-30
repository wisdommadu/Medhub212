<?php
// client.php - Patient Dashboard
include 'config.php';
include 'functions.php';
redirectIfNotLoggedIn();
redirectIfNotRole('patient');
$pageTitle = 'Patient Dashboard';

include 'header.php';
?>
<h2 class="animate__animated animate__fadeIn">Patient Dashboard</h2>
<div class="row fade-in-section">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Appointments</h5>
                <p>View and book appointments.</p>
                <a href="appointment.php" class="btn btn-primary">Go to Appointments</a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Records</h5>
                <p>Access your medical records.</p>
                <a href="records.php" class="btn btn-primary">Go to Records</a>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
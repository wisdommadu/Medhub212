<?php
// config.php - Database and Email Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'medhub_db');
define('EMAIL_FROM', getenv('EMAIL_FROM') ?: 'your-email@gmail.com');
define('EMAIL_PASS', getenv('EMAIL_PASS') ?: 'your-app-password');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
<?php
// logout.php - Logout Script
include 'functions.php';
session_destroy();
header("Location: index.php");
exit;
?>
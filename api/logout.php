<?php
// File: /api/logout.php
session_start();

// Debug
error_log("Logout initiated for user ID: " . (isset($_SESSION['user']['id']) ? $_SESSION['user']['id'] : 'not logged in'));

// Destroy session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to login page
header("Location: ../public/pages/login.php");
exit;
?>
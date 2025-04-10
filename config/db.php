<?php
// File: /travel-buddy/config/db.php

function loadEnv($filePath) {
    if (!file_exists($filePath)) {
        error_log("Error: .env file not found at $filePath");
        die("Error: .env file not found at $filePath");
    }

    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue; // Skip comments
        list($key, $value) = explode('=', $line, 2);
        putenv(trim($key) . '=' . trim($value));
        // Also set in $_ENV for consistency (optional but helpful)
        $_ENV[trim($key)] = trim($value);
    }
}

// Load the .env file from the root directory
loadEnv(__DIR__ . '/../.env'); // __DIR__ is /travel_buddy/config, so ../ goes to /travel_buddy

// Fetch environment variables with fallbacks
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'travel_buddy';
$username = getenv('DB_USER') ?: 'default_user'; // Replace with a safe default or throw error
$password = getenv('DB_PASS') ?: 'default_password'; // Replace with a safe default or throw error

// Construct DSN
$dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

// Initialize PDO with global scope
global $pdo;
try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    error_log("Database connection established successfully");
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    $pdo = null; // Explicitly set to null on failure
    die("Connection failed: " . $e->getMessage());
}

// Make $pdo available globally
if (!isset($GLOBALS['pdo']) || $GLOBALS['pdo'] === null) {
    $GLOBALS['pdo'] = $pdo;
}
?>
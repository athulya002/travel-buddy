<?php
session_start();
include '../config/db.php'; // From public/api/ to root config/

// Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    die("Not authorized");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $trip_type = $_POST['trip_type'] ?? '';
    $destination = $_POST['destination'] ?? '';
    $travel_date = $_POST['travel_date'] ?? '';
    $budget = $_POST['budget'] ?? '';
    $gender_preference = $_POST['gender_preference'] ?? '';
    $created_by = $_SESSION['user']['id']; // Get logged-in user ID

    // Debugging
    error_log("Received POST data: " . print_r($_POST, true));

    try {
        if ($trip_type === 'solo') {
            $stmt = $pdo->prepare("
                INSERT INTO solo_trips (destination, travel_date, budget, gender_preference, created_by)
                VALUES (:destination, :travel_date, :budget, :gender_preference, :created_by)
            ");
            $stmt->execute([
                ':destination' => $destination,
                ':travel_date' => $travel_date,
                ':budget' => $budget,
                ':gender_preference' => $gender_preference,
                ':created_by' => $created_by
            ]);
        } elseif ($trip_type === 'group') {
            $total_members = $_POST['total_members'] ?? '';
            $stmt = $pdo->prepare("
                INSERT INTO group_trips (destination, travel_date,gender_preference, total_members, created_by,budget)
                VALUES (:destination, :travel_date, :gender_preference, :total_members, :created_by, :budget)
            ");
            $stmt->execute([
                ':destination' => $destination,
                ':travel_date' => $travel_date,
                ':gender_preference' => $gender_preference,
                ':total_members' => $total_members,
                ':created_by' => $created_by,
                ':budget' => $budget
            ]);
        } else {
            throw new Exception("Invalid trip type");
        }

        // Redirect back with success message
        header("Location: ../public/pages/dashboard.php?success=1");
        exit;
    } catch (Exception $e) {
        error_log("Database error in trips.php: " . $e->getMessage());
        header("Location: ../public/pages/create_trip.php?error=1");
        exit;
    }
} else {
    // If not POST, redirect back
    header("Location: ../public/pages/create_trip.php");
    exit;
}

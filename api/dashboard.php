<?php
// File: /api/dashboard.php
// Use an absolute path for the include
require_once __DIR__ . "/../config/db.php"; // Same as in login.php

function fetchUserTrips($user_id) {
    global $pdo;

    // Define success message
    $success_message = '';
    if (isset($_GET['success']) && $_GET['success'] == 1) {
        $success_message = "Trip created successfully!";
    }

    // Initialize $all_trips
    $all_trips = [];

    // Fetch trips safely
    try {
        if (!isset($pdo)) {
            error_log("PDO connection not set in dashboard.php");
            return ['error' => 'Database connection error. Please try again later.'];
        }

        // Fetch solo trips (includes created_at)
        $solo_trips_query = $pdo->prepare("
            SELECT 'Solo' AS trip_type, id, destination, travel_date, budget, gender_preference, NULL AS total_members, created_at
            FROM solo_trips
            WHERE created_by = :user_id
        ");
        $solo_trips_query->execute([':user_id' => $user_id]);
        $solo_trips = $solo_trips_query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Fetch group trips (no created_at column)
        $group_trips_query = $pdo->prepare("
            SELECT 'Group' AS trip_type, id, destination, travel_date, budget, gender_preference, total_members, NULL AS created_at
            FROM group_trips
            WHERE created_by = :user_id
        ");
        $group_trips_query->execute([':user_id' => $user_id]);
        $group_trips = $group_trips_query->fetchAll(PDO::FETCH_ASSOC) ?: [];

        // Combine the trips into a single array
        $all_trips = array_merge($solo_trips, $group_trips);
    } catch (PDOException $e) {
        error_log("Database error in fetchUserTrips: " . $e->getMessage());
        return ['error' => 'Error fetching trips: ' . $e->getMessage()];
    }

    // Make $success_message available to the including file
    $GLOBALS['success_message'] = $success_message;

    return $all_trips;
}
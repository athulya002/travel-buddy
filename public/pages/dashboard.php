<?php
// File: /public/pages/dashboard.php
// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ensure user is logged in
if (!isset($_SESSION['user']['id'])) {
    header("Location: /public/pages/login.php?error=not_logged_in");
    exit;
}

// Include the API to fetch trips
include '../../api/dashboard.php';

// Fetch trips for the logged-in user
$user_id = $_SESSION['user']['id'];
$all_trips = fetchUserTrips($user_id);

// Handle potential errors from the API
if (isset($all_trips['error'])) {
    $error_message = $all_trips['error'];
    $all_trips = [];
} else {
    $error_message = '';
}

include '../includes/navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Travel Buddy</title>
    <link rel="stylesheet" href="../assets/dashboard.css">
</head>
<body>
    <div class="container">
        <h2>Welcome to Your Dashboard</h2>

        <!-- Display success message -->
        <?php if ($success_message): ?>
            <p class="success-message" id="successMessage"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <!-- Display error message if database connection fails -->
        <?php if (isset($error_message) && $error_message): ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <!-- Navigation links -->
        <div class="links">
            <a href="create_trip.php">Create a New Trip</a>
            <a href="../api/logout.php">Logout</a>
        </div>

        <!-- Trips Table -->
        <h3>Your Trips</h3>
        <?php if (count($all_trips) > 0): ?>
            <table id="tripsTable">
                <thead>
                    <tr>
                        <th data-sort="trip_type">Trip Type</th>
                        <th data-sort="destination">Destination</th>
                        <th data-sort="travel_date">Travel Date</th>
                        <th data-sort="budget">Budget</th>
                        <th data-sort="gender_preference">Gender Preference</th>
                        <th data-sort="total_members">Total Members</th>
                        <th data-sort="created_at">Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_trips as $trip): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trip['trip_type']); ?></td>
                            <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                            <td><?php echo htmlspecialchars($trip['travel_date']); ?></td>
                            <td><?php echo htmlspecialchars($trip['budget']); ?></td>
                            <td><?php echo htmlspecialchars($trip['gender_preference']); ?></td>
                            <td><?php echo $trip['total_members'] !== null ? htmlspecialchars($trip['total_members']) : 'N/A'; ?></td>
                            <td><?php echo $trip['created_at'] !== null ? htmlspecialchars($trip['created_at']) : 'N/A'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No trips found. Start by creating a new trip!</p>
        <?php endif; ?>
    </div>

    <script src="../js/dashboard.js"></script>
    <script>
        // Auto-hide the success message after 3 seconds
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000); // Hide after 3 seconds
        }
    </script>
</body>
</html>
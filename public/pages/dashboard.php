<?php
// File: /public/pages/dashboard.php
session_start();
if (!isset($_SESSION['user']['id'])) {
    header("Location: /public/pages/login.php");
    exit;
}

include '../../api/dashboard.php';

$user_id = $_SESSION['user']['id'];
$all_trips = fetchUserTrips($user_id); // Trips created by the user
$joinable_trips = fetchJoinableTrips($user_id);
$pending_requests = fetchPendingJoinRequests($user_id);
$joined_trips = fetchJoinedTrips($user_id); // Trips the user has joined (including pending)

if (isset($all_trips['error'])) {
    $error_message = $all_trips['error'];
    $all_trips = [];
} elseif (isset($joinable_trips['error'])) {
    $error_message = $joinable_trips['error'];
    $joinable_trips = [];
} elseif (isset($pending_requests['error'])) {
    $error_message = $pending_requests['error'];
    $pending_requests = [];
} elseif (isset($joined_trips['error'])) {
    $error_message = $joined_trips['error'];
    $joined_trips = [];
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
        <?php if (isset($_GET['success'])): ?>
            <p class="success-message" id="successMessage"><?php echo htmlspecialchars("Request " . $_GET['success'] . " successfully!"); ?></p>
        <?php endif; ?>

        <!-- Display error message -->
        <?php if ($error_message): ?>
            <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
        <?php elseif (isset($_GET['error'])): ?>
            <p class="error-message">
                <?php
                $error = $_GET['error'];
                $error_messages = [
                    'invalid_trip_type' => 'Invalid trip type.',
                    'already_requested' => 'You have already requested to join this trip.',
                    'join_failed' => 'Failed to join the trip. Please try again.',
                    'invalid_trip' => 'Invalid trip ID.',
                    'unauthorized_action' => 'You are not authorized to perform this action.',
                    'update_failed' => 'Failed to update the request. Please try again.',
                    'solo_trip_limit_exceeded' => 'Cannot approve more than one member for a solo trip.'
                ];
                echo htmlspecialchars($error_messages[$error] ?? 'An unknown error occurred.');
                ?>
            </p>
        <?php endif; ?>

        <!-- Navigation links -->
        <div class="links">
            <a href="create_trip.php">Create a Trip</a>
            <a href="../../api/logout.php">Logout</a>
        </div>

        <!-- Your Trips Section -->
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
                        <th data-sort="created_at">Created At</th>
                        <th>Creator</th>
                        <th>Members</th>
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
                            <td><?php echo isset($trip['created_at']) ? htmlspecialchars($trip['created_at']) : 'N/A'; ?></td>
                            <td>
                                <span class="creator-tag">Creator: <?php echo htmlspecialchars($trip['creator_name']) . ' (' . htmlspecialchars($trip['creator_email']) . ')'; ?></span>
                            </td>
                            <td>
                                <?php foreach ($trip['members'] as $member): ?>
                                    <div>
                                        <?php echo htmlspecialchars($member['name']) . ' (' . htmlspecialchars($member['email']) . ')'; ?>
                                    </div>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No trips found. Start by creating a new trip!</p>
        <?php endif; ?>

        <!-- Joined Trips Section -->
        <h3>Your Joined Trips</h3>
        <?php if (count($joined_trips) > 0): ?>
            <table id="joinedTripsTable">
                <thead>
                    <tr>
                        <th data-sort="trip_type">Trip Type</th>
                        <th data-sort="destination">Destination</th>
                        <th data-sort="travel_date">Travel Date</th>
                        <th data-sort="budget">Budget</th>
                        <th data-sort="gender_preference">Gender Preference</th>
                        <th data-sort="created_at">Created At</th>
                        <th>Creator</th>
                        <th>Members</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joined_trips as $trip): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trip['trip_type']); ?></td>
                            <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                            <td><?php echo htmlspecialchars($trip['travel_date']); ?></td>
                            <td><?php echo htmlspecialchars($trip['budget']); ?></td>
                            <td><?php echo htmlspecialchars($trip['gender_preference']); ?></td>
                            <td><?php echo isset($trip['created_at']) ? htmlspecialchars($trip['created_at']) : 'N/A'; ?></td>
                            <td>
                                <span class="creator-tag">Creator: <?php echo htmlspecialchars($trip['creator_name']) . ' (' . htmlspecialchars($trip['creator_email']) . ')'; ?></span>
                            </td>
                            <td>
                                <?php 
                                $members = $trip['members'] ?? [];
                                foreach ($members as $member): ?>
                                    <div>
                                        <?php echo htmlspecialchars($member['name'] ?? 'Unknown') . ' (' . htmlspecialchars($member['email'] ?? 'Unknown') . ')'; ?>
                                    </div>
                                <?php endforeach; 
                                if (empty($members)) echo '<div>No members yet</div>';
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($trip['status'] ?? 'Unknown'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No trips joined yet (pending or approved).</p>
        <?php endif; ?>

        <!-- Joinable Solo Trips Section -->
        <h3>Joinable Solo Trips</h3>
        <?php if (count($joinable_trips) > 0): ?>
            <table id="joinableTripsTable">
                <thead>
                    <tr>
                        <th>Trip Type</th>
                        <th>Destination</th>
                        <th>Travel Date</th>
                        <th>Budget</th>
                        <th>Gender Preference</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($joinable_trips as $trip): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($trip['trip_type']); ?></td>
                            <td><?php echo htmlspecialchars($trip['destination']); ?></td>
                            <td><?php echo htmlspecialchars($trip['travel_date']); ?></td>
                            <td><?php echo htmlspecialchars($trip['budget']); ?></td>
                            <td><?php echo htmlspecialchars($trip['gender_preference']); ?></td>
                            <td><?php echo htmlspecialchars($trip['created_at']); ?></td>
                            <td>
                                <a href="../../api/join_trip.php?trip_id=<?php echo $trip['id']; ?>&type=solo" class="join-btn">Join</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No joinable solo trips available.</p>
        <?php endif; ?>

        <!-- Pending Join Requests Section -->
        <h3>Pending Join Requests</h3>
        <?php if (count($pending_requests) > 0): ?>
            <table id="pendingRequestsTable">
                <thead>
                    <tr>
                        <th>Trip Destination</th>
                        <th>Travel Date</th>
                        <th>Requester</th>
                        <th>Requested At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_requests as $request): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($request['destination']); ?></td>
                            <td><?php echo htmlspecialchars($request['travel_date']); ?></td>
                            <td><?php echo htmlspecialchars($request['name']); ?></td>
                            <td><?php echo htmlspecialchars($request['joined_at']); ?></td>
                            <td>
                                <a href="../../api/manage_join_request.php?request_id=<?php echo $request['request_id']; ?>&action=approve" class="join-btn">Approve</a>
                                <a href="../../api/manage_join_request.php?request_id=<?php echo $request['request_id']; ?>&action=reject" class="join-btn" style="background-color: #ff4444;">Reject</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No pending join requests.</p>
        <?php endif; ?>
    </div>

    <script src="../js/dashboard.js"></script>
    <script>
        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.display = 'none';
            }, 3000);
        }
    </script>
</body>
</html>
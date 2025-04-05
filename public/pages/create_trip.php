
<?php include '../includes/navbar.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Trip</title>
    <link rel="stylesheet" href="../assets/create_trip.css">
</head>
<body>
<div class="container">
    <div class="section create">
        <img src="../assets/solo-travel.jpg" alt="Create a Trip" class="trip-image">
        <button class="btn" id="openCreateModal">Create a Trip</button>
    </div>

    <div class="section join">
        <img src="../assets/group-travel.jpg" alt="Create a Squad of 4 People" class="trip-image">
        <button class="btn" id="openGroupModal">Create a Squad of 4 People</button>
    </div>
</div>

<!-- Create Trip Modal -->
<div id="createModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeCreateModal">×</span>
        <h2>Create a Trip</h2>
        <form id="soloTripForm" action="../../api/trips.php" method="POST">
            <input type="hidden" name="trip_type" value="solo">
            <label for="createDest">Destination:</label>
            <input type="text" id="createDest" name="destination" required>

            <label for="createDate">Travel Date:</label>
            <input type="date" id="createDate" name="travel_date" required>

            <label for="createBudget">Budget (in USD):</label>
            <input type="number" id="createBudget" name="budget" min="0" step="1" required>

            <label for="createGenderPref">Partner Gender Preference:</label>
            <select id="createGenderPref" name="gender_preference" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="any">Any</option>
            </select>

            <button type="submit">Create Trip</button>
        </form>
    </div>
</div>

<!-- Group Travel Modal -->
<div id="groupModal" class="modal">
    <div class="modal-content">
        <span class="close" id="closeGroupModal">×</span>
        <h2>Create a Squad of 4 People</h2>
        <form id="groupTripForm" action="../../api/trips.php" method="POST">
            <input type="hidden" name="trip_type" value="group">
            <label for="groupDest">Destination:</label>
            <input type="text" id="groupDest" name="destination" required>

            <label for="groupDate">Travel Date:</label>
            <input type="date" id="groupDate" name="travel_date" required>

            <label for="groupBudget">Budget per Person (in USD):</label>
            <input type="number" id="groupBudget" name="budget" min="0" step="1" required>

            <label for="groupGenderPref">Partners' Gender Preference:</label>
            <select id="groupGenderPref" name="gender_preference" required>
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="any">Any</option>
            </select>

            <label for="groupMembers">Total Members:</label>
            <input type="number" id="groupMembers" name="total_members" value="4" readonly>

            <button type="submit">Create Trip</button>
        </form>
    </div>
</div>

<script src="../js/create_trip.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/navbar.css">
</head>

<body>

    <body>
        <nav class="navbar">
            <div class="logo">
                <a href="#" data-page="dashboard" class="nav-link">Travel Buddy</a>
            </div>
            <ul class="nav-links">
                <li><a href="dashboard.php" class="nav-link" data-page="dashboard">Dashboard</a></li>
                <li><a href="matches.php" class="nav-link" data-page="matches">Matches</a></li>
                <li><a href="requests.php" class="nav-link" data-page="requests">Requests</a></li>
                <li><a href="create_trip.php" class="nav-link" data-page="create_trip">Create Trip</a></li>
                <?php if (isset($_SESSION['user'])): ?>
                    <li><a href="../logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="../pages/login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>


    </body>
</body>

</html>
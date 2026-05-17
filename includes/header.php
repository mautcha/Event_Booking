<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/site.css">
    <title>Event Booking System</title>
</head>
<body>
<div class="container">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.gif" width="50" height="40" alt="Logo">
        </a>
        <div class="collapse navbar-collapse">
<ul class="navbar-nav mr-auto">
    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
    
    <?php if (isset($_SESSION['userID'])): ?>
        <!-- Show these ONLY if the user is logged in -->
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Logout</a></li>
    <?php endif; ?>
</ul>
        </div>
    </nav>


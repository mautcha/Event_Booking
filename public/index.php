<?php
    session_start();
    include '../config/connect.php';
    require_once '../includes/header.php';
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>INDEX PAGE</h2></p>
    </center>
</div>

<div>
    <?php if (isset($_SESSION['userID'])): ?>
        <!-- Show this if they are already logged in -->
        <button><a href="dashboard.php">GO TO DASHBOARD</a></button>
    <?php else: ?>
        <!-- Show these if they are logged out -->
        <button><a href="register.php">REGISTER NEW USER</a></button><br>
        <button><a href="login.php">LOGIN</a></button>
    <?php endif; ?>
</div>

<?php require_once '../includes/footer.php'; ?>

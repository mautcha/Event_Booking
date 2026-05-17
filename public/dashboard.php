<?php
session_start();
if (!isset($_SESSION['userID'])) {
    header("Location: login.php");
    exit();
}
require_once '../includes/header.php';
?>
<div class="mt-5">
    <h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?>!</h2>
    <p>You are logged in as a <strong><?= strtoupper($_SESSION['role']); ?></strong>.</p>
    
    <div class="row mt-4">
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <div class="col-md-4">
                <a href="manage_events.php" class="btn btn-lg btn-success btn-block">Manage Events</a>
            </div>
            <div class="col-md-4">
                <a href="manage_admins.php" class="btn btn-lg btn-warning btn-block">Manage Admins</a>
            </div>
        <?php elseif ($_SESSION['role'] === 'student'): ?>
            <div class="col-md-4">
                <a href="book_event.php" class="btn btn-lg btn-primary btn-block">Browse Events</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once '../includes/footer.php'; ?>

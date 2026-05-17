<?php
session_start();
include '../config/connect.php';
require_once '../includes/header.php';

// Security check: Only students should access this view
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

try {
    // Get all events from the database, sorted by date
    $stmt = $db->query("SELECT * FROM Events ORDER BY eventDate ASC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Upcoming Event List</h2></p>
    </center>
</div>

<div>
    <?php if (count($events) > 0): ?>
        <table border="1" cellpadding="10" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
            <tr style="background-color: #f2f2f2;">
                <th>Event Name</th>
                <th>Date & Time</th>
                <th>Max Capacity</th>
            </tr>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($event['name']) ?></strong></td>
                    <!-- Showing both Date and Time clearly -->
                    <td><?= date('F j, Y - g:i a', strtotime($event['eventDate'])) ?></td>
                    <td><?= htmlspecialchars($event['maxCapacity']) ?> attendees</td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="margin-top: 20px;">No events are currently scheduled. Please check back later!</p>
    <?php endif; ?>
    
    <br>
    <button><a href="dashboard.php" style="text-decoration:none;">Back to Dashboard</a></button>
</div>

<?php require_once '../includes/footer.php'; ?>

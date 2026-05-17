<?php
session_start();
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') { header("Location: dashboard.php"); exit(); }
require_once '../config/connect.php';
require_once '../models/Event.php';

$eventModel = new Event($db);

if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM EVENTS WHERE eventID = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: manage_events.php");
    exit();
}

$events = $eventModel->readAll();
require_once '../includes/header.php';
?>
<div class="mt-4">
    <h2>Manage Events</h2>
    <a href="create_event.php" class="btn btn-primary mb-3">Add New Event</a>
    <table class="table table-bordered">
        <thead class="thead-dark">
            <tr><th>Title</th><th>Date</th><th>Capacity</th><th>Seats</th><th>Actions</th></tr>
        </thead>
        <tbody>
            <?php foreach ($events as $e): ?>
            <tr>
                <td><?= htmlspecialchars($e['name']) ?></td>
                <td><?= htmlspecialchars($e['eventDate']) ?></td>
                <td><?= htmlspecialchars($e['maxCapacity']) ?></td>
                <td><a href="manage_seats.php?event_id=<?= $e['eventID'] ?>" class="btn btn-sm btn-info">View Seats</a></td>
                <td>
                    <a href="edit_event.php?id=<?= $e['eventID'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="manage_events.php?delete=<?= $e['eventID'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Cancel this event?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>

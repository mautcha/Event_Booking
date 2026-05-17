<?php
session_start();
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') { header("Location: dashboard.php"); exit(); }
require_once '../config/connect.php';
require_once '../models/Seat.php';

$seatModel = new Seat($db);
$eventID = $_GET['event_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seatModel->updateStatus($_POST['seatID'], $_POST['status']);
    header("Location: manage_seats.php?event_id=" . $eventID);
    exit();
}

$seats = $seatModel->getSeatsByEvent($eventID);
require_once '../includes/header.php';
?>
<div class="mt-4">
    <h2>Manage Seats for Event #<?= htmlspecialchars($eventID) ?></h2>
    <a href="manage_events.php" class="btn btn-secondary mb-3">Back to Events</a>
    
    <table class="table table-bordered">
        <thead class="thead-dark"><tr><th>Seat Number</th><th>Priority</th><th>Status</th><th>Update Status</th></tr></thead>
        <tbody>
            <?php foreach ($seats as $seat): ?>
            <tr>
                <td><?= htmlspecialchars($seat['seatNumber']) ?></td>
                <td><?= $seat['isPriority'] ? 'Yes' : 'No' ?></td>
                <td><span class="badge badge-<?= $seat['status'] == 'available' ? 'success' : 'danger' ?>"><?= htmlspecialchars($seat['status']) ?></span></td>
                <td>
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="seatID" value="<?= $seat['seatID'] ?>">
                        <select name="status" class="form-control form-control-sm mr-2">
                            <option value="available" <?= $seat['status'] == 'available' ? 'selected' : '' ?>>Available</option>
                            <option value="reserved" <?= $seat['status'] == 'reserved' ? 'selected' : '' ?>>Reserved</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once '../includes/footer.php'; ?>

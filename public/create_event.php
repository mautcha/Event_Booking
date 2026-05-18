<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../config/connect.php';
include '../models/Event.php';
require_once '../includes/header.php';

// Security check
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

$messageText = "";
$messageType = "";

// Process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventModel = new Event($db);
    
    $stmt = $db->prepare("SELECT adminID FROM Admin WHERE userID = ?");
    $stmt->execute([$_SESSION['userID']]);
    $adminRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($adminRow) {
        $adminID = $adminRow['adminID'];
        $formattedDate = str_replace('T', ' ', $_POST['eventDate']);
        
        if ($eventModel->create($adminID, $_POST['name'], $formattedDate, $_POST['maxCapacity'])) {
            $messageText = "New Event Created Successfully!";
            $messageType = "success";
        } else {
            $messageText = "Error: Database failed to record the event transaction.";
            $messageType = "danger";
        }
    } else {
        $messageText = "Error: Could not find your Admin profile in the database.";
        $messageType = "danger";
    }
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Create New Event</h2></p>
    </center>
</div>

<div class="container mt-3" style="max-width: 600px;">
    <?php if (!empty($messageText)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($messageText) ?>
            <?php if ($messageType === 'success'): ?>
                <br><a href="manage_events.php" class="alert-link">Click here to go back to Manage Events</a>
            <?php endif; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<div>
    <form method="post">
        <pre>
            Event Name:      <input type="text" name="name" required>
            Date & Time:     <input type="datetime-local" name="eventDate" required>
            Max Capacity:    <input type="number" name="maxCapacity" required>
            
            <input type="submit" value="Save Event"> <button type="button" onclick="window.location.href='manage_events.php'">Cancel</button>
        </pre>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

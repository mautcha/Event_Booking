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

$eventID = $_GET['id'] ?? null;
if (!$eventID) {
    echo "<script>window.location.href='manage_events.php';</script>";
    exit();
}

$eventModel = new Event($db);

// --- UPDATE LOGIC ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CRITICAL FIX: Convert '2026-05-07T13:00' to '2026-05-07 13:00:00'
    $rawDate = $_POST['eventDate'];
    $formattedDate = date('Y-m-d H:i:s', strtotime($rawDate));
    
    $name = $_POST['name'];
    $maxCapacity = $_POST['maxCapacity'];

    if ($eventModel->update($eventID, $name, $formattedDate, $maxCapacity)) {
        echo "<script>
                alert('Event updated successfully!');
                window.location.href = 'manage_events.php';
              </script>";
    } else {
        echo "<div style='color:red;'>Error: Could not update the event in the database.</div>";
    }
}

// --- FETCH LOGIC ---
$event = $eventModel->readOne($eventID);
if (!$event) {
    die("Event not found.");
}

// Prepare the date for the HTML input (needs the 'T')
$dateForInput = date('Y-m-d\TH:i', strtotime($event['eventDate']));
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Edit Event Details</h2></p>
    </center>
</div>

<div style="margin: 20px;">
    <form method="POST">
        <table border="0" cellpadding="5">
            <tr>
                <td>Event Name:</td>
                <td><input type="text" name="name" value="<?= htmlspecialchars($event['name']) ?>" required></td>
            </tr>
            <tr>
                <td>Date & Time:</td>
                <td><input type="datetime-local" name="eventDate" value="<?= $dateForInput ?>" required></td>
            </tr>
            <tr>
                <td>Capacity:</td>
                <td><input type="number" name="maxCapacity" value="<?= htmlspecialchars($event['maxCapacity']) ?>" required></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <br>
                    <input type="submit" value="Update Event" style="cursor:pointer; padding: 5px 15px;">
                    <a href="manage_events.php" style="margin-left:10px;">Cancel</a>
                </td>
            </tr>
        </table>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

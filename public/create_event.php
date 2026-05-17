<?php
session_start();
// Turn on error reporting
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

// Process the form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $eventModel = new Event($db);
    
    // THE FIX: Look up the correct adminID using your logged-in userID
    $stmt = $db->prepare("SELECT adminID FROM Admin WHERE userID = ?");
    $stmt->execute([$_SESSION['userID']]);
    $adminRow = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($adminRow) {
        $adminID = $adminRow['adminID']; // We found the real admin ID!
        
        // Format the date string so MySQL accepts it
        $formattedDate = str_replace('T', ' ', $_POST['eventDate']);
        
        // Save the event using the correct adminID
        if ($eventModel->create($adminID, $_POST['name'], $formattedDate, $_POST['maxCapacity'])) {
            echo "<script language='javascript'>
                    alert('New Event Created Successfully!');
                    window.location.href = 'manage_events.php';
                  </script>";
        }
    } else {
        echo "<script language='javascript'>
                alert('Error: Could not find your Admin profile in the database.');
              </script>";
    }
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Create New Event</h2></p>
    </center>
</div>

<div>
    <form method="post">
        <pre>
            Event Name:      <input type="text" name="name" required>
            Date & Time:     <input type="datetime-local" name="eventDate" required>
            Max Capacity:    <input type="number" name="maxCapacity" required>
            
            <input type="submit" value="Save Event">
        </pre>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../config/connect.php';
require_once '../includes/header.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

$messageText = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $position = $_POST['position'];
    $department = $_POST['department'];

    try {
        $db->beginTransaction();

        $stmt1 = $db->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
        $stmt1->execute([$name, $email, $password]);
        
        $newUserID = $db->lastInsertId();

        $stmt2 = $db->prepare("INSERT INTO Admin (userID, position, department) VALUES (?, ?, ?)");
        $stmt2->execute([$newUserID, $position, $department]);

        $db->commit();

        $messageText = "New Admin Created Successfully! You can see them in management now.";
        $messageType = "success";
    } catch (Exception $e) {
        $db->rollBack();
        $messageText = "Error creating admin: " . $e->getMessage();
        $messageType = "danger";
    }
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Add New Administrator</h2></p>
    </center>
</div>

<div class="container mt-3" style="max-width: 600px;">
    <?php if (!empty($messageText)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($messageText) ?>
            <?php if ($messageType === 'success'): ?>
                <br><a href="manage_admins.php" class="alert-link">Click here to go back to Manage Admins</a>
            <?php endif; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
</div>

<div style="position: relative; z-index: 10; margin: 20px;">
    <form method="POST">
        <pre style="font-family: monospace; font-size: 14px;">
Full Name:       <input type="text" name="name" required><br>
Email Address:   <input type="email" name="email" required><br>
Password:        <input type="password" name="password" required><br>
Position:        <input type="text" name="position" placeholder="e.g. Dean" required><br>
Department:      <input type="text" name="department" placeholder="e.g. CCS" required><br>
            
                 <input type="submit" value="Register Admin"> <button type="button" onclick="window.location.href='manage_admins.php'">Cancel</button>
        </pre>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

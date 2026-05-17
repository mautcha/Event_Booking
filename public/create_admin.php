<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../config/connect.php';
require_once '../includes/header.php';

// Security check: Only admins can create other admins
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

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

        echo "<script>alert('New Admin Created Successfully!'); window.location.href='manage_admins.php';</script>";
        exit();
    } catch (Exception $e) {
        $db->rollBack();
        die("Error creating admin: " . $e->getMessage());
    }
}
?>

<div style='background-color:#ffff00; padding: 10px; margin-bottom: 20px;'>
    <center>
        <h2 style="color:white; margin:0;">Add New Administrator</h2>
    </center>
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

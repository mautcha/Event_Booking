<?php
// public/setup_admin.php
require_once '../config/connect.php';

$name = "Super Admin";
$email = "admin@cituniversity.edu";
$password = "admin123";
$position = "Dean";
$department = "College of Computer Studies";

try {
    $db->beginTransaction();
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt1 = $db->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, 'admin')");
    $stmt1->execute([$name, $email, $hashed_password]);
    $userID = $db->lastInsertId();

    $stmt2 = $db->prepare("INSERT INTO Admin (userID, position, department) VALUES (?, ?, ?)");
    $stmt2->execute([$userID, $position, $department]);

    $db->commit();
    echo "<h1>Success!</h1>";
    echo "<p>First admin created. You can now <a href='login.php'>log in here</a>.</p>";
    echo "<p><strong>Email:</strong> " . $email . "</p>";
    echo "<p><strong>Password:</strong> " . $password . "</p>";
    
} catch (Exception $e) {
    die("Error setting up admin: " . $e->getMessage());
}
?>

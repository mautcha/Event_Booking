<?php
session_start();
include '../config/connect.php';

if (isset($_GET['delete_id']) && $_SESSION['role'] === 'admin') {
    try {
        $userID = $_GET['delete_id'];
        // Deleting from Users table cascades to the Admin table automatically
        $stmt = $db->prepare("DELETE FROM Users WHERE userID = ?");
        $stmt->execute([$userID]);
        
        echo "<script>alert('Administrator removed successfully.'); window.location.href='manage_admins.php';</script>";
        exit();
    } catch (Exception $e) {
        die("Delete Error: " . $e->getMessage());
    }
}

require_once '../includes/header.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

try {
    $query = "SELECT u.userID, u.name, u.email, a.position, a.department 
              FROM Users u 
              JOIN Admin a ON u.userID = a.userID 
              WHERE u.role = 'admin' 
              ORDER BY u.name ASC";
    $stmt = $db->query($query);
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Manage Administrators</h2></p>
    </center>
</div>

<div style="margin: 20px;">
    <button><a href="create_admin.php" style="text-decoration:none;">+ ADD NEW ADMIN</a></button>
    <br><br>

    <?php if (count($admins) > 0): ?>
        <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%;">
            <tr style="background-color: #f2f2f2;">
                <th>Name</th>
                <th>Email</th>
                <th>Position</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin['name']) ?></td>
                    <td><?= htmlspecialchars($admin['email']) ?></td>
                    <td><?= htmlspecialchars($admin['position']) ?></td>
                    <td><?= htmlspecialchars($admin['department']) ?></td>
                    <td>
                        <a href="edit_admin.php?id=<?= $admin['userID'] ?>">Edit</a> |
                        <!-- NOTICE: We changed the link to point to this same page with delete_id -->
                        <a href="manage_admins.php?delete_id=<?= $admin['userID'] ?>"
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No other administrators found.</p>
    <?php endif; ?>
    
    <br>
    <button><a href="dashboard.php" style="text-decoration:none;">Back to Dashboard</a></button>
</div>

<?php require_once '../includes/footer.php'; ?>

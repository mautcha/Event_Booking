<?php
// public/edit_admin.php
session_start();
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require_once '../config/connect.php';

$adminID = $_GET['id'] ?? null;
if (!$adminID) {
    header("Location: manage_admins.php");
    exit();
}

// 1. Handle the UPDATE form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $db->beginTransaction();
        
        // Update USER table
        $stmt1 = $db->prepare("UPDATE USERS SET name = ?, email = ? WHERE userID = ?");
        $stmt1->execute([$_POST['name'], $_POST['email'], $adminID]);
        
        // Update ADMIN table
        $stmt2 = $db->prepare("UPDATE ADMIN SET position = ?, department = ? WHERE userID = ?");
        $stmt2->execute([$_POST['position'], $_POST['department'], $adminID]);
        
        $db->commit();
        header("Location: manage_admins.php");
        exit();
    } catch (Exception $e) {
        $db->rollBack();
        $error = "Failed to update admin.";
    }
}

// 2. Fetch current data to populate the form
$query = "SELECT u.name, u.email, a.position, a.department FROM USERS u JOIN ADMIN a ON u.userID = a.userID WHERE u.userID = ?";
$stmt = $db->prepare($query);
$stmt->execute([$adminID]);
$admin = $stmt->fetch(PDO::FETCH_ASSOC);

require_once '../includes/header.php';
?>

<div class="col-md-6 mt-4">
    <h2>Edit Administrator</h2>
    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    
    <form action="edit_admin.php?id=<?= $adminID ?>" method="POST" class="card p-4">
        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($admin['name']) ?>" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($admin['email']) ?>" required>
        </div>
        <div class="form-group">
            <label>Position</label>
            <input type="text" name="position" class="form-control" value="<?= htmlspecialchars($admin['position']) ?>" required>
        </div>
        <div class="form-group">
            <label>Department</label>
            <input type="text" name="department" class="form-control" value="<?= htmlspecialchars($admin['department']) ?>" required>
        </div>
        
        <div class="mt-3">
            <button type="submit" class="btn btn-primary">Update Admin</button>
            <a href="manage_admins.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once '../includes/footer.php'; ?>

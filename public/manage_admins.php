<?php
session_start();
include '../config/connect.php';

// Security check: Guard workspace against unauthorized visitors
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

$messageText = "";
$messageType = "";

// 1. HANDLE ADMIN REMOVAL LOGIC
if (isset($_GET['delete_id'])) {
    try {
        $userID = $_GET['delete_id'];
        
        // Deleting from Users table cascades to the Admin table automatically
        $stmt = $db->prepare("DELETE FROM Users WHERE userID = ?");
        $stmt->execute([$userID]);
        
        $messageText = "Administrator account successfully removed from system registries.";
        $messageType = "warning";
    } catch (Exception $e) {
        $messageText = "Delete operational error: " . $e->getMessage();
        $messageType = "danger";
    }
}

// 2. CONFIGURE PAGINATION CONTROLS (Fulfills strict 1-15 records threshold)
$perPage = 15;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $perPage;

try {
    // A. Count total administrators to determine the total number of pages
    $countQuery = "SELECT COUNT(*) as total FROM Users WHERE role = 'admin'";
    $countStmt = $db->query($countQuery);
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $perPage);

    // B. Fetch only the 15 records needed for the current active page
    $query = "SELECT u.userID, u.name, u.email, a.position, a.department 
              FROM Users u 
              JOIN Admin a ON u.userID = a.userID 
              WHERE u.role = 'admin' 
              ORDER BY u.name ASC
              LIMIT :limit OFFSET :offset";
              
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

require_once '../includes/header.php';
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Manage Administrators</h2></p>
    </center>
</div>

<div style="margin: 20px;">
    
    <?php if (!empty($messageText)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show mb-4" role="alert" style="max-width: 100%;">
            <?= htmlspecialchars($messageText) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

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
                        <a href="manage_admins.php?delete_id=<?= $admin['userID'] ?>&page=<?= $currentPage ?>"
                           onclick="return confirm('Are you sure you want to permanently delete this administrator profile?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="manage_admins.php?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $currentPage === $i ? 'active' : '' ?>">
                            <a class="page-link" href="manage_admins.php?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="manage_admins.php?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <p>No other administrators found on this page index.</p>
    <?php endif; ?>
    
    <br>
    <button><a href="dashboard.php" style="text-decoration:none;">Back to Dashboard</a></button>
</div>

<?php require_once '../includes/footer.php'; ?>

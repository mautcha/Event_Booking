<?php
session_start();
include '../config/connect.php';
require_once '../includes/header.php';

if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'student') {
    echo "<script>window.location.href='dashboard.php';</script>";
    exit();
}

$perPage = 15;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $perPage;

try {
    $countQuery = "SELECT COUNT(*) as total FROM Events WHERE status = 'active'";
    $countStmt = $db->query($countQuery);
    $totalRecords = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
    $totalPages = ceil($totalRecords / $perPage);

    $query = "SELECT * FROM Events 
              WHERE status = 'active' 
              ORDER BY eventDate ASC 
              LIMIT :limit OFFSET :offset";
              
    $stmt = $db->prepare($query);
    $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Upcoming Event List</h2></p>
    </center>
</div>

<div style="margin: 20px;">
    <?php if (count($events) > 0): ?>
        <table border="1" cellpadding="10" style="margin-top: 20px; width: 100%; border-collapse: collapse;">
            <tr style="background-color: #f2f2f2;">
                <th>Event Name</th>
                <th>Date & Time</th>
                <th>Max Capacity</th>
            </tr>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($event['name']) ?></strong></td>
                    <td><?= date('F j, Y - g:i a', strtotime($event['eventDate'])) ?></td>
                    <td><?= htmlspecialchars($event['maxCapacity']) ?> attendees</td>
                </tr>
            <?php endforeach; ?>
        </table>

        <?php if ($totalPages > 1): ?>
            <nav aria-label="Student catalog page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="book_event.php?page=<?= $currentPage - 1 ?>">Previous</a>
                    </li>
                    
                    <?php for($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $currentPage === $i ? 'active' : '' ?>">
                            <a class="page-link" href="book_event.php?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="book_event.php?page=<?= $currentPage + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    <?php else: ?>
        <p style="margin-top: 20px; color: #666;">No active events are currently scheduled on this page index. Please check back later!</p>
    <?php endif; ?>
    
    <br>
    <button><a href="dashboard.php" style="text-decoration:none;">Back to Dashboard</a></button>
</div>

<?php require_once '../includes/footer.php'; ?>

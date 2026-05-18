<?php
session_start();
if (!isset($_SESSION['userID']) || $_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
require_once '../config/connect.php';
require_once '../models/Event.php';

$eventModel = new Event($db);
$message = "";
$messageType = "";

if (isset($_GET['confirm_action']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $action = $_GET['confirm_action'];
    
    if ($action === 'deactivate') {
        $eventModel->toggleStatus($id, 'inactive');
        $message = "Event successfully deactivated and archived.";
        $messageType = "warning";
    } elseif ($action === 'activate') {
        $eventModel->toggleStatus($id, 'active');
        $message = "Event successfully restored to active status!";
        $messageType = "success";
    }
}

$currentTab = $_GET['tab'] ?? 'active';
$perPage = 15;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $perPage;

$events = $eventModel->readPaginated($currentTab, $perPage, $offset);
$totalRecords = $eventModel->countTotalByStatus($currentTab);
$totalPages = ceil($totalRecords / $perPage);

require_once '../includes/header.php';
?>

<div class="mt-4">
<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>Manage Events Dashboard Workspace</h2></p>
    </center>
</div>
    
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message) ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['action']) && isset($_GET['id'])): ?>
        <?php
            $reqAction = $_GET['action'];
            $reqId = $_GET['id'];
            $alertClass = ($reqAction === 'deactivate') ? 'alert-warning' : 'alert-info';
        ?>
        <div class="alert <?= $alertClass ?> border p-3 mb-4" role="alert">
            <h4 class="alert-heading">📌 Action Required: Confirm Status Modification</h4>
            <p>Are you sure you want to change the tracking status flag of this event listing to <strong><?= strtoupper($reqAction) ?></strong>?</p>
            <hr>
            <div class="d-flex">
                <a href="manage_events.php?tab=<?= $currentTab ?>&confirm_action=<?= $reqAction ?>&id=<?= $reqId ?>&page=<?= $currentPage ?>" class="btn btn-sm <?= ($reqAction === 'deactivate') ? 'btn-danger' : 'btn-success' ?> mr-2">
                    Yes, Proceed
                </a>
                <a href="manage_events.php?tab=<?= $currentTab ?>&page=<?= $currentPage ?>" class="btn btn-sm btn-secondary">
                    Cancel Action
                </a>
            </div>
        </div>
    <?php endif; ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <ul class="nav nav-tabs card-header-tabs m-0">
            <li class="nav-item">
                <a class="nav-link <?= $currentTab === 'active' ? 'active font-weight-bold' : '' ?>" href="manage_events.php?tab=active">Active Events</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $currentTab === 'inactive' ? 'active font-weight-bold' : '' ?>" href="manage_events.php?tab=inactive">Archived / Inactive</a>
            </li>
        </ul>
        <a href="create_event.php" class="btn btn-primary">+ Add New Event</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>Title Name</th>
                <th>Scheduled Date & Time</th>
                <th>Max Target Capacity</th>
                <th>Seat Mapping</th>
                <th>Actions Workspace</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($events) > 0): ?>
                <?php foreach ($events as $e): ?>
                <tr <?= (isset($_GET['id']) && $_GET['id'] == $e['eventID']) ? 'style="background-color: #fff3cd;"' : '' ?>>
                    <td><?= htmlspecialchars($e['name']) ?></td>
                    <td><?= htmlspecialchars($e['eventDate']) ?></td>
                    <td><?= htmlspecialchars($e['maxCapacity']) ?> attendees</td>
                    <td>
                        <a href="manage_seats.php?event_id=<?= $e['eventID'] ?>" class="btn btn-sm btn-info">View Seats</a>
                    </td>
                    <td>
                        <a href="edit_event.php?id=<?= $e['eventID'] ?>" class="btn btn-sm btn-warning">Edit Details</a>
                        
                        <?php if ($currentTab === 'active'): ?>
                            <a href="manage_events.php?tab=active&action=deactivate&id=<?= $e['eventID'] ?>&page=<?= $currentPage ?>" class="btn btn-sm btn-danger">Deactivate</a>
                        <?php else: ?>
                            <a href="manage_events.php?tab=inactive&action=activate&id=<?= $e['eventID'] ?>&page=<?= $currentPage ?>" class="btn btn-sm btn-success">Reactivate</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">No records found matching this category context.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
        <nav aria-label="Page navigation navigation-workspace">
            <ul class="pagination justify-content-center">
                <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                    <a class="page-item page-link" href="manage_events.php?tab=<?= $currentTab ?>&page=<?= $currentPage - 1 ?>">Previous</a>
                </li>
                
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?= $currentPage === $i ? 'active' : '' ?>">
                        <a class="page-link" href="manage_events.php?tab=<?= $currentTab ?>&page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $currentPage >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="manage_events.php?tab=<?= $currentTab ?>&page=<?= $currentPage + 1 ?>">Next</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    <br>
    <button><a href="dashboard.php" style="text-decoration:none;">Back to Dashboard</a></button>
</div>

<?php require_once '../includes/footer.php'; ?>

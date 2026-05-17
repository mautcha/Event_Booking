<?php
// models/Event.php
class Event {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE (Defaults to 'active')
    public function create($adminID, $name, $eventDate, $maxCapacity) {
        try {
            // Added status column defaulting to 'active'
            $query = "INSERT INTO Events (adminID, name, eventDate, maxCapacity, status) VALUES (?, ?, ?, ?, 'active')";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$adminID, $name, $eventDate, $maxCapacity]);
        } catch (Exception $e) {
            die("Create Event Error: " . $e->getMessage());
        }
    }

    // READ PAGINATED DATA (Filters by Status and applies 1-15 Limit)
    public function readPaginated($status, $limit = 15, $offset = 0) {
        try {
            $query = "SELECT * FROM Events WHERE status = ? ORDER BY eventDate ASC LIMIT ? OFFSET ?";
            $stmt = $this->conn->prepare($query);
            // Bind integers correctly for MySQL LIMIT/OFFSET requirements
            $stmt->bindValue(1, $status, PDO::PARAM_STR);
            $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Read Paginated Events Error: " . $e->getMessage());
        }
    }

    // COUNT TOTALS (Needed to calculate how many pages exist)
    public function countTotalByStatus($status) {
        try {
            $query = "SELECT COUNT(*) as total FROM Events WHERE status = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$status]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (Exception $e) {
            return 0;
        }
    }

    // READ (One)
    public function readOne($eventID) {
        try {
            $query = "SELECT * FROM Events WHERE eventID = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$eventID]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Read Single Event Error: " . $e->getMessage());
        }
    }

    // UPDATE
    public function update($eventID, $name, $eventDate, $maxCapacity) {
        try {
            $query = "UPDATE Events SET name = ?, eventDate = ?, maxCapacity = ? WHERE eventID = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$name, $eventDate, $maxCapacity, $eventID]);
        } catch (Exception $e) {
            die("Update Event Error: " . $e->getMessage());
        }
    }

    // TOGGLE STATUS FLAG (Active/Inactive Toggling instead of hard Delete)
    public function toggleStatus($eventID, $newStatus) {
        try {
            $query = "UPDATE Events SET status = ? WHERE eventID = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$newStatus, $eventID]);
        } catch (Exception $e) {
            die("Toggle Status Error: " . $e->getMessage());
        }
    }
}
?>

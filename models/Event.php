<?php
class Event {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // CREATE
    public function create($adminID, $name, $eventDate, $maxCapacity) {
        try {
            $query = "INSERT INTO Events (adminID, name, eventDate, maxCapacity) VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$adminID, $name, $eventDate, $maxCapacity]);
        } catch (Exception $e) {
            die("Create Event Error: " . $e->getMessage());
        }
    }

    // READ (All)
    public function readAll() {
        try {
            $query = "SELECT * FROM Events ORDER BY eventDate ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            die("Read Events Error: " . $e->getMessage());
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

    // DELETE
    public function delete($eventID) {
        try {
            $query = "DELETE FROM Events WHERE eventID = ?";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([$eventID]);
        } catch (Exception $e) {
            die("Delete Event Error: " . $e->getMessage());
        }
    }
}
?>

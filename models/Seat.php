<?php
class Seat {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function getSeatsByEvent($eventID) {
        $query = "SELECT * FROM SEAT WHERE eventID = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$eventID]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($seatID, $status) {
        $query = "UPDATE SEAT SET status = ? WHERE seatID = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $seatID]);
    }
}
?>

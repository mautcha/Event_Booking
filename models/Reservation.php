<?php
class Reservation {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function bookSeat($userID, $seatID) {
        try {
            $this->conn->beginTransaction();

            $query1 = "INSERT INTO RESERVATION (userID, seatID, timestamp, status) VALUES (?, ?, NOW(), 'confirmed')";
            $stmt1 = $this->conn->prepare($query1);
            $stmt1->execute([$userID, $seatID]);

            $query2 = "UPDATE SEAT SET status = 'reserved' WHERE seatID = ?";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->execute([$seatID]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>

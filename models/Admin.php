<?php
class Admin {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function create($name, $email, $password, $position, $department) {
        try {
            $this->conn->beginTransaction();
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt1 = $this->conn->prepare("INSERT INTO USER (name, email, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt1->execute([$name, $email, $hashed]);
            $userID = $this->conn->lastInsertId();

            $stmt2 = $this->conn->prepare("INSERT INTO ADMIN (userID, position, department) VALUES (?, ?, ?)");
            $stmt2->execute([$userID, $position, $department]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }
}
?>

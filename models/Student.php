<?php
class Student {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $email, $password, $program, $yearLevel) {
        try {
            $this->conn->beginTransaction();
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert into Users
            $stmt1 = $this->conn->prepare("INSERT INTO Users (name, email, password, role) VALUES (?, ?, ?, 'student')");
            $stmt1->execute([$name, $email, $hashed]);
            $userID = $this->conn->lastInsertId();

            // Insert into Student
            $stmt2 = $this->conn->prepare("INSERT INTO Student (userID, program, yearLevel) VALUES (?, ?, ?)");
            $stmt2->execute([$userID, $program, $yearLevel]);

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // This will print the exact MySQL error to the screen!
            die("Database Error: " . $e->getMessage());
        }
    }
}
?>

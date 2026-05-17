<?php
class User {
    protected $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        try {
            $query = "SELECT * FROM Users WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if ($user && password_verify($password, $user['password'])) {
                return $user; // Returns userID, name, email, role
            }
            return false;
            
        } catch (Exception $e) {
            die("Login Database Error: " . $e->getMessage());
        }
    }
}
?>

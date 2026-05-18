class Admin {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readOne($adminID) {
        // Query joins both user and admin fields assuming a split table structure
        $query = "SELECT u.name, u.email, a.position, a.department 
                  FROM USERS u 
                  JOIN ADMIN a ON u.userID = a.userID 
                  WHERE u.userID = ? LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$adminID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($adminID, $name, $email, $position, $department) {
            try {
                $stmt1 = $this->conn->prepare("UPDATE USERS SET name = ?, email = ? WHERE userID = ?");
                $stmt1->execute([$name, $email, $adminID]);
                
                $stmt2 = $this->conn->prepare("UPDATE ADMIN SET position = ?, department = ? WHERE userID = ?");
                $stmt2->execute([$position, $department, $adminID]);
                return true;
                
            } catch (Exception $e) {
                return false;
            }
        }
}

<?php
    session_start();
    include '../config/connect.php';
    require_once '../includes/header.php';
?>

<div>
    <form method="post">
        <pre>
            Email:<input type="email" name="txtemail" required>
            Password:<input type="password" name="txtpassword" required>
            
            <input type="submit" name="btnLogin" value="Login">
        </pre>
    </form>
</div>

<?php
    if(isset($_POST['btnLogin'])){
        $email = $_POST['txtemail'];
        $pwd = $_POST['txtpassword'];
        
        // Check Users table if email is existing
        $sql = "SELECT * FROM Users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        
        $count = $stmt->rowCount();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($count == 0){
            echo "<script language='javascript'>
                        alert('Email not existing.');
                  </script>";
                  
        } else if(!password_verify($pwd, $row['password'])){
            echo "<script language='javascript'>
                alert('Incorrect password');
                 </script>";
        } else {
            $_SESSION['userID'] = $row['userID'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['name'] = $row['name'];
            
            echo "<script language='javascript'>
                window.location.href = 'dashboard.php';
                 </script>";
        }
    }
?>

<?php require_once '../includes/footer.php'; ?>

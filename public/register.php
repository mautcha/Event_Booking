<?php
    session_start();
    include '../config/connect.php';
    require_once '../includes/header.php';
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>User Registration Page</h2></p>
    </center>
</div>

<div>
    <form method="post">
        <pre>
            Full Name:<input type="text" name="txtname" required>
            Email:<input type="email" name="txtemail" required>
            Password:<input type="password" name="txtpassword" required>
            Program:<input type="program" name="txtprogram" required>
            
            Year Level:
            <select name="txtyearlevel" required>
            <option value="">----</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
            </select>
                                    
            
            <input type="submit" name="btnRegister" value="Register">
        </pre>
    </form>
</div>

<?php
    if(isset($_POST['btnRegister'])){
        // retrieve data from form
        $name = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $pwd = $_POST['txtpassword'];
        $program = $_POST['txtprogram'];
        $yearlevel = $_POST['txtyearlevel'];
            
        $hashed_pword = password_hash($pwd, PASSWORD_DEFAULT);
        
        try {
            $db->beginTransaction();
            
            // save data to Users table
            $sql1 = "INSERT INTO Users(name, email, password, role) VALUES (?, ?, ?, 'student')";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute([$name, $email, $hashed_pword]);
            
            $userID = $db->lastInsertId();
            
            // save data to Student table
            $sql2 = "INSERT INTO Student(userID, program, yearLevel) VALUES (?, ?, ?)";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute([$userID, $program, $yearlevel]);
            
            $db->commit();
            
            echo "<script language='javascript'>
                alert('New record saved.');
                window.location.href = 'login.php';
                  </script>";
                  
        } catch(Exception $e) {
            $db->rollBack();
            echo "<script language='javascript'>
                alert('Registration failed. That email might already be used.');
                  </script>";
        }
    }
?>

<?php require_once '../includes/footer.php'; ?>

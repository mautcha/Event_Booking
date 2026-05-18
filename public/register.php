<?php
    session_start();
    include '../config/connect.php';
    require_once '../includes/header.php';

    $messageText = "";
    $messageType = "";

    if(isset($_POST['btnRegister'])){
        $name = $_POST['txtname'];
        $email = $_POST['txtemail'];
        $pwd = $_POST['txtpassword'];
        $program = $_POST['txtprogram'];
        $yearlevel = $_POST['txtyearlevel'];
            
        $hashed_pword = password_hash($pwd, PASSWORD_DEFAULT);
        
        try {
            $db->beginTransaction();
            
            $sql1 = "INSERT INTO Users(name, email, password, role) VALUES (?, ?, ?, 'student')";
            $stmt1 = $db->prepare($sql1);
            $stmt1->execute([$name, $email, $hashed_pword]);
            
            $userID = $db->lastInsertId();
            
            $sql2 = "INSERT INTO Student(userID, program, yearLevel) VALUES (?, ?, ?)";
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute([$userID, $program, $yearlevel]);
            
            $db->commit();
            
            $messageText = "New record saved successfully! You can now login.";
            $messageType = "success";
                  
        } catch(Exception $e) {
            $db->rollBack();
            
            $messageText = "Registration failed. That email might already be used.";
            $messageType = "danger";
        }
    }
?>

<div style='background-color:#ffff00'>
    <center>
        <p style="color:white"><h2>User Registration Page</h2></p>
    </center>
</div>

<div class="container mt-3" style="max-width: 500px;">
    <?php if (!empty($messageText)): ?>
        <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($messageText) ?>
            <?php if ($messageType === 'success'): ?>
                <br><a href="login.php" class="alert-link">Click here to go to the Login Page</a>
            <?php endif; ?>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>
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

<?php require_once '../includes/footer.php'; ?>

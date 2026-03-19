<?php
include 'dbconnection.php';


if(isset($_POST['register'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    
    $check = mysqli_query($conn, "SELECT * FROM users");
    $count = mysqli_num_rows($check);
     if($count == 0){
        $role = "admin";     
    } else {
        $role = "student";   
    }

    $sql = "INSERT INTO users(name,email,password,role) VALUES('$name','$email','$password','$role')";
   mysqli_query($conn,$sql);
    header("Location: login.php");

    echo "<div class='success'>Registered Successfully!</div>";

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="registerstyle.css">
</head>
<body>
    <div class="register-box">
    <h2>Register</h2>
   
    <form method="post">
        Name:<br><input type="text" name="name" required><br>
        Email:<br><input type="email" name="email" required><br>
        Password:<br><input type="password" name="password" required><br><br>
        <button name="register">Register</button>
    </form>
    
    <a href="login.php" class="btn-link" >Login</a>
</body>
</html>
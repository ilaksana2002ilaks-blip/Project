
<?php
session_start();
include("dbconnection.php");
$message = "";

if(isset($_POST['login'])){
    $email = $_POST['email'];
    $password = $_POST['password']; 

    
    $res = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");
    $row = mysqli_fetch_assoc($res);

    if($row){
      
        if (password_verify($password, $row['password'])){
            $_SESSION['id'] = $row['id']; 
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['role'] = $row['role'];

            if($row['role']=="admin"){
                header("Location: admin/admindb.php");
            }
            else{
                header("Location:student/studentdb.php");
            }
            exit();
        } else {
            $message = "❌ Invalid Password!";
        }
    } else {
        $message = "❌ Invalid Email!";
    }
}
?>

<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="registerstyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="register-box">
    <h2>Login</h2>
    <?php if($message != ""){ ?>
        <p class="error-msg"><?php echo $message; ?></p>
    <?php } ?>
    <form method="post">
        Email:<br><input type="email" name="email" required><br>
        Password:<br><input type="password" name="password" required><br><br>
        <button name="login">Login</button>
    </form>
    <a href="register.php">Register</a>
</div>
</body>
</html>



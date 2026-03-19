<?php
session_start();

if(isset($_SESSION['user_id'])){
    header("Location: admindb.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kids Learning Management System</title>
    <link rel="stylesheet" href="style4.css">
</head>
<body>


<header class="main-header">
    <div class="logo">🎓 Child Jesus Pre School  KLMS</div>
    <div class="nav-buttons">
        <a href="login.php" class="btn blue">Login</a>
        <a href="register.php" class="btn red">Register</a>
    </div>
</header>


<section class="hero">
    <div class="hero-content">
        <h1>Kids Learning Management System</h1>
        <p>Learn A-Z Letters, 1-20 Numbers & Fun Quizzes</p>
    </div>
    
</section>


</body>
</html>
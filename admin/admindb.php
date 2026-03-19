<?php
session_start();


include("../dbconnection.php"); 


if(!isset($_SESSION['role']) || $_SESSION['role'] != "admin"){
    header("Location: ../login.php");
    exit();
}


$course_count = 0;
$user_count = 0;
$question_count = 0;


$result1 = mysqli_query($conn,"SELECT * FROM courses");
if($result1){
    $course_count = mysqli_num_rows($result1);
}


$result2 = mysqli_query($conn,"SELECT * FROM users WHERE role='student'");
if($result2){
    $user_count = mysqli_num_rows($result2);
}


$result3 = mysqli_query($conn,"SELECT * FROM questions");
if($result3){
    $question_count = mysqli_num_rows($result3);
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<link rel="stylesheet" href="admindb.css">

<div class="sidebar">
<h2>⚙ KLMS Admin</h2>
<ul>
<li><a href="../dashboard.php">🏠 Dashboard</a></li>
<li><a href="./course.php">➕ Add Course</a></li>
<li><a href="/klms/admin/qustion.php">❓ Add Question</a></li>
<li><a href="/klms/admin/result.php">📊 View Results</a></li>
<li><a href="../student.php">👤 Student Details</a></li>
<li><a href="/klms/admin/admin_upload_material.php">⬇️Upload </a></li>
<li><a href="../index.php">🚪 Logout</a></li>
</ul>
</div>

<div class="main-content">
   <div class="main">

<div class="header">

<div class="header-left">
<h2>Dashboard</h2>
</div>

<div class="header-right">
<span>👤 Admin</span>
</div>
</div>
</div>


<div class="cards">
<div class="card-container">

<a href="viewcourses.php" class="card-link">
<div class="card">
<h3>📚 Total Courses</h3>
<p><?php echo $course_count; ?></p>
</div>
</a>

<a href="viewstudents.php" class="card-link">
<div class="card">
<h3>👥 Total Students</h3>
<p><?php echo $user_count; ?></p>
</div>
</a>

<a href="viewquestions.php" class="card-link">
<div class="card">
<h3>📖 Total Questions</h3>
<p><?php echo $question_count; ?></p>
</div>
</a>
<a href="student_progress.php" class="card-link">
<div class="card">
<h3>📊 Students Progress</h3>
<p>View Chart</p>
</div>
</a>


</div>
</div>
<br>
<br>
<div class=".sidebar">
            <h2>Recent Activities</h2>
            <ul>
                <b>
                <li>New Student Registered</li>
                <li>New Course Added</li>
                <li>Quiz Completed</li>
</b>
            </ul>
        </div>
        </div>
</div>
</div>

</body>
</html>
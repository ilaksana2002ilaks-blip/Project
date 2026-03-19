<?php
session_start();
include("dbconnection.php");

if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit;
}

if($_SESSION['role'] != "student"){
    header("Location: admin/admindb.php");
    exit;
}

$user_id = $_SESSION['id'];

$course_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM courses"));
$result_count = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM results WHERE user_id='$user_id'"));
$total_score = mysqli_fetch_assoc(mysqli_query($conn,
"SELECT SUM(score) as total FROM results WHERE user_id='$user_id'"));
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<h2>Welcome <?php echo $_SESSION['username']; ?></h2>

<p>Total Courses: <?php echo $course_count; ?></p>
<p>Quizzes Taken: <?php echo $result_count; ?></p>
<p>Total Score: <?php echo $total_score['total'] ?? 0; ?></p>

<a href="course.php">My Courses</a><br>
<a href="result.php">My Results</a><br>
<a href="logout.php">Logout</a>

</body>
</html>
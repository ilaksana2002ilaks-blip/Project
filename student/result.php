<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['id'])){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['id'];

$sql = "
SELECT r.*, c.course_name 
FROM results r
JOIN courses c ON r.course_id = c.course_id
WHERE r.student_id = '$student_id'
ORDER BY r.id DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>My Quiz Results</title>

<style>

body{
font-family:Arial;
background:linear-gradient(135deg,#4e73df,#1cc88a);
padding:40px;
}

.container{
background:white;
padding:30px;
border-radius:10px;
max-width:900px;
margin:auto;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#4e73df;
color:white;
padding:12px;
}

td{
padding:10px;
border-bottom:1px solid #ddd;
}
.back-btn{
display:inline-block;
margin-top:20px;
padding:10px 15px;
background:#1cc88a;
color:white;
text-decoration:none;
border-radius:6px;
}

.back-btn:hover{
background:#17a673;
}


</style>

</head>

<body>

<div class="container">

<h2>My Quiz Results</h2>

<table>

<tr>
<th>Course</th>
<th>Score</th>
<th>Percentage</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php
while($row = $result->fetch_assoc()){

$score = $row['score'];
$total = $row['total_questions'];

$percentage = 0;

if($total > 0){
$percentage = ($score/$total)*100;
}

$status = ($percentage >= 50) ? "PASS" : "FAIL";
?>

<tr>

<td><?php echo $row['course_name']; ?></td>

<td><?php echo $score." / ".$total; ?></td>

<td><?php echo round($percentage); ?>%</td>

<td><?php echo $status; ?></td>

<td><?php echo $row['date_taken']; ?></td>

</tr>

<?php } ?>

</table>
<a class="back-btn" href="studentdb.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>
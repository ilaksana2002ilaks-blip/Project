<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$results = $conn->query("
    SELECT r.*, u.name AS student_name, c.course_name
    FROM results r
    JOIN users u ON r.student_id = u.id
    JOIN courses c ON r.course_id = c.course_id
    ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Quiz Results</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family: Arial, sans-serif;
background: linear-gradient(135deg,#4e73df,#1cc88a);
margin:0;
padding:40px;
}

.container{
background:white;
padding:30px;
border-radius:12px;
box-shadow:0 8px 20px rgba(0,0,0,0.15);
max-width:1000px;
margin:auto;
}

h1{
text-align:center;
color:#2c3e50;
margin-bottom:25px;
}

table{
width:100%;
border-collapse:collapse;
}

th{
background:#4e73df;
color:white;
padding:12px;
text-align:left;
}

td{
padding:10px;
border-bottom:1px solid #ddd;
}

tr:nth-child(even){
background:#f2f2f2;
}

tr:hover{
background:#eaf2ff;
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

<h1>Quiz Results</h1>

<table>

<tr>
<th>Student</th>
<th>Course</th>
<th>Score</th>
<th>Percentage</th>
<th>Date Taken</th>
</tr>

<?php while($r = $results->fetch_assoc()){ 

$percentage = 0;

if($r['total_questions'] > 0){
$percentage = ($r['score'] / $r['total_questions']) * 100;
}

?>

<tr>
<td><?php echo $r['student_name']; ?></td>

<td><?php echo $r['course_name']; ?></td>

<td><?php echo $r['score']." / ".$r['total_questions']; ?></td>

<td><?php echo round($percentage); ?>%</td>

<td><?php echo $r['date_taken']; ?></td>

</tr>

<?php } ?>

</table>

<a class="back-btn" href="admindb.php">⬅ Back to Dashboard</a>

</div>

</body>
</html>
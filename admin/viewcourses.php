<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['role']) || $_SESSION['role']!="admin"){
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM courses");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>All Courses</title>
<style>
body{
    font-family: Arial, sans-serif;
    background:#f4f4f4;
    padding: 20px;
    margin:0;
}

h2{
    text-align:center;
    margin-bottom:20px;
    color:#333;
}

.table-container{
    max-width:800px;
    margin: auto;
    overflow-x:auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

table{
    width:100%;
    border-collapse: collapse;
}

table th, table td{
    border: 1px solid #ccc;
    padding: 12px;
    text-align:left;
}

table th{
    background-color: #007BFF;
    color:white;
    font-weight: bold;
}

table tr:nth-child(even){
    background-color:#f9f9f9;
}

table tr:hover{
    background-color:#e2eaff;
}


@media screen and (max-width:600px){
    table th, table td{
        padding:10px;
        font-size:14px;
    }
}
</style>
</head>
<body>

<h2>All Courses</h2>

<div class="table-container">
<table>
<tr>
<th>ID</th>
<th>Course Name</th>
</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?php echo $row['course_id']; ?></td>
<td><?php echo htmlspecialchars($row['course_name']); ?></td>
</tr>
<?php } ?>

</table>
</div>

</body>
</html>
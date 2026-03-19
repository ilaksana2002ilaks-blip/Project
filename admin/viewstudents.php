<?php
session_start();
include("../dbconnection.php");

$result = mysqli_query($conn,"SELECT * FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student List</title>
<style>
body{
    font-family: Arial, sans-serif;
    padding: 10px;
}

h2{
    text-align:left;
}


.table-container{
    overflow-x: auto;
}


table{
    width:70%;
    border-collapse: collapse;
    align:center;
}

table th, table td{
    border: 1px solid #ccc;
    padding: 10px;
    text-align:left;
}

table th{
    background-color: #0ac6f0;
}


@media screen and (max-width:600px){
    table th, table td{
        padding: 8px;
        font-size: 14px;
    }
}
</style>
</head>
<body>

<h2>Student List</h2>

<div class="table-container">
<table>
<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
</tr>

<?php
while($row=mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
</tr>
<?php } ?>
</table>
</div>

</body>
</html>
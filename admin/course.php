<?php
session_start();
include '../dbconnection.php';   


if(!isset($_SESSION['id']) || $_SESSION['role'] != 'admin'){
    header("Location: ../admin/course.php");   
    exit();
}

$message = '';

if(isset($_POST['add_course'])){
    $course_name = $_POST['course_name'];
    $description = $_POST['description'];

    $sql = "INSERT INTO courses(course_name, description) VALUES('$course_name', '$description')";

    if($conn->query($sql)){
        $message = "Course added successfully!";
        header("Location:course.php");  
        exit();
    } else {
        $message = "Error: " . $conn->error;
    }
}
if(isset($_GET['delete'])){
$id=$_GET['delete'];

mysqli_query($conn,"DELETE FROM courses WHERE course_id='$id'");
header("Location:course.php");
}

$courses=mysqli_query($conn,"SELECT * FROM courses");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Course</title>
    <link rel="stylesheet" href="course.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <div class="card">
        <div class="header">
    <h1>Add Course</h1>
    <a href="../dashboard.php">⬅Back to Dashboard</a> | 
    <a href="../logout.php">Logout</a>

    <?php if($message != ''){ echo "<p>$message</p>"; } ?>
    <hr>
<div class="form">
    <form method="post">
        Course Name:<br>
        <input type="text" name="course_name" required><br>
        Description:<br>
        <textarea name="description" rows="4" required></textarea><br><br>
       <div class="button">
        <button name="add_course">Add Course</button>
    </form>
    </div>
</div>
<h2>All Courses</h2>

<table border="1">

<tr>
<th>ID</th>
<th>Name</th>
<th>Action</th>
</tr>
<?php while($c=mysqli_fetch_assoc($courses)){ ?>

<tr>

<td><?php echo $c['course_id']; ?></td>

<td><?php echo $c['course_name']; ?></td>

<td>
<a href="course.php?delete=<?php echo $c['course_id']; ?>">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>
</body>
</html>
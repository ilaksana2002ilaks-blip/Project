<?php
session_start();
include("../dbconnection.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}

$message = "";


if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    $result = $conn->query("SELECT file_path FROM study_materials WHERE material_id=$id");
    $row = $result->fetch_assoc();

    if($row){
        if(file_exists($row['file_path'])){
            unlink($row['file_path']);
        }

        $conn->query("DELETE FROM study_materials WHERE material_id=$id");
        $message = "Material deleted successfully!";
    }
}


if(isset($_POST['submit'])){
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];

    if(isset($_FILES['file']) && $_FILES['file']['error'] == 0){

        $target_dir = "../uploads/";

        if(!is_dir($target_dir)){
            mkdir($target_dir,0777,true);
        }

        $file_name = time().'_'.basename($_FILES['file']['name']);
        $target_file = $target_dir.$file_name;

        if(move_uploaded_file($_FILES['file']['tmp_name'],$target_file)){

            $stmt = $conn->prepare("INSERT INTO study_materials(course_id,title,file_path) VALUES(?,?,?)");
            $stmt->bind_param("iss",$course_id,$title,$target_file);

            if($stmt->execute()){
                $message="Material uploaded successfully!";
            }else{
                $message="Database error!";
            }

        }else{
            $message="File upload failed!";
        }

    }else{
        $message="Please select a file!";
    }
}


$courses = $conn->query("SELECT * FROM courses");


$materials = $conn->query("
SELECT study_materials.*, courses.course_name
FROM study_materials
JOIN courses ON study_materials.course_id = courses.course_id
ORDER BY uploaded_at DESC
");
?>

<!DOCTYPE html>
<html>
<head>

<title>Admin Upload Study Material</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>

body{
font-family:Arial;
background:#f4f4f4;
padding:20px;
}

h2{
text-align:center;
}

form{
background:white;
padding:20px;
max-width:600px;
margin:auto;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,0.1);
}

select,input{
width:95%;
padding:10px;
margin:8px 0;
}

input[type="submit"]{
background:#007BFF;
color:white;
border:none;
cursor:pointer;
}

input[type="submit"]:hover{
background:#0056b3;
}

table{
width:100%;
margin-top:30px;
border-collapse:collapse;
background:white;
}

table th,table td{
padding:10px;
border:1px solid #ddd;
text-align:center;
}

.delete-btn{
background:red;
color:white;
padding:5px 10px;
text-decoration:none;
border-radius:5px;
}

.delete-btn:hover{
background:darkred;
}

.download-btn{
background:green;
color:white;
padding:5px 10px;
text-decoration:none;
border-radius:5px;
}

.download-btn:hover{
background:darkgreen;
}

.message{
text-align:center;
color:green;
font-weight:bold;
}

</style>

</head>

<body>

<h2>Upload Study Material</h2>

<?php if($message) echo "<p class='message'>$message</p>"; ?>

<form method="post" enctype="multipart/form-data">

<label>Course</label>

<select name="course_id" required>

<option value="">Select Course</option>

<?php while($row=$courses->fetch_assoc()){ ?>

<option value="<?= $row['course_id'] ?>">
<?= $row['course_name'] ?>
</option>

<?php } ?>

</select>

<label>Material Title</label>

<input type="text" name="title" placeholder="Enter material title" required>

<label>Select File</label>

<input type="file" name="file" required>

<input type="submit" name="submit" value="Upload Material">

</form>


<h2>Uploaded Materials</h2>

<table>

<tr>
<th>ID</th>
<th>Course</th>
<th>Title</th>
<th>Download</th>
<th>Delete</th>
</tr>

<?php while($row=$materials->fetch_assoc()){ ?>

<tr>

<td><?= $row['material_id'] ?></td>

<td><?= $row['course_name'] ?></td>

<td><?= $row['title'] ?></td>

<td>
<a class="download-btn" href="<?= $row['file_path'] ?>" download>
Download
</a>
</td>

<td>
<a class="delete-btn"
href="?delete=<?= $row['material_id'] ?>"
onclick="return confirm('Are you sure to delete this material?')">
Delete
</a>
</td>

</tr>

<?php } ?>

</table>

</body>
</html>
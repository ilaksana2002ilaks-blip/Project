<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}

if(!isset($_GET['course_id'])){
    die("Course not selected!");
}

$course_id = intval($_GET['course_id']);

$stmt = $conn->prepare("SELECT * FROM questions WHERE course_id=?");
$stmt->bind_param("i",$course_id);
$stmt->execute();
$result = $stmt->get_result();

if($result->num_rows == 0){
    die("No questions available for this course.");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Quiz</title>

<style>

body{
font-family:Arial;
background:#f2f2f2;
padding:20px;
}

h2{
text-align:center;
}

form{
max-width:800px;
margin:auto;
}

.question-box{
background:white;
padding:15px;
margin-bottom:15px;
border-radius:8px;
box-shadow:0 2px 5px rgba(0,0,0,0.1);
}

label{
display:block;
margin-bottom:8px;
cursor:pointer;
}

button{
width:100%;
padding:12px;
background:#4CAF50;
color:white;
border:none;
border-radius:5px;
font-size:16px;
cursor:pointer;
}

button:hover{
background:#45a049;
}

</style>

</head>

<body>

<h2>Quiz</h2>


<form method="POST" action="submit.php">


<input type="hidden" name="course_id" value="<?php echo $course_id; ?>">

<?php
$number=1;

while($row=$result->fetch_assoc()):
?>

<div class="question-box">

<p><b>Q<?php echo $number++; ?>:</b> <?php echo htmlspecialchars($row['question_text']); ?></p>

<label>
<input type="radio" name="answer[<?php echo $row['id']; ?>]" value="1" required>
<?php echo htmlspecialchars($row['option1']); ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $row['id']; ?>]" value="2">
<?php echo htmlspecialchars($row['option2']); ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $row['id']; ?>]" value="3">
<?php echo htmlspecialchars($row['option3']); ?>
</label>

<label>
<input type="radio" name="answer[<?php echo $row['id']; ?>]" value="4">
<?php echo htmlspecialchars($row['option4']); ?>
</label>

</div>

<?php endwhile; ?>

<button type="submit">Submit Quiz</button>

</form>


</body>
</html>
<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['id'])){
    header("Location: ../login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['course_id']) || empty($_POST['answer'])){
    die("Quiz not submitted properly. Please go back and try again.");
}

$student_id = $_SESSION['id'];
$course_id = intval($_POST['course_id']);
$answers = $_POST['answer'];

$score = 0;
$total_questions = count($answers);   


foreach($answers as $question_id => $selected_option){
    $stmt = $conn->prepare("SELECT correct_option FROM questions WHERE id=?");
    $stmt->bind_param("i",$question_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if($row && $row['correct_option'] == $selected_option){
        $score++;
    }

    $stmt->close();
}


$stmt2 = $conn->prepare("
    INSERT INTO results(student_id,course_id,score,total_questions,date_taken) 
    VALUES(?,?,?,?,NOW())
");
$stmt2->bind_param("iiii",$student_id,$course_id,$score,$total_questions);
$stmt2->execute();
$stmt2->close();


$percentage = ($total_questions > 0) ? ($score / $total_questions) * 100 : 0;
?>


<html>
<head>
<title>Quiz Result</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    font-family:Arial;
    background:linear-gradient(to right,#4facfe,#00f2fe);
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.result-container{
    background:white;
    padding:40px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.2);
    text-align:center;
}
.result-container h2{
    margin-bottom:20px;
}
.result-container a{
    padding:10px 20px;
    background:#4facfe;
    color:white;
    text-decoration:none;
    border-radius:20px;
}
</style>

</head>
<body>

<div class="result-container">

<h2>Your Score: <?php echo $score; ?> / <?php echo $total_questions; ?> <br><br>
Percentage : <?php echo round($percentage); ?>%</h2>



<a href="studentdb.php">Back to Dashboard</a>

</div>

</body>
</html>
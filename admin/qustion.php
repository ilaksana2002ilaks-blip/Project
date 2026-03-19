<?php
session_start();
include("../dbconnection.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}


if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM questions WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: qustion.php");
    exit();
}


$courses = $conn->query("SELECT * FROM courses");


if(isset($_POST['add_question'])){
    $course_id = $_POST['course_id'];
    $question = $_POST['question'];
    $o1 = $_POST['option1'];
    $o2 = $_POST['option2'];
    $o3 = $_POST['option3'];
    $o4 = $_POST['option4'];
    $answer = $_POST['answer'];

    $stmt = $conn->prepare("INSERT INTO questions(course_id, question_text, option1, option2, option3, option4, correct_option) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("issssss", $course_id, $question, $o1, $o2, $o3, $o4, $answer);
    $stmt->execute();

    header("Location: qustion.php");
    exit();
}


$questions = $conn->query("SELECT q.*, c.course_name 
                           FROM questions q 
                           JOIN courses c ON q.course_id=c.course_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Questions</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            font-family: Arial;
            background: linear-gradient(135deg,#4e73df,#1cc88a);
            padding:30px;
        }
        .card{
            background:white;
            padding:20px;
            border-radius:10px;
            margin-bottom:20px;
        }
        input, select{
            padding:6px;
            margin:5px;
        }
        button{
            padding:8px 12px;
            background:#4e73df;
            color:white;
            border:none;
            border-radius:5px;
            cursor:pointer;
        }
        button:hover{
            background:#2e59d9;
        }
        .delete{
            color:red;
            text-decoration:none;
            margin-left:10px;
        }
    </style>
</head>
<body>

<h1>Manage Questions</h1>

<div class="card">
<h3>Add New Question</h3>
<form method="POST">
    Course:
    <select name="course_id" required>
        <?php while($c = $courses->fetch_assoc()){ ?>
            <option value="<?php echo $c['course_id']; ?>">
                <?php echo $c['course_name']; ?>
            </option>
        <?php } ?>
    </select><br>

    Question: <input type="text" name="question" required><br>

    Option 1: <input type="text" name="option1" required>
    Option 2: <input type="text" name="option2" required><br>

    Option 3: <input type="text" name="option3" required>
    Option 4: <input type="text" name="option4" required><br>

    Correct Answer: <input type="text" name="answer" required><br>

    <button type="submit" name="add_question">➕ Add Question</button>
</form>
</div>

<div class="card">
<h3>All Questions</h3>
<ul>
<?php while($q = $questions->fetch_assoc()){ ?>
    <li>
        <b><?php echo $q['course_name']; ?></b> :
        <?php echo $q['question_text']; ?>
        (Correct: <?php echo $q['correct_option']; ?>)
        <a class="delete" 
           href="qustion.php?delete=<?php echo $q['id']; ?>"
           onclick="return confirm('Are you sure to delete?');">
           ❌ Delete
        </a>
    </li>
<?php } ?>
</ul>
</div>

<a href="admindb.php">⬅ Back to Dashboard</a>

</body>
</html>
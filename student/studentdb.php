<?php
session_start();
include '../dbconnection.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['id'] ?? 0;
$student_name = $_SESSION['user_name'] ?? 'Student';


$courses = $conn->query("SELECT * FROM courses");


$result_chart = $conn->query("SELECT score, date_taken FROM results WHERE student_id='$student_id' ORDER BY date_taken ASC");
$labels = [];
$data = [];
while($row = $result_chart->fetch_assoc()){
    $labels[] = date("d M", strtotime($row['date_taken']));
    $data[] = $row['score'];
}


$total_courses = mysqli_num_rows($conn->query("SELECT * FROM courses"));
$total_quizzes = mysqli_num_rows($conn->query("SELECT * FROM results WHERE student_id='$student_id'"));
?>


<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard</title>
<link rel="stylesheet" href="sdstyle.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<div class="sidebar">
<h1>KLMS Student</h1>
<a href="studentdb.php">Dashboard</a>
<a href="student_materials.php">Study Materials</a>
<a href="result.php">My Results</a>
<a href="mydetails.php">My details</a>
<a href="../index.php">Logout</a>
</div>

<div class="main-content">
<h1 >
    <img src="icon1.png" alt="Student" class="student-icon">
    Hello!  Welcome, <b><?php echo htmlspecialchars($student_name); ?></b>
</h1>


<div class="cards">
    <div class="card">
        <h3>Total Courses</h3>
        <p><?php echo $total_courses; ?></p>
    </div>
    <div class="card">
        <h3>Quizzes Taken</h3>
        <p><?php echo $total_quizzes; ?></p>
    </div>
</div>


<div id="courses-section">
<h2>Available Courses Quiz</h2>
<div class="course-grid">
    <?php while($row = $courses->fetch_assoc()): ?>
        <div class="course-card">
            <h3><?php echo htmlspecialchars($row['course_name']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <a href="quiz.php?course_id=<?php echo $row['course_id']; ?>">Start Quiz</a>
        </div>
    <?php endwhile; ?>
</div>
</div>

<h2>My Quiz Progress</h2>
<div class="chart-container">
<canvas id="progressChart"></canvas>
</div>

<script>
const ctx = document.getElementById('progressChart').getContext('2d');
const progressChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Score',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive:true,
        scales: { y: { beginAtZero:true, suggestedMax:10 } },
        plugins: { legend:{ display:false } }
    }
});
</script>

</div>
</body>
</html>
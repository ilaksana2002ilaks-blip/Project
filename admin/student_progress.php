<?php
session_start();
include("../dbconnection.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin'){
    header("Location: ../login.php");
    exit();
}


$query = "
SELECT u.name AS student_name, r.score
FROM users u
JOIN results r ON u.id = r.student_id
WHERE u.role='student'
AND r.date_taken = (
    SELECT MAX(date_taken)
    FROM results r2
    WHERE r2.student_id = u.id
)
ORDER BY u.name
";

$result = mysqli_query($conn,$query);

$labels = [];
$data = [];

while($row = mysqli_fetch_assoc($result)){
    $labels[] = $row['student_name'];
    $data[] = $row['score'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Students Latest Scores</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { 
    font-family: Arial;
 padding:10px; 
}
h2 { text-align:center; }
.chart-container {
     width:95%; max-width:900px;
      margin:auto; 
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

<h2>Students Latest Quiz Scores</h2>
<div class="chart-container">
<canvas id="adminChart"></canvas>
</div>
<a class="back-btn" href="admindb.php">⬅ Back to Dashboard</a>
<script>
const ctx = document.getElementById('adminChart').getContext('2d');
const adminChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($labels); ?>,
        datasets: [{
            label: 'Latest Score',
            data: <?php echo json_encode($data); ?>,
            backgroundColor: 'rgba(99, 112, 255, 0.6)',
            borderColor: 'rgb(99, 148, 255)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, suggestedMax: 10 } },
        plugins: { legend: { display: false } }
    }
});
</script>

</body>
</html>
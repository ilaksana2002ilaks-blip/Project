<?php
session_start();
include("../dbconnection.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['id'] ?? 0;


$result = mysqli_query($conn,"SELECT score, date_taken FROM results WHERE student_id='$student_id' ORDER BY date_taken ASC");

$labels = [];
$data = [];

while($row = mysqli_fetch_assoc($result)){
    $labels[] = date("d M", strtotime($row['date_taken']));
    $data[] = $row['score'];
}
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Progress</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body { 
    font-family: Arial;
    padding:10px; 
}
h2 {
     text-align:center; 
    }
.chart-container { 
    width:95%;
    max-width:700px; 
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

<h2>My Quiz Progress</h2>
<div class="chart-container">
<canvas id="progressChart"></canvas>
</div>
<a class="back-btn" href="studentdb.php">⬅ Back to Dashboard</a>

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
        responsive: true,
        scales: {
            y: { beginAtZero: true, suggestedMax: 10 }
        },
        plugins: { legend: { display: false } }
    }
});
</script>

</body>
</html>
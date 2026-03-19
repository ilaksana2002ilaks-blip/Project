<?php
session_start();
include("../dbconnection.php");


if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: login.php");
    exit();
}

$student_id = $_SESSION['id'] ?? 0;


$stmt = $conn->prepare("
    SELECT s.*, u.email 
    FROM students s 
    LEFT JOIN users u ON s.user_id = u.id 
    WHERE s.user_id = ?
");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Dashboard</title>
    <style>
        body {
             font-family: Arial, sans-serif;
              margin: 30px;
               background-color: #f4f4f9;
             }
        h2 { color: #333; }
        table { width: 50%; 
        border-collapse: collapse;
         background: #fff; 
         box-shadow: 0 0 10px rgba(0,0,0,0.05);
         }
        table th, table td { 
            padding: 15px; border: 1px solid #ddd;
             text-align: left;
             }
        table th { 
            background-color: #4CAF50;
             color: white;
              width: 30%;
             }
        table tr:nth-child(even) { 
            background-color: #f2f2f2;
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

<h2>My Details</h2>

<?php if($student){ ?>
<table>
<tr><th>Full Name</th><td><?php echo htmlspecialchars($student['full_name']); ?></td></tr>
<tr><th>Address</th><td><?php echo htmlspecialchars($student['address']); ?></td></tr>
<tr><th>Age</th><td><?php echo htmlspecialchars($student['age']); ?></td></tr>
<tr><th>Parents Name</th><td><?php echo htmlspecialchars($student['parents_name']); ?></td></tr>
<tr><th>Phone</th><td><?php echo htmlspecialchars($student['phone']); ?></td></tr>
<tr><th>Email</th><td><?php echo htmlspecialchars($student['email']); ?></td></tr>
</table>
<a class="back-btn" href="studentdb.php">⬅ Back to Dashboard</a>
<?php } else { ?>
<p>No details found.</p>
<?php } ?>

</body>
</html>
<?php
session_start();
include("../dbconnection.php");

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student'){
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT s.material_id, s.title, s.file_path, s.uploaded_at, c.course_name 
        FROM study_materials s
        JOIN courses c ON s.course_id = c.course_id
        ORDER BY s.uploaded_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Study Materials</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            font-family: Arial, sans-serif; 
            background:#f4f4f4; 
            padding:20px;
            margin:0;
        }

        h2{
            text-align:center; 
            margin-bottom:20px;
            color:#333;
        }

        .table-container{
            overflow-x:auto;
            max-width:1000px;
            margin:auto;
            background:white;
            border-radius:10px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            padding:12px 15px;
            border:1px solid #ddd;
            text-align:left;
        }

        th{
            background:#007BFF;
            color:white;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }

        tr:hover{
            background:#e2eaff;
        }

        a{
            color:#007BFF;
            text-decoration:none;
            margin-right:10px;
        }
        a:hover{
            text-decoration:underline;
        }

        @media(max-width:600px){
            table, thead, tbody, th, td, tr{
                display:block;
                width:100%;
            }

            thead tr{
                display:none;
            }

            tr{
                margin-bottom:20px;
                border-bottom:2px solid #ddd;
                padding:10px 0;
            }

            td{
                text-align:right;
                padding-left:50%;
                position:relative;
            }

            td::before{
                content: attr(data-label);
                position:absolute;
                left:10px;
                width:45%;
                text-align:left;
                font-weight:bold;
            }

            a{
                display:inline-block;
                margin:5px 0;
            }
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

<h2>Study Materials</h2>

<div class="table-container">
<table>
    <thead>
        <tr>
            <th>Course</th>
            <th>Title</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php if($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td data-label="Course"><?= htmlspecialchars($row['course_name']) ?></td>
                    <td data-label="Title"><?= htmlspecialchars($row['title']) ?></td>
                    <td data-label="Uploaded At"><?= $row['uploaded_at'] ?></td>
                    <td data-label="Action">
                        <a href="<?= $row['file_path'] ?>" target="_blank">View</a>
                        <a href="<?= $row['file_path'] ?>" download>Download</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">No materials found.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
<a class="back-btn" href="studentdb.php">⬅ Back to Dashboard</a>

</body>
</html>
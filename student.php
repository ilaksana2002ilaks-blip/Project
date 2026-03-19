<?php
session_start();
include("dbconnection.php");


if(!isset($_SESSION['role'])){
    header("Location: login.php");
    exit();
}


$edit_id = 0;
$full_name = $address = $age = $parents_name = $phone = $email = "";
$user_id = 0;

if(isset($_GET['edit'])){
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT s.*, u.email, u.id as user_id FROM students s LEFT JOIN users u ON s.user_id=u.id WHERE s.id=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result_edit = $stmt->get_result();
    if($row = $result_edit->fetch_assoc()){
        $full_name = $row['full_name'];
        $address = $row['address'];
        $age = $row['age'];
        $parents_name = $row['parents_name'];
        $phone = $row['phone'];
        $email = $row['email'];
        $user_id = $row['user_id'];
    }
}

if(isset($_POST['add'])){
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $parents_name = $_POST['parents_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt_check = $conn->prepare("SELECT id FROM users WHERE email=?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $res_check = $stmt_check->get_result();

    if($res_check->num_rows > 0){
        $user_row = $res_check->fetch_assoc();
        $user_id = $user_row['id'];
    } else {
        $password_hashed = password_hash("student123", PASSWORD_DEFAULT);
        $role = 'student';
        $stmt_user = $conn->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
        $stmt_user->bind_param("ssss",$full_name,$email,$password_hashed,$role);
        $stmt_user->execute();
        $user_id = $stmt_user->insert_id;
    }
    $stmt = $conn->prepare("INSERT INTO students(full_name,address,age,parents_name,phone,user_id) VALUES (?,?,?,?,?,?)");
    $stmt->bind_param("ssissi",$full_name,$address,$age,$parents_name,$phone,$user_id);
    $stmt->execute();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

if(isset($_POST['update'])){
    $edit_id = $_POST['id'];
    $full_name = $_POST['full_name'];
    $address = $_POST['address'];
    $age = $_POST['age'];
    $parents_name = $_POST['parents_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];

    $stmt_user = $conn->prepare("UPDATE users SET name=?, email=? WHERE id=?");
    $stmt_user->bind_param("ssi",$full_name,$email,$user_id);
    $stmt_user->execute();

    $stmt = $conn->prepare("UPDATE students SET full_name=?, address=?, age=?, parents_name=?, phone=? WHERE id=?");
    $stmt->bind_param("ssissi",$full_name,$address,$age,$parents_name,$phone,$edit_id);
    $stmt->execute();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


if(isset($_GET['delete']) && $_SESSION['role']=="admin"){
    $delete_id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
    $stmt->bind_param("i",$delete_id);
    $stmt->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}


if($_SESSION['role'] == "admin"){
    $result = $conn->query("SELECT s.*, u.email FROM students s LEFT JOIN users u ON s.user_id = u.id");
}else{
    $student_id = $_SESSION['id'];
    $stmt = $conn->prepare("SELECT s.*, u.email FROM students s LEFT JOIN users u ON s.user_id=u.id WHERE u.id=?");
    $stmt->bind_param("i",$student_id);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management</title>
    <style>
        body { 
            font-family: Arial;
             margin:30px; 
             background:#f4f4f9;
             }
        h2 {
             color:#333; 
            }
        form {
             background:#fff; 
             padding:20px;
              border-radius:8px; 
              width:450px; 
              margin-bottom:30px;
               box-shadow:0 0 10px rgba(0,0,0,0.1);
            }
        input[type="text"], input[type="number"], input[type="email"] {
             width:95%;
              padding:8px;
               margin:5px 0 15px 0;
                border:1px solid #ccc;
                 border-radius:4px;
                 }
        input[type="submit"] {
             background:#4CAF50; 
             color:white; 
             padding:10px 20px;
              border:none;
               border-radius:4px;
                cursor:pointer;
            }
        input[type="submit"]:hover {
             background:#45a049;
            }
        table { 
            width:90%;
             border-collapse:collapse;
              background:#fff;
               box-shadow:0 0 10px rgba(0,0,0,0.05);
            }
        table th, table td {
             padding:15px;
              border:1px solid #ddd;
               text-align:left;
            }
        table th { 
            background:#4CAF50;
             color:white; 
            }
        table tr:nth-child(even) { 
            background:#f2f2f2; 
        }
        a { 
            background:green;
             color:white; 
             padding:5px 10px;
              text-decoration:none;
               border-radius:5px;
             }
        a:hover { 
            text-decoration: underline;
         }
        .action-links a { 
            margin-right:10px;
        }
        .delete-but{ 
            background:red;
             color:white;
              padding:5px 10px;
               text-decoration:none;
                border-radius:5px;
            }
    </style>
</head>
<body>

<h2><?php echo ($edit_id>0)?"Edit Student":"Student Form"; ?></h2>

<form method="POST">
Full Name<br>
<input type="text" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>" required><br>

Address<br>
<input type="text" name="address" value="<?php echo htmlspecialchars($address); ?>" required><br>

Age<br>
<input type="number" name="age" value="<?php echo htmlspecialchars($age); ?>" required><br>

Parents Name<br>
<input type="text" name="parents_name" value="<?php echo htmlspecialchars($parents_name); ?>" required><br>

Phone<br>
<input type="text" name="phone" value="<?php echo htmlspecialchars($phone); ?>" required><br>

Email (login)<br>
<input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>

<?php if($edit_id>0){ ?>
<input type="hidden" name="id" value="<?php echo $edit_id; ?>">
<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
<input type="submit" name="update" value="Update Student">
<?php } else { ?>
<input type="submit" name="add" value="Add Student">
<?php } ?>
</form>

<hr>

<h2>Student Details</h2>

<table>
<tr>
<th>Name</th>
<th>Address</th>
<th>Age</th>
<th>Parents</th>
<th>Phone</th>
<th>Email</th>
<th>Action</th>
</tr>

<?php while($row=$result->fetch_assoc()){ ?>
<tr>
<td><?php echo $row['full_name']; ?></td>
<td><?php echo $row['address']; ?></td>
<td><?php echo $row['age']; ?></td>
<td><?php echo $row['parents_name']; ?></td>
<td><?php echo $row['phone']; ?></td>
<td><?php echo $row['email']; ?></td>
<td class="action-links">
<a href="?edit=<?php echo $row['id']; ?>">Edit</a>
<?php if($_SESSION['role']=="admin"){ ?>
 <a class="delete-but" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Delete?')">Delete</a>
<?php } ?>
</td>
</tr>
<?php } ?>
</table>
<br>
<a href="../klms/admin/admindb.php">⬅Back to Dashboard</a> 

</body>
</html>
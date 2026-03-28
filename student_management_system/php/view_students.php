<?php
include 'session_check.php';
include 'db_connect.php';

// 1. Handle Adding a Student
if (isset($_POST['add_student'])) {
    // Remove $sid = $_POST['student_id'];
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $dept = $_POST['dept_id'];

    // We no longer insert student_id; MySQL does it for us
    $sql = "INSERT INTO students (first_name, last_name, email, dept_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $fname, $lname, $email, $dept);
    
    if ($stmt->execute()) {
        header("Location: view_students.php?msg=added");
    }
}

// 2. Fetch all departments for the dropdown
$depts = $conn->query("SELECT * FROM departments");

// 3. Fetch all students for the table
$students = $conn->query("SELECT s.*, d.dept_name FROM students s LEFT JOIN departments d ON s.dept_id = d.dept_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Students | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <h2>Manage Students</h2>

        <?php if (isset($_GET['msg'])): ?>
    <div id="alert-box" class="alert <?php echo ($_GET['msg'] == 'deleted') ? 'alert-danger' : 'alert-success'; ?>">
        <?php 
            if ($_GET['msg'] == 'added') {
                echo "<i class='fas fa-check-circle'></i> Student recorded successfully!";
            } elseif ($_GET['msg'] == 'deleted') {
                echo "<i class='fas fa-trash-alt'></i> Student deleted permanently.";
            } elseif ($_GET['msg'] == 'edited') {
                echo "<i class='fas fa-edit'></i> Student updated successfully!";
            } elseif ($_GET['msg'] == 'error') {
                echo "<i class='fas fa-exclamation-triangle'></i> An error occurred.";
            }
        ?>
    </div>
    <script>
        setTimeout(function() { document.getElementById('alert-box').style.display = 'none'; }, 3000);
    </script>
<?php endif; ?>

            <div class="form-container">
                <h3>Add New Student</h3>
                <form method="POST" class="inline-form">
                    <input type="number" name="student_id" placeholder="ID (e.g. 202401)" required>
                    <input type="text" name="first_name" placeholder="First Name" required>
                    <input type="text" name="last_name" placeholder="Last Name" required>
                    <input type="email" name="email" placeholder="Email" required>
                    
                    <select name="dept_id" required>
                        <option value="">Select Department</option>
                        <?php while($d = $depts->fetch_assoc()): ?>
                            <option value="<?php echo $d['dept_id']; ?>"><?php echo $d['dept_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                    
                    <button type="submit" name="add_student" class="add-btn">Add Student</button>
                </form>
            </div>

            <hr>

            <table class="content-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
    <?php if($students->num_rows > 0): ?>
        <?php while($row = $students->fetch_assoc()): ?>
            <tr>
                <td><strong><?php echo $row['registration_no']; ?></strong></td>
                <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><span class="dept-tag"><?php echo $row['dept_name']; ?></span></td>
                
                <td>
                    <a href="edit_student.php?id=<?php echo $row['student_id']; ?>" class="text-edit" title="Edit Student">
                        <i class="fas fa-edit"></i>
                    </a>
                    
                    <a href="delete_student.php?id=<?php echo $row['student_id']; ?>" class="text-danger" title="Delete Student" onclick="return confirm('Are you sure you want to permanently delete this student?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr>
            <td colspan="5" style="text-align:center; padding: 50px; color: #7f8c8d;">
                <i class="fas fa-user-slash" style="font-size: 3rem; display:block; margin-bottom:10px;"></i>
                No students found in the database.
            </td>
        </tr>
    <?php endif; ?>
</tbody>
            </table>
        </div>
    </div>
</body>
</html>
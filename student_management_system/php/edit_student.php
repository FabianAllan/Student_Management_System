<?php
include 'session_check.php';
include 'db_connect.php';

// Security check: Admins only
if ($_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// 1. THE GET PHASE: Fetch existing data to populate the form
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // If student doesn't exist, kick them back to the list
    if ($result->num_rows == 0) {
        header("Location: view_students.php");
        exit();
    }
    
    $student = $result->fetch_assoc();
} else {
    header("Location: view_students.php");
    exit();
}

// 2. THE POST PHASE: Handle the form submission to update the database
if (isset($_POST['update_student'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];
    $email = $_POST['email'];
    $dept = $_POST['dept_id'];

    $update_sql = "UPDATE students SET first_name = ?, last_name = ?, email = ?, dept_id = ? WHERE student_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sssii", $fname, $lname, $email, $dept, $student_id);
    
    if ($update_stmt->execute()) {
        header("Location: view_students.php?msg=edited");
        exit();
    }
}

// Fetch departments for the dropdown
$depts = $conn->query("SELECT * FROM departments");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* A slightly cleaner layout for a dedicated edit page */
        .edit-form { max-width: 500px; margin: 20px 0; display: flex; flex-direction: column; gap: 15px; }
        .edit-form input, .edit-form select { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; }
        .edit-form label { font-weight: bold; margin-bottom: -10px; color: #34495e; }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <h2><i class="fas fa-user-edit"></i> Edit Student Details</h2>
            <hr>

            <div class="form-container">
                <form method="POST" class="edit-form">
                    <label>Registration Number</label>
                    <input type="text" value="<?php echo $student['registration_no']; ?>" disabled style="background: #e9ecef;">
                    
                    <label>First Name</label>
                    <input type="text" name="first_name" value="<?php echo $student['first_name']; ?>" required>
                    
                    <label>Last Name</label>
                    <input type="text" name="last_name" value="<?php echo $student['last_name']; ?>" required>
                    
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?php echo $student['email']; ?>" required>
                    
                    <label>Department</label>
                    <select name="dept_id" required>
                        <?php while($d = $depts->fetch_assoc()): ?>
                            <option value="<?php echo $d['dept_id']; ?>" 
                                <?php echo ($d['dept_id'] == $student['dept_id']) ? 'selected' : ''; ?>>
                                <?php echo $d['dept_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    
                    <button type="submit" name="update_student" class="add-btn">Save Changes</button>
                    <a href="view_students.php" style="text-align: center; color: #7f8c8d; text-decoration: none; margin-top: 10px;">Cancel and go back</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
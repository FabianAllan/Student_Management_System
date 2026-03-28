<?php
include 'session_check.php';
include 'db_connect.php';

// 1. Handle Adding a Course
if (isset($_POST['add_course'])) {
    $code = $_POST['course_code'];
    $name = $_POST['course_name'];
    $credits = $_POST['credits'];
    $dept = $_POST['dept_id'];

    $sql = "INSERT INTO courses (course_code, course_name, credits, dept_id) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssii", $code, $name, $credits, $dept);
    
    if ($stmt->execute()) {
        header("Location: view_courses.php?msg=course_added");
    }
}

// 2. Fetch all departments for the dropdown
$depts = $conn->query("SELECT * FROM departments");

// 3. Fetch all courses for the table
$courses = $conn->query("SELECT c.*, d.dept_name FROM courses c JOIN departments d ON c.dept_id = d.dept_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Courses | SMS</title>
    <link rel="stylesheet" href="style.css">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <h2>Course Catalog</h2>

    <!-- Alert Box for Feedback Messages -->
            <?php if (isset($_GET['msg'])): ?>
    <div id="alert-box" class="alert <?php echo ($_GET['msg'] == 'deleted') ? 'alert-danger' : 'alert-success'; ?>">
        <?php 
            if ($_GET['msg'] == 'course_added') {
                echo "<i class='fas fa-check-circle'></i> Course recorded successfully!";
            } elseif ($_GET['msg'] == 'deleted') {
                echo "<i class='fas fa-trash-alt'></i> Course deleted permanently.";
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
    <h3>Define New Course</h3>
    <form method="POST" class="inline-form">
        <select name="dept_id" id="dept_select" required onchange="updatePrefix()">
    <option value="">-- Select Department --</option>
    
    <?php 
    // Resetting pointer just in case
    $depts->data_seek(0); 
    
    // Using a clean PHP echo to prevent any HTML bracket mix-ups
    while($d = $depts->fetch_assoc()) { 
        echo '<option value="' . $d['dept_id'] . '" data-name="' . $d['dept_name'] . '">' . $d['dept_name'] . '</option>';
    } 
    ?>
    

        <input type="text" name="course_code" id="course_code" placeholder="Course Code" required>
        
        <input type="text" name="course_name" placeholder="Course Title" required>
        <input type="number" name="credits" placeholder="Credits" min="1" max="6" required>
    
        <button type="submit" name="add_course" class="add-btn">Create Course</button>
    </form>
</div>

            <hr>

            <table class="content-table">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Credits</th>
                        <th>Department</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($courses->num_rows > 0): ?>
                        <?php while($row = $courses->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?php echo $row['course_code']; ?></strong></td>
                            <td><?php echo $row['course_name']; ?></td>
                            <td><?php echo $row['credits']; ?></td>
                            <td><?php echo $row['dept_name']; ?></td>

                             <td>
                    
                    <a href="delete_course.php?id=<?php echo $row['course_id']; ?>" class="text-danger" title="Delete_Course" onclick="return confirm('Are you sure you want to permanently delete this course?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5">No courses registered yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
            
<!--  JavaScript function to update course code prefix based on selected department -->
    <script>
function updatePrefix() {
    const deptSelect = document.getElementById('dept_select');
    const codeInput = document.getElementById('course_code');
    
    // Get the name of the selected department
    const selectedOption = deptSelect.options[deptSelect.selectedIndex];
    const deptName = selectedOption.getAttribute('data-name');

    // Logic for prefixes
    let prefix = "";
    if (deptName === "CyberSecurity") {
        prefix = "CYB";
    } else if (deptName === "Artificial Intelligence") {
        prefix = "AI";
    } else if (deptName === "DS" || deptName === "Data Science") {
        prefix = "DS";
    } else if (deptName === "Software Engineering") {
        prefix = "SE";
    }

    // Set the value of the text field
    codeInput.value = prefix;
    
    // Put the cursor at the end so the user can just start typing the number
    codeInput.focus();
}
</script>
</body>
</html>
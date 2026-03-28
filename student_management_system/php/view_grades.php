<?php
include 'session_check.php';
include 'db_connect.php';

// 1. The Grading Logic Function
function calculateGrade($score) {
    if ($score >= 80) return 'A';
    if ($score >= 75) return 'B+';
    if ($score >= 70) return 'B';
    if ($score >= 65) return 'C+';
    if ($score >= 60) return 'C';
    if ($score >= 55) return 'D+';
    if ($score >= 50) return 'D';
    return 'F';
}

// 2. Handle Adding a Grade
if (isset($_POST['add_grade'])) {
    $sid = $_POST['student_id'];
    $cid = $_POST['course_id'];
    $score = $_POST['score'];
    $grade_letter = calculateGrade($score); // Auto-calculate!

    $sql = "INSERT INTO grades (student_id, course_id, score, grade_letter) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iids", $sid, $cid, $score, $grade_letter);
    
    if ($stmt->execute()) {
        header("Location: view_grades.php?msg=graded");
    }
}

// 3. Fetch Students and Courses for Dropdowns
$students_list = $conn->query("SELECT student_id, first_name, last_name FROM students");
$courses_list = $conn->query("SELECT course_id, course_name FROM courses");

// 4. Fetch All Grades for the Table (The Big Join)
$grades_query = "SELECT g.*, s.first_name, s.last_name, c.course_name 
                 FROM grades g 
                 JOIN students s ON g.student_id = s.student_id 
                 JOIN courses c ON g.course_id = c.course_id";
$grades = $conn->query($grades_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Grade Book | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <h2>Academic Grade Book</h2>


<?php if (isset($_GET['msg'])): ?>
    <div id="alert-box" class="alert <?php echo ($_GET['msg'] == 'deleted') ? 'alert-danger' : 'alert-success'; ?>">
        <?php 
            if ($_GET['msg'] == 'graded') echo "<i class='fas fa-check-circle'></i> Grade recorded successfully!";
            elseif ($_GET['msg'] == 'deleted') echo "<i class='fas fa-trash-alt'></i> Grade deleted permanently.";
            elseif ($_GET['msg'] == 'edited') echo "<i class='fas fa-edit'></i> Grade updated successfully!";
            elseif ($_GET['msg'] == 'error') echo "<i class='fas fa-exclamation-triangle'></i> An error occurred.";
        ?>
    </div>
    <script>
        setTimeout(function() { document.getElementById('alert-box').style.display = 'none'; }, 3000);
    </script>
<?php endif; ?>

            <div class="form-container">
                <h3>Record New Score</h3>
                <form method="POST" class="inline-form">
                    <select name="student_id" required>
                        <option value="">Select Student</option>
                        <?php while($s = $students_list->fetch_assoc()): ?>
                            <option value="<?php echo $s['student_id']; ?>">
                                <?php echo $s['first_name'] . " " . $s['last_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <select name="course_id" required>
                        <option value="">Select Course</option>
                        <?php while($c = $courses_list->fetch_assoc()): ?>
                            <option value="<?php echo $c['course_id']; ?>">
                                <?php echo $c['course_name']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <input type="number" name="score" placeholder="Score (0-100)" min="0" max="100" required>
                    
                    <button type="submit" name="add_grade" class="add-btn">Save Grade</button>
                </form>
            </div>

            <hr>

            <table class="content-table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Course</th>
                        <th>Score</th>
                        <th>Grade</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $grades->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['first_name'] . " " . $row['last_name']; ?></td>
                        <td><?php echo $row['course_name']; ?></td>
                        <td><?php echo $row['score']; ?>%</td>
                        <td><strong><?php echo $row['grade_letter']; ?></strong></td>

                        <td>
                            <a href="edit_grade.php?id=<?php echo $row['grade_id']; ?>" class="text-edit" title="Edit Grade">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <a href="delete_grade.php?id=<?php echo $row['grade_id']; ?>" class="text-danger" title="Delete Grade" onclick="return confirm('Are you sure you want to permanently delete this grade record?');">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
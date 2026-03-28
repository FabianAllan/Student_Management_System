<?php
include 'session_check.php';
include 'db_connect.php';

if ($_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// Grading Algorithm
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

// 1. GET Phase: Fetch the specific grade
if (isset($_GET['id'])) {
    $grade_id = $_GET['id'];
    
    // Join with students and courses to display the names contextually
    $query = "SELECT g.*, s.first_name, s.last_name, c.course_name 
              FROM grades g 
              JOIN students s ON g.student_id = s.student_id 
              JOIN courses c ON g.course_id = c.course_id 
              WHERE g.grade_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $grade_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 0) {
        header("Location: view_grades.php");
        exit();
    }
    $grade_data = $result->fetch_assoc();
} else {
    header("Location: view_grades.php");
    exit();
}

// 2. POST Phase: Update the grade
if (isset($_POST['update_grade'])) {
    $new_score = $_POST['score'];
    $new_letter = calculateGrade($new_score);

    $update_sql = "UPDATE grades SET score = ?, grade_letter = ? WHERE grade_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("dsi", $new_score, $new_letter, $grade_id);
    
    if ($update_stmt->execute()) {
        header("Location: view_grades.php?msg=edited");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Grade | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .edit-form { max-width: 400px; margin: 20px 0; display: flex; flex-direction: column; gap: 15px; }
        .edit-form input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; box-sizing: border-box; }
        .edit-form label { font-weight: bold; margin-bottom: -10px; color: #34495e; }
        .readonly-text { background: #e9ecef; color: #6c757d; cursor: not-allowed; }
    </style>
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <h2><i class="fas fa-edit"></i> Edit Grade</h2>
            <hr>

            <div class="form-container">
                <form method="POST" class="edit-form">
                    <label>Student</label>
                    <input type="text" value="<?php echo $grade_data['first_name'] . ' ' . $grade_data['last_name']; ?>" class="readonly-text" readonly>
                    
                    <label>Course</label>
                    <input type="text" value="<?php echo $grade_data['course_name']; ?>" class="readonly-text" readonly>
                    
                    <label>Score (0-100)</label>
                    <input type="number" name="score" id="score_input" value="<?php echo $grade_data['score']; ?>" min="0" max="100" step="0.01" required onkeyup="calculateGradeUI()" onchange="calculateGradeUI()">
                    
                    <label>Calculated Grade</label>
                    <input type="text" id="grade_display" value="<?php echo $grade_data['grade_letter']; ?>" class="readonly-text" readonly style="font-weight: bold;">
                    
                    <button type="submit" name="update_grade" class="add-btn" style="margin-top: 10px;">Save Updated Grade</button>
                    <a href="view_grades.php" style="text-align: center; color: #7f8c8d; text-decoration: none;">Cancel</a>
                </form>
            </div>
        </div>
    </div>

    <script>
    function calculateGradeUI() {
        const scoreInput = document.getElementById('score_input').value;
        const gradeDisplay = document.getElementById('grade_display');
        if (scoreInput === "") { gradeDisplay.value = ""; return; }
        
        const score = parseFloat(scoreInput);
        if (score >= 80) { gradeDisplay.value = 'A'; gradeDisplay.style.color = 'green'; } 
        else if (score >= 75) { gradeDisplay.value = 'B+'; gradeDisplay.style.color = 'blue'; } 
        else if (score >= 70) { gradeDisplay.value = 'B'; gradeDisplay.style.color = 'blue'; } 
        else if (score >= 65) { gradeDisplay.value = 'C+'; gradeDisplay.style.color = '#d35400'; } 
        else if (score >= 60) { gradeDisplay.value = 'C'; gradeDisplay.style.color = '#d35400'; } 
        else if (score >= 55) { gradeDisplay.value = 'D+'; gradeDisplay.style.color = '#e67e22'; } 
        else if (score >= 50) { gradeDisplay.value = 'D'; gradeDisplay.style.color = '#e67e22'; } 
        else { gradeDisplay.value = 'F'; gradeDisplay.style.color = 'red'; }
    }
    // Run once on load to colorize the existing grade
    window.onload = calculateGradeUI;
    </script>
</body>
</html>
<?php
include 'session_check.php';
include 'db_connect.php';

// Ensure only students can access this specific page
if ($_SESSION['user_role'] != 'student') {
    header("Location: dashboard.php");
    exit();
}

$student_id = $_SESSION['student_id'];

// 1. Fetch Student Details & Department
$stmt = $conn->prepare("SELECT s.*, d.dept_name FROM students s 
                        JOIN departments d ON s.dept_id = d.dept_id 
                        WHERE s.student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

// 2. Fetch Grades and Course Credits
$query = "SELECT g.*, c.course_name, c.course_code, c.credits 
          FROM grades g 
          JOIN courses c ON g.course_id = c.course_id 
          WHERE g.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$results = $stmt->get_result();

// 3. GPA Logic (Optional but impressive)
$total_points = 0;
$total_credits = 0;
$grade_data = [];

while($row = $results->fetch_assoc()) {
    $grade_data[] = $row;
    $total_points += ($row['score'] * $row['credits']);
    $total_credits += $row['credits'];
}
$gpa = ($total_credits > 0) ? round(($total_points / ($total_credits * 100)) * 5, 2) : 0; // 5.0 Scale
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Portal | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <div class="sidebar student-theme">
            <div class="logo-container">
                <h2>UniSys <span style="font-size: 50px; color: #f1c40f;">Student</span></h2>
            </div>
            <ul class="nav-links">
                <li><a href="#"><i class="fas fa-id-card"></i> My Results</a></li>
                <li class="logout-item"><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>

        <div class="main-content">
            <header class="main-header">
                <div>
                    <h1>Welcome, <?php echo $student['first_name']; ?>!</h1>
                    <p>ID: <?php echo $student['registration_no']; ?> | <?php echo $student['dept_name']; ?></p>
                </div>
                <div class="gpa-badge">
                    <span>GPA</span>
                    <strong><?php echo $gpa; ?></strong>
                </div>
            </header>

            <div class="transcript-container">
                <h3>Academic Transcript</h3>
                <table class="content-table">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Credits</th>
                            <th>Score</th>
                            <th>Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(count($grade_data) > 0): ?>
                            <?php foreach($grade_data as $row): ?>
                            <tr>
                                <td><?php echo $row['course_code']; ?></td>
                                <td><?php echo $row['course_name']; ?></td>
                                <td><?php echo $row['credits']; ?></td>
                                <td><?php echo $row['score']; ?>%</td>
                                <td><strong><?php echo $row['grade_letter']; ?></strong></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No grades published yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
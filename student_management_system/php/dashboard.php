<?php
include 'session_check.php'; // Lock the page
include 'db_connect.php';   // Connect to DB

// Fetch counts for the Stat Cards
$student_count = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'];
$course_count = $conn->query("SELECT COUNT(*) as total FROM courses")->fetch_assoc()['total'];
$dept_count = $conn->query("SELECT COUNT(*) as total FROM departments")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard | SMS</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <?php include 'sidebar.php'; ?>

        <div class="main-content">
            <header class="main-header">
                <h1>System Overview</h1>
                <p>Logged in as: <strong><?php echo $_SESSION['user_name']; ?></strong></p>
            </header>

            <div class="stat-cards">
                <div class="card">
                    <i class="fas fa-user-graduate"></i>
                    <h3><?php echo $student_count; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="card">
                    <i class="fas fa-book-open"></i>
                    <h3><?php echo $course_count; ?></h3>
                    <p>Total Courses</p>
                </div>
                <div class="card">
                    <i class="fas fa-building"></i>
                    <h3><?php echo $dept_count; ?></h3>
                    <p>Departments</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
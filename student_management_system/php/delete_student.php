<?php
include 'session_check.php';
include 'db_connect.php';

// 1. Security Check: Only Admins are allowed to delete records
if ($_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
    exit();
}

// 2. Check if an ID was actually passed in the URL
if (isset($_GET['id'])) {
    $student_id = $_GET['id'];

    // 3. Prepare the DELETE statement safely to prevent SQL Injection
    $stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);

    // 4. Execute and redirect back to the table
    if ($stmt->execute()) {
        header("Location: view_students.php?msg=deleted");
    } else {
        header("Location: view_students.php?msg=error");
    }
    
    $stmt->close();
} else {
    // If someone tries to access this page without an ID, send them back
    header("Location: view_students.php");
}

$conn->close();
?>
<?php
include 'session_check.php';
include 'db_connect.php';

if ($_SESSION['user_role'] != 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $grade_id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM grades WHERE grade_id = ?");
    $stmt->bind_param("i", $grade_id);

    if ($stmt->execute()) {
        header("Location: view_grades.php?msg=deleted");
    } else {
        header("Location: view_grades.php?msg=error");
    }
    $stmt->close();
} else {
    header("Location: view_grades.php");
}
$conn->close();
?>
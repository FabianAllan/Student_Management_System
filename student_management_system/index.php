<?php
session_start();
include 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role == 'admin') {
        $stmt = $conn->prepare("SELECT * FROM admins WHERE username = ?");
    } else {
        $stmt = $conn->prepare("SELECT * FROM students WHERE email = ?");
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Checking the password
        if ($password === $user['password']) {
            $_SESSION['user_role'] = $role;
            $_SESSION['user_name'] = ($role == 'admin') ? $user['full_name'] : $user['first_name'];
            
            if ($role == 'admin') {
                $_SESSION['admin_id'] = $user['admin_id'];
                header("Location: dashboard.php");
            } else {
                $_SESSION['student_id'] = $user['student_id'];
                header("Location: student_portal.php");
            }
            exit();
        } else { $error = "Invalid password."; }
    } else { $error = "Account not found."; }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Student Management System</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body { background: #f4f7f6; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; font-family: sans-serif; }
        .login-card { background: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 20px rgba(0,0,0,0.1); width: 350px; text-align: center; }
        .role-toggle { margin-bottom: 20px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #009879; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        button:hover { background: #007f65; }
        .error { color: red; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>STUDENT MANAGEMENT SYSTEM <br> Login</h2>
        <?php if($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <div class="role-toggle">
                <input type="radio" name="role" value="admin" id="admin" checked> <label for="admin">Admin</label>
                <input type="radio" name="role" value="student" id="student"> <label for="student">Student</label>
            </div>
            <input type="text" name="username" placeholder="Username or Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
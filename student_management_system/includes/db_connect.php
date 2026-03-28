<?php
// Database credentials
$host = "localhost";
$username = "root"; 
$password = "";     
$database = "student_management_system"; // Updated database name

// Establish the connection
$conn = new mysqli($host, $username, $password, $database);

// Check if the connection failed
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
<?php
session_start();

// If the "user_role" session variable isn't set, the user isn't logged in
if (!isset($_SESSION['user_role'])) {
    header("Location: index.php");
    exit();
}
?>
<?php
session_start();
ob_start(); // Start output buffering
include '../db/database_connection.php'; // Ensure this file does not output anything

if (isset($_POST['email']) && isset($_POST['password'])) {
    // Sanitize form data
    $user = mysqli_real_escape_string($conn, $_POST['email']);
    $pass = mysqli_real_escape_string($conn, $_POST['password']);
    
    // Query to check credentials
    $query = "SELECT * FROM SVM_Principal WHERE user_name='$user' AND user_password='$pass'";
    $result = mysqli_query($conn, $query);
    
    // If credentials match, redirect to index.html
    if (mysqli_num_rows($result) > 0) {
        header("Location: ../index.html"); // Adjust path if needed
        exit();
    } else {
        echo "Incorrect username or password. Please try again.";
    }
} else {
    echo "Please fill in all required fields.";
}
?>


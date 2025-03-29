<?php
$servername = "localhost"; 
$username   = "root";     // Default XAMPP username
$password   = "mayur";         // Default XAMPP password is empty
$dbname     = "SVM_local";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
?>
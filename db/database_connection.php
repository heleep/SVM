<?php
$servername = "localhost"; 
$username   = "root";     
$password   = "mayur";         // Default XAMPP password is empty
$dbname     = "SVM_local";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}
?>
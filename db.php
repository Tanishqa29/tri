<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "employee";

$conn = mysqli_connect($host, $user, $password, $database);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>

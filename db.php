<?php
$host = "localhost";
$user = "root";
$password = "password123";
$database = "noticeboard";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
<?php
$hostname = "127.0.0.1"; 
$username = "root";
$password = ""; 
$database = "web";
$port = "3306"; 

$conn = mysqli_connect($hostname, $username, $password, $database, $port);

if (!$conn) {
    die("Connection failed: (" . mysqli_connect_errno() . ") " . mysqli_connect_error());
}
?>

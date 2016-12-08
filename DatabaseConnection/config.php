<?php

$servername = "localhost";
$username = "epicharyana";
$password = "epicharyana123";
$dbname = "epicharyanadb";
// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>

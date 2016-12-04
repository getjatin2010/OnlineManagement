<?php 

error_reporting(E_ERROR | E_PARSE);
session_start();

$_SESSION['username'] = $_POST["username"]; 
$_SESSION['password'] = $_POST["password"]; 

header("Location: ../Face/login.php?id=''");		

?>
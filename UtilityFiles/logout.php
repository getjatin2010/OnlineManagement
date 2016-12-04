<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
$_SESSION['username']=NULL;
$_SESSION['password']=NULL;
$_SESSION['dist_id']=NULL;
header("Location: ../Face/login.php?id=''");
?>
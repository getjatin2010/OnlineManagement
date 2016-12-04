<?php
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==NULL)
 {
	include('../Html/login.html');
 }
else if($var==0)
 {
 	header("Location: ../Face/home.php#/pending");	
 }
else
{
   header("Location: ../Face/home_non_admin.php#/pending");	
}
 
?>




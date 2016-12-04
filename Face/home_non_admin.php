<?php
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==NULL || $var==0)
{
header("Location: login.php?id=''");	
}

include('../Html/home_non_admin.html');

?>

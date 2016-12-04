<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include('../UtilityFiles/checkLogin.php');
include('../UtilityFiles/getAdminRecords.php');
$var = check_login_func();
if($var==NULL || $var!=0)
{
	header("Location: ../Face/login.php?id=''");	
	exit();	
}
$var = getRecords();
echo $var;


function getRecords()
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM transactions where delivered = 0 ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}
?>



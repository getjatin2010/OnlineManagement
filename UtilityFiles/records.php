<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include('../UtilityFiles/checkLogin.php');
include('../UtilityFiles/getNonAdminRecords.php');
$var = check_login_func();
if($var==0 || $var==NULL)
{
	header("Location: ../Face/login.php?id=''");	
}

$var = getRecords();
echo $var;



function getRecords()
{
	$userId = $_SESSION['username'];
	$sql = "SELECT * FROM transactions where districtId = ".$_SESSION["dist_id"]." && Disabled = 0 ORDER BY dateOfRecord DESC";
	//$sql = "SELECT * FROM transactions where delivered = 0 ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}


?>

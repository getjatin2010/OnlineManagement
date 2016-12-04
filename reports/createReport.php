<?php
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();

if($var==NULL || $var!=0)
{
	header("Location: ../Face/login.php?id=''");
	exit();	
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);

$_SESSION['startDate'] = $arr->fromDate;
$_SESSION['endDate'] = $arr->toDate;
$_SESSION['startDate'] = substr($_SESSION['startDate'], 0,10);
$_SESSION['endDate'] = substr($_SESSION['endDate'], 0,10);
$_SESSION['mobile'] = $arr->mobile;
echo $_SESSION['startDate'];
echo $_SESSION['endDate'];


?>

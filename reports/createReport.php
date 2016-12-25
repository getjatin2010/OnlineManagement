<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();

if($var==NULL || $var!=0)
{
	header("Location: ../Face/login.php?id=''");
	exit();	
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);

$_SESSION['reportRoundId'] = $arr->roundId;
echo $_SESSION['reportRoundId'];
?>

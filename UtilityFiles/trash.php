<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include('../UtilityFiles/checkLogin.php');
include('../UtilityFiles/getAdminRecords.php');
$var = check_login_func();
if($var==NULL || $var!=0)
{
	$return_arr = array();
	$return_arr["login"] = "0";
	$var = json_encode($return_arr);
	echo $var;
	return;
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$roundId = $arr->roundId;


$var = getRecords($roundId);	
echo $var;


function getRecords($roundId)
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM transactions where Disabled = 1 && roundId = '$roundId' ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}


function getRecordsChoosing($value)
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM transactions where districtId = '$value' && Disabled = 1  ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}

?>



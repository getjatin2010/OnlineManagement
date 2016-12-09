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

if($arr->districtId>=1 && $arr->districtId <=22)
{
$var = getRecordsChoosing($arr->districtId);
}
else
{
$var = getRecords();	
}
echo $var;


function getRecords()
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM transactions where delivered = 0 && Disabled = 0 ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}


function getRecordsChoosing($value)
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM transactions where delivered = 0 && districtId = '$value' && Disabled = 0  ORDER BY dateOfRecord DESC";	
	$response = adminRecords($sql);
	return $response;
}

?>



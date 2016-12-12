<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include('../UtilityFiles/checkLogin.php');
include('../UtilityFiles/getContactRecords.php');
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


$var = getRecords();	
echo $var;


function getRecords()
{
	$userId = $_SESSION['username'];	
	$sql = "SELECT * FROM Contacts ORDER BY id ASC";
	$response = ContactRecords($sql);
	return $response;
}


?>



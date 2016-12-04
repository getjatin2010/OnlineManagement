<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==null)
{
	header("Location: ../Face/login.php?id=''");	
	exit();
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$var = getRecords($arr);
echo $var;


function getRecords($arr)
{
	include "../DatabaseConnection/config.php";
	$recordId = mysqli_real_escape_string($conn,$arr->recordId);
	//$recordId  = 168;
	$sql = "SELECT * FROM response WHERE recordId = '".$recordId."'";
	$row_array = array();
			
	$fetch = mysqli_query($conn,$sql); 
	$row = 	$fetch->fetch_assoc();

	$row_array['responseId'] = $row['responseId'];
	$row_array['comments'] = $row['comments'];
	$row_array['defective'] = $row['defective'];
	$row_array['received'] = $row['received'];
	$row_array['time'] = $row['time'];
	$row_array['response_by'] = $row['response_by'];
	
	mysqli_close($conn);
	return json_encode($row_array);
}
?>
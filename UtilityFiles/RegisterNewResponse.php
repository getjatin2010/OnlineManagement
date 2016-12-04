<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==0 || $var==NULL)
{
	header("Location: ../Face/login.php?id=''");	
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$var = enterRecordsInDb($arr);

function enterRecordsInDb($arr)
{


	include "../DatabaseConnection/config.php";
	
	$sender = $_SESSION['username'];
	
	$recordId = mysqli_real_escape_string($conn,$arr->recordId);
	$receivedQdata = mysqli_real_escape_string($conn,$arr->response_quant_received);
	$defective = mysqli_real_escape_string($conn,$arr->response_quant_defective);
	$comments = mysqli_real_escape_string($conn,$arr->response_comments);

	
	
	$sql = "INSERT INTO response(recordId, comments, defective, received, response_by) VALUES ('$recordId','$comments','$defective','$receivedQdata','".$sender."')";
	

	$query=mysqli_query($conn,$sql);
	
	$sql = "UPDATE transactions SET delivered = 1 WHERE recordId = '$recordId'";
	
	$query2=mysqli_query($conn,$sql);

	if ($query && $query2) 
	{
		return true;
	}
	mysqli_close($conn);
	return false;
}

?>

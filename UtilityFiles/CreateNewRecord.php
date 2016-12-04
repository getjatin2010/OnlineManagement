<?php
error_reporting(E_ERROR | E_PARSE);
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
$var = enterRecordsInDb($arr);

function enterRecordsInDb($arr)
{


	include "../DatabaseConnection/config.php";

	$districtData = $arr->districtSelected;
	$distJson = json_decode($districtData);
	$dist_no = $distJson->districtId;

	$assemblyData = $arr->assemblySelected;
	$assemblyJson = json_decode($assemblyData);
	$ac_no = $assemblyJson->acid;
	
	
	$mediumData = $arr->mediumSelected;
	$mediumJson = json_decode($mediumData);
	$medium_no = $mediumJson->medium_id;	


	$quantity = mysqli_real_escape_string($conn,$arr->quantity);
	$date_rec = mysqli_real_escape_string($conn,$arr->sendDate);
	$comments = mysqli_real_escape_string($conn,$arr->comment);
	$userId = $_SESSION['username'];
	$date_rec = substr($date_rec, 0,10);
	echo $date_rec;

	$sql = "INSERT INTO transactions (districtId,acid, mobileNumberSender, DateOfSending, Quantity, mediumId, AdditionalComments) VALUES ('$dist_no','$ac_no','$userId','$date_rec','$quantity','$medium_no','$comments')";
	echo $sql; 
	$query=mysqli_query($conn,$sql);

	if ($query) 
	{
		echo "true";
		return true;
	}
	mysqli_close($conn);
	return false;
}

?>
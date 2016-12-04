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


$var = enterRecordsInDb();
echo $var;


function enterRecordsInDb()
{
	include "../DatabaseConnection/config.php";
	$userId = $_SESSION['username'];
	$sql = "SELECT * FROM mediumofsending";
	$return_arr = array();
	
	$fetch = mysqli_query($conn,$sql); 
	
	while ($row = $fetch->fetch_assoc()) {			
		$row_array['medium_id'] = $row['medium_id'];
		$row_array['medium_name'] = $row['medium_name'];
	array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>
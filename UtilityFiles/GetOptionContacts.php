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


$var = getDistrictList();
echo $var;


function getDistrictList()
{
	include "../DatabaseConnection/config.php";
	$userId = $_SESSION['username'];
	$sql = "SELECT DISTINCT districtName, id FROM Contacts";
	$return_arr = array();
	
		$row_array['district'] = "All";
		$row_array['districtId'] = "99";
		array_push($return_arr,$row_array);
	
	$fetch = mysqli_query($conn,$sql); 		
	while ($row = $fetch->fetch_assoc()) {			
		$row_array['district'] = $row['districtName'];
		$row_array['districtId'] = $row['id'];
	array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>

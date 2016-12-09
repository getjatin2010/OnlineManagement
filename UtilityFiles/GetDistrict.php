<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==NULL || $var!=0)
{
	
	$return_arr = array();
	$return_arr["login"] = "0";
	$var = json_encode($return_arr);
	echo $var;
	return;
}


$var = getDistrictList();
echo $var;


function getDistrictList()
{
	include "../DatabaseConnection/config.php";
	$userId = $_SESSION['username'];
	$sql = "SELECT * FROM district";
	$return_arr = array();
	
	$fetch = mysqli_query($conn,$sql); 
	
	while ($row = $fetch->fetch_assoc()) {			
		$row_array['district'] = $row['district_name'];
		$row_array['districtId'] = $row['DistrictId'];
	array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>
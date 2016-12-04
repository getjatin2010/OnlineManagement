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
$var = getRecords($arr);
//$var = getRecords("1");
//$var = "Geg";
echo $var;


function getRecords($arr)
{
	include "../DatabaseConnection/config.php";
	$sql = "SELECT * FROM aclist WHERE dist_no = ".$arr->districtId."";
	$return_arr = array();
			
	$fetch = mysqli_query($conn,$sql); 
	
	while ($row = $fetch->fetch_assoc()) {			
		$row_array['acid'] = $row['ac_no'];
		$row_array['ac'] = $row['ac_name_e'];
		$row_array['distid'] = $row['dist_no'];
	    array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>
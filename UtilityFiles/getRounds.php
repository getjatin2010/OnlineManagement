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


$var = getRounds();
echo $var;


function getRounds()
{
	include "../DatabaseConnection/config.php";
	$sql = "SELECT * FROM  `Rounds`";
	$return_arr = array();
	
	$fetch = mysqli_query($conn,$sql); 
	while ($row = $fetch->fetch_assoc()) {			
		$row_array['roundId'] = $row['roundId'];
		$row_array['from'] = $row['from'];
		$row_array['to'] = $row['to'];
		array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>
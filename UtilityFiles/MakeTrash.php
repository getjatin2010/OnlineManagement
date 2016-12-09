<?php
error_reporting(E_ERROR | E_PARSE);
session_start();
include('../UtilityFiles/checkLogin.php');
$var = check_login_func();
if($var==null)
{
	
	$return_arr = array();
	$return_arr["login"] = "0";
	$var = json_encode($return_arr);
	echo $var;
	return;
}


$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$var = makeTrash($arr);
echo $var;


function makeTrash($arr)
{
	include "../DatabaseConnection/config.php";
	$recordId = mysqli_real_escape_string($conn,$arr->recordId);
	//$recordId  = 168;
	$sql = "UPDATE  `transactions` SET  `Disabled` =  '1' WHERE  `recordId` = '".$recordId."'";
	$row_array = array();			
	$fetch = mysqli_query($conn,$sql); 
	if($fetch)
		{
		$row_array['trashed'] = '1';
		}
	else
	{
	$row_array['trashed'] = '0';
	} 
	mysqli_close($conn);
	return json_encode($row_array);
}
?>

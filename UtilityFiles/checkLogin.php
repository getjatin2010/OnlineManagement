<?php
function check_login_func()
{
	$message = "Invalid Username/Password";
	include "../DatabaseConnection/config.php";
	if(!isset($_SESSION['username']))
	{
		return NULL;
	}
	if(!isset($_SESSION['password']))
	{	
		return NULL;
	}
	$name = mysqli_real_escape_string($conn,$_SESSION['username']);
	$pass = mysqli_real_escape_string($conn,$_SESSION['password']);
	
	$sql = "SELECT * FROM userdetail WHERE Name = '$name'";

	$query=mysqli_query($conn,$sql);

	if ($query) 
	{
	$row = mysqli_fetch_assoc($query);
	if(strtolower($row['password'])==strtolower($pass) && $row['password']!=null)
	{
	   if(!isset($_SESSION['dist_id']))
	    {
	     $_SESSION['dist_id'] = $row['DistrictId'];
	    }
	 return $_SESSION['dist_id'];		
	}
	
	}
	mysqli_close($conn);
	
	echo "<script type='text/javascript'>alert('$message');</script>";	
	return NULL;
}
?>

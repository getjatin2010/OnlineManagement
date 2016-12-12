<?php

function ContactRecords($sql)
{
		
	include "../DatabaseConnection/config.php";
	$return_arr = array();
	$fetch = mysqli_query($conn,$sql); 	
	while ($row = $fetch->fetch_assoc()) 
{			
		$row_array['id'] = $row['id'];
		$row_array['districtName'] = $row['districtName'];
		$row_array['PersonName'] = $row['PersonName'];
		$row_array['stdCode'] = $row['stdCode'];
		$row_array['ContactLL'] = $row['ContactLL'];
		$row_array['ContactMM'] = $row['ContactMM'];
	    array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>

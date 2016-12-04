<?php

function adminRecords($sql)
{
		
	include "../DatabaseConnection/config.php";
	$return_arr = array();
	$fetch = mysqli_query($conn,$sql); 	
	while ($row = $fetch->fetch_assoc()) 
{			
		$row_array['recordId'] = $row['recordId'];
		$districtId = $row['districtId'];
		$acId = $row['acid'];
		$mediumId = $row['mediumId']; 
		
		$sqlmedium = "SELECT * FROM mediumofsending where  medium_id = '".$mediumId."'";
		$fetchmedium = mysqli_query($conn,$sqlmedium); 			
		$rowmedium = $fetchmedium->fetch_assoc();
		$row_array['medium_name'] = $rowmedium['medium_name'];


		$sqlDistrict = "SELECT * FROM district where  DistrictId = '".$districtId."'";
		$fetchDistrict = mysqli_query($conn,$sqlDistrict); 			
		$rowDistrict = $fetchDistrict->fetch_assoc();
		$row_array['district'] = $rowDistrict['district_name'];
		$row_array['districtId'] = $districtId;

		$sqlac = "SELECT * FROM aclist where  ac_no = '".$acId."'";
		$fetchac = mysqli_query($conn,$sqlac); 			
		$rowac = $fetchac->fetch_assoc();
		$row_array['assembly'] = $rowac['ac_name_e'];
		$row_array['assemblyId'] = $acId;

		$dateTemp=date_create($row['dateOfSending']);
		$row_array['dateOfSending'] = date_format($dateTemp,"d-M-Y");	
	
		$row_array['dateOfRecord'] = $row['dateOfRecord'];
		$row_array['quantity'] = $row['quantity'];
		$row_array['additionalComments'] = $row['additionalComments'];
		
				
		$delivered = $row['delivered'];
		$row_array['deliveryCode'] = $delivered;
		
	if($delivered=='1')
	{
	

	$recordId = $row_array['recordId'];
	$sql2 = "SELECT * FROM response WHERE recordId = '".$recordId."'";		
	$fetch2 = mysqli_query($conn,$sql2); 
	$row2 = $fetch2->fetch_assoc();

	$row_array['responseId'] = $row2['responseId'];
	$row_array['comments'] = $row2['comments'];
	$row_array['defective'] = $row2['defective'];
	$row_array['received'] = $row2['received'];	
	$dateTemp=date_create($row2['time']);
	$row_array['time'] = date_format($dateTemp,"d-M-Y");
	$row_array['response_by'] = $row2['response_by'];
	$row_array['disableButton'] = 'false';
	$row_array['delivered'] = "VIEW RESPONSE";
	$row_array['backgroundColor'] = "#AAFCA3";
	if($row_array['defective']>0 || $row_array['quantity'] != $row_array['received'] )
		{	
		$row_array['backgroundColor'] = "#FFAEAE";
		}
	
	}		
	else
	{
	$row_array['disableButton'] = 'true';	
	$row_array['delivered'] = "RECEIVE EPIC";
	$row_array['backgroundColor'] = "#FFAEAE";
	}

	    $row_array['mobileNumberSender'] = $row['mobileNumberSender'];	
	    array_push($return_arr,$row_array);
}
	mysqli_close($conn);
	return json_encode($return_arr);
}
?>
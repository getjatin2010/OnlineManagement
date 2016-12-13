<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
require('../fpdf181/fpdf.php');
include('../UtilityFiles/checkLogin.php');



$var = check_login_func();
if($var==NULL)
{
	header("Location: ../Face/login.php?id=''");	
	exit();	
}

$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$totalG = 0;



class PDF extends FPDF
{


function Header()
{
    global $title;

    // Arial bold 15
    $this->SetFont('Arial','B',15);
    $this->Image('../img/logo.png',10,6,30);
    // Calculate width of title and position
    $w = $this->GetStringWidth($title)+6;
    $this->SetX((210-$w)/2);
    // Colors of frame, background and text
    $this->SetDrawColor(0,80,180);
    $this->SetTextColor(220,50,50);
    // Thickness of frame (1 mm)
    $this->SetLineWidth(1);
    // Title
    $this->Cell($w,15,$title,0,0,'C');
    $this->Ln(7);
    $this->SetTextColor(0,0,0); 
    // Line break
    $this->Ln(10);
     $this->SetTextColor(220,50,50);	
    $this->SetFont('Arial','B',10);

    $this->SetX(0);
    $this->Cell(30,15,"R ID",0,0,'C');
    $this->SetX(20);
    $this->Cell(30,15,"Sending Date",0,0,'C');
    
    $this->SetX(50);
    $this->Cell(30,15,"Epics Sent",0,0,'C');
    
    $this->SetX(80);
    $this->Cell(30,15,"Epics Received",0,0,'C');
    
    $this->SetX(110);
    $this->Cell(30,15,"Missing Epics",0,0,'C');
    
    $this->SetX(140);
    $this->Cell(30,15,"Defective Epics",0,0,'C');
    
    $this->SetX(170);
    $this->Cell(30,15,"Correct Epics",0,0,'C');
    
    $this->Ln(10);
	
}


function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Text color in gray
    $this->SetTextColor(128);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
function ChapterTitle($num, $label)
{
    // Arial 12
    $this->SetFont('Arial','',12);
    // Background color
    $this->SetFillColor(200,220,255);
    // Title
    $this->Cell(0,6,"$num : $label",0,1,'L',true);
       // Line break
  
}

function ChapterBody($num, $title, $acid)
{


global $totalGDelivered,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;
include "../DatabaseConnection/config.php";

$sql = "SELECT * FROM `transactions` WHERE `Disabled` = 0 && `acid` =  '$acid'  ORDER BY recordId ASC";
$fetch = mysqli_query($conn,$sql); 
$firstTime = true;

$totalDelivered = 0;
$totalReceived = 0;
$totalMissing = 0;
$totalDefective = 0;
$totalCorrect = 0;

while ($row = $fetch->fetch_assoc()) 
{          

    if($firstTime==true)
    {
        
        $this->ChapterTitle($acid,$title);       
        $firstTime = false; 
    }


    $epicDelivered = $row['quantity'];
	$totalDelivered = $totalDelivered + $epicDelivered;

    $recordId = $row['recordId'];
    $dateOfSending =  $row['dateOfSending'];
    $newDate = date("d-m-Y", strtotime($dateOfSending));

     $this->SetTextColor(0,0,0);    
     $this->SetFont('Arial','B',11);


    if( $row['delivered']==1)
    {
    $sql = "SELECT * FROM `response` WHERE  `recordId` =  '". $row['recordId']."'";
    $fetch2 = mysqli_query($conn,$sql); 

    $row2 = $fetch2->fetch_assoc();

    $epicReceived =  $row2['received'];
    $totalReceived = $totalReceived + $epicReceived;
    
    $epicDefective =  $row2['defective'];
	$totalDefective = $totalDefective + $epicDefective;

    $epicMissing = $epicDelivered -$epicReceived;
	$totalMissing = $totalMissing + $epicMissing;


    $epicCorrect = $epicReceived -$epicDefective;
	$totalCorrect = $totalCorrect + $epicCorrect;
    //$dateString = $this->changeFormat($receivedDate);


    $this->SetX(0);
    $this->Cell(30,15,$recordId,0,0,'C');
    $this->SetX(20);
    $this->Cell(30,15,$newDate,0,0,'C');
    
    $this->SetX(50);
    $this->Cell(30,15,$epicDelivered,0,0,'C');
    
    $this->SetX(80);
    $this->Cell(30,15,$epicReceived,0,0,'C');
    
    $this->SetX(110);
    $this->Cell(30,15,$epicMissing,0,0,'C');
    
    $this->SetX(140);
    $this->Cell(30,15,$epicDefective,0,0,'C');

    $this->SetX(170);
    $this->Cell(30,15,$epicCorrect,0,0,'C');



    }
    else
    {
    $this->SetX(0);
    $this->Cell(30,15,$recordId,0,0,'C');
    $this->SetX(20);
    $this->Cell(30,15,$newDate,0,0,'C');
    $this->SetX(50);
    $this->Cell(30,15,$epicDelivered,0,0,'C');
    $this->SetX(90);
    $this->Cell(30,15,"NOT RECEIVED",0,0,'C');         
    }
$this->Ln(5);    
}



if($firstTime==false)
{


$this->Ln(5);  	
$y = $this->GetY();
$this->SetX(10);
$this->SetLineWidth(.5);
$this->Line(60,$y,200,$y); 	

 	$this->SetX(0);
    $this->SetX(50);
    $this->Cell(30,5,$totalDelivered,0,0,'C');
    $totalGDelivered = $totalGDelivered + $totalDelivered;
    
    $this->SetX(80);
    $this->Cell(30,5,$totalReceived,0,0,'C');
    $totalGReceived = $totalGReceived + $totalReceived;

    $this->SetX(110);
    $this->Cell(30,5,$totalMissing,0,0,'C');
    $totalGMissing = $totalGMissing + $totalMissing;    

    $this->SetX(140);
    $this->Cell(30,5,$totalDefective,0,0,'C');
    $totalGDefective= $totalGDefective + $totalDefective;

    $this->SetX(170);
    $this->Cell(30,5,$totalCorrect,0,0,'C');
	$totalGCorrect = $totalGCorrect + $totalCorrect;



$this->Ln(10);



}


 }
function PrintGrandTotal($word)
{


global $totalGDelivered,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;

$this->Ln(5);
    $this->SetFont('Arial','B',12);
    $w = $this->GetStringWidth($word)+6;
    $this->SetX(10);	
    $this->SetDrawColor(0,80,180);
    $this->SetTextColor(220,50,50);
    $this->SetLineWidth(1);
    //$this->Cell($w,15,$word,0,0,'C');

    $this->SetTextColor(0,0,0); 
    $this->SetFont('Arial','B',12);
    $this->Ln(15);

    $totalGDeliveredString = "Total EPICS Sent";
    $totalGReceivedString = "Total EPICS Received";
    $totalGMissingString = "Total EPICS Missing";
    $totalGDefectiveString = "Total EPICS Defective";
    $totalGCorrectString = "Total EPICS Correct";
    
    $this->SetX(10);
    
    $this->Cell(20,5,$totalGDeliveredString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGDelivered,0,0,'C');	
    $this->Ln(5);

    $this->Cell(20,5,$totalGReceivedString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGReceived,0,0,'C');	
    $this->Ln(5);

    $this->Cell(20,5,$totalGMissingString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGMissing,0,0,'C');	
    $this->Ln(5);

    $this->Cell(20,5,$totalGDefectiveString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGDefective,0,0,'C');	
    $this->Ln(8);
	$this->Cell(20,5,$totalGCorrectString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGCorrect,0,0,'C');	
    $this->Ln(5);
   

}


function PrintChapter($num, $title, $id)
{
    $this->ChapterBody($num, $title, $id);
}

}

global $totalGDelivered,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;

$totalGDelivered = 0;
$totalGReceived = 0;
$totalGCorrect = 0;
$totalGDefective = 0;
$totalGMissing = 0;

$pdf = new PDF();
$pdf->SetTitle("EPICS UNDER TRANSITION");
$pdf->SetAuthor('Ecarts');
$districtId = $_SESSION['dist_id'];
include "../DatabaseConnection/config.php";

$sql = "SELECT * FROM district where DistrictId = '$districtId'";
$fetch = mysqli_query($conn,$sql); 
$row = $fetch->fetch_assoc();
$districtName  = $row['district_name'];
$title = "DELIVERY REPORT OF ".$districtName;
$sql = "SELECT * FROM `aclist` WHERE `dist_no` =  '$districtId'";
$fetch = mysqli_query($conn,$sql); 
$count = 0 ;
$pdf->AddPage();	
while ($row = $fetch->fetch_assoc()) {			
$count++;
$pdf->PrintChapter($count,$row['ac_name_e'],$row['ac_no']);
}
$pdf->PrintGrandTotal('');
$pdf->Output();
mysqli_close($conn);
?>

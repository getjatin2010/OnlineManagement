<?php
session_start();
require('../fpdf181/fpdf.php');
include('../UtilityFiles/checkLogin.php');



$var = check_login_func();
if($var==NULL || $var!=0)
{
	header("Location: ../Face/login.php?id=''");	
	exit();	
}

$filedata = file_get_contents('php://input',true);
$arr = json_decode($filedata);
$startDate = $_SESSION['startDate'];
$endDate = $_SESSION['endDate'];
$totalG = 0;
$totalGReceived = 0;
$totalGDefective = 0;
$totalGCorrect = 0;
$totalGMissing = 0;



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
    $dateStarting = $this->changeFormat($_SESSION['startDate']);
    $dateEnding = $this->changeFormat($_SESSION['endDate']);
    $line2 =  $dateStarting."    to    ".$dateEnding;
    $w = $this->GetStringWidth($line2)+6;
    $this->SetX((210-$w)/2);
    $this->SetTextColor(0,0,0); 
    $this->Cell($w,15,$line2,0,0,'C');
    // Line break
    $this->Ln(10);
     $this->SetTextColor(220,50,50);	
    $this->SetFont('Arial','B',10);
    $this->SetX(50);
    $this->Cell(30,15,"Sent Epics",0,0,'C');
    $this->Cell(30,15,"Missing Epics",0,0,'C');
    $this->Cell(30,15,"Received Epics",0,0,'C');
    $this->Cell(30,15,"Defective Epics",0,0,'C');
    $this->Cell(30,15,"Correct Epics",0,0,'C');
    
    $this->Ln(10);

	
}


function changeFormat($input)
{
   $inputArr = explode ("-", $input);
  return ($inputArr[2]."-".$inputArr[1]."-".$inputArr[0]); 
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
    $this->Ln(4);
}

function ChapterBody($file)
{


global $totalG,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;

include "../DatabaseConnection/config.php";
$this->SetFont('Times','',12);
$sql = "SELECT * FROM aclist WHERE dist_no = ".$file."";
$fetchAc = mysqli_query($conn,$sql); 
mysqli_close($conn);
$totalD = 0;
$totalDReceived = 0 ;
$totalDDefective = 0;
$totalDCorrect = 0;
$totalDMissing = 0;


$countAc = 0 ;
while ($rowAc = $fetchAc->fetch_assoc()) {			
		$countAc = $countAc + 1;
		$this->Cell(20,5,$rowAc['ac_no'].' '.$rowAc['ac_name_e'],0,0);


/// Getting Data

/// geetting transactions that happened
		include "../DatabaseConnection/config.php";
		$sql = "SELECT * FROM `transactions` WHERE `Disabled` = 0 && `delivered` = 1 && `acid` = '".$rowAc['ac_no']."' && `dateOfSending` <= '".$_SESSION['endDate']."' && `dateOfSending` >= '".$_SESSION['startDate']."'";		
		$fetchtrans = mysqli_query($conn,$sql);
		mysqli_close($conn);			
		$total = 0 ;
		$totalDefective = 0;
		$totalReceived = 0;		
		$totalMissing = 0;
		$totalCorrect = 0;
		$responseFound = 0;
		 
		while ($rowtrans = $fetchtrans->fetch_assoc())
		{
		  $reponseFound = 1;
		  $total = $total + $rowtrans['quantity'];
		  //finding response for each transaction
		  include "../DatabaseConnection/config.php";
		  $sql = "SELECT * FROM `response` WHERE `recordId` = '".$rowtrans['recordId']."'";
		  $fetchresp = mysqli_query($conn,$sql);
		  mysqli_close($conn);
		  $rowresp = $fetchresp->fetch_assoc();
		  $totalDefective = $totalDefective + $rowresp['defective'];					
		  $totalReceived = $totalReceived + $rowresp['received'];
		  
		 if(mysqli_num_rows($fetchresp)>0)
		 {	
			$responseFound = 2;
		 }	
				
		}
		if($responseFound == 2)
		{
			$totalMissing = $total - $totalReceived;
			$totalCorrect = $totalReceived - $totalDefective;
		}
		else if ($responseFound == 1)
		{
		$totalDefective = "";
		$totalReceived = "";		
		$totalMissing = "";
		$totalCorrect = "";
		}
		else
		{
			$totalReceived = '-';
			$totalDefective = '-';
			$totalMissing = '-';
			$totalCorrect = '-';		
		}
		$this->SetX(50);
    		$this->Cell(30,5,$total,0,0,'C');
		$this->Cell(30,5,$totalMissing,0,0,'C');
		$this->Cell(30,5,$totalReceived,0,0,'C');
		$this->Cell(30,5,$totalDefective,0,0,'C');
		$this->Cell(30,5,$totalCorrect,0,0,'C');
		$this->Ln();

		if($responseFound==2)
		{
		$totalD = $totalD+$total;
		$totalDReceived  = $totalDReceived + $totalReceived;
		$totalDDefective = $totalDDefective + $totalDefective;
		$totalDCorrect = $totalDCorrect + $totalCorrect;
		$totalDMissing = $totalDMissing + $totalMissing; 		
		}
	}
	$this->Ln();
	$y = $this->GetY();
        $this->Cell(30,5,"Total",0,0,'C');
	$this->SetX(50);	
	$this->Line(50,$y,200,$y);
	$this->Cell(30,5,$totalD,0,0,'C');
	$this->Cell(30,5,$totalDMissing,0,0,'C');
	$this->Cell(30,5,$totalDReceived,0,0,'C');
	$this->Cell(30,5,$totalDDefective,0,0,'C');
	$this->Cell(30,5,$totalDCorrect,0,0,'C');
	$this->Ln(10);
	

	//---------------------
	$totalG = $totalG+$totalD;
	$totalGReceived  = $totalDReceived + $totalGReceived;
	$totalGDefective = $totalDDefective + $totalGDefective;
	$totalGCorrect = $totalDCorrect + $totalGCorrect;
	$totalGMissing = $totalDMissing + $totalGMissing;
	
	//---------------------
	
 }

function PrintGrandTotal($word)
{


global $totalG,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;

$this->Ln(5);
    $this->SetFont('Arial','B',12);
    $w = $this->GetStringWidth($word)+6;
    $this->SetX(10);	
    $this->SetDrawColor(0,80,180);
    $this->SetTextColor(220,50,50);
    $this->SetLineWidth(1);
    $this->Cell($w,15,$word,0,0,'C');

    $this->SetTextColor(0,0,0); 
    $this->SetFont('Arial','B',12);
    $this->Ln(15);
    $totalString = "Total Sent Epics";
    $totalMissingString = "Total Missing Epics";	 
    $totalDefectiveString = "Total Defective Epics";	 
    $totalCorrectString = "Total Correct Epics";	 
    $totalReceivedString = "Total Received Epics";	 
    
    $this->SetX(30);
    $this->Cell(20,5,$totalString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalG,0,0,'C');	
    $this->Ln(5);
    
    $this->SetX(30);
    $this->Cell(20,5,$totalMissingString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGMissing,0,0,'C');	
    $this->Ln(5);
   
   $this->SetX(30);
    $this->Cell(20,5,$totalReceivedString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGReceived,0,0,'C');	
    $this->Ln(5);
   	
   $this->SetX(30);
    $this->Cell(20,5,$totalDefectiveString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGDefective,0,0,'C');	
    $this->Ln(5);
   

    $this->SetX(30);
    $this->Cell(20,5,$totalCorrectString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalGCorrect,0,0,'C');	
    $this->Ln(5);
   

}


function PrintChapter($num, $title, $file)
{
    $this->ChapterTitle($num,$title);
    $this->ChapterBody($file);
}

}

$pdf = new PDF();
$title = "ACCEPTED EPIC SUMMARY";
$pdf->SetTitle("ACCEPTED EPIC SUMMARY");
$pdf->SetAuthor('Ecarts');

include "../DatabaseConnection/config.php";
$sql = "SELECT * FROM district";
$fetch = mysqli_query($conn,$sql); 
$count = 0 ;
mysqli_close($conn);
$pdf->AddPage();	
while ($row = $fetch->fetch_assoc()) {			
$count++;
$pdf->PrintChapter($count,$row['district_name'],$row['DistrictId']);
}
$pdf->PrintGrandTotal('GRAND TOTAL');
$pdf->Output();
?>


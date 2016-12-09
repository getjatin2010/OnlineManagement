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
    $this->SetX(50);
    $this->Cell(30,15,"Sent Epics",0,0,'C');
    
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
    $this->Ln(4);
}

function ChapterBody($num, $title, $file)
{

$headerPrinted = false;
global $totalG,$totalGReceived,$totalGDefective,$totalGCorrect,$totalGMissing;

include "../DatabaseConnection/config.php";
$this->SetFont('Times','',12);
$sql = "SELECT * FROM aclist WHERE dist_no = ".$file."";
$fetchAc = mysqli_query($conn,$sql); 
mysqli_close($conn);
$totalD = 0;


$countAc = 0 ;
while ($rowAc = $fetchAc->fetch_assoc()) {			
		$countAc = $countAc + 1;

/// Getting Data

/// geetting transactions that happened
		include "../DatabaseConnection/config.php";
		$sql = "SELECT * FROM `transactions` WHERE `Disabled` = 0 && `delivered` = 0 && `acid` = '".$rowAc['ac_no']."'";		
		$fetchtrans = mysqli_query($conn,$sql);
		mysqli_close($conn);			
		$total = 0 ;
		while ($rowtrans = $fetchtrans->fetch_assoc())
		{
		  $reponseFound = 1;
		  $total = $total + $rowtrans['quantity'];	
		}
		if($total!=0)
		{
		if($headerPrinted==false)
		{
   		$this->ChapterTitle($num,$title);
		$headerPrinted = true;		
		}		
		$this->Cell(20,5,$rowAc['ac_no'].' '.$rowAc['ac_name_e'],0,0);
		$this->SetX(50);
    		$this->Cell(30,5,$total,0,0,'C');
		$this->Ln();
		$totalD = $totalD+$total;
		}		
	}
	if($totalD!=0)
	{
	$this->Ln();
	$y = $this->GetY();
	$this->SetX(50);	
	$this->Line(61,$y,69,$y);
	$this->Cell(30,5,$totalD,0,0,'C');
	$this->Ln(10);
	
	//---------------------
	$totalG = $totalG+$totalD;
	//---------------------
	}
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
    $totalString = "Total epics under Transition";
    $this->SetX(10);
    $this->Cell(20,5,$totalString,0,0);
    $this->SetX(70);
    $this->Cell(30,5,$totalG,0,0,'C');	
    $this->Ln(5);
   

}


function PrintChapter($num, $title, $file)
{
    $this->ChapterBody($num, $title, $file);
}

}

$pdf = new PDF();
$title = "EPICS UNDER TRANSITION";
$pdf->SetTitle("EPICS UNDER TRANSITION");
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
$pdf->PrintGrandTotal('');
$pdf->Output();
?>


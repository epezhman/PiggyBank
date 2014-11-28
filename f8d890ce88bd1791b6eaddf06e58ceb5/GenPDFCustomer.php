<?php
session_start();
require_once("accesscontrol.php");
require_once("fpdf.php");
require_once("dbconnect.php");

class PiggyPDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    $this->Image('../images/logo.png',5,5,20);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    $this->Cell(80);
    // Title
    $this->Cell(45,10,'PiggyBank GmbH',1,0,'C');
    // Line break
    $this->Ln(20);
}

// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}
}

$fullName = NULL;
$userID = NULL;
$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
$customerFullName = $dbConnection->prepare("SELECT customerName, customerID FROM Customer WHERE customerUsername LIKE (?)");
$customerFullName->bind_param("s", $userUsername);
$customerFullName->execute();
$customerFullName->bind_result($name, $ID);
$customerFullName->store_result();

if($customerFullName->num_rows() == 1)
{
	while($customerFullName->fetch())
	{
		$fullName = $name;
		$userID = $ID;
	}
}
$customerFullName->free_result();
$customerFullName->close();

// Retrieve customer's account number
$accountQuery = $dbConnection->prepare("SELECT accountNumber FROM Account WHERE accountOwner LIKE (?)");
$accountQuery->bind_param("s", $userID);
$accountQuery->execute();
$accountQuery->bind_result($aNumber);
$accountQuery->store_result();
if($accountQuery->num_rows() == 1){
	while($accountQuery->fetch()){
		$accountNumber = $aNumber;
	}
}
$accountQuery->free_result();
$accountQuery->close();

if(! empty($userID))
{
	$pdf = new PiggyPDF();

	$pdf->AddPage();

	//$pdf->SetFont('Arial','B',15);
	// Move to the right
	// Title
	$pdf->Cell(0, 10,'Transaction History of '. $fullName,1, 0, 'C');
	
	// Line break
	$pdf->Ln(20);	
	
	$transfers = $dbConnection->prepare("SELECT transactionSender, transactionReceiver, transactionAmount, transactionTime, transactionApproved FROM Transaction WHERE transactionSender LIKE (?) OR transactionReceiver LIKE (?) ORDER BY transactionTime DESC");
	$transfers->bind_param("ss", mysqli_real_escape_string($dbConnection,$accountNumber), mysqli_real_escape_string($dbConnection,$accountNumber));
	$transfers->execute();
	$transfers->bind_result( $transactionSender, $transactionReceiver, $transactionAmount, $transactionTime, $transactionApproved);
	$transfers->store_result();
	$i = 0;


	$pdf->SetFillColor(0,0,0);
	$pdf->SetTextColor(209,163,25);
	$pdf->SetTextColor(209,163,25);
	$pdf->SetLineWidth(.3);
	$pdf->SetFont('','B', '10');
	// Header
	$w = array(8, 45, 45, 20, 45, 30);
	$pdf->Cell($w[0],7,'#',1,0,'C',true);
	$pdf->Cell($w[1],7,'Sender',1,0,'C',true);
	$pdf->Cell($w[2],7,'Receiver',1,0,'C',true);
	$pdf->Cell($w[3],7,'Amount',1,0,'C',true);
	$pdf->Cell($w[4],7,'Sent Date',1,0,'C',true);
	$pdf->Cell($w[5],7,'Status',1,0,'C',true);

	$pdf->Ln();
	// Color and font restoration
	$pdf->SetFillColor(224,235,255);
	$pdf->SetTextColor(0);
	$pdf->SetFont('');
	// Data
	$fill = false;
	$euro = iconv('utf-8', 'cp1252', '€');
	while($transfers->fetch())
	{
		$i++;
		$pdf->Cell($w[0],6,$i,'LR',0,'C',$fill);
			
		$pdf->Cell($w[1],6,$transactionSender,'LR',0,'C',$fill);
		
		$pdf->Cell($w[2],6,$transactionReceiver,'LR',0,'C',$fill);

		$pdf->Cell($w[3],6,$euro.$transactionAmount,'LR',0,'C',$fill);

		$pdf->Cell($w[4],6,$transactionTime,'LR',0,'C',$fill);
		
		if($transactionApproved)
		{
			$pdf->Cell($w[5],6,'Approved','LR',0,'C',$fill);
				
		}
		else
		{
			$pdf->Cell($w[5],6,'Pending','LR',0,'C',$fill);
		}

		$pdf->Ln();
		$fill = !$fill;
	}
	
	$pdf->Cell(array_sum($w),0,'','T');

	$transfers->free_result();
	$transfers->close();

		$pdf->Output();
	}

?>


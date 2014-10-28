<?php
session_start();
require_once("accesscontrol.php");
require_once("fpdf.php");
require_once("dbconnect.php");


if (isset($_POST['customerID'])) {


	$fullName = NULL;
	$userID = NULL;
	$userUsername = mysqli_real_escape_string($dbConnection,trim($_GET['customerID']));
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


	if(! empty($userID))
	{
		$pdf = new FPDF();

		$pdf->AddPage();

		$pdf->SetFont('Arial','B',15);
		// Move to the right
		// Title
		$pdf->Cell(0,15,'Piggy Bank GmbH  --  Transactions of '. $fullName,1);

		// Line break
		$pdf->Ln(20);

		$transfers = $dbConnection->prepare("SELECT transactionReceiver, transactionAmont, transactionTime, transactionApproved FROM Transaction WHERE transactionSender LIKE (?) ORDER BY transactionTime DESC");
		$transfers->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
		$transfers->execute();
		$transfers->bind_result( $transactionReceiver, $transactionAmont, $transactionTime, $transactionApproved);
		$transfers->store_result();
		$i = 0;


		$pdf->SetFillColor(255,0,0);
		$pdf->SetTextColor(255);
		$pdf->SetDrawColor(128,0,0);
		$pdf->SetLineWidth(.3);
		$pdf->SetFont('','B', '10');
		// Header
		$w = array(40, 35, 40, 45, 30);
		$pdf->Cell($w[0],7,'#',1,0,'C',true);
		$pdf->Cell($w[1],7,'Receiver',1,0,'C',true);
		$pdf->Cell($w[2],7,'Amount',1,0,'C',true);
		$pdf->Cell($w[3],7,'Sent Date',1,0,'C',true);
		$pdf->Cell($w[4],7,'Status',1,0,'C',true);

		$pdf->Ln();
		// Color and font restoration
		$pdf->SetFillColor(224,235,255);
		$pdf->SetTextColor(0);
		$pdf->SetFont('');
		// Data
		$fill = false;

		while($transfers->fetch())
		{
			$i++;
			$pdf->Cell($w[0],6,$i,'LR',0,'L',$fill);
				
			$customerFullName = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerID LIKE (?)");
			$customerFullName->bind_param("s", mysqli_real_escape_string($dbConnection,$transactionReceiver));
			$customerFullName->execute();
			$customerFullName->bind_result($name);
			$customerFullName->store_result();

			while($customerFullName->fetch())
			{
				$pdf->Cell($w[1],6,$name,'LR',0,'L',$fill);
			}

			$customerFullName->free_result();
			$customerFullName->close();

			$pdf->Cell($w[2],6,$transactionAmont,'LR',0,'L',$fill);

			$pdf->Cell($w[3],6,$transactionTime,'LR',0,'L',$fill);

			if($transactionApproved)
			{
				$pdf->Cell($w[4],6,'Approved','LR',0,'L',$fill);

			}
			else
			{
				$pdf->Cell($w[4],6,'Pending','LR',0,'L',$fill);
			}

			$pdf->Ln();
			$fill = !$fill;
		}

		$pdf->Cell(array_sum($w),0,'','T');

		$transfers->free_result();
		$transfers->close();

		$pdf->Output();
	}
}

?>


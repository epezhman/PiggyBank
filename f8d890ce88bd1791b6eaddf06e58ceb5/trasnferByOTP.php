<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<table style="width: 100%">
		<tr>
			<td align="center">
				<h1>Your request is being processed. Please wait.</h1>
			
			<td>
		
		</tr>
		<tr>
			<td align="center"><img src="../images/loading.gif" alt="loading.gif" />
			</td>
		</tr>
	</table>
	<?php

	session_start();
	require_once("accesscontrol.php");
	require_once("utils.php");
	require_once("dbconnect.php");

	function doTransfer($transactionSender, $transactionReceiver, $transactionAmount){
		try{
			global $dbConnection;
			$transactionSender = mysqli_real_escape_string($dbConnection, $transactionSender);
			$transactionReceiver = mysqli_real_escape_string($dbConnection, $transactionReceiver);
			$transcationAmount = mysqli_real_escape_string($dbConnection, $transactionAmount);
			$approved = false;
			if($transactionAmount <= 10000 )
				$approved = true;
			$transactionID = "";
			$loop = true;
			while($loop){
				$transactionID = getRandomStringWithoutDot(20);
				$checkAny = $dbConnection->prepare("SELECT * FROM Transaction WHERE transactionID LIKE (?)");
				$checkAny->bind_param("s", mysqli_real_escape_string($dbConnection,$transactionID));
				$checkAny->execute();
				$checkAny->store_result();
				if($checkAny->num_rows() == 0){
					$checkAny->free_result();
					$checkAny->close();
					$loop = false;
				}
				$checkAny->free_result();
				$checkAny->close();
			}
			// Store the transaction details
			$transferDB = $dbConnection->prepare("INSERT INTO Transaction VALUES (?,?,?,?,?,?,?)");
			$transferDB->bind_param("ssssss", mysqli_real_escape_string($dbConnection,$transactionID), $transactionSender, $transactionReceiver, $transcationAmount, mysqli_real_escape_string($dbConnection,date('Y-m-d H:i:s')), $approved);
			$transferDB->execute();
			if($transferDB->affected_rows >= 1){
					

				if($approved){
					// Update the sender's account
					// Step 1 - Get account balance before transaction
					$senderBalance = 0.0;
					$senderAccountQuery = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountNumber LIKE (?)");
					$senderAccountQuery->bind_param("s", $transactionSender);
					$senderAccountQuery->execute();
					$senderAccountQuery->bind_result($accountBalance);
					$senderAccountQuery->store_result();
					while($senderAccountQuery->fetch()){
						$senderBalance = $accountBalance;
					}
					$senderAccountQuery->close();
					$newBalance = $senderBalance - $transactionAmount;
					// Step 2 - Deduce the transferred amount
					$updateSenderAccountQuery = $dbConnection->prepare("UPDATE Account SET accountBalance= ? WHERE accountNumber LIKE (?)");
					$updateSenderAccountQuery->bind_param("ss", $newBalance, $transactionSender);
					$updateSenderAccountQuery->execute();
					$updateSenderAccountQuery->close();
					// Update the receiver's account
					// Step 1 - Get account balance before transaction
					$receiverBalance = 0.0;
					$receiverAccountQuery = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountNumber LIKE (?)");
					$receiverAccountQuery->bind_param("s", $transactionReceiver);
					$receiverAccountQuery->execute();
					$receiverAccountQuery->bind_result($accountBalance);
					$receiverAccountQuery->store_result();
					while($receiverAccountQuery->fetch()){
						$receiverBalance = $accountBalance;
					}
					$receiverAccountQuery->close();
					// Step 2 - Deduce the transferred amount
					$newBalance = $receiverBalance + $transactionAmount;
					$updateReceiverAccountQuery = $dbConnection->prepare("UPDATE Account SET accountBalance= ? WHERE accountNumber LIKE (?)");
					$updateReceiverAccountQuery->bind_param("ss",  $newBalance, $transactionReceiver);
					$updateReceiverAccountQuery->execute();
					$updateReceiverAccountQuery->close();
				}
			}
			else
				return false;
			$transferDB->close();

		}catch(Exception $e){
			return false;
		}
		return true;
	}

	function validateInput($input, $type){
		$regExpressions =  array("ReceiverId"=>"^[a-zA-Z0-9]+$", "TransferToken"=>"^[a-zA-Z0-9]+$", "Amount"=>"^[0-9.]+$");
		try{
			if (ereg($regExpressions[$type], $input))
				return true;
			else
				return false;
		}catch(Exception $e){
			return false;
		}
	}

	// -----------------------------
	// | Entry Point of the script |
	// -----------------------------
	try{
		//Check the referer first to deny nosey requests
		if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php") === false)
			header("Location: ../error.php?id=404");
			
		// TODO: Add a re-authentication here or a CSRF Token
		$_SERVER["HTTP_REFERER"] = "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php";

		// Retrieve and validate posted parameters
		if(validateInput(trim($_POST['ReceiverId']), "ReceiverId"))
			$receiverAccount = $_POST['ReceiverId'];
		if(validateInput(trim($_POST['TransferToken']), "TransferToken"))
			$transferToken =  $_POST['TransferToken'];
		if(validateInput(trim($_POST['Amount']), "Amount"))
			$amount = $_POST['Amount'];
		$userUsername = $_SESSION['username'];
		$transferFlag = true;

		if (isset($_POST['Amount'])) {
			if(!filter_var(trim($_POST['Amount']), FILTER_VALIDATE_FLOAT))
				$amount = false;
		}

		// Retrieve sender account number and balance for further computations
		$senderAccountQuery = $dbConnection->prepare("SELECT customerID, accountNumber, accountBalance FROM Account INNER JOIN Customer INNER JOIN User WHERE User.userUsername = Customer.customerUsername AND Customer.customerID = Account.accountOwner AND User.userUsername LIKE (?)");
		$senderAccountQuery->bind_param("s", $userUsername);
		$senderAccountQuery->execute();
		$senderAccountQuery->bind_result($cID, $sAccount, $sBalance);
		$senderAccountQuery->store_result();
		while($senderAccountQuery->fetch()){
			$customerID = $cID;
			$senderAccount = $sAccount;
			$senderBalance = $sBalance;
		}
		// Check for sufficient funds
		if($senderBalance - floatval(trim($amount)) < 0){
			$_SESSION["invNotEnoughMoney"] = true;
			$transferFlag = false;
		}
		// Check for self-transfer
		if($receiverAccount == $senderAccount){
			$_SESSION["invNotYourself"] = true;
			$transferFlag = false;
		}

		$customerInfo = $dbConnection->prepare("SELECT customerPIN, customerTransferSecurityMethod FROM Customer WHERE customerUsername LIKE (?)");
		$customerInfo->bind_param("s", mysqli_real_escape_string($dbConnection,$_SESSION['username']));
		$customerInfo->execute();
		$customerInfo->bind_result($pin, $cMethod);
		$customerInfo->store_result();

		if($customerInfo->num_rows() == 1)
		{
			while($customerInfo->fetch())
			{
				$customerPIN = $pin;
				$customerMethod = $cMethod;
			}
		}
		$customerInfo->free_result();
		$customerInfo->close();

		if($receiverAccount and $transferToken and $amount){
			$tokenStatus = 0;

			if($customerMethod == "2")
			{
				$toBeHashed = trim($amount) + trim($receiverAccount);
					
				$salt = $customerPIN + strrev($customerPIN);
					
				$firstHash =  hash('sha256', $toBeHashed.$salt);
					
				$checkFlag = true;
					
				for($i = 0 ; $i < 15 && $checkFlag ; $i++)
				{
					$firstHash =  hash('sha256', $toBeHashed.$salt);
					$hahsed = $firstHash;
					if($hahsed."" == trim($transferToken) )
						$checkFlag= false;
				}
					
				if($checkFlag)
				{
					$tokenStatus = 1;
				}
			}
			else
			{
				$tokenStatus = 1;
			}

			if($tokenStatus == 1){
				$_SESSION["invInvalidOTP"] = true;
				$transferFlag = false;
			}

			// Retrieve the accountNumber of the receiver, if they exist.
			$accountExistQuery = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountNumber LIKE (?) ");
			$accountExistQuery->bind_param("s", mysqli_real_escape_string($dbConnection,$receiverAccount));
			$accountExistQuery->execute();
			$accountExistQuery->bind_result($aBalance);
			$accountExistQuery->store_result();
			while($accountExistQuery->fetch()){
				$accountOwnerBalance = $aBalance;
			}
			// If the receiver account does not exist
			if(!isset($accountOwnerBalance)){
				$_SESSION["invNotFoundAccount"] = true;
				$transferFlag = false;
			}
			$accountExistQuery->free_result();
			$accountExistQuery->close();

			// All checks done? Carry out the transaction
			if($transferFlag)
				if (doTransfer($senderAccount, $receiverAccount, $amount)){
				$_SESSION["invSuccessPaid"] = true;
			}
			else{
				header("Location: ../error.php");
				exit();
			}

			else{
				$_SESSION["invReceiverId"] = $receiverId ? NULL : $_POST["ReceiverId"];
				$_SESSION["invTransferToken"] = $transferToken ? NULL : $_POST["TransferToken"];
				$_SESSION["invAmount"] = $amount ? NULL : $_POST["Amount"];
			}
			header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
			exit();
		}
	}catch(Exception $e){
		header("Location ../error.php");
		exit();
}
?>
</body>
</html>

<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<table width="100%">
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
	function getRandomString($length = 8){
		$alphabet = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ._";
		$validCharNumber = strlen($alphabet);
		$result = "";
		for ($i = 0; $i < $length; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $alphabet[$index];
		}
		return $result;
	}

	function getRandomStringWithoutDot($length = 8){
		$alphabet = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ_";
		$validCharNumber = strlen($alphabet);
		$result = "";
		for ($i = 0; $i < $length; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $alphabet[$index];
		}
		return $result;
	}

	function validateInput($input, $type){
		// Peforms the same input validations carried out on the client-side to double check for errors/malice
		$regExpressions =  array("name"=>"/[A-Za-z ]+/", "address"=>"/[a-zA-Z0-9,'-. ]+/", "username"=>"/[0-9A-Za-z_.]+/", "password"=>"/[a-zA-Z0-9_.@!?]/");
		try{
			if (preg_match($regExpressions[$type], $input) == 1)
				return true;
			else
				return false;
		}catch(Exception $e){
			return false;
		}
	}

	function doTransfer($transactionSender, $transactionReceiver, $transactionAmount, $transactionDesc){
		try{
			global $dbConnection;
			$transactionSender = mysqli_real_escape_string($dbConnection, $transactionSender);
			$transactionReceiver = mysqli_real_escape_string($dbConnection, $transactionReceiver);
			$transcationAmount = mysqli_real_escape_string($dbConnection, $transactionAmount);
			$transactionDesc = mysqli_real_escape_string($dbConnection, $transactionDesc);
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
			$transferDB = $dbConnection->prepare("INSERT INTO Transaction  (transactionID, transactionSender, transactionReceiver, transactionAmount, transactionTime, transactionApproved, transactionDesc) VALUES (?,?,?,?,?,?,?)");
			$transferDB->bind_param("sssssss", mysqli_real_escape_string($dbConnection,$transactionID), $transactionSender, $transactionReceiver, $transcationAmount, mysqli_real_escape_string($dbConnection,date('Y-m-d H:i:s')), $approved, $transactionDesc);
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

	try{
		// Some basic access control checks
		ob_start();
		require("accesscontrol.php");
		if(ob_get_clean() == -1){
			header("Location: ../error.php?id=404");
			exit();
		}
		session_start();
		// Check for the CSRF token
		if(!isset($_POST["csrfToken"]) or ($_POST["csrfToken"] != $_SESSION["csrfToken"])){
			header("Location: ../error.php?id=403");
			exit();
		}

		require_once("dbconnect.php");
		$targetDir = "tmp/";
		$targetDir = $targetDir.sha1($_FILES["transFile"]["name"]).".txt";
		if(move_uploaded_file($_FILES["transFile"]["tmp_name"], $targetDir)){
			$parsedTransactions = exec("./parseTransaction ".$targetDir);
			if(!empty($parsedTransactions)){
				$transactionStatuses = "{\"transactions\": [";
				// Carry out the transfers
				$allTransactions = json_decode($parsedTransactions);
				// Iterate over transactions and carry them out
				foreach($allTransactions->{"transactions"} as $transaction){
					$transactionReceiver = $transaction->{"receiver"};
					$transactionToken = $transaction->{"token"};
					$transactionAmount = $transaction->{"amount"};
					$transactionDesc = $transaction->{"desc"};
					$transcationSender = "";
					$transferFlag = true;

					// Retrieve sender account number and balance for further computations
					$senderAccountQuery = $dbConnection->prepare("SELECT customerID, accountNumber, accountBalance FROM Account INNER JOIN Customer INNER JOIN User WHERE User.userUsername = Customer.customerUsername AND Customer.customerID = Account.accountOwner AND User.userUsername LIKE (?)");
					$senderAccountQuery->bind_param("s", $_SESSION["username"]);
					$senderAccountQuery->execute();
					$senderAccountQuery->bind_result($cID, $sAccount, $sBalance);
					$senderAccountQuery->store_result();
					while($senderAccountQuery->fetch()){
						$customerID = $cID;
						$transactionSender = $sAccount;
						$senderBalance = $sBalance;
					}
					// Check for sufficient funds
					if($senderBalance - floatval(trim($transactionAmount)) < 0){
						//$_SESSION["invNotEnoughMoney"] = true;
						$transactionStatuses = $transactionStatuses."{\"".$transactionReceiver."\":\"Insufficient Funds.\"}, ";
						$transferFlag = false;
					}
					// Check for self-transfer
					if($transactionReceiver == $transactionSender){
						//$_SESSION["invNotYourself"] = true;
						$transactionStatuses = $transactionStatuses."{\"".$transactionReceiver."\":\"Transfer to own account.\"}, ";
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
					
					if($transactionReceiver and $transactionToken and $transactionAmount){
						$tokenStatus = 0;
							

						if($customerMethod == "2")
						{
							$toBeHashed = trim($transactionAmount) . trim($transactionReceiver);

							$customerPIN = openssl_decrypt($customerPIN, "AES-128-CBC", "SomeVeryCrappyPassword?!!!WithNum2014");

							$salt = $customerPIN . strrev($customerPIN);
								
							$firstHash =  hash('sha256', $toBeHashed.$salt);
								
							$checkFlag = true;
							for($i = 0 ; $i < 15 && $checkFlag ; $i++)
							{
								$firstHash =  hash('sha256', $firstHash);
								if($firstHash == trim($transactionToken) )
									$checkFlag= false;
							}
								
							if($checkFlag)
							{
								$tokenStatus = 1;
							}
							//$tokenStatus = 0;
						}
						else
						{
							$tokenStatus = 1;
						}
						// Check if that particular token is valid
						if($tokenStatus == 1){
							//$_SESSION["invUsedToken"] = true;
							$transactionStatuses = $transactionStatuses."{\"".$transactionReceiver."\":\"Invalid Token.\"}, ";
							$transferFlag = false;
						}

							
							
							
						// Retrieve the accountNumber of the receiver, if they exist.
						$accountExistQuery = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountNumber LIKE (?) ");
						$accountExistQuery->bind_param("s", mysqli_real_escape_string($dbConnection,$transactionReceiver));
						$accountExistQuery->execute();
						$accountExistQuery->bind_result($aBalance);
						$accountExistQuery->store_result();
						while($accountExistQuery->fetch()){
							$accountOwnerBalance = $aBalance;
						}
						// If the receiver account does not exist
						if(!isset($accountOwnerBalance)){
							//	$_SESSION["invNotFoundAccount"] = true;
							$transferFlag = false;
						}
						$accountExistQuery->free_result();
						$accountExistQuery->close();

						// All checks done? Carry out the transaction
						if($transferFlag){
							if (doTransfer($transactionSender, $transactionReceiver, $transactionAmount, $transactionDesc))
								echo "Transaction Successful.<br/>";
							else{
								$transactionStatuses = $transactionStatuses."{\"".$transactionReceiver."\":\"Error Encountered.\"}, ";
								echo "Transaction Failed.<br/>";
							}
						}
					}
					$transactionStatuses = $transactionStatuses." ]}";
					$_SESSION["transactionStatus"] = $transactionStatuses;
				} // End of for each
				// Return parameters to transfer
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
				exit();
			} // End of Not empty parsed file
		} // End of arguments check and upload
		else{
			// return "unable to upload file".
			header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php?uploadfailure");
			exit();
		}
	}catch(Exception $e){
		header("Location ../error.php");
		exit();
	}
	?>
</body>
</html>

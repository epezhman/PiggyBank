<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php

	session_start();

	require_once("accesscontrol.php");
	require_once("utils.php");
	require_once("dbconnect.php");

	function validateInput($input, $type){
		//$regExpressions =  array("ReceiverId"=>"/^[a-zA-Z_]+$/", "TransferToken"=>"/^[a-zA-Z0-9]+$/", "Amount"=>"/^[0-9.]+$/");
		$regExpressions =  array("ReceiverId"=>"/^[a-zA-Z0-9_]+$/", "TransferToken"=>"/^[a-zA-Z0-9]+$/", "Amount"=>"/^[0-9.]+$/");

		try{
			if (preg_match($regExpressions[$type], $input) == 1)
				return true;
			else
				return false;
		}catch(Exception $e){
			return false;
		}
	}

	function doTransfer($transactionSender, $transactionReceiver, $transactionAmont, $transactionToken){
		try{
			global $dbConnection;
			$sender = mysqli_real_escape_string($dbConnection, $transactionSender);
			$receiver = mysqli_real_escape_string($dbConnection, $transactionReceiver);
			$amount = mysqli_real_escape_string($dbConnection, $transactionAmont);
			$token = mysqli_real_escape_string($dbConnection, $transactionToken);
			$approved = false;
			if($transactionAmont <= 10000 )
				$approved = true;
			$transactionID = "";
			$loop = true;
			while($loop)
			{
				$transactionID = getRandomStringWithoutDot(20);
				$checkAny = $dbConnection->prepare("SELECT * FROM Transaction WHERE transactionID LIKE (?)");
				$checkAny->bind_param("s", mysqli_real_escape_string($dbConnection,$transactionID));
				$checkAny->execute();
				$checkAny->store_result();
				if($checkAny->num_rows() == 0)
				{
					$checkAny->free_result();
					$checkAny->close();
					$loop = false;
				}
				$checkAny->free_result();
				$checkAny->close();

			}

			$transferDB = $dbConnection->prepare("INSERT INTO Transaction VALUES (?,?,?,?,?,?,?)");
			$transferDB->bind_param("sssssss", mysqli_real_escape_string($dbConnection,$transactionID)
					, $sender, $receiver, $amount, mysqli_real_escape_string($dbConnection,date('Y-m-d H:i:s')), $approved, $token);
			$transferDB->execute();
			if($transferDB->affected_rows >= 1){
				$updateToken = $dbConnection->prepare("UPDATE Token SET tokenUsed=1 WHERE tokenID LIKE (?)");
				$updateToken->bind_param("s",$token);
				$updateToken->execute();
				$updateToken->close();

				if($approved)
				{
					$customerBalance = 0.0;
					$customerAccount = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountOwner LIKE (?) ");
					$customerAccount->bind_param("s", $sender);
					$customerAccount->execute();
					$customerAccount->bind_result($customerBalanceDB);
					$customerAccount->store_result();

					while($customerAccount->fetch())
					{
						$customerBalance = $customerBalanceDB;
					}
					$customerAccount->close();

					$updateAccount = $dbConnection->prepare("UPDATE Account SET accountBalance= ? WHERE accountOwner LIKE (?)");
					$updateAccount->bind_param("ss",mysqli_real_escape_string($dbConnection, $customerBalance -$transactionAmont),$sender);
					$updateAccount->execute();
					$updateAccount->close();
					
					
					$customerBalance = 0.0;
					$customerAccount = $dbConnection->prepare("SELECT accountBalance FROM Account WHERE accountOwner LIKE (?)");
					$customerAccount->bind_param("s", $receiver);
					$customerAccount->execute();
					$customerAccount->bind_result($customerBalanceDB);
					$customerAccount->store_result();
					
					while($customerAccount->fetch())
					{
						$customerBalance = $customerBalanceDB;
					}
					$customerAccount->close();
					
					
					$updateAccount = $dbConnection->prepare("UPDATE Account SET accountBalance= ? WHERE accountOwner LIKE (?)");
					$updateAccount->bind_param("ss",mysqli_real_escape_string($dbConnection, $customerBalance  + $transactionAmont),$receiver);
					$updateAccount->execute();
					$updateAccount->close();
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
		//Check the referer first to deny nosey requests
		if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php") === false)
			header("Location: ../error.php?id=404");

		$_SERVER["HTTP_REFERER"] = "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php";

		// Retrieve and validate posted parameters
		$receiverId = validateInput(trim($_POST['ReceiverId']), "ReceiverId");
		$transferToken = validateInput(trim($_POST['TransferToken']), "TransferToken");
		$amount = validateInput(trim($_POST['Amount']), "Amount");

		if (isset($_POST['Amount'])) {
			if(!filter_var(trim($_POST['Amount']), FILTER_VALIDATE_FLOAT)) {
				$amount = false;
			}
		}
		
		$userID = NULL;
		$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
		$customerID = $dbConnection->prepare("SELECT customerID FROM Customer WHERE customerUsername LIKE (?)");
		$customerID->bind_param("s", $userUsername);
		$customerID->execute();
		$customerID->bind_result($ID);
		$customerID->store_result();
		if($customerID->num_rows() == 1)
		{
			while($customerID->fetch())
			{
				$userID = $ID;
			}
			$customerID->free_result();
			$customerID->close();
		}
		if(!empty($userID) and $receiverId and $transferToken and $amount)
		{

			$trasnferFlag = true;
			$tokensCountCount = 0;
			$tokensUsedStatus = 0;
			$tokensCount = $dbConnection->prepare("SELECT COUNT(*), tokenUsed FROM Token WHERE tokenCustomer LIKE (?) AND tokenID LIKE(?) ");
			$tokensCount->bind_param("ss", mysqli_real_escape_string($dbConnection,$userID), mysqli_real_escape_string($dbConnection,trim($_POST['TransferToken'])));
			$tokensCount->execute();
			$tokensCount->bind_result($tokenCount, $usedStatus);
			$tokensCount->store_result();

			while($tokensCount->fetch())
			{
				$tokensCountCount = $tokenCount;
				$tokensUsedStatus = $usedStatus;
			}
			if($tokensCountCount == 0)
			{
				$_SESSION["invNotFoundToken"] = true;
				$trasnferFlag = false;
			}
			if($tokensUsedStatus == 1)
			{
				$_SESSION["invUsedToken"] = true;
				$trasnferFlag = false;
			}

			$tokensCount->free_result();
			$tokensCount->close();

			// 			$receiverExist = 0;
			// 			$receiverExistQuery = $dbConnection->prepare("SELECT COUNT(*) FROM Customer WHERE customerID LIKE (?) ");
			// 			$receiverExistQuery->bind_param("s", mysqli_real_escape_string($dbConnection,trim($_POST['ReceiverId'])));
			// 			$receiverExistQuery->execute();
			// 			$receiverExistQuery->bind_result($receiverExistCount);
			// 			$receiverExistQuery->store_result();

			// 			while($receiverExistQuery->fetch())
			// 			{
			// 				$receiverExist = $receiverExistCount;
			// 			}			if(input)

			// 			if($receiverExist != 1)
			// 			{
			// 				$_SESSION["invNotFoundReceiver"] = true;
			// 				$trasnferFlag = false;
			// 			}
			// 			if(trim($_POST['ReceiverId']) == $userID)
			// 			{
			// 				$_SESSION["invNotYourself"] = true;
				// 				$trasnferFlag = false;
				// 			}

				// 			$receiverExistQuery->free_result();
				// 			$receiverExistQuery->close();

				$customerBalance = 0.0;
				$customerAccount = $dbConnection->prepare("SELECT accountBalance, accountNumber FROM Account WHERE accountOwner LIKE (?) ");
				$customerAccount->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
				$customerAccount->execute();
				$customerAccount->bind_result($customerBalanceDB, $customerAccountNumber);
				$customerAccount->store_result();

				while($customerAccount->fetch())
				{
					$customerBalance = $customerBalanceDB;
				}

				if($customerBalance - floatval(trim($_POST['Amount'])) < 0)
				{
					$_SESSION["invNotEnoughMoney"] = true;
					$trasnferFlag = false;
				}

				$customerAccount->free_result();
				$customerAccount->close();

				$accountOwner = Null;
				$accountOwnerBalance = 0.0;
				$accountExistQuery = $dbConnection->prepare("SELECT accountOwner, accountBalance FROM Account WHERE accountNumber LIKE (?) ");
				$accountExistQuery->bind_param("s", mysqli_real_escape_string($dbConnection,trim($_POST['ReceiverId'])));
				$accountExistQuery->execute();
				$accountExistQuery->bind_result($accountOwner,$accountExistBalance);
				$accountExistQuery->store_result();

				while($accountExistQuery->fetch())
				{
					$accountOwner = $accountOwner;
					$accountOwnerBalance = $accountExistBalance;
				}
				if($accountOwner == Null)
				{
					$_SESSION["invNotFoundAccount"] = true;
					$trasnferFlag = false;
				}
				if($accountOwner == $userID)
				{
					$_SESSION["invNotYourself"] = true;
					$trasnferFlag = false;
				}


				$accountExistQuery->free_result();
				$accountExistQuery->close();


				if($trasnferFlag)
				{
					if (doTransfer($userID, $accountOwner, floatval(trim($_POST['Amount'])), trim($_POST['TransferToken'])))
						$_SESSION["invSuccessPaid"] = true;
					else
						header("Location: ../error.php");
				}
			}
			else{
				$_SESSION["invReceiverId"] = $receiverId ? NULL : $_POST["ReceiverId"];
				$_SESSION["invTransferToken"] = $transferToken ? NULL : $_POST["TransferToken"];
				$_SESSION["invAmount"] = $amount ? NULL : $_POST["Amount"];
			}
			header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");

		}catch(Exception $e){
			header("Location ../error.php");
		}
		?>
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
</body>
</html>

<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php
	
	session_start();
	
	require("accesscontrol.php");

	require_once("dbconnect.php");
	
	function validateInput($input, $type){
		// Peforms the same input validations carried out on the client-side to double check for errors/malice
		$regExpressions =  array("ReceiverId"=>"/[a-zA-Z_]+$/", "TransferToken"=>"/[a-zA-Z0-9]+$+/", "Amount"=>"/[0-9.]+$/");
		try{
			if (preg_match($regExpressions[$type], $input) == 1)
				return true;
			else
				return false;
		}catch(Exception $e){
			return false;
		}
	}

	function registerCustomer(){
		// Carries out the necessary SQL statements to provision new users
		try{
			// Prepare the parameters
			$userUsername = mysqli_real_escape_string($dbConnection, $_POST['username']);

		}catch(Exception $e){
			return false;
		}
		return true;
	}

	try{
		// Check the referer first to deny nosey requests
// 		if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php") === false)
// 			header("Location: ../error.php?id=404");

// 		$_SERVER["HTTP_REFERER"] = "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php";
// 		// Retrieve and validate posted parameters
		$receiverId = validateInput($_POST['ReceiverId'], "ReceiverId");
		$transferToken = validateInput($_POST['TransferToken'], "TransferToken");
		$amount = validateInput($_POST['Amount'], "Amount");

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
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
			}
			if($tokensUsedStatus == 1)
			{
				$_SESSION["invUsedToken"] = true;
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
			}

			$tokensCount->free_result();
			$tokensCount->close();

			$receiverExist = 0;
			$receiverExistQuery = $dbConnection->prepare("SELECT COUNT(*) FROM Customer WHERE customerID LIKE (?) ");
			$receiverExistQuery->bind_param("s", mysqli_real_escape_string($dbConnection,trim($_POST['ReceiverId'])));
			$receiverExistQuery->execute();
			$receiverExistQuery->bind_result($receiverExistCount);
			$receiverExistQuery->store_result();

			while($receiverExistQuery->fetch())
			{
				$receiverExist = $receiverExistCount;
			}
			if($receiverExist != 1)
			{
				$_SESSION["invNotFoundReceiver"] = true;
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
			}
				
			$receiverExistQuery->free_result();
			$receiverExistQuery->close();

			// 			if (registerCustomer())
				// 				header("Location: ../notify.php?mode=success");
				// 			else
					// 				header("Location: ../error.php");

		}
		else{
			// Otherwise, post back to signup.php to inform user of failure
			$_SESSION["invReceiverId"] = $receiverId ? NULL : $_POST["ReceiverId"];
			$_SESSION["invTransferToken"] = $transferToken ? NULL : $_POST["TransferToken"];
			$_SESSION["invAmount"] = $amount ? NULL : $_POST["Amount"];
			header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
		}

	}catch(Exception $e){
		header("Location ../error.php");
	}
	?>
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
</body>
</html>

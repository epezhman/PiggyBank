<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php
	require("accesscontrol.php");

	require_once("dbconnect.php");

	function validateInput($input, $type){
		// Peforms the same input validations carried out on the client-side to double check for errors/malice
		$regExpressions =  array("ReceiverId"=>"/^[a-zA-Z_]+$/", "TransferToken"=>"/^[a-zA-Z0-9]+$+/", "Amount"=>"/^[0-9.]+$/");
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
		if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php") === false)
			header("Location: ../error.php?id=404");

		$_SERVER["HTTP_REFERER"] = "/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php";
		// Retrieve and validate posted parameters
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
		if(!empty($userID))
		{
			
			$tokenT = NULL;
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
			
		}


		// If validation succeeds, add user to database
		if($receiverId and $transferToken and $amount ){
			// Register user
			if (registerCustomer())
				header("Location: ../notify.php?mode=success");
			else
				header("Location: ../error.php");
		}
		else{
			// Otherwise, post back to signup.php to inform user of failure
			$_SESSION["invReceiverId"] = $fullnameStatus ? NULL : $_POST["ReceiverId"];
			$_SESSION["invTransferToken"] = $addressStatus ? NULL : $_POST["TransferToken"];
			$_SESSION["invAmount"] = $dobStatus ? NULL : $_POST["Amount"];

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

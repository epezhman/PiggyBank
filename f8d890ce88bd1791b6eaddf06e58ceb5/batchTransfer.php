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
        <td align="center">
            <img src="../images/loading.gif" alt="loading.gif"/>
        </td>
    </tr>
</table>

<?php

    
function doTransfer($transactionSender, $transactionReceiver, $transactionAmont, $transactionToken){
	try{
		global $dbConnection;
		$sender = mysqli_real_escape_string($dbConnection, $transactionSender);
		$receiver = mysqli_real_escape_string($dbConnection, $transactionReceiver);
		$amount = mysqli_real_escape_string($dbConnection, $transactionAmont);
		$token = mysqli_real_escape_string($dbConnection, $transactionToken);
		$approved = 0;
		if($transactionAmont <= 10000 )
			$approved = 1;
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
		$transferDB->bind_param("sssssis", mysqli_real_escape_string($dbConnection,$transactionID)
				, $sender, $receiver, $amount, mysqli_real_escape_string($dbConnection,date('Y-m-d H:i:s')), $approved, $token);
		$transferDB->execute();
                echo $transferDB->error;
                echo $transferDB->affected_rows;
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

function checkToken($transactionCustomer, $transactionToken){
	global $dbConnection;
	$tokensCount = $dbConnection->prepare("SELECT tokenUsed FROM Token WHERE tokenCustomer LIKE (?) AND tokenID LIKE (?) ");
	$tokensCount->bind_param("ss", mysqli_real_escape_string($dbConnection,$transactionCustomer), $transactionToken);
	$tokensCount->execute();
	$tokensCount->bind_result($usedStatus);
	$tokensCount->store_result();
        if ($tokensCount->num_rows() < 1)
            return false;
	while($tokensCount->fetch()){
		$tokensUsedStatus = $usedStatus;
	}
	if($tokensUsedStatus == 1)
		return false;

	$tokensCount->free_result();
	$tokensCount->close();
	return true;
}

function getCustomerID($userUsername){
	global $dbConnection;
	$userID = NULL;
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
	return $userID;
}

function getSenderAccount($accountOwner){
        global $dbConnection;
        $senderAccount = NULL;
        $accountSender = $dbConnection->prepare("SELECT accountNumber FROM Account WHERE accountOwner LIKE (?)");
        $accountSender->bind_param("s", $accountOwner);
        $accountSender->execute();
        $accountSender->bind_result($accountNumber);
        $accountSender->store_result();
        if($accountSender->num_rows() == 1)
        {
                while($accountSender->fetch())
                {
                        $senderAccount = $accountNumber;
                }
                $accountSender->free_result();
                $accountSender->close();
        }
        return $senderAccount;
}

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

try{
	// Some basic access control checks
    ob_start();
    require("accesscontrol.php");
    if(ob_get_clean() == -1){
        header("Location: ../error.php?id=404");
        exit();
    }
  require_once("dbconnect.php");
  session_start();
  $targetDir = "tmp/";
  $targetDir = $targetDir.sha1($_FILES["transFile"]["name"]).".txt";
  if(move_uploaded_file($_FILES["transFile"]["tmp_name"], $targetDir)){
	$parsedTransactions = exec("./parseTransaction ".$targetDir);
	if(!empty($parsedTransactions)){
                // Carry out the transfers
                $allTransactions = json_decode($parsedTransactions);
                // Iterate over transactions and carry them out
                foreach($allTransactions->{"transactions"} as $transaction){
                    $tReceiver = $transaction->{"receiver"};
                    $tToken = $transaction->{"token"};
                    $tAmount = $transaction->{"amount"};
                    // Get Sender ID
                    $customerID = getCustomerID($_SESSION["username"]);
                    // Check Token
                    if(checkToken($customerID, $tToken)) 
                        if(doTransfer($customerID, $tReceiver, $tAmount, $tToken))
                            echo "Transaction Successful.<br/>";
                        else
                            echo "Transaction Failed.<br/>";
                        
                }
                //exit();
		// Return parameters to transfer
		header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
		exit();
	}
	else{
		//return "insufficient arguments".
		header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php?parsefailure");
		exit();
	}
  }
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

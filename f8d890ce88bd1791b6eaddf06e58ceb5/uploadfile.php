<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
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
   session_start();
  $targetDir = "tmp/";
  $targetDir = $targetDir.sha1($_FILES["transFile"]["name"]).".txt";
  if(move_uploaded_file($_FILES["transFile"]["tmp_name"], $targetDir)){
	$parsedTransaction = exec("./parseTransaction ".$targetDir);
	if(!empty($parsedTransaction)){
		list($transactionReciver, $transactionToken, $transactionAmount) = explode(":", $parsedTransaction);
		// Return parameters to transfer
		header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php?receiver=".$transactionReciver."&token=".$transactionToken."&amount=".$transactionAmount);
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
</body>
</html>

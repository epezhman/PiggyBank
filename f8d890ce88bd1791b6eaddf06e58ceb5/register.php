<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
<?php
//if(strpos(getenv("HTTP_REFERER", "/PiggyBank") === false)){
    // Some basic access control code
//    ob_start();
//    require("accesscontrol.php");
//    if(!ob_get_clean()){
//        header("Location: ../error.php?id=404");
//        exit();
//    }


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

function getRandomNumber($length = 8){
    $alphabet = "1234567890";
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
                                                                                                                                    
    $regExpressions =  array("name"=>"^[A-Za-z ]+$", "address"=>"^[a-zA-Z0-9,'-. ]+$", "username"=>"^[0-9A-Za-z_.]+$", "password"=>"^[a-zA-Z0-9_.@!?]+$", "dob"=>"^[0-9/]+$", "hashedpassword"=>"^[a-f0-9]{64}$");

    try{
        if (ereg($regExpressions[$type], $input)){          
		return true;
        }else{
            return false;
	}
    }catch(Exception $e){
        return false;
    } 
}

function registerCustomer(){
// Carries out the necessary SQL statements to provision new users
    try{
            // Connect to the database
            require_once("dbconnect.php");
            // Prepare the parameters
            
            $secMethod = 1;
            $PIN = 0;
            if($_POST["secMethod"] == "1")
            {
            	$secMethod = 1;
            }
            else if($_POST["secMethod"] == "2")
            {
            	$secMethod = 2;
            }
            
            if($secMethod == 2)
            {
            	$PIN =  openssl_encrypt(getRandomNumber(6), "AES-128-CBC", "SomeVeryCrappyPassword?!!!WithNum2014");
            }
            
            $userUsername = mysqli_real_escape_string($dbConnection, $_POST['username']);
            $userPassword = mysqli_real_escape_string($dbConnection, $_POST['hashedPassword']);
            $userRole = 2;
            $userSecurityQuestion = $_POST["secquestion"];
            $userSecurityAnswer = mysqli_real_escape_string($dbConnection, $_POST['hashedAnswer']);
            $customerID = getRandomNumber(10);
            $customerName = mysqli_real_escape_string($dbConnection, $_POST['fullname']);
            $customerDOB = mysqli_real_escape_string($dbConnection, $_POST['dob']);
            $customerEmail = mysqli_real_escape_string($dbConnection, $_POST['email']);
            $customerAddress = mysqli_real_escape_string($dbConnection, $_POST['address']);
            $accountID = "PB".getRandomNumber();
            $accountBalance = 0;//rand(0,15000); // Initialize the customer account with a zero balance
            $PIN = mysqli_real_escape_string($dbConnection, $PIN);
            $dummy = "bla";
            // Prepare the SQL statements
            $availableStmt = $dbConnection->prepare("SELECT userUsername FROM User WHERE userUsername LIKE (?)");
            $userStmt = $dbConnection->prepare("INSERT INTO User VALUES (?,?,?,0,?,?)");
            $customerStmt = $dbConnection->prepare("INSERT INTO Customer VALUES (?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?,?)");
            $accountStmt = $dbConnection->prepare("INSERT INTO Account VALUES (?,?,0,?)");
            // Bind parameters
            $availableStmt->bind_param("s", $userUsername);
            $userStmt->bind_param("sssss", $userUsername, $userPassword, $userRole, $userSecurityQuestion, $userSecurityAnswer);
            $customerStmt->bind_param("sssssssis",$customerID, $customerName, $customerDOB, $customerEmail, $customerAddress, $userUsername, $PIN, $secMethod, $dummy); 
            $accountStmt->bind_param("ssi", $accountID, $customerID, $accountBalance);
            // Execute the statements
            // 1- Check if username is already taken
            $availableStmt->execute();
            $availableUser = $availableStmt->fetch();
            if(count($availableUser) > 0){
                header("Location: ../error.php?id=available");
                exit();
            }
            $userStmt->execute();
            if($userStmt->affected_rows < 1){
                return false;
            }
            $customerStmt->execute();
            if($customerStmt->affected_rows < 1){
                // Delete the inserted user  
                $deleteStmt = $dbConnection->prepare("DELETE FROM User WHERE userUsername=?");
                $deleteStmt->bind_param("s", $userUsername);
                $deleteStmt->execute();
                return false;
            }
            else{
                // Add the account data
                $accountStmt->execute();
                // Report success to register.php
                return true;
            }

    }catch(Exception $e){
        return false;
    }
    return true;
}

try{
    $_SERVER["HTTP_REFERER"] = "PiggyBank/f8d890ce88bd1791b6eaddf06e58ceb5/register.php";
    // Retrieve and validate posted parameter
    $fullnameStatus = validateInput($_POST['fullname'], "name");
    $addressStatus = validateInput($_POST['address'], "address");
    $dobStatus1 = validateInput($_POST['dob'], "dob");
    list($dd,$mm,$yyyy) = explode("/", $_POST['dob']);
    $dobStatus2 = checkdate($mm, $dd, $yyyy);
    $emailStatus = (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false) ? true : false;
    $usernameStatus = validateInput($_POST['username'], "username");
    
    if(preg_match("/[0-9a-f]{64}/", $_POST["hashedPassword"]) == 1 )
        $passwordStatus = true;
    else
        $passwordStatus = false;
        
                  
    $confirmStatus = ($_POST["hashedConfirm"] != $_POST["hashedPassword"]) ? false : true;
   
   
    if(preg_match("/[0-9a-f]{64}/", $_POST["hashedAnswer"]) == 1)
        $secQuestionStatus = true;
    else
        $secQuestionsStatus = false;
          
  
    // If validation succeeds, add user to database
    if($fullnameStatus and $addressStatus and $dobStatus1 and $dobStatus2 and $emailStatus and $usernameStatus and $passwordStatus and $confirmStatus and $secQuestionStatus){
        // Register user
        if (registerCustomer())
            header("Location: ../notify.php?mode=success");
        else
           header("Location: ../error.php");
    }
    else{
        // Otherwise, post back to signup.php to inform user of failure
        session_start();
        $_SESSION["invFullname"] = $fullnameStatus ? $_POST["fullname"] : NULL;
        $_SESSION["invAddress"] = $addressStatus ?  $_POST["address"] : NULL;
        $_SESSION["invDOB"] = ($dobStatus1 & $dobStatus2) ? $_POST["dob"] : NULL;
        $_SESSION["invEmail"] = $emailStatus ?  $_POST["email"] : NULL;
        $_SESSION["invUsername"] = $usernameStatus ? $_POST["username"] : NULL;
        $_SESSION["invPassword"] = $passwordStatus ?  $_POST["password"] : NULL;
        $_SESSION["invConfirm"] = $confirmStatus ?  $_POST["confirm"] : NULL;
        header("Location: ../signup.php");
      
        
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
        <td align="center">
            <img src="../images/loading.gif" alt="loading.gif"/>
        </td>
    </tr>
</table>
</body>
</html>

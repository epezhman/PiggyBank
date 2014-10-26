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

function authenticateUser($userUsername, $userPassword){
// Carries out the necessary SQL statements to authenticate users
   try{
            // Connect to the database
            require_once("dbconnect.php");
            // Prepare the parameters
            $userUsername = mysqli_real_escape_string($dbConnection, $_POST['username']);
            $userPassword = mysqli_real_escape_string($dbConnection, $_POST['hashedpassword']);
            // Prepare the SQL statements
            $authStmt = $dbConnection->prepare("CALL authUser(?,?,@role)");
            $roleStmt = $dbConnection->prepare("SELECT @role");
            // Bind parameters
            $authStmt->bind_param("ss", $userUsername, $userPassword);
            // Execute the statements
            // 1- Check if username is already taken
            $authStmt->execute();
            $result = $dbConnection->query("SELECT @role");
            $row =  mysqli_fetch_row($result);
        
    }catch(Exception $e){
        echo $e;
        return "";
    }
    return $row[0];
}

try{
    // Check the referer first to deny nosey requests
    if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
        header("Location: ../error.php?id=404");

    $usernameStatus = validateInput($_POST['username'], "username");
    $passwordStatus = (strlen($_POST["hashedpassword"]) < 8) ? false : validateInput($_POST['hashedpassword'], "password");
    // If validation succeeds, add user to database
    if($usernameStatus and $passwordStatus){
        // Authenticate user
        $role = authenticateUser($_POST['username'], $_POST['hashedpassword']);
        if(empty($role)){
            header("Location: ../signin.php?failure=".$_POST["username"]);
        }      
        else{
                session_start();
                $_SESSION['loginstatus'] = 'authenticated';
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['userrole'] = $role;
                $_SESSION['userloggedin'] = time();
            // Determine role and redirect accordingly
            if($role == "customer")
                header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerMyTransfers.php");    
            else if($role == "admin")
                header("Location: ../16fa71ac26d19ce19ed9e28b39009f50/eCustomerManagers.php");

        }
    }
    else{
        header("Location: ../signin.php?failure=".$_POST["username"]);
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

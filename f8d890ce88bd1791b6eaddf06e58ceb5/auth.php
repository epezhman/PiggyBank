<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
<?php
//if(strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false){
//    ob_start();
//    require("accesscontrol.php");
//    if(!ob_get_clean()){
//        header("Location: ../error.php?id=404");
//        exit();
//    }

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
////////////////////////
// Script entry point //
////////////////////////
try{
    // Check whether CAPTCHA is right
    include_once("securimage/securimage.php");
    $securimage = new Securimage();
    if($securimage->check($_POST["captcha_code"]) == false){
        header("Location: ../error.php?id=captcha");
        exit();
    } 
    $usernameStatus = validateInput($_POST['username'], "username");
    $passwordStatus = validateInput($_POST['hashedpassword'], "password");
    // If validation succeeds, add user to database
    if($usernameStatus and $passwordStatus){
        // Authenticate user
        $role = authenticateUser($_POST['username'], $_POST['hashedpassword']);
        if(empty($role)){
            header("Location: ../signin.php?failure=".$_POST["username"]);
            exit();
        }      
        else{
                session_start();
                $_SESSION['loginstatus'] = 'authenticated';
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['userrole'] = $role;
                $_SESSION['userloggedin'] = time();
                require_once("utils.php");
                $_SESSION["csrfToken"] = generateCSRFToken($_SESSION["username"]);
            // Determine role and redirect accordingly
            if($role == "customer"){
                header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerMyTransfers.php");
                exit();
            }
            else if($role == "employee"){
                header("Location: ../16fa71ac26d19ce19ed9e28b39009f50/eCustomerManagers.php");
                exit();
            }
            else if($role == "admin"){
                header("Location: ./approveEmployees.php");
                exit();
            }
        }
    }
    else{
        header("Location: ../signin.php?failure=".$_POST["username"]);
        exit();
    }
}catch(Exception $e){
  //echo $e;
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

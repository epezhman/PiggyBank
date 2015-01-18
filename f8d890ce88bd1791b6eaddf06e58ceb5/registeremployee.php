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
    $regExpressions =  array("name"=>"^[A-Za-z ]+$", "address"=>"^[a-zA-Z0-9,'-. ]+$", "username"=>"^[0-9A-Za-z_.]+$", "password"=>"^[a-zA-Z0-9_.@!?]$", "hashedpassword"=>"^[a-f0-9]{64}$");
    try{
        if (ereg($regExpressions[$type], $input) == 1)
            return true;
        else
            return false;
    }catch(Exception $e){
        return false;
    } 
}

function registerEmployee(){
// Carries out the necessary SQL statements to provision new users
    try{
            // Connect to the database
            require_once("dbconnect.php");
            // Prepare the parameters
            $userUsername = mysqli_real_escape_string($dbConnection, $_POST['username']);
            $userPassword = mysqli_real_escape_string($dbConnection, $_POST['hashedPassword']);
            $userRole = 1;
            $userSecurityQuestion = $_POST["secquestion"];
            $userSecurityAnswer = mysqli_real_escape_string($dbConnection, $_POST['hashedAnswer']);
            $employeeID = "E".getRandomNumber(9);
            $employeeName = mysqli_real_escape_string($dbConnection, $_POST['fullname']);
            $employeeDOB = mysqli_real_escape_string($dbConnection, $_POST['dob']);
            $employeeEmail = mysqli_real_escape_string($dbConnection, $_POST['email']);
            $employeeDept = mysqli_real_escape_string($dbConnection, $_POST['department']);
            $employeeBranch = mysqli_real_escape_string($dbConnection, $_POST['branch']);
            $employeeAddress = mysqli_real_escape_string($dbConnection, $_POST['address']);
            // Prepare the SQL statements
            $availableStmt = $dbConnection->prepare("SELECT userUsername FROM User WHERE userUsername LIKE (?)");
            $userStmt = $dbConnection->prepare("INSERT INTO User VALUES (?,?,?,0,?,?)");
            $employeeStmt = $dbConnection->prepare("INSERT INTO Employee VALUES (?,?,STR_TO_DATE(?,'%d/%m/%Y'),?,?,?,?,?)");
            // Bind parameters
            $availableStmt->bind_param("s", $userUsername);
            $userStmt->bind_param("sssss", $userUsername, $userPassword, $userRole, $userSecurityQuestion, $userSecurityAnswer);
            $employeeStmt->bind_param("sssssiis",$employeeID, $employeeName, $employeeDOB, $employeeAddress, $employeeEmail, $employeeDept, $employeeBranch, $userUsername);
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
            $employeeStmt->execute();
            if($employeeStmt->affected_rows < 1){
                // Delete the inserted user  
                $deleteStmt = $dbConnection->prepare("DELETE FROM User WHERE userUsername=?");
                $deleteStmt->bind_param("s", $userUsername);
                $deleteStmt->execute();
                return false;
            }
            else{
                // Report success to joinus.php
                return true;
            }

    }catch(Exception $e){
        return false;
    }
    return true;
}
///////////////////////////////
// Entry point of the script //
///////////////////////////////
try{
    // Check whether CAPTCHA is right
    include_once("securimage/securimage.php");
    $securimage = new Securimage();
    if($securimage->check($_POST["captcha_code"]) == false){
        header("Location: ../error.php?id=captcha");
        exit();
    }

    $_SERVER["HTTP_REFERER"] = "PiggyBank/f8d890ce88bd1791b6eaddf06e58ceb5/registeremployee.php";
    // Retrieve and validate posted parameters
    $fullnameStatus = validateInput($_POST['fullname'], "name");
    $addressStatus = validateInput($_POST['address'], "address");
    list($dd,$mm,$yyyy) = explode("/", $_POST['dob']);
    $dobStatus = checkdate($mm, $dd, $yyyy);
    $emailStatus = (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false) ? true : false;
	$deptStatus = preg_match($_POST['department'], "/[0-9]+/") ?  true : false;
	$branchStatus = preg_match($_POST['branch'], "/[0-9]+/") ?  true : false;
    $usernameStatus = validateInput($_POST['username'], "username");
    if(preg_match("/[0-9a-f]{64}/", $_POST["hashedPassword"]) == 1)
        $passwordStatus = true;
    else
        $passwordStatus = false;
//    $passwordStatus = (strlen($_POST["password"]) < 8) ? false : validateInput($_POST['password'], "password");
    $confirmStatus = ($_POST["hashedConfirm"] != $_POST["hashedPassword"]) ? false : true;
    if(preg_match("/[0-9a-f]{64}/", $_POST["hashedPassword"]) == 1)
        $secQuestionStatus = true;
    else
        $secQuestionStatus = false;
    // If validation succeeds, add user to database
    if($fullnameStatus and $addressStatus and $dobStatus and $emailStatus and $usernameStatus and $passwordStatus and $confirmStatus and $secQuestionStatus){
        // Register user
        if (registerEmployee())
            header("Location: ../notify.php?mode=success");
        else
           header("Location: ../error.php");
    }
    else{
        // Otherwise, post back to signup.php to inform user of failure
        session_start();
        $_SESSION["invFullname"] = $fullnameStatus ? NULL : $_POST["fullname"];
        $_SESSION["invAddress"] = $addressStatus ? NULL : $_POST["address"];
        $_SESSION["invDOB"] = $dobStatus ? NULL : $_POST["dob"];
        $_SESSION["invEmail"] = $emailStatus ? NULL : $_POST["email"];
        $_SESSION["invDept"] = $deptStatus ? NULL : $_POST["department"];
        $_SESSION["invBranch"] = $branchStatus ? NULL : $_POST["branch"];
        $_SESSION["invUsername"] = $usernameStatus ? NULL : $_POST["username"];
        $_SESSION["invPassword"] = $passwordStatus ? NULL : $_POST["password"];
        $_SESSION["invConfirm"] = $confirmStatus ? NULL : $_POST["confirm"];
        header("Location: ../joinus.php");
    
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

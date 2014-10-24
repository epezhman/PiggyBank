<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
<?php
// Define some constant parameters for database connection
//define($dbHost, "localhost");
//define($dbUser, "piggy");
//define($dbPassword, "8aa259f4c7");
//define($dbName, "piggybank");

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

function registerCustomer(){
// Carries out the necessary SQL statements to provision new users
    try{
        $dbHost= "localhost";
        $dbUser= "piggy";
        $dbPassword= "8aa259f4c7";
        $dbName= "piggybank";
      
        $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if(mysqli_connect_errno()){
            header("Location: ../error.php");
        } 
        else{
            // Prepare the parameters
            $userUsername = mysqli_real_escape_string($dbConnection, $_POST['username']);
            $userPassword = hash("sha256", mysqli_real_escape_string($dbConnection, $_POST['password']));
            $userRole = 2;
            $customerID = getRandomString(10);
            $customerName = mysqli_real_escape_string($dbConnection, $_POST['fullname']);
            $customerDOB = mysqli_real_escape_string($dbConnection, $_POST['dob']);
            $customerEmail = mysqli_real_escape_string($dbConnection, $_POST['email']);
            $customerAddress = mysqli_real_escape_string($dbConnection, $_POST['address']);
            // Prepare the SQL statements
            $availableStmt = $dbConnection->prepare("SELECT userUsername FROM User WHERE userUsername LIKE (?)");
            $userStmt = $dbConnection->prepare("INSERT INTO User VALUES (?,?,?,0)");
            $customerStmt = $dbConnection->prepare("INSERT INTO Customer VALUES (?,?,?,?,?,?)");
            // Bind parameters
            $availableStmt->bind_param("s", $userUsername);
            $userStmt->bind_param("sss", $userUsername, $userPassword, $userRole);
            $customerStmt->bind_param("ssssss",$customerID, $customerName, $customerDOB, $customerEmail, $customerAddress, $userUsername);
            // Execute the statements
            // 1- Check if username is already taken
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
                // Report success to register.php
                $dbConnection->close();
                return true;
            }
        }

    }catch(Exception $e){
        $dbConnection->close();
//        echo $e;
        return false;
    }
    $dbConnection->close();
    return true;
}

try{
    // Check the referer first to deny nosey requests
    if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/signup.php") === false)
        header("Location: ../error.php?id=404");

    $_SERVER["HTTP_REFERER"] = "PiggyBank/f8d890ce88bd1791b6eaddf06e58ceb5/register.php";
    // Retrieve and validate posted parameters
    $fullnameStatus = validateInput($_POST['fullname'], "name");
    $addressStatus = validateInput($_POST['address'], "address");
    list($dd,$mm,$yyyy) = explode("/", $_POST['dob']);
    $dobStatus = checkdate($mm, $dd, $yyyy);
    $emailStatus = (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) != false) ? true : false;
    $usernameStatus = validateInput($_POST['username'], "username");
    $passwordStatus = (strlen($_POST["password"]) < 8) ? false : validateInput($_POST['password'], "password");
    $confirmStatus = ($_POST["confirm"] != $_POST["password"]) ? false : true;
    // If validation succeeds, add user to database
    if($fullnameStatus and $addressStatus and $dobStatus and $emailStatus and $usernameStatus and $passwordStatus and $confirmStatus){
        // Register user
        if (registerCustomer())
            header("Location: ../notify.php?mode=success");
        else
           header("Location: ../error.php");
    }
    else{
        // Otherwise, post back to signup.php to inform user of failure
        $_SESSION["invFullname"] = $fullnameStatus ? NULL : $_POST["fullname"];
        $_SESSION["invAddress"] = $addressStatus ? NULL : $_POST["address"];
        $_SESSION["invDOB"] = $dobStatus ? NULL : $_POST["dob"];
        $_SESSION["invEmail"] = $emailStatus ? NULL : $_POST["email"];
        $_SESSION["invUsername"] = $usernameStatus ? NULL : $_POST["username"];
        $_SESSION["invPassword"] = $passwordStatus ? NULL : $_POST["password"];
        $_SESSION["invConfirm"] = $confirmStatus ? NULL : $_POST["confirm"];
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
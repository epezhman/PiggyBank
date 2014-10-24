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

function authenticateUser($userUsername, $userPassword){
// Carries out the necessary SQL statements to authenticate users
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
            while ($row = mysqli_fetch_array($result)){   
                echo $row[0]; 
            }
        }
    }catch(Exception $e){
        $dbConnection->close();
        echo $e;
        return "";
    }
    $dbConnection->close();
    return $result;
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
        if(is_null($role)){
            header("Location: ../signin.php?failure=".$_POST["username"]);
        }      
        else{
                $_SESSION["loginstatus"] = "authenticated";
                $_SESSION["username"] = $_POST["username"];
                $_SESSION["userrole"] = $role;
                $_SESSION["userloggedin"] = time();
            // Determine role and redirect accordingly
            if($role == "customer")
                header("Location: ../customerdir");    
            else if($role == "admin")
                header("Location: ../admindir");
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

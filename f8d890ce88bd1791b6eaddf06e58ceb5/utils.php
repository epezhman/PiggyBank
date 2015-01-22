<?php
    // Some basic access control checks
    ob_start();
    require("accesscontrol.php");
    if(ob_get_clean() == -1){
        header("Location: ../error.php?id=404");
        exit();
    }

function generateCSRFToken($username){
    require_once("dbconnect.php");
    global $dbConnection;
    $passQuery = $dbConnection->prepare("SELECT userPassword FROM User WHERE userUsername LIKE (?)");
    $passQuery->bind_param("s", $_SESSION["username"]);
    $passQuery->execute();
    $passQuery->bind_result($userPassword);
    if($passQuery->num_rows > 0)
        return "";
    $length = 256;
    $cryptostrong = true;
    $tokenRandom = bin2hex(openssl_random_pseudo_bytes($length, $cryptostrong));
    $csrfToken = hash("sha1", $userPassword."|".hash("sha1", $userPassword."|".$username.time().$tokenRandom));
    return $csrfToken;
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
    $alphabet = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ";
    $validCharNumber = strlen($alphabet);
    $result = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $alphabet[$index];
    }
    return $result;
}

?>

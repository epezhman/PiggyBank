<?php
//if(strpos(getenv("HTTP_REFERER", "/PiggyBank") === false){
   // ob_start()
//    require("accesscontrol.php");    
   // if(!ob_get_clean()){
//        header("Location: ../error.php?id=404");
//        exit();
//    }
    $dbHost= "localhost";
    $dbUser= "piggy";
    $dbPassword= "8aa259f4c7";
    $dbName= "piggybank";

    global $dbConnection;
    $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    if(mysqli_connect_errno()){
        header("Location: ../error.php");
        exit();
    }
?>

<?php
    // Check the referer first to deny nosey requests
    if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
        header("Location: ../error.php?id=404");

        $dbHost= "localhost";
        $dbUser= "piggy";
        $dbPassword= "8aa259f4c7";
        $dbName= "piggybank";

        global $dbConnection;
        $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if(mysqli_connect_errno()){
            header("Location: ../error.php");
        }

?>

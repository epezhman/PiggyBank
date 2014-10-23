<?php
    function getRandomString($length = 6) {

        $validCharacters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ._";

        $validCharNumber = strlen($validCharacters);

     

        $result = "";

     

        for ($i = 0; $i < $length; $i++) {

            $index = mt_rand(0, $validCharNumber - 1);

            $result .= $validCharacters[$index];

        }

     

        return $result;

    }
        $dbHost= "localhost";
        $dbUser= "piggy";
        $dbPassword= "8aa259f4c7";
        $dbName= "piggybank";
     
        $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);

$userStmt = $dbConnection->prepare("INSERT INTO User VALUES (?,?,?,0)");
            $userUsername = "asd";
            $userPassword = hash("sha256", "asd");
            $userRole = 2;
            $userStmt->bind_param("sss", $userUsername, $userPassword, $userRole);

$userStmt->execute();

            if($userStmt->affected_rows < 1)
              echo $dbConnection->error;



?>


<?php
session_start();
// This page is meant to enforce a primitive access control policy on the users
// 1- Check if user is logged in 
    if($_SESSION['loginstatus'] != "authenticated")
        header("Location: ../error.php?id=404");

// 2- Check session expiry time (15 mins)
   if(isset($_SESSION["LAST_ACTIVITY"]) and (time() - $_SESSION["LAST_ACTIVITY"] > 900)){
       session_destroy();
       header("Location: ../error.php?id=440");    
   }
?>

<?php
session_start();
// This page is meant to enforce a primitive access control policy on the users
// 1- Check if user is logged in 
    if(!isset($_SESSION['loginstatus']) or ($_SESSION['loginstatus'] != "authenticated")){
        return -1;
     }

// 2- Check session expiry time (15 mins)
   if(isset($_SESSION["LAST_ACTIVITY"]) and (time() - $_SESSION["LAST_ACTIVITY"] > 900)){
       session_unset();
       session_destroy();
       return -1;
   }
   return 0;
?>

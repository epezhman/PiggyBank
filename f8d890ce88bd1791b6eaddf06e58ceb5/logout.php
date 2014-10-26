<?php
    // Some basic access control checks
    require("accesscontrol.php");

    // Check referrer
    if(strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
        header("Location: ../error.php?id=404");

    // Finally destroy the session and redirect user
    session_destroy();
    header("Location: ../notify.php?mode=signout");
?>

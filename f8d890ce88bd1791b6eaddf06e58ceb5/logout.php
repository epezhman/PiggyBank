<?php
    // Some basic access control checks
    ob_start();
    require("accesscontrol.php");
    if(ob_get_clean() == -1){
        header("Location: ../error.php?id=404");
        exit();
    }

    // Finally destroy the session and redirect user
    session_unset();
    session_destroy();
    // Remove all temporary files
    $tmpFiles = glob("tmp/*");
    foreach($f as $tmpFiles){
		//if(is_file($f))
			unlink($f);
	}
    header("Location: ../notify.php?mode=signout");
?>

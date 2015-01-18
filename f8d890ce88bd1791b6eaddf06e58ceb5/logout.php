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
    echo count($tmpFiles);
    foreach($tmpFiles as $f){
		if(is_file($f))
			unlink($f);
    }
    // Delete any remaining Java class files to save space
    $classFiles = glob("java/SCS/*.class");
    foreach($classFiles as $cf){
    if(is_file($cf))
        unlink($cf);
    }
    // Also remove the SCS.jar file because it contains the secret of a client
    if(is_file("java/SCS.jar"))
        unlink("java/SCS.jar");

    header("Location: ../notify.php?mode=signout");
?>

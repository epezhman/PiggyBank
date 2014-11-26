<?php

    // Some basic access control checks
    ob_start();
    require("accesscontrol.php");
    if(ob_get_clean() == -1){
        header("Location: ../error.php?id=404");
        exit();
    }
    require_once("dbconnect.php");

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
	$alphabet = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ_";
	$validCharNumber = strlen($alphabet);
	$result = "";
	for ($i = 0; $i < $length; $i++) {
		$index = mt_rand(0, $validCharNumber - 1);
		$result .= $alphabet[$index];
	}
	return $result;
}

?>

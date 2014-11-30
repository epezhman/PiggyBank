<?php
session_start();
ob_start();
require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php");
$authenticated = ob_get_clean();
if($authenticated == -1){
	header("Location: ../error.php?id=404");
	exit();
}
if($authenticated == -2){
	header("Location: ../error.php?id=440");
	exit();
}

if($_SESSION["userrole"] != "customer"){
	header("Location: ../error.php?id=404");
	exit();
}
try{
	// Connect to the database
	require_once("dbconnect.php");
	$customerInfo = $dbConnection->prepare("SELECT customerTransferSecurityMethod FROM Customer WHERE customerUsername LIKE (?)");
	$customerInfo->bind_param("s", mysqli_real_escape_string($dbConnection,$_SESSION['username']));
	$customerInfo->execute();
	$customerInfo->bind_result( $cMethod);
	$customerInfo->store_result();

	if($customerInfo->num_rows() == 1)
	{
		while($customerInfo->fetch())
		{
			$customerMethod = $cMethod;
		}
	}
	$customerInfo->free_result();
	$customerInfo->close();
	
	if($customerMethod == "2")
	{
		$attachment_location = $_SERVER["DOCUMENT_ROOT"] . "/SCS.jar";
		echo $attachment_location;
		if (file_exists($attachment_location)) {
		
			header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
			header("Cache-Control: public"); // needed for i.e.
			header("Content-Type: application/java-archive");
			header("Content-Transfer-Encoding: Binary");
			header("Content-Length:".filesize($attachment_location));
			header("Content-Disposition: attachment; filename=\"SCS.jar\"");
			readfile($attachment_location);
				
			exit;
		} else {
			header("Location ../error.php");
		}
	}
}catch(Exception $e){
	header("Location ../error.php");
}
?>
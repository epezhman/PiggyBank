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
            // Generate SCS token for user
            // Step 1 - Retrieve user password
            $passQuery = $dbConnection->prepare("SELECT userPassword FROM User WHERE userUsername LIKE (?)");
            $passQuery->bind_param("s", mysqli_real_escape_string($dbConnection, $_SESSION["username"]));
            $passQuery->execute();
            $passQuery->bind_result($uPass);
            $passQuery->store_result();
            if($passQuery->num_rows > 0){
                while($passQuery->fetch())
                $userPassword = $uPass;
            }
            else{
                header("Location: ../error.php");
                exit();
            }
            // Step 2 - Generate Token
            $userName = $_SESSION["username"];
            $length = 256;
            $cryptostrong = true;
            $tokenUsername = $_POST["username"];
            $tokenTime = time();
            $tokenRandom = bin2hex(openssl_random_pseudo_bytes($length, $cryptostrong));
            $SCSToken = hash("sha1", $userPassword."|".hash("sha1", $userPassword."|".$tokenUsername.$tokenTime.$tokenRandom));
            // Step 3 - Store token in database
            $tokenStmt = $dbConnection->prepare("UPDATE Customer SET customerSCSToken=? WHERE customerUsername LIKE (?)");
            $tokenStmt->bind_param("ss", $SCSToken, $_SESSION["username"]);
            $tokenStmt->execute();
            if($tokenStmt->affected_rows < 1){
                header("Location: ../error.php");
                exit();
            }
            // Step 4 -Update, compile, and download customized Java SCS
            // Step 4.a. Add the user token
            exec("cp java/SCSMain.java java/SCS/SCSMain.java");
            exec("cp java/OTPGenerator.java java/SCS/OTPGenerator.java");
            $fileContent = file_get_contents("java/SCS/OTPGenerator.java");
            $fileNewContent = str_replace("__USERSCSTOKEN__", $SCSToken, $fileContent);
            file_put_contents("java/SCS/OTPGenerator.java", $fileNewContent);
            // Step 4.b. Compile the code
            chdir("java");
            exec("javac -nowarn SCS/SCSMain.java");
            $javaFiles = glob("SCS/*.java");
            foreach($javaFiles as $jf){
                if(is_file($jf)){
                    unlink($jf);
                }
            }
            // Step 4.c. Create the JAR file
            system("jar cvfm SCS.jar SCS/META-INF/MANIFEST.MF SCS/* images/");
            chdir("..");
            // Step 4.d. Download the JAR
            $JARFile = 'java/SCS.jar';
            if (file_exists($JARFile)) {
                header('Content-Description: Your PiggyBank SCS');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($JARFile));
                header('Content-Transfer-Encoding: binary');
                header('Connection: Keep-Alive');
                header('Expires: 0');
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Content-Length: ' . filesize($JARFile));
                ob_clean();
                flush();
                readfile($JARFile);
                // Remove it
                unlink($JARFile);
                exit;
            }
            else{
                header("Location: ../error.php");
                exit();
            }
	}
	}catch(Exception $e){
	header("Location ../error.php");
}
?>

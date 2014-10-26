<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php

	session_start();

	require("accesscontrol.php");

	require_once("dbconnect.php");
	
	$_SESSION["invUploadingFile"] = true;

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


	try{


		$userID = NULL;
		$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
		$customerID = $dbConnection->prepare("SELECT customerID FROM Customer WHERE customerUsername LIKE (?)");
		$customerID->bind_param("s", $userUsername);
		$customerID->execute();
		$customerID->bind_result($ID);
		$customerID->store_result();
		if($customerID->num_rows() == 1)
		{
			while($customerID->fetch())
			{
				$userID = $ID;
			}
			$customerID->free_result();
			$customerID->close();
		}
		if(!empty($userID))
		{
			$target_dir = "../b62692cfa701844a5c279d5863eb52/";
			$target_dir = $target_dir .$userID.microtime(true).getRandomString() ;
			$uploadOk=1;

			// Check if file already exists
			if (file_exists($target_dir . $_FILES["uploadFile"]["name"])) {
				$_SESSION["invDupFile"] = true;
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
				$uploadOk = 0;
			}

			// Check file size
			if ($uploadFile_size > 500000) {
				$_SESSION["invFileBig"] = true;
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
				$uploadOk = 0;
			}

			// Only plain text files allowed
			if (!($uploadFile_type == "text/plain")) {
				$_SESSION["invMimeError"] = true;
				header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
				$uploadOk = 0;
			}

			// Check if $uploadOk is set to 0 by an error
			if ($uploadOk == 0) {
			} else {
				if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_dir)) {
					echo "The file ". basename( $_FILES["uploadFile"]["name"]). " has been uploaded.";
				} else {
					$_SESSION["invUnknownError"] = true;
					header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
				}
			}
		}

	}catch(Exception $e){

	}
	?>
	<table width="100%">
		<tr>
			<td align="center">
				<h1>Your request is being processed. Please wait.</h1>
			
			<td>
		
		</tr>
		<tr>
			<td align="center"><img src="../images/loading.gif" alt="loading.gif" />
			</td>
		</tr>
	</table>
</body>
</html>

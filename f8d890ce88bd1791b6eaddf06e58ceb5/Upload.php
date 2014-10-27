<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php

	session_start();

	require_once("accesscontrol.php");
	require_once("utils.php");
	require_once("dbconnect.php");

	$_SESSION["invUploadingFile"] = true;

	define("UPLOAD_DIR", "./tmp/");


	try{

		if($_FILES['uploadFile']['name'])
		{
			//if no errors...
			if(!$_FILES['uploadFile']['error'])
			{
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
					$target_dir = $userID.'txt';
					$upFlag = true;

					// Check if file already exists
					if (file_exists($target_dir)) {
						$_SESSION["invUnknownError"] = true;
						$upFlag = false;
					}

					// Check file size
					if ($_FILES['uploadFile']['size'] > 102400) {
						$_SESSION["invFileBig"] = true;
						$upFlag = false;
					}

					// Only plain text files allowed
					if (!($_FILES['uploadFile']['type'] == "text/plain")) {
						$_SESSION["invMimeError"] = true;
						$upFlag = false;
					}

					$name = preg_replace("/[^A-Z0-9._-]/i", "_", $_FILES["uploadFile"]["name"]);
					$i = 0;
					$parts = pathinfo($name);
					while (file_exists(UPLOAD_DIR . $name)) {
						$i++;
						$name = $parts["filename"] . "-" . $i . "." . $parts["extension"];
					}
						
					if($upFlag)
					{
						if (move_uploaded_file($_FILES['uploadFile']['tmp_name'], UPLOAD_DIR . $name)) {
							$_SESSION["invSuccessUpload"] = true;
						} else {
							$_SESSION["invUnknownError"] = true;
						}
					}
				}
				else
					$_SESSION["invUnknownError"] = true;
			}
			else
				$_SESSION["invUnknownError"] = true;
		}
		else
			$_SESSION["invUnknownError"] = true;
		header("Location: ../5e8cb842691cc1b8c7598527b5f2277f/CustomerNewTransfer.php");
                exit();

	}catch(Exception $e){
		header("Location: ../error.php");
	}
	?>
	<table style="width: 100%">
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

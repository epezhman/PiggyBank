<html>
<head>
<title>PiggyBank GmbH</title>
</head>
<body>
	<?php

	/// MAYBE SENT EMAIL FLAG??

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

	function generateAndSend(){
		try{

			// Connect to the database
			require_once("dbconnect.php");

			session_start();

			if(isset($_SESSION["username"]) and isset($_SESSION["loginstatus"]) and isset($_SESSION["userrole"]))
			{
				if($_SESSION["loginstatus"] == "authenticated" )
				{
					$userID = NULL;
					$userName = NULL;
					$userEmail = NULL;
					$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
					$customerID = $dbConnection->prepare("SELECT customerID, customerName, customerEmail  FROM Customer WHERE customerUsername LIKE (?)");
					$customerID->bind_param("s", $userUsername);
					$customerID->execute();

					$customerID->bind_result($ID, $name , $email);

					$customerID->store_result();

					if($customerID->num_rows() == 1)
						while($customerID->fetch())
						{
							$userID = $ID;
							$userName = $name;
							$userEmail = $email;
						}
						$customerID->free_result();
						$customerID->close();

						if(!empty($userID))
						{
							$tokens = array();

							$tokensCountCount = 0;
							$tokensCount = $dbConnection->prepare("SELECT COUNT(*) FROM Token WHERE tokenCustomer LIKE (?) ");
							$tokensCount->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
							$tokensCount->execute();
							$tokensCount->bind_result($tokenCount);
							$tokensCount->store_result();
								
							while($tokensCount->fetch())
							{
								$tokensCountCount = $tokenCount;
							}
								
							$tokensCount->free_result();
							$tokensCount->close();
								
							if($tokensCountCount  < 100)
							{
								while(count($tokens) < 100)
								{
									$cnt  = 0;
									$tempToken =  substr(sha1($userID.$cnt++ .microtime(true).getRandomString()), 0, 15);

									$checkAny = $dbConnection->prepare("SELECT * FROM Token WHERE tokenID LIKE (?) AND tokenUsed = 0");
									$checkAny->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
									$checkAny->execute();
									$checkAny->store_result();
									if($customerID->num_rows() == 0)
									{
										$tokenDB = $dbConnection->prepare("INSERT INTO Token VALUES (?,?,0)");
										$tokenDB->bind_param("ss", mysqli_real_escape_string($dbConnection,$tempToken), mysqli_real_escape_string($dbConnection,$userID));
										$tokenDB->execute();
										if($tokenDB->affected_rows >= 1){
											array_push($tokens,$tempToken);
										}
										$tokenDB->close();

									}
									$checkAny->free_result();
									$checkAny->close();
								}


								$emailBody = "<div style='background-color: #101010; width: 450px; height: 40px; color: white; text-align: left; font-size:x-large; padding: 4px;'>Piggy Bank GmbH</div>";

								$emailBody = $emailBody. "<p> Dear $userName, Here are your Transfer Toknes:</p></br></br>";

								$emailBody = $emailBody. "<table style='border-collapse: collapse; border-spacing: 0;'><thead><tr><th style='border-top: 1px solid #DDDDDD;line-height: 1.42857;padding: 8px;vertical-align: top'>#</th><th style='border-top: 1px solid #DDDDDD;line-height: 1.42857;padding: 8px;vertical-align: top'>Token Value</th></tr></thead><tbody>";

								for($i = 0 ; $i < count($tokens) ; $i++)
								{
									$cnt = $i+1;
									$emailBody = $emailBody."<tr>".
											"<td style='border-top: 1px solid #DDDDDD;line-height: 1.42857;padding: 8px;vertical-align: top;'>$cnt</td>".
											"<td style='border-top: 1px solid #DDDDDD;line-height: 1.42857;padding: 8px;vertical-align: top;'>$tokens[$i]</td>".
											"</tr>";

								}

								$emailBody = $emailBody."</tbody></table>";
								echo $emailBody;

								$headers = "MIME-Version: 1.0" . "\r\n";
								$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

								mail($userEmail,"Your Transfer Tokens by Pigg Bank GmbH",$emailBody,$headers);
							}

						}
						else
							header("Location: ../signin.php");

				}
				else
					header("Location: ../signin.php");
			}
			else {
				header("Location: ../signin.php");
			}

		}catch(Exception $e){
			header("Location ../error.php");
		}
	}

	generateAndSend();

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

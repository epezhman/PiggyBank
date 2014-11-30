<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="./images/piggyFav.ico">

    <style id="antiClickjack">
body {
	display: none !important;
}
</style>
<script src="./js/secure.js"></script>

    <!-- To be Changed!! -->
    <title>
        Thank you for choosing PiggyBank GmbH
    </title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- our CSS -->
    <link href="./css/framework.css" rel="stylesheet">
    <script src="./js/jquery-1.11.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/sha256.js"></script>
<script type="text/javascript">
    var validated = new Array();
    var flag = true;
    function prepareForm(){
        // This function is meant to prepare the registration form
        $("#usernamespan").hide();
        $("#passwordspan").hide();
        $("#confirmspan").hide();
        validated["username"] = false;
        validated["password"] = false;
        validated["confirm"] = false;
        
    }
    function validateElement(e, type){
        if(type=="username"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("A username is required");
                validated["username"] = false;
            }
            else
                if(!e.value.match("^[a-zA-Z0-9_.]+$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid username");
                    validated["username"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["username"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
      }
        validateForm();
    }
    function validateForm(){
        // As the name implies, this function is used to validate form
        if (validated["username"]){
            $('#submit').prop("disabled", false);
                if(flag){
                    $('#submit').animate({opacity: "0.5"}, 300);
                    $('#submit').animate({opacity: "1.0"}, 300);
                    $('#submit').animate({opacity: "0.5"}, 300);
                    $('#submit').animate({opacity: "1.0"}, 300);
                    flag = false;
                }
        }
        else{
               $('#submit').animate({opacity:"0.5"}, 300);
               $('#submit').prop("disabled", true);
               flag = true;
        }
    }
function validatePasswords(e, type){
	 if(type=="password"){
		if(e.value == ""){
			$('#'+e.id+'span').css("background","#CC0000");
			$('#'+e.id+'span').html("A password is required");
			validated["password"] = false;
		}
		else{ // Non-empty password field
				if(e.value.length<10){
					$('#'+e.id+'span').css("background","#CC0000");
                        $('#'+e.id+'span').html("Can't be less than 10 characters");
                        validated["password"] = false;
                    } 
                    else if(e.value.length >= 10 && e.value.match("^[a-zA-Z0-9]+$")){
                        $('#'+e.id+'span').css("background","#CC0000");
                        $('#'+e.id+'span').html("Weak Password");
                        validated["password"] = false;
                    }
                    else if(e.value.length >= 10 && e.value.match("^[a-zA-Z0-9_.@!?]+$")){
                        $('#'+e.id+'span').css("background","#00CC00");
                        $('#'+e.id+'span').html("Strong Password");
                        validated["password"] = true;
                    }
                    else{
                       $('#'+e.id+'span').css("background","#CC0000");
                       $('#'+e.id+'span').html("Invalid Password");
                       validated["password"] = false;
                   }
            }
            // Check if confirm password has already been set and update its status
            if(document.getElementById("confirm").value != "" && document.getElementById("confirm").value != e.value && validated["password"]){
                $('#confirmspan').css("background","#CC0000");
                $('#confirmspan').html("Passwords do not match");
                validated["confirm"] = false;
            }     
            $('#'+e.id+'span').fadeIn('slow');
    }
    else if(type=="confirm"){
        if(e.value == ""){
            $('#'+e.id+'span').css("background","#CC0000");
            $('#'+e.id+'span').html("You need to confirm the password");
            validated["confirm"] = false;
        }
        else{
            if(e.value != document.getElementById("password").value){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Passwords do not match");
                validated["confirm"] = false;
            }
            else{
                $('#'+e.id+'span').css("background","#00CC00");
                $('#'+e.id+'span').html("Confirmed");
                validated["confirm"] = true;
            }
        }
        $('#'+e.id+'span').fadeIn('slow');
    }
     if (validated["password"] && validated["confirm"]){
            $('#submit').prop("disabled", false);
                if(flag){
                    $('#submit').animate({opacity: "0.5"}, 300);
                    $('#submit').animate({opacity: "1.0"}, 300);
                    $('#submit').animate({opacity: "0.5"}, 300);
                    $('#submit').animate({opacity: "1.0"}, 300);
                    flag = false;
                }
        }
        else{
               $('#submit').animate({opacity:"0.5"}, 300);
               $('#submit').prop("disabled", true);
               flag = true;
        }
}
    
    function handleSecrets(f){
		
        f.hashedAnswer.value = SHA256(f.secanswer.value);
        f.secanswer.value = "";
    }
    function handlePasswords(f){
        f.hashedPassword.value = SHA256(f.password.value);
        f.password.value = "";
        f.hashedConfirm.value = SHA256(f.confirm.value);
        f.confirm.value = "";
	}
</script>

</head>

<body onload="prepareForm()">
<div id="wrap">
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
		    <div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="signin.php"><img src="./images/logo.png" alt="" class="logoStyle" /> Piggy Bank GmbH</a>
			</div>
			<div class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<li><a href="signin.php">Sign in</a></li>
					<li><a href="signup.php">Sign up</a></li>
					<li><a href="joinus.php">Join us</a></li>
				</ul>
		    </div>
		</div>
	</div>

<?php
	// Check the referer first to deny nosey requests
	//if (strpos($_SERVER["HTTP_REFERER"], "/PiggyBank/") === false){
	//	header("Location: error.php?id=404");
	//	exit();
	//}

function sendEmail($eAddress, $eSubject, $eMessage){
    // Send the email message via the sendmail MTA
//    mail($eAddress, $eSubject, $eMessage, "From:noreply@piggybank.de");
    try{
        // Pear Mail Library
        require_once "Mail.php";

         $from = "noreply@piggybank.de";
         $to = $eAddress;
         $subject = $eSubject;
         $body = $eMessage;

         $headers = array(
             'From' => $from,
             'To' => $to,
             'Subject' => $subject
         );

        $smtp = Mail::factory('smtp', array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => '465',
            'auth' => true,
            'username' => 'piggybankgmbh@gmail.com',
            'password' => 'optimus_159_prime'
        ));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) 
            echo "<script>alert(\"Error Encountered: " . $mail->getMessage() . "\");</script>" ;
        else 
            echo "<script>alert(\"Successful Operation. Email sent.\");</script>";
           
    }catch(Exception $e){
        header("Location: ../error.php?id=404");
        exit();
    }
}
?>
<div class="container-fluid">
	<div class="col-sm-12">
		<div style="padding-top:10px">
			<br/><br/><br/><?php
			if(isset($_GET["user"]) and isset($_GET["reset_token"]))
				echo "<form action=\"reset.php\" method=\"post\" onsubmit=\"handlePasswords(this)\" class=\"form-signup\" role=\"form\">";
			else
				echo "<form action=\"reset.php\" method=\"post\" onsubmit=\"handleSecrets(this)\" class=\"form-signup\" role=\"form\">";
			
			?>
				<table style="width:700px;" align="center">
					<tr><td><h3><b>No worries, PiggyBank GmbH got you covered.</b></h3></td><td><img src="/PiggyBank/images/helmetpig.png" style="width:80px;height:80px;"/></td></tr></table><br/><br/>
				<table style="width:700px; table-layout:fixed;" align="center">
					<col width="150"><col width="250"><col width="300">
					<?php
						// Render content
						require_once("./f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
						global $dbConnection;
					        echo "<tr>";
						if(isset($_POST["username"]) and empty($_POST["hashedAnswer"])){
							echo "<td style=\"padding: 20px 0px;\"><label for=\"username\">Username</label></td>";
							echo "<td><input class=\"form-control\" style=\"width:200px\" id=\"username\" name=\"username\" type=\"text\" onkeyup=\"validateElement(this, 'username')\" onfocus=\"validateElement(this, 'username')\" autofocus=\"true\" placeholder=\"john.doe\" value=\"".htmlspecialchars($_POST["username"])."\"></td>";
							echo "<td><span id=\"usernamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                                                        // Retrieve security questions and populate drop down list
                                                        $securityQuestionsQuery = $dbConnection->prepare("SELECT securityQuestionDesc FROM SecurityQuestion INNER JOIN User WHERE User.userSecurityQuestion = SecurityQuestion.securityQuestionID AND User.userUsername LIKE (?) AND User.userApproved=1");
                                                        $securityQuestionsQuery->bind_param("s", mysqli_real_escape_string($dbConnection, $_POST["username"]));
                                                        $securityQuestionsQuery->execute();
                                                        $securityQuestionsQuery->bind_result($secQDesc);
                                                        $securityQuestionsQuery->store_result();
                                                        if($securityQuestionsQuery->num_rows > 0){
                                                            while($securityQuestionsQuery->fetch()){
                                                                $securityQuestion = $secQDesc;
                                                            }
                                                           echo "</tr><tr>";
                                                           echo "<td style=\"padding: 10px 0px;\"><label for=\"username\">Security Question</label></td>";
                                                           echo "<td colspan=\"2\">".$securityQuestion."</td>";
                                                           // Render the answer space
                                                           echo "</tr><tr>";
                                                           echo "<td style=\"padding: 20px 0px;\"><label for=\"secanswer\">Answer</label></td>";
                                                           echo "<td colspan=\"2\"><input class=\"form-control\" style=\"width:200px\" id=\"secanswer\" name=\"secanswer\" type=\"text\" placeholder=\"My Secret Answer\"></td>";
                                                           //echo "<td><span id=\"secanswerspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                                                       }
                                                       $securityQuestionsQuery->free_result();
                                                       $securityQuestionsQuery->close();
                                                           echo "</tr><tr>";
                                                           echo "<td colspan=\"3\" align=\"right\"  style=\"padding: 30px 0px;\"> <input type=\"submit\" value=\"Submit\" id=\"submit\" style=\"width:80px; height:30px;\" class=\"btn btn-primary\"></td>";
						}
						else if(isset($_POST["username"]) and !empty($_POST["hashedAnswer"])){
							// Check if the answer is right for the given user, generate reset key and send email
							$securityAnswerQuery = $dbConnection->prepare("SELECT customerName, customerEmail FROM Customer INNER JOIN User WHERE User.userUsername = Customer.customerUsername AND User.userUsername LIKE (?) AND User.userSecurityAnswer LIKE (?)");
							$securityAnswerQuery->bind_param("ss", mysqli_real_escape_string($dbConnection, $_POST["username"]), mysqli_real_escape_string($dbConnection, $_POST["hashedAnswer"]));
							$securityAnswerQuery->execute();
							$securityAnswerQuery->bind_result($cName, $cEmail);
							$securityAnswerQuery->store_result();
							if($securityAnswerQuery->num_rows > 0){
								while($securityAnswerQuery->fetch()){
									$customerName = $cName;
									$customerEmail = $cEmail;
								}
								$passQuery = $dbConnection->prepare("SELECT userPassword FROM User WHERE userUsername LIKE (?)");
								$passQuery->bind_param("s", mysqli_real_escape_string($dbConnection, $_POST["username"]));
								$passQuery->execute();
								$passQuery->bind_result($uPass);
								$passQuery->store_result();
								if($passQuery->num_rows > 0){
									while($passQuery->fetch())
										$userPassword = $uPass;
								}
								// Generate reset token for user
								$length = 256;
								$cryptostrong = true;
								$tokenUsername = $_POST["username"];
								$tokenTime = time();
								$tokenRandom = bin2hex(openssl_random_pseudo_bytes($length, $cryptostrong));
								$customerToken = hash("sha1", $userPass."|".hash("sha1", $userPass."|".$tokenUsername.$tokenTime.$tokenRandom));
								// Add token to database
								$resetTokenQuery = $dbConnection->prepare("INSERT INTO ResetTokens VALUES (?,?,?)");
								$resetTokenQuery->bind_param("sss", $customerToken, $tokenTime, $tokenUsername);
								$resetTokenQuery->execute();
								if($resetTokenQuery->affected_rows == 1){
									//Send Email to customer
									$emailSubject = "PiggyBank GmbH - Password Reset Link for ".$customerName;
									$emailMessage = "Dear Customer,\n\nYou recently submitted a request to reset your PiggyBank Online Banking password.\n\nPlease follow  the link below to reset your password.\n\nhttps://".$_SERVER["SERVER_ADDR"]."/PiggyBank/reset.php?user=".$tokenUsername."&reset_token=".$customerToken."\n\nThank you for banking with us.\n\n Your PiggyBank GmbH";
									sendEmail($customerEmail, $emailSubject, $emailMessage);
									echo "</tr><tr><td colspan=\"3\"><h2><b>A link containing a reset link has been successfully sent to your email account.</b></h2></td>";
								}
							}
							else{
								echo "</tr><tr><td colspan=\"3\"><h2><b>Oink!! Unfortunately, the answer you supplied was not right.</b></h2></td>";
							}
						}
						else if(isset($_GET["user"]) and isset($_GET["reset_token"])){
							// Reset the user password
							// Step 1 - Check validity of username and token
							$validTokenQuery = $dbConnection->prepare("SELECT resetTokenID, resetTokenTimestamp, resetTokenUsername FROM ResetTokens WHERE resetTokenID LIKE (?) AND resetTokenUsername LIKE (?) ORDER BY resetTokenTimestamp DESC");
							$validTokenQuery->bind_param("ss", mysqli_real_escape_string($dbConnection, $_GET["reset_token"]), mysqli_real_escape_string($dbConnection, $_GET["user"]));
							$validTokenQuery->execute();
							$validTokenQuery->bind_result($tID, $tTimestamp, $tUsername);
							$validTokenQuery->store_result();
							if($validTokenQuery->num_rows > 0){
								while($validTokenQuery->fetch()){
									$tokenID = $tID;
									$tokenTimestamp = $tTimestamp;
									$tokenUsername = $tUsername;
								}
								//echo $tokenTimestamp." and ".time()." and ".time()-$tokenTimestamp;
								// Check validity of token [20 minutes given]
								if(time() - $tokenTimestamp > 1200)
									echo "</tr><tr><td colspan=\"3\"><h2><b>Oink!! Unfortunately, the token has expired. Please request another reset token.</b></h2></td>";
								else{
										// Step 2 - Render content and take in new password
										echo "</tr><tr><td style=\"padding: 5px 0px;\"><label for=\"password\">New Password</label></td>";
										echo "<td><input class=\"form-control\" style=\"width:200px\" id=\"password\" type=\"password\" name=\"password\" placeholder=\"epiclysecret\" onkeyup=\"validatePasswords(this,'password')\"></td>";
										echo "<td><span id=\"passwordspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
										echo "</tr><tr><td style=\"padding: 5px 0px;\"><label for=\"confirm\">Confirm Password</label></td>";
										echo "<td><input class=\"form-control\" style=\"width:200px\" id=\"confirm\" type=\"password\" name=\"confirm\" placeholder=\"epiclysecret\" onkeyup=\"validatePasswords(this, 'confirm')\"></td>";
										echo "<td><span id=\"confirmspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td></tr>";
										//echo "<tr><td><input id=\"hashedPassword\" type=\"hidden\" name=\"hashedPassword\" value=\"\"></td></tr>";
									//	echo "<tr><td><input id=\"hashedConfirm\" type=\"hidden\" name=\"hashedConfirm\" value=\"\"></td></tr>";
										echo "<tr><td><input id=\"resetToken\" type=\"hidden\" name=\"resetToken\" value=\"".$tokenID."\"></td></tr>";
										echo "<tr><td><input id=\"resetUser\" type=\"hidden\" name=\"resetUser\" value=\"".$tokenUsername."\"></td></tr>";
										echo "<tr><td colspan=\"3\" align=\"right\"  style=\"padding: 30px 0px;\"> <input type=\"submit\" value=\"Submit\" id=\"submit\" style=\"width:80px; height:30px;\" class=\"btn btn-primary\" disabled></td>";
								}
							}
							else{
								echo "</tr><tr><td colspan=\"3\"><h2><b>Oink!! That is an invalid Token. Please use the one supplied in the email we sent you.</b></h2></td>";
							}
						}
						// Final case
						else if(isset($_POST["hashedPassword"]) and isset($_POST["hashedConfirm"]) and isset($_POST["resetToken"]) and isset($_POST["resetUser"])){
							// Check token for the last time
							$validTokenQuery = $dbConnection->prepare("SELECT resetTokenID, resetTokenTimestamp, resetTokenUsername FROM ResetTokens WHERE resetTokenID LIKE (?) AND resetTokenUsername LIKE (?) ORDER BY resetTokenTimestamp DESC");
							$validTokenQuery->bind_param("ss", mysqli_real_escape_string($dbConnection, $_POST["resetToken"]), mysqli_real_escape_string($dbConnection, $_POST["resetUser"]));
							$validTokenQuery->execute();
							$validTokenQuery->bind_result($tID, $tTimestamp, $tUsername);
							$validTokenQuery->store_result();
							if($validTokenQuery->num_rows > 0){
								while($validTokenQuery->fetch()){
									$tokenID = $tID;
									$tokenTimestamp = $tTimestamp;
									$tokenUsername = $tUsername;
								}
								//echo $tokenTimestamp." and ".time()." and ".time()-$tokenTimestamp;
								// Check validity of token [20 minutes given]
								if(time() - $tokenTimestamp > 1200)
									echo "</tr><tr><td colspan=\"3\"><h2><b>Oink!! Unfortunately, the token has expired. Please request another reset token.</b></h2></td>";
								else{
									// Insert the new passwords into the database
									$updatePasswordQuery = $dbConnection->prepare("UPDATE User SET userPassword=? WHERE userUsername LIKE (?)");
									$updatePasswordQuery->bind_param("ss", mysqli_real_escape_string($dbConnection, $_POST["hashedPassword"]), mysqli_real_escape_string($dbConnection, $_POST["resetUser"]));
									$updatePasswordQuery->execute();
									if($updatePasswordQuery->affected_rows == 1){
										// Remove Token
										$deleteTokenQuery = $dbConnection->prepare("DELETE FROM ResetTokens WHERE resetTokenID LIKE (?)");
										$deleteTokenQuery->bind_param("s", mysqli_real_escape_string($dbConnection, $_POST["resetToken"]));
										$deleteTokenQuery->execute();
										if($deleteTokenQuery->affected_rows > 0){
											//Send Email to customer
											echo "</tr><tr><td colspan=\"3\"><h2><b>Password successfully reset.</b></h2></td>";
										}
									}
								}
							}
						}
						else{
							echo "<td style=\"padding: 5px 0px;\"><label for=\"username\">Username</label></td>";
							echo "<td><input class=\"form-control\" style=\"width:200px\" id=\"username\" name=\"username\" type=\"text\" onkeyup=\"validateElement(this, 'username')\" placeholder=\"john.doe\"></td>";         
							echo "<td><span id=\"usernamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                                                        echo "</tr><tr>";
                                                        echo "<td colspan=\"3\" align=\"right\"  style=\"padding: 30px 0px;\"> <input type=\"submit\" value=\"Submit\" id=\"submit\" style=\"width:80px; height:30px;\" class=\"btn btn-primary\" disabled></td>";

						}
					        echo "</tr>";
					?>
                        <tr><td colspan="3"><input id="hashedAnswer" type="hidden" name="hashedAnswer" value=""></td></tr>
                        <tr><td colspan="3"><input id="hashedPassword" type="hidden" name="hashedPassword" value=""></td></tr>
                        <tr><td colspan="3"><input id="hashedConfirm" type="hidden" name="hashedConfirm" value=""></td></tr>
			</table>
		</form>
	   </div>
	</div>
</div>
</div>
<div id="push"></div>
<div id="footer">
	<div class="container">
		<p class="text-muted text-center">© 2014 Piggy Bank GmbH</p>
	</div>
</div>
<script src="./js/jquery-1.11.1.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
</body>
</html>

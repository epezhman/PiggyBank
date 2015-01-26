<?php
session_start();
ob_start();
require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/utils.php");
$_SESSION["csrfToken"] = generateCSRFToken($_SESSION["username"]);
setcookie("csrfToken", $_SESSION["csrfToken"], 0, "/");
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
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");

	$fullName = NULL;
	$userName = NULL;
	$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
	$customerFullName = $dbConnection->prepare("SELECT customerName, customerTransferSecurityMethod FROM Customer WHERE customerUsername LIKE (?)");
	$customerFullName->bind_param("s", $userUsername);
	$customerFullName->execute();

	$customerFullName->bind_result($name, $cMethod);

	$customerFullName->store_result();

	if($customerFullName->num_rows() == 1)
	{
		while($customerFullName->fetch())
		{
			$fullName = $name;
			$customerMethod = $cMethod;
		}
	}
	$customerFullName->free_result();
	$customerFullName->close();
}catch(Exception $e){
	header("Location ../error.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=ed$approvedge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Piggy Bank GmbH">
<meta name="author" content="Alei , Sara , ePezhman">
<link rel="icon" href="../images/piggyFav.ico">

<style id="antiClickjack">
body {
	display: none !important;
}
</style>
<script src="../js/secure.js"></script>

<!-- To be Changed!! -->
<title>PiggyBankGmbH - New Transfer</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="../css/framework.css" rel="stylesheet">
<script src="../js/jquery-1.11.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script type="text/javascript">
    var validated = new Array();
    var flag = true;
    
    function prepareForm(){
        // This function is meant to prepare the trasfer form
        $("#ReceiverIdSpan").hide();
        $("#TransferTokenSpan").hide();
        $("#AmountSpan").hide();
        $("#DescSpan").hide();
        validated["ReceiverId"] = false;
        validated["TransferToken"] = false;
        validated["Amount"] = false;
        validated["Desc"] = true;
        <?php
        if (isset($_GET['token'])) {
        	echo "validateElement(document.getElementById('TransferToken'), 'TransferToken');\n";
		if (isset($_GET['receiver']))
			echo "validateElement(document.getElementById('ReceiverId'), 'ReceiverId');\n";
		if (isset($_GET['amount']))
			echo "validateElement(document.getElementById('Amount'), 'Amount');\n";
	    if (issef($_GET['desc']))
			echo "validateElement(document.getElementById('Desc'), 'Desc');\n";
        } 
        ?>
    }
    
    function validateElement(e, type){
        // This function is used to validate individual
    	if(type == "ReceiverId"){
            if(e.value == ""){
                $('#'+e.id+'Span').addClass("alert-danger");
                $('#'+e.id+'Span').removeClass("alert-success");
                $('#'+e.id+'Span').text("Receiver is required");
                validated["ReceiverId"] = false;
            }
            else
                /*if(!e.value.match("^[a-zA-Z_]+$")){
                	$('#'+e.id+'Span').addClass("alert-danger");
                    $('#'+e.id+'Span').removeClass("alert-success");
                    $('#'+e.id+'Span').text("Invalid fullname");
                    validated["ReceiverId"] = false;
                }*/
            if(!e.value.match("^[a-zA-Z0-9]+$")){
            	$('#'+e.id+'Span').addClass("alert-danger");
                $('#'+e.id+'Span').removeClass("alert-success");
                $('#'+e.id+'Span').text("Invalid Receiver ID");
                validated["ReceiverId"] = false;
            }
                else if(e.value.length != 10){
            		$('#'+e.id+'Span').addClass("alert-danger");
                	$('#'+e.id+'Span').removeClass("alert-success");
                	$('#'+e.id+'Span').text("Receiver ID must be 10 char length ");
                	validated["ReceiverId"] = false;
            	}
                else{
                	$('#'+e.id+'Span').addClass("alert-success");
                    $('#'+e.id+'Span').removeClass("alert-danger");
                    $('#'+e.id+'Span').text("Check");
                    validated["ReceiverId"] = true;
                }
            $('#'+e.id+'Span').fadeIn('slow');
        }
    	else if(type == "TransferToken"){
            if(e.value == ""){
            	$('#'+e.id+'Span').addClass("alert-danger");
                $('#'+e.id+'Span').removeClass("alert-success");
                <?php
					if($customerMethod == 1)
					{
						echo "$('#'+e.id+'Span').text(\"Transfer Token is required\");\n";
						
					}
					else if($customerMethod == 2)
					{
						echo "$('#'+e.id+'Span').text(\"OTP is required\");\n";
					}
                ?>
                validated["TransferToken"] = false;
            }
            else
                if(!e.value.match("^[a-f0-9]+$")){
                	$('#'+e.id+'Span').addClass("alert-danger");
                    $('#'+e.id+'Span').removeClass("alert-success");
                    <?php
                    if($customerMethod == 1)
					{
						echo "$('#'+e.id+'Span').text(\"Invalid Token\");\n";
					}
					else if($customerMethod == 2)
					{
						echo "$('#'+e.id+'Span').text(\"Invalid OTP\");\n";
					}
					?>
                    validated["TransferToken"] = false;
                }
                
                	 <?php
                             if($customerMethod == 1)
         					{
         						echo "else if(e.value.length != 15){";
         						echo "$('#'+e.id+'Span').addClass(\"alert-danger\");\n";
         						echo "$('#'+e.id+'Span').removeClass(\"alert-success\");\n";
         						echo "$('#'+e.id+'Span').text(\"Token must be 15 char length \");\n";
         						echo "validated[\"TransferToken\"] = false;\n";
         						echo "}";
         					}
         			?>
            	
                else{
                	$('#'+e.id+'Span').addClass("alert-success");
                    $('#'+e.id+'Span').removeClass("alert-danger");
                    $('#'+e.id+'Span').text("Check");
                    validated["TransferToken"] = true;
                }
            $('#'+e.id+'Span').fadeIn('slow');
        }// This function is used to validate individual
    	if(type == "Desc"){
            if(e.value != ""){
				if(!e.value.match("^[a-zA-Z0-9 ,.]+$")){
					$('#'+e.id+'Span').addClass("alert-danger");
					$('#'+e.id+'Span').removeClass("alert-success");
					$('#'+e.id+'Span').text("Invalid Description");
					validated["Desc"] = false;
				}
                else{
                	$('#'+e.id+'Span').addClass("alert-success");
                    $('#'+e.id+'Span').removeClass("alert-danger");
                    $('#'+e.id+'Span').text("Check");
                    validated["Desc"] = true;
                }
            $('#'+e.id+'Span').fadeIn('slow');    
            }
            else
				validated["Desc"] = true;
        }
    	else if(type == "Amount"){
            if(e.value == ""){
            	$('#'+e.id+'Span').addClass("alert-danger");
                $('#'+e.id+'Span').removeClass("alert-success");
                $('#'+e.id+'Span').text("Amount is required");
                validated["Amount"] = false;
            }
            else
                if(!e.value.match("^[0-9.]+$")){
                	$('#'+e.id+'Span').addClass("alert-danger");
                    $('#'+e.id+'Span').removeClass("alert-success");
                    $('#'+e.id+'Span').text("Invalid Amount");
                    validated["Amount"] = false;
                }
                else{
                	$('#'+e.id+'Span').addClass("alert-success");
                    $('#'+e.id+'Span').removeClass("alert-danger");
                    $('#'+e.id+'Span').text("Check");
                    validated["Amount"] = true;
                }
            $('#'+e.id+'Span').fadeIn('slow');
        }
			
			validateForm();
	}
	
    function validateForm(){
        // As the name implies, this function is used to validate form
        if (validated["ReceiverId"] && validated["TransferToken"] && validated["Amount"] && validated["Desc"]){ 
            $('#submit').prop("disabled", false);
            if(flag){            
                flag = false;
            }
        }
        else{
            $('#submit').prop("disabled", true);
            flag = true;
         }
    }

    
	</script>
</head>

<body onload="prepareForm()">
	<div id="wrap">
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed"
						data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="CustomerMyTransfers.php"><img
						src="../images/logo.png" alt="" class="logoStyle" /> PiggyBank
						GmbH</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">

						<li class="visible-xs active"><a href="CustomerNewTransfer.php">New
								Transfer</a></li>
						<li class="visible-xs"><a href="CustomerMyTransfers.php">My
								Transfers and Accounts</a></li>
						<li><a href="../Help.php">Help</a></li>
						<?php 
						try{
							if(! empty($fullName))
							{
								echo "<li><a >Welcome $fullName</a></li>";
							}
						}catch(Exception $e){
							header("Location ../error.php");
						}
						?>
						<li><a href="../f8d890ce88bd1791b6eaddf06e58ceb5/logout.php">Log
								Out</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-3 col-md-2 sidebar">
					<ul class="nav nav-sidebar">
						<li class="active"><a href="CustomerNewTransfer.php">New Transfer</a>
						</li>
						<li><a href="CustomerMyTransfers.php">My Transfers and Accounts</a>
						</li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<!-- Beggining of body, above is Layout -->

					<h1 class="page-header">New Transfer</h1>

					<noscript>Javascript is switched off. Some features will not work
						properly. Please enable Javascript.</noscript>

					<?php
					if($customerMethod == 1)
					{
						echo "<h4>Please use one of your valid TAN Token to commmit the transaction</h4>";

					}
					else if($customerMethod == 2)
					{
						echo "<h4>Please generate one OTP with yout SCS to commmit the transaction</h4>";
					}
					?>

					<ul class="nav nav-tabs">
						<?php 
						if(isset($_SESSION["invUploadingFile"]))
						{
							echo "<li ><a href=\"#TransferByForm\" role=\"tab\" data-toggle=\"tab\">Transfer by Form</a></li>".
									"<li class=\"active\"><a href=\"#TransferByFile\" role=\"tab\" data-toggle=\"tab\">Transfer by File</a></li>";
						}
						else
						{
							echo "<li class=\"active\"><a href=\"#TransferByForm\" role=\"tab\"data-toggle=\"tab\">Transfer by Form</a></li>".
									"<li><a href=\"#TransferByFile\" role=\"tab\" data-toggle=\"tab\">Transfer by File</a></li>";
						}
						?>

					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div
							class="tab-pane <?php  if(!isset($_SESSION["invUploadingFile"])) echo "active"; ?>"
							id="TransferByForm">


							<div class="row">
								<div class="col-md-12">
									<?php
									if($customerMethod == 1)
									{
										echo "<form class=\"form-horizontal\" action=\"../f8d890ce88bd1791b6eaddf06e58ceb5/transfer.php\" method=\"POST\">";
									}
									else if($customerMethod == 2)
									{
										echo "<form class=\"form-horizontal\" action=\"../f8d890ce88bd1791b6eaddf06e58ceb5/transferByOTP.php\" method=\"POST\">";
									}
									?>
									<div class="form-group">
										<label for="ReceiverId" class="col-sm-2 control-label">Receiver</label>
										<div class="col-sm-6">
											<?php
											if(isset($_GET['receiver']))
												echo "<input type=\"text\" class=\"form-control\" id=\"ReceiverId\" placeholder=\"Receiver\" name=\"ReceiverId\" onload=\"validateElement(this, 'ReceiverId')\"
												onblur=\"validateElement(this, 'ReceiverId')\" onkeyup=\"validateElement(this, 'ReceiverId')\" value=\"".htmlspecialchars($_GET['receiver'], ENT_QUOTES)."\">";
											else
												echo "<input type=\"text\" class=\"form-control\" id=\"ReceiverId\" placeholder=\"Receiver\" name=\"ReceiverId\" onload=\"validateElement(this, 'ReceiverId')\"
												onblur=\"validateElement(this, 'ReceiverId')\" onkeyup=\"validateElement(this, 'ReceiverId')\">";
											?>
										</div>
										<div class="col-sm-4">
											<span class="alert" id="ReceiverIdSpan"> </span>
										</div>

									</div>
									<div class="form-group">
										<label for="TransferToken" class="col-sm-2 control-label"> <?php
										if($customerMethod == 1)
										{
											echo "Transfer Token";

										}
										else if($customerMethod == 2)
										{
											echo "OTP";
										}
										?>
										</label>
										<div class="col-sm-6">

											<?php
											if($customerMethod == 1)
											{
												$placeHolder =  "Transfer Token";

											}
											else if($customerMethod == 2)
											{
												$placeHolder =  "OTP";
											}
											if (isset($_GET['token'])) {
												$token = htmlspecialchars($_GET['token'], ENT_QUOTES);
												echo "<input type='text' class='form-control' id='TransferToken' placeholder='$placeHolder' name='TransferToken' value='$token' onload='validateElement(this, \"TransferToken\")' onblur='validateElement(this, \"TransferToken\")'  onkeyup='validateElement(this, \"TransferToken\")' >";
											}
											else
											{
												echo "<input type='text' class='form-control' id='TransferToken' placeholder='$placeHolder' name='TransferToken' onload='validateElement(this, \"TransferToken\")' onblur='validateElement(this, \"TransferToken\")' onkeyup='validateElement(this, \"TransferToken\")'>";
											}
											?>
										</div>
										<div class="col-sm-4">
											<span class="alert" id="TransferTokenSpan"> </span>
										</div>
									</div>
									<div class="form-group">
										<label for="Amount" class="col-sm-2 control-label">Amount</label>
										<div class="col-sm-6">
											<div class="input-group">
												<?php
												if(isset($_GET['amount']))
													echo "<input type=\"number\" class=\"form-control\" id=\"Amount\" placeholder=\"Amount in Euro\" name=\"Amount\" onload=\"validateElement(this, 'Amount')\"
													onblur=\"validateElement(this, 'Amount')\" onkeyup=\"validateElement(this, 'Amount')\" value=\"".htmlspecialchars($_GET["amount"], ENT_QUOTES)."\"> <span class=\"input-group-addon\">€</span>";
												else
													echo "<input type=\"number\" class=\"form-control\" id=\"Amount\" placeholder=\"Amount in Euro\" name=\"Amount\" onload=\"validateElement(this, 'Amount')\"
													onblur=\"validateElement(this, 'Amount')\" onkeyup=\"validateElement(this, 'Amount')\"> <span class=\"input-group-addon\">€</span>";
												?>

											</div>
										</div>
										<div class="col-sm-4">
											<span class="alert" id="AmountSpan"> </span>
										</div>
									</div>
									<div class="form-group">
										<label for="Desc" class="col-sm-2 control-label">Customer Reference</label>
										<div class="col-sm-6">
											<?php
											if(isset($_GET['desc']))
												echo "<input type=\"text\" class=\"form-control\" id=\"Desc\" placeholder=\"Customer Reference\" name=\"Desc\" onload=\"validateElement(this, 'Desc')\"
												onblur=\"validateElement(this, 'Desc')\" onkeyup=\"validateElement(this, 'Desc')\" value=\"".htmlspecialchars($_GET['desc'], ENT_QUOTES)."\">";
											else
												echo "<input type=\"text\" class=\"form-control\" id=\"Desc\" placeholder=\"Customer Reference\" name=\"Desc\" onload=\"validateElement(this, 'Desc')\"
												onblur=\"validateElement(this, 'Desc')\" onkeyup=\"validateElement(this, 'Desc')\">";
											?>
										</div>
										<div class="col-sm-4">
											<span class="alert" id="DescSpan"> </span>
										</div>

									</div>
									<div class="form-group">
										<div class="col-sm-offset-2 col-sm-10">
											<input type="submit" value="Submit" id="submit"
												style="width: 80px; heigh: 30px;" class="btn btn-primary"
												disabled />
										</div>
									</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<div style="text-align: center;">
										<?php
										if(isset($_SESSION["invReceiverId"]) or isset($_SESSION["invTransferToken"]) or isset($_SESSION["invInvalidOTPass"]) or isset($_SESSION["invAmount"])
												or isset($_SESSION["invNotFoundToken"]) or isset($_SESSION["invUsedToken"]) or isset($_SESSION["invNotFoundReceiver"])
												or isset($_SESSION["invNotYourself"]) or isset($_SESSION["invNotEnoughMoney"]) or isset($_SESSION["invNotFoundAccount"]))
										{
											echo "<span class='alert alert-danger' >";
											if(isset($_SESSION["invReceiverId"]))
											{
												echo "Invalid Receiver Id.  ";
											}
											if(isset($_SESSION["invTransferToken"]))
											{
												echo "Invalid Transfer Token.  ";
											}
											if(isset($_SESSION["invAmount"]))
											{
												echo "Invalid Amount.  ";
											}
											if(isset($_SESSION["invNotFoundToken"]))
											{
												echo "Token could not be found.   ";
											}
											if(isset($_SESSION["invUsedToken"]))
											{
												echo "You already used this token.  ";
											}
											if(isset($_SESSION["invNotFoundReceiver"]))
											{
												echo "This Reciver does not exist.   ";
											}
											if(isset($_SESSION["invNotYourself"]))
											{
												echo "You can't transfer money to yourself.   ";
											}
											if(isset($_SESSION["invNotEnoughMoney"]))
											{
												echo "Can't do the transfer while your balance is not enough.  ";
											}
											if(isset($_SESSION["invNotFoundAccount"]))
											{
												echo "Receiver account not found.  ";
											}
											if(isset($_SESSION["invInvalidOTPass"]))
											{
												echo "Provided OTP has expired or invalid.";
											}
											echo "</span>";

											$_SESSION["invReceiverId"] = null;
											$_SESSION["invTransferToken"] = null;
											$_SESSION["invAmount"] = null;
											$_SESSION["invNotFoundToken"] = null;
											$_SESSION["invUsedToken"] = null;
											$_SESSION["invNotFoundReceiver"] = null;
											$_SESSION["invNotYourself"] = null;
											$_SESSION["invNotEnoughMoney"] = null;
											$_SESSION["invNotFoundAccount"] = null;
											$_SESSION["invInvalidOTPass"] = null;


										}
										if(isset($_SESSION["invSuccessPaid"]))
										{
											echo "<span class='alert alert-success' >";
											echo "Transfer was added successfully <br />";
											echo "</span>";
											$_SESSION["invSuccessPaid"] = null;
										}
										?>
									</div>
								</div>

							</div>
						</div>
						<div
							class="tab-pane <?php  if(isset($_SESSION["invUploadingFile"])){ echo "active"; $_SESSION["invUploadingFile"] = null;}?>"
							id="TransferByFile">
							<div class="row">
								<div class="col-md-12">
								<?php
									if($customerMethod == 1)
									{
										echo "<form class=\"form-horizontal\" action=\"../f8d890ce88bd1791b6eaddf06e58ceb5/batchTransfer.php\" method=\"POST\" enctype=\"multipart/form-data\">";
									}
									else if($customerMethod == 2)
									{
										echo "<form class=\"form-horizontal\" action=\"../f8d890ce88bd1791b6eaddf06e58ceb5/batchTransferOTP.php\" method=\"POST\" enctype=\"multipart/form-data\">";
									}
									?>
								
									
										<div class="form-group">
											<label for="InputFile" class="col-sm-2 control-label">File
												input</label>
											<div class="col-sm-8">
												<input type="file" id="transFile"
													onchange="$('#submitFile').removeAttr('disabled')"
													name="transFile">
												<p class="help-block">Kindly upload a file containing the
													account number you wish to transfer funds to, one of your
													TANs and the amount (in Euros) you wish to transfer, each
													on a separate line.</p>
												<p class="help-block">
													<b>New</b>: Now you can include multiple transactions in
													the same file. Please use the same format as before leaving
													an empty line between each transaction.
												</p>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-offset-2 col-sm-10">
												<button type="submit" id="submitFile"
													class="btn btn-primary" disabled="disabled">Submit</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">

									<div style="text-align: center;">
										<?php
										if(isset($_SESSION["invFileBig"]) or isset($_SESSION["invMimeError"]) or isset($_SESSION["invUnknownError"])
												or isset($_SESSION["invDupFile"]) or isset($_SESSION["InvFormatError"]) or isset($_SESSION["InvFormatError2"]))
										{
											echo "<span class='alert alert-danger' >";
											if(isset($_SESSION["invFileBig"]))
											{
												echo "File is too big.  ";
											}
											if(isset($_SESSION["invMimeError"]))
											{
												echo "Only Plain Text is accepted.  ";
											}
											if(isset($_SESSION["invUnknownError"]))
											{
												echo "Sorry, Your File was not uploaded.  ";
											}
											if(isset($_SESSION["invDupFile"]))
											{
												echo "Duplicate File.  ";
											}
											if(isset($_SESSION["InvFormatError"]))
											{
												echo "File has a wrong format.  ";
											}

											echo "</span>";
											$_SESSION["invFileBig"] = null;
											$_SESSION["invMimeError"] = null;
											$_SESSION["invUnknownError"] = null;
											$_SESSION["invDupFile"] = null;
											$_SESSION["InvFormatError"] = null;
										}
										if(isset($_SESSION["invSuccessUpload"]))
										{
											echo "<span class='alert alert-success' >";
											echo "Transfer was added successfully <br />";
											echo "</span>";
											$_SESSION["invSuccessUpload"] = null;
										}
										?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- End of body, bottom is Layout -->

				</div>
			</div>
		</div>
		<div id="push"></div>
	</div>
	<div id="footer">
		<div class="container">
			<p class="text-muted text-center">© 2014 Piggy Bank GmbH</p>
		</div>
	</div>


</body>
</html>

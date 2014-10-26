<?php 
try{
	session_start();
	if(!isset($_SESSION["username"]) or  !isset($_SESSION["loginstatus"]))
		header("Location: ../signin.php");
	if($_SESSION["loginstatus"] != "authenticated" )
		header("Location: ../signin.php");
}catch(Exception $e){
	header("Location ../error.php");
}
?>
<?php 
try{
	// Connect to the database
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
	
	//require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/UserInfo.php");
	
	$fullName = NULL;

	$userName = NULL;
	$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
	$customerFullName = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerUsername LIKE (?)");
	$customerFullName->bind_param("s", $userUsername);
	$customerFullName->execute();

	$customerFullName->bind_result($name);

	$customerFullName->store_result();

	if($customerFullName->num_rows() == 1)
	{
		while($customerFullName->fetch())
		{
			$fullName = $name;
		}
		$customerFullName->free_result();
		$customerFullName->close();

	}
}catch(Exception $e){
	header("Location ../error.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Piggy Bank GmbH">
<meta name="author" content="Alei , Sara , ePezhman">
<link rel="icon" href="../images/piggyFav.ico">

<!-- To be Changed!! -->
<title>PB - Customer Home</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="../css/framework.css" rel="stylesheet">

<script type="text/javascript">
    var validated = new Array();
    var flag = true;
    function prepareForm(){
        // This function is meant to prepare the trasfer form
        $("#receiverID").hide();
        $("#transferToken").hide();
        $("#amount").hide();
        
        validated["receiverID"] = false;
        validated["transferToken"] = false;
        validated["amount"] = false;
        
        
    }
    function validateElement(e, type){
        // This function is used to validate individual
        if(type == "receiverID")
	    if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Username is required");
                $('#'+e.id+'span').fadeIn('slow');
                validated["username"] = false;
			}
			else{
				$('#'+e.id+'span').fadeOut('slow');
				validated["username"] = true;
			}
                
       else if(type == "password")
           if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Password is required");
                $('#'+e.id+'span').fadeIn('slow');
                validated["password"] = false;
	   }
           else if(e.value.length < 8){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Password too short");
                $('#'+e.id+'span').fadeIn('slow');
                validated["password"] = false;
           }
	   else{
		$('#'+e.id+'span').fadeOut('slow');
		validated["password"] = true;
	   }
			
			validateForm();
	}
	
    function validateForm(){
        // As the name implies, this function is used to validate form
        if (validated["username"] && validated["password"]){ 
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
	</script>


</head>

<body>
	<div id="wrap">
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed"
						data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#"><img src="../images/logo.png"
						alt="" class="logoStyle" /> Piggy Bank GmbH</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">

						<li class="visible-xs active"><a href="CustomerNewTransfer.php">New
								Transfer</a></li>
						<li class="visible-xs"><a href="CustomerMyTokens.php">My Tokens</a>
						</li>
						<li class="visible-xs"><a href="CustomerMyTransfers.php">My
								Transfers</a></li>

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
						<li><a href="#">Log Out</a></li>
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
						<li><a href="CustomerMyTokens.php">My Tokens</a></li>
						<li><a href="CustomerMyTransfers.php">My Transfers</a></li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<!-- Beggining of body, above is Layout -->

					<h1 class="page-header">New Transfer</h1>

					<ul class="nav nav-tabs" role="tablist">
						<li class="active"><a href="#TransferByForm" role="tab"
							data-toggle="tab">Transfer by Form</a></li>
						<li><a href="#TransferByFile" role="tab" data-toggle="tab">Transfer
								by File</a></li>
					</ul>

					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="TransferByForm">

							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="ReceiverId" class="col-sm-2 control-label">Receiver</label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="ReceiverId"
											placeholder="Receiver" name="ReceiverId">
									</div>
								</div>
								<div class="form-group">
									<label for="TransferToken" class="col-sm-2 control-label">Transfer
										Token</label>
									<div class="col-sm-8">
										<?php
										if (isset($_GET['token'])) {
											$token = trim($_GET['token']);
											echo "<input type='text' class='form-control' id='TransferToken' placeholder='Transfer Token' name='TransferToken'value='$token' >";
										}
										else
										{
											echo "<input type='text' class='form-control' id='TransferToken' placeholder='Transfer Token' name='TransferToken'>";
										}
										?>
									</div>
								</div>
								<div class="form-group">
									<label for="Amount" class="col-sm-2 control-label">Amount</label>
									<div class="col-sm-8">
										<div class="input-group">
											<input type="number" class="form-control" id="Amount"
												placeholder="Amount in Euro" name="Amount"> <span
												class="input-group-addon">€</span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</form>

						</div>
						<div class="tab-pane" id="TransferByFile">

							<form class="form-horizontal" role="form">
								<div class="form-group">
									<label for="InputFile" class="col-sm-2 control-label">File
										input</label>
									<div class="col-sm-8">
										<input type="file" id="InputFile">
										<p class="help-block">Upload the file containing ReceiverId,
											Transfer Token and Amount of Euros you wish to trasnfer.</p>
									</div>
								</div>

								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button type="submit" class="btn btn-primary">Submit</button>
									</div>
								</div>
							</form>

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
	<script src="../js/jquery-1.11.1.min.js"></script>
	<script src="../js/bootstrap.min.js"></script>

</body>
</html>

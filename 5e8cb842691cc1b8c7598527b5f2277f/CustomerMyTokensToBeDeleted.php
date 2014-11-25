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
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");

	$fullName = NULL;

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
<meta http-equiv="X-UA-Compatible" content="IE=edge">
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
<title>PiggyBank GmbH - My Tokens</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="../css/framework.css" rel="stylesheet">

</head>

<body>
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
					<a class="navbar-brand" href="CustomerMyTransfer.php"><img
						src="../images/logo.png" alt="" class="logoStyle" /> Piggy Bank
						GmbH</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">

						<li class="visible-xs"><a href="CustomerNewTransfer.php">New
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
						<li><a href="CustomerNewTransfer.php">New Transfer</a></li>
						<li><a href="CustomerMyTransfers.php">My Transfers and Accounts</a>
						</li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<!-- Beggining of body, above is Layout -->

					<h1 class="page-header">My Tokens</h1>

					<div class="table-responsive">
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>#</th>
									<th>Token Value</th>
									<th>Status</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
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
										$tokens = $dbConnection->prepare("SELECT tokenID, tokenUsed FROM Token WHERE tokenCustomer LIKE (?) ORDER BY tokenUsed");
										$tokens->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
										$tokens->execute();
										$tokens->bind_result($tokenID, $tokenUsed);
										$tokens->store_result();
										$cnt =0;
										while($tokens->fetch())
										{
											$cnt++;
											if($tokenUsed == 0)
											{
												echo "<tr>".
														"<td>$cnt</td>".
														"<td>$tokenID</td>".
														"<td><span class=\"label label-success\"><span class=\"glyphicon glyphicon-ok\"></span> Available</span></td>".
														"<td><a href=\"CustomerNewTransfer.php?token=$tokenID\" class=\"btn btn-default btn-xs\" data-toggle=\"tooltip\" title=\"To Transfer\"> <span class=\"glyphicon glyphicon-arrow-right\"></span></a></td>".
														"</tr>";

											}
											else
											{
												echo "<tr>".
														"<td>$cnt</td>".
														"<td>$tokenID</td>".
														"<td><span class=\"label label-danger\"><span class=\"glyphicon glyphicon-remove\"></span> Used</span></td>".
														"<td></td>".
														"</tr>";
											}

										}
										$tokens->free_result();
										$tokens->close();
									}
									else
										header("Location: ../signin.php");

								}catch(Exception $e){
									header("Location ../error.php");
								}
								?>
							</tbody>
						</table>
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

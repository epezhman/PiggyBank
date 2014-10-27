<?php
session_start();
require("../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php");
if($_SESSION["userrole"] != "customer")
	header("Location: ../error.php?id=404");
try{
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");


	$fullName = NULL;
	$userID = NULL;
	$userUsername = mysqli_real_escape_string($dbConnection,$_SESSION['username']);
	$customerFullName = $dbConnection->prepare("SELECT customerName, customerID FROM Customer WHERE customerUsername LIKE (?)");
	$customerFullName->bind_param("s", $userUsername);
	$customerFullName->execute();
	$customerFullName->bind_result($name, $ID);
	$customerFullName->store_result();

	if($customerFullName->num_rows() == 1)
	{
		while($customerFullName->fetch())
		{
			$fullName = $name;
			$userID = $ID;
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

<!-- To be Changed!! -->
<title>PiggyBank GmbH - My Transfers and Accounts</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="../css/framework.css" rel="stylesheet">

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

						<li class="visible-xs"><a href="CustomerNewTransfer.php">New
								Transfer</a></li>
						<li class="visible-xs"><a href="CustomerMyTokens.php">My Tokens</a>
						</li>
						<li class="visible-xs active"><a href="CustomerMyTransfers.php">My
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
						<li><a href="CustomerMyTokens.php">My Tokens</a></li>
						<li class="active"><a href="CustomerMyTransfers.php">My Transfers
								and Accounts</a>
						</li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<!-- Beggining of body, above is Layout -->

					<h1 class="page-header">My Transfers and Accounts</h1>

					<fieldset>
						<legend>My Account Numbers</legend>
						<div class="row">
							<?php
							try{
								$customerAccount = $dbConnection->prepare("SELECT accountNumber FROM Account WHERE accountOwner LIKE (?) ");
								$customerAccount->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
								$customerAccount->execute();
								$customerAccount->bind_result($customerAccountNumber );
								$customerAccount->store_result();

								while($customerAccount->fetch())
								{
									echo "<div class=\"col-md-12\"><h3 class=\"\">";
									echo $customerAccountNumber;
									echo "</div></h3>";
								}

								$customerAccount->free_result();
								$customerAccount->close();

							}catch(Exception $e){
								header("Location ../error.php");
							}
							?>
						</div>
					</fieldset>
					<br>
					<fieldset>
						<legend>My Transfers</legend>

					</fieldset>
					<div class="table-responsive">
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>#</th>
									<th>Receiver</th>
									<th>Amount (€)</th>
									<th>Sent Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<?php
								try{
									$transfers = $dbConnection->prepare("SELECT transactionReceiver, transactionAmont, transactionTime, transactionApproved FROM Transaction WHERE transactionSender LIKE (?) ");
									$transfers->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
									$transfers->execute();
									$transfers->bind_result( $transactionReceiver, $transactionAmont, $transactionTime, $transactionApproved);
									$transfers->store_result();
									$i = 0;
									while($transfers->fetch())
									{
										$i++;
										echo "<tr>";

										echo "<td>$i</td>";

										$customerFullName = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerID LIKE (?)");
										$customerFullName->bind_param("s", mysqli_real_escape_string($dbConnection,$transactionReceiver));
										$customerFullName->execute();
										$customerFullName->bind_result($name);
										$customerFullName->store_result();
											
										 
											while($customerFullName->fetch())
											{
												echo "<td>$name</td>";
											}
										 
										$customerFullName->free_result();
										$customerFullName->close();

										echo "<td>$transactionAmont</td>";

										echo "<td>$transactionTime</td>";

										if($transactionApproved)
										{
											echo "<td><span class=\"label label-success\"><span
											class=\"glyphicon glyphicon-ok\"></span> Sent</span></td>";
										}
										else
										{
											echo "<td><span class=\"label label-warning\"><span
										class=\"glyphicon glyphicon-time\"></span> Pending</span></td>";
										}

										echo "</tr>";
									}

									$transfers->free_result();
									$transfers->close();

								}catch(Exception $e){
									header("Location ../error.php");
								}
								?>
							</tbody>
							<tfoot>
								<tr>
									<td colspan="3"><span>Count : 20; Page 1 of 2</span>
									</td>
									<td colspan="3">
										<div class="marginPagingHeight30">
											<ul class="pagination pagination-sm marginPaging">
												<li class="active"><a href="javascript:void(0);">1</a>
												</li>
												<li><a href="#">2</a>
												</li>
											</ul>
										</div>
									</td>
								</tr>
							</tfoot>
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

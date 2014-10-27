﻿<?php
    session_start();
    require("../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php");
    if($_SESSION["userrole"] != "customer")
        header("Location: ../error.php?id=404");
?>
<?php 
try{
	// Connect to the database
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
	
	//require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/UserInfo.php");
	
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
						<li><a href="../f8d890ce88bd1791b6eaddf06e58ceb5/logout.php">Log Out</a></li>
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
						<li class="active"><a href="CustomerMyTransfers.php">My Transfers and Accounts</a>
						</li>
					</ul>
				</div>
				<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

					<!-- Beggining of body, above is Layout -->

					<h1 class="page-header">My Transfers</h1>

					<fieldset>
						<legend>Filter</legend>
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<label class="control-label col-sm-2" for="">Receiver</label>
								<div class="col-sm-10">
									<input type="text" name="Receiver" />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" value="Filter" id="submit"
										class="btn btn-default" />
								</div>
							</div>
						</form>
					</fieldset>

					<div class="table-responsive">
						<table class="table table-striped table-hover ">
							<thead>
								<tr>
									<th>#</th>
									<th>Receiver</th>
									<th>Amount (€)</th>
									<th>Submit Date</th>
									<th>Sent Date</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>1</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-warning"><span
											class="glyphicon glyphicon-time"></span> Pending</span></td>
								</tr>
								<tr>
									<td>2</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-warning"><span
											class="glyphicon glyphicon-time"></span> Pending</span></td>
								</tr>
								<tr>
									<td>3</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-warning"><span
											class="glyphicon glyphicon-time"></span> Pending</span></td>
								</tr>
								<tr>
									<td>4</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="label label-success"><span
											class="glyphicon glyphicon-ok"></span> Sent</span></td>
								</tr>
								<tr>
									<td>5</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="label label-success"><span
											class="glyphicon glyphicon-ok"></span> Sent</span></td>
								</tr>
								<tr>
									<td>6</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td><span class="label label-success"><span
											class="glyphicon glyphicon-ok"></span> Sent</span></td>
								</tr>
								<tr>
									<td>7</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-danger"><span
											class="glyphicon glyphicon-remove"></span> Rejected</span></td>
								</tr>
								<tr>
									<td>8</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-danger"><span
											class="glyphicon glyphicon-remove"></span> Rejected</span></td>
								</tr>
								<tr>
									<td>9</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-danger"><span
											class="glyphicon glyphicon-remove"></span> Rejected</span></td>
								</tr>
								<tr>
									<td>10</td>
									<td>Stefan</td>
									<td>100</td>
									<td><span class="badge">12.12.2014 13:30</span></td>
									<td></td>
									<td><span class="label label-danger"><span
											class="glyphicon glyphicon-remove"></span> Rejected</span></td>
								</tr>
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

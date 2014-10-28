<?php
session_start();
require("../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php");
if($_SESSION["userrole"] != "customer")
	header("Location: ../error.php?id=404");
try{
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");

	$page = 1;

	if (isset($_GET['page'])) {
		if(filter_var(trim($_GET['page']), FILTER_VALIDATE_INT)) {
			$page = trim($_GET['page']);
		}
	}

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
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed"
						data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span> <span
							class="icon-bar"></span> <span class="icon-bar"></span> <span
							class="icon-bar"></span>
					</button>
					<a class="navbar-brand"><img src="../images/logo.png" alt=""
						class="logoStyle" /> Piggy Bank GmbH</a>
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
						<legend>Export My Transfers</legend>
						<a class="btn btn-default"
							href="../f8d890ce88bd1791b6eaddf06e58ceb5/GenPDFCustomer.php"
							target="blank">Export</a>
					</fieldset>
					<br> <br>

					<fieldset>
						<legend>
							<?php echo $fullName;?>
							Accounts
						</legend>
						<div class="row">
							<?php
							try{
								$customerAccount = $dbConnection->prepare("SELECT accountNumber, accountBalance FROM Account WHERE accountOwner LIKE (?) ");
								$customerAccount->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
								$customerAccount->execute();
								$customerAccount->bind_result($customerAccountNumber, $accountBalance );
								$customerAccount->store_result();

								while($customerAccount->fetch())
								{
									echo "<div class=\"col-md-12\"><h4 class=\"\">";
									echo "Account Number: $customerAccountNumber <br>";
									echo "Balance: $accountBalance €";
									echo "</div></h4>";
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
									$count = 0;

									$transfers = $dbConnection->prepare("SELECT COUNT(*) FROM Transaction WHERE transactionSender LIKE (?)");
									$transfers->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
									$transfers->execute();
									$transfers->bind_result( $total);
									$transfers->store_result();
									while($transfers->fetch())
									{
										$count = $total;
									}
									$transfers->free_result();
									$transfers->close();

									$totalPage = $count%10 == 0 ? floor($count/10) : floor($count/10) +1;

									if($page != 1)
									{
										if($page > $totalPage or $page < 1)
											$page = 1;
									}
									$begin = ($page - 1) *10;

									$transfers = $dbConnection->prepare("SELECT transactionReceiver, transactionAmont, transactionTime, transactionApproved FROM Transaction WHERE transactionSender LIKE (?) ORDER BY transactionTime DESC LIMIT 10 OFFSET $begin ");
									$transfers->bind_param("s", mysqli_real_escape_string($dbConnection,$userID));
									$transfers->execute();
									$transfers->bind_result( $transactionReceiver, $transactionAmont, $transactionTime, $transactionApproved);
									$transfers->store_result();
									$i = $begin;
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
									<td colspan="3"><span>Count : <?php echo $count;?> - Page <?php echo $page;?>
											of <?php echo $totalPage;?>
									</span>
									</td>
									<td colspan="3">
										<div class="marginPagingHeight30">
											<ul class="pagination pagination-sm marginPaging">
												<?php 
												if($page - 2 > 1)
												{
													echo "<li><a href=\"CustomerMyTransfers.php?page=1\"><<</a></li>";
												}
												for ($j = 2 ; $j >= 1; $j--)
												{
													if($page - $j >= 1)
													{
														$m = $page - $j;
														echo "<li><a href=\"CustomerMyTransfers.php?page=$m\">$m</a></li>";
													}
												}
												echo "<li class='active'><a href=\"javascript:void(0);\">$page</a></li>";
												for ($j = 1 ; $j <= 2; $j++)
												{
													if($page + $j <= $totalPage)
													{
														$m = $page + $j;
														echo "<li><a href=\"CustomerMyTransfers.php?page=$m\">$m</a></li>";
													}
												}
												if($page + 2 < $totalPage)
												{
													echo "<li><a href=\"CustomerMyTransfers.php?page=$totalPage\">>></a></li>";
												}
												?>
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

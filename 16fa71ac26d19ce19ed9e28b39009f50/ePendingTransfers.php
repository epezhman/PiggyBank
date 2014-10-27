
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Piggy Bank GmbH">
<meta name="author" content="Alei , Sara , ePezhman">
<link rel="icon" href="../images/piggyFav.ico">

<!-- To be Changed!! -->
<title>PiggyBank GmbH - Pending Transfers</title>

<!-- Bootstrap core CSS -->
<link href="../css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="../css/framework.css" rel="stylesheet">

</head>
<?php
ob_start();
require "../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php";
$authenticated =  ob_get_clean();
if($authenticated == -1){
	header("Location: ../error.php?id=404");
	exit();
}
if($authenticated == -2){
	header("Location: ../error.php?id=440");
	exit();
}

session_start();
if($_SESSION["userrole"] != "admin"){
	header("Location: ../error.php?id=404");
	exit();
}
?>
<body>
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
                    <a class="navbar-brand" href="eCustomerManagers.php"><img src="../images/logo.png" alt="" class="logoStyle" /> Piggy Bank GmbH</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                       
			<li class="visible-xs"><a href="ePendingRegistrations.php">Pending Registrations</a></li>
                        <li class="visible-xs"><a href="eCustomerManagers.php">Registered Customers</a></li>
                        <li class="visible-xs active"><a href="ePendingTransfers.php">Pending Transfers</a></li>
                        <li class="visible-xs"><a href="eTransfers.php">All Transfers</a></li>

                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="../f8d890ce88bd1791b6eaddf06e58ceb5/logout.php">Log Out</a></li>

                    </ul>
                    
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">
			<li><a href="ePendingRegistrations.php">Pending Registrations</a></li>
                        <li><a href="eCustomerManagers.php">Registered Customers</a></li>
                        <li class="active"><a href="ePendingTransfers.php">Pending Transfers</a></li>
                        <li><a href="eTransfers.php">All Transfers</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <!-- Beggining of body, above is Layout -->

                    <h1 class="page-header">Pending Transfers</h1>

                     <fieldset>
			<br></br>	
       <!--                 <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-1" for="">Transfer</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Sender" />
				    <input type="submit" value="Filter" id="submit" class="btn btn-default" style="margin-left:5%;" />
                                </div>
                            </div>
                    
                        </form> -->
                    </fieldset>
		    <br></br>	


<?php
    // Check the referer first to deny nosey requests
 //   if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
 //       header("Location: ../error.php?id=404");
       require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
       
        if(mysqli_connect_errno()){
            header("Location: ../error.php");
            exit();
        }
?>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover ">
                            <thead>
                                <tr>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Amount (€)</th>
                                    <th>Submit Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                           
		<?php
		
		if(isset($_POST['remove'])){
		$var = $_POST['remove'];
		$deleteTransactionStmt = $dbConnection->prepare("delete from Transaction where transactionID=?");
		$deleteTransactionStmt->bind_param("s",$var);
		$deleteTransactionStmt->execute();
		}

		if(isset($_POST['approve'])){
		$var = $_POST['approve'];
		
		$updateStmt = $dbConnection->prepare("update Transaction set transactionApproved=1 where transactionID=?");
		$updateStmt->bind_param("s",$var);
		$updateStmt->execute();

		$result1 = $dbConnection->query("select transactionSender,transactionReceiver,transactionAmont from Transaction where transactionID=" .$var)or die(mysql_error());
		$row1 = mysqli_fetch_row($result1);
		echo $row1;
		
		$updateReceiverStmt = $dbConnection->prepare("update Account set accountBalance=accountBalance+? where accountOwner=?");//or die(mysql_error());
		$updateReceiverStmt->bind_param("ds",$row1[2], $row1[1]);
		$updateReceiverStmt->execute();

		$updateSenderStmt = $dbConnection->prepare("update Account set accountBalance=accountBalance-? where accountOwner=?");
		$updateSenderStmt->bind_param("ds", $row1[2], $row1[0]);
		$updateSenderStmt->execute();
		
		}
		
		$result = $dbConnection->query("select C1.customerName,C2.customerName,transactionAmont,transactionTime,C1.customerID,C2.customerID,Transaction.transactionID from Transaction,Customer C1,Customer C2 where transactionSender=C1.customerID and transactionReceiver=C2.customerID and transactionApproved=0 and transactionAmont>10000") or die(mysql_error());
		while($row = mysqli_fetch_row($result)){
		$index= 0;
		
		echo '<tr>';
		echo '<td style="width:25%" >' . $row[0]. '</td>';
		echo '<td style="width:25%" >' . $row[1]. '</td>';
		echo '<td style="width:20%" >' . $row[2]. '</td>';
		echo '<td style="width:20%" >' . $row[3]. '</td>';
		echo '<td>';
		echo '<form method="post" action="ePendingTransfers.php">';
		echo '<button  type="submit" name="remove" id="remove" class="btn btn-default btn-xs" data-toggle="tooltip" title="Remove" value=' . $row[6]. '>
                      <span class="glyphicon glyphicon-remove"></span>
                      </button>

                      <button type="submit" name="approve" id="approve" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Approve" value=' . $row[6]. '>
                      <span class="glyphicon glyphicon-ok"></span>
                       </button>';
		$index++;
		echo '</form>';
		echo '</td>';
		}

		

		?>	
		</tbody>
		

   			
		</table>
</div>
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

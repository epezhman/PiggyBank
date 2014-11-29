
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
			<li class="visible-xs"><a href="ePendingEmployees.php">Pending Employee </a></li>
                        <li class="visible-xs active"><a href="eCustomerManagers.php">Registered Customers</a></li>
                        <li class="visible-xs"><a href="ePendingTransfers.php">Pending Transfers</a></li>
                        <li class="visible-xs"><a href="eTransfers.php">All Transfers</a></li>


                      <!--  <li><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>-->
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
                                    <th>Sender Account</th>
                                    <th>Receiver</th>
                                    <th>Receiver Account</th>
                                    <th>Amount (€)</th>
                 
                                    <th>Submit Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                      <tbody>     
		<?php
		
		if(isset($_POST['remove'])){
		/*$var = $_POST['remove'];
		$deleteTransactionStmt = $dbConnection->prepare("delete from Transaction where transactionID=?");
		$deleteTransactionStmt->bind_param("s",$var);
		$deleteTransactionStmt->execute();*/
                $var = $_POST['remove'];
		
		$updateStmt = $dbConnection->prepare("update Transaction set transactionApproved=2 where transactionID=?");
		$updateStmt->bind_param("s",$var);
		$updateStmt->execute();
		
		}

		if(isset($_POST['approve'])){
                 
		$var = $_POST['approve'];
		 
		$updateStmt = $dbConnection->prepare("update Transaction set transactionApproved=1 where transactionID=?");
		$updateStmt->bind_param("s",$var);
		$updateStmt->execute();

		//$transactionStmt = $dbConnection->prepare("select transactionSender,transactionReceiver,transactionAmont from Transaction where transactionID=?");
		//$transactionStmt->bind_param("s",$var);
		//$transactionStmt->execute();
		//$transactionStmt->store_results();
		//if($transactionStmt->num_rows > 0) echo "<h1>>heyho</h1>";
		$result = $dbConnection->query("select transactionSender,transactionReceiver,transactionAmount from Transaction where transactionID='".$var."'");
		$row1 =  mysqli_fetch_row($result);
		//$row1 = mysqli_fetch_row($result1);
		
		
		$updateReceiverStmt = $dbConnection->prepare("update Account set accountBalance=accountBalance+? where accountNumber=?");//or die(mysql_error());
		$updateReceiverStmt->bind_param("ds",$row1[2], $row1[1]);
		$updateReceiverStmt->execute();

		$updateSenderStmt = $dbConnection->prepare("update Account set accountBalance=accountBalance-? where accountNumber=?");
		$updateSenderStmt->bind_param("ds", $row1[2], $row1[0]);
		$updateSenderStmt->execute();
		
		}
		
		/*$result = $dbConnection->query("select C1.customerName,A1.accountNumber,C2.customerName,A2.accountNumber,transactionAmont,transactionTime,C1.customerID,C2.customerID,Transaction.transactionID from Transaction,Customer C1,Customer C2,Account A1,Account A2 where transactionSender=A1.accountNumber and transactionReceiver=A2.accountNumber and C1.customerID=A1.accountOwner and C2.customerID=A2.accountOwner and transactionApproved=0 and transactionAmount>10000") or die(mysql_error());
		while($row = mysqli_fetch_row($result)){
		$index= 0;
		
		echo '<tr>';
		echo '<td style="width:15%" >' . $row[0]. '</td>';
		echo '<td style="width:15%" >' . $row[1]. '</td>';
		echo '<td style="width:15%" >' . $row[2]. '</td>';
		echo '<td style="width:15%" >' . $row[3]. '</td>';
                echo '<td style="width:15%" >€' . $row[4]. '</td>';
                echo '<td style="width:15%" >' . $row[5]. '</td>';
		echo '<td>';
		echo '<form method="post" action="ePendingTransfers.php">';
		echo '<button  type="submit" name="remove" id="remove" class="btn btn-default btn-xs" data-toggle="tooltip" title="Remove" value=' . $row[8]. '>
                      <span class="glyphicon glyphicon-remove"></span>
                      </button>

                      <button type="submit" name="approve" id="approve" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Approve" value=' . $row[8]. '>
                      <span class="glyphicon glyphicon-ok"></span>
                       </button>';
		$index++;
		echo '</form>';
		echo '</td>';
		}*/

                try{
                                                                
                       $transfers = $dbConnection->prepare("SELECT transactionReceiver, transactionSender, transactionAmount, transactionTime, transactionApproved,A1.accountNumber,A2.accountNumber,A1.accountOwner,A2.accountOwner,transactionID  FROM Transaction, Account A1, Account A2 WHERE transactionSender=A1.accountNumber AND transactionReceiver=A2.accountNumber AND (transactionApproved=0) AND transactionAmount>10000 ");
                       $transfers->execute();
                       $transfers->bind_result( $transactionReceiver, $transactionSender, $transactionAmont, $transactionTime, $transactionApproved, $accountNrSender, $accountNrReceiver,$accountOwnerSender,$accountOwnerReceiver,$transID);
                       $transfers->store_result();
                                
                        while($transfers->fetch())
                        {
                                                                                
                        echo "<tr>";
                        $customerFullName = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerID=?");
                        $customerFullName->bind_param("s", mysqli_real_escape_string($dbConnection, $accountOwnerSender));
                        $customerFullName->execute();
                        $customerFullName->bind_result($nameSender);
                        $customerFullName->store_result();
                                                                                        
                                                                                        
                        while($customerFullName->fetch())
                        {
                                echo "<td>$nameSender</td>";
                        }

                        $customerFullName->free_result();
                        $customerFullName->close();
                                                                                
                                                                                
                        echo "<td>$transactionSender</td>";

                        

                        $customerFullName = $dbConnection->prepare("SELECT customerName FROM Customer WHERE customerID=?");
                        $customerFullName->bind_param("s", mysqli_real_escape_string($dbConnection, $accountOwnerReceiver));
                        $customerFullName->execute();
                        $customerFullName->bind_result($nameReceiver);
                        $customerFullName->store_result();
                                                                                        
                                                                                        
                        while($customerFullName->fetch())
                        {
                                echo "<td>$nameReceiver</td>";
                        }
                                                                                        
                        $customerFullName->free_result();
                        $customerFullName->close();
                                                                                
                                                                                
                        echo "<td>$transactionReceiver</td>";

                        echo "<td>$transactionAmont</td>";

                        echo "<td>$transactionTime</td>";

                        echo '<td>';
                        echo '<form method="post" action="ePendingTransfers.php">';
                        echo '<button  type="submit" name="remove" id="remove" class="btn btn-default btn-xs" data-toggle="tooltip" title="Remove" value=' . $transID. '>
                        <span class="glyphicon glyphicon-remove"></span>
                        </button>

                        <button type="submit" name="approve" id="approve" class="btn btn-primary btn-xs" data-toggle="tooltip" title="Approve" value=' . $transID. '>
                        <span class="glyphicon glyphicon-ok"></span>
                        </button>';
                        $index++;
                        echo '</form>';
                        echo '</td>';

                        echo "</tr>";
                        }

                         $transfers->free_result();
                          $transfers->close();

                        }catch(Exception $e){
                                header("Location ../error.php");
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

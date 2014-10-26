<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="../images/piggyFav.ico">
   			
    <!-- To be Changed!! -->
    <title>
        PiggyBank - Pending Customer Requests 
    </title>

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
        <?php
            ob_start();
            require "../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php";
            if(ob_get_clean() == -1){
                header("Location: ../error.php?id=404");
                exit();
            }
            session_start();
            if($_SESSION["userrole"] != "admin"){
                header("Location: ../error.php?id=404");
                exit();
            }

        ?>
	<div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">                      
                        <li class="active"><a href="ePendingRegistrations.php">Pending Registrations</a></li>
			<li><a href="eCustomerManagers.php">Registered Customers</a></li>
			<li><a href="ePendingTransfers.php">Pending Transfers</a></li>                       
			<li><a href="eTransfers.php">All Transfers</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <!-- Beggining of body, above is Layout -->

                    <h1 class="page-header">Pending Registrations</h1>

                    <fieldset>
			<br></br>	
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-1" for="">Customer</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Sender" />
				    <input type="submit" value="Filter" id="submit" class="btn btn-default" style="margin-left:5%;" />
                                </div>
                            </div>
                    
                        </form>
                    </fieldset>
		    <br></br>	
<?php

        if(mysqli_connect_errno()){
            header("Location: ../error.php");
            exit();
        }
function sendEmail($eAddress, $eSubject, $eBody){




}
function generateTokens($custID){
    // Generates 100 unique tokens and returns them to the caller
    $customerTokens = array();
    $counter = 0;
    while($counter < 100){
        $tempToken =  substr(sha1($custID.$counter++ .microtime(true).getRandomString()), 0, 15);
        // Now check whether token is already in the Token's table
        $tokenAvailableStmt = $dbConnection->prepare("SELECT * FROM Token WHERE tokenID =? AND tokenUsed=0");
        $tokenAvailableStmt->bind_param("s", $tempToken);
        $tokenAvailableStmt->execute();
    }

}
?>

		<div class="table-responsive">
		<table class="table table-striped table-hover ">
		<thead>
			<tr><th>Name</th><th>Date of Birth</th><th>Address</th><th>Account Balance</th><th>Action</th></tr>
		</thead>
		
		<tbody>	
		<?php
		

		if(isset($_POST['remove'])){
		   //mail should be sent
		$var = $_POST['remove'];
		$dbConnection->query("delete from User where User.userUsername='$var'")or die(mysql_error());
		
		}

		if(isset($_POST['approve'])){
		    $var = $_POST['approve'];
                    $dbConnection->query("update User set userApproved=1 where User.userUsername='$var'")or die(mysql_error());
		    // Generate tokens and email customer
                    // 1- Retrieve customer details based on username
                    $customerStmt = $dbConnection->prepare("SELECT customerID, customerName, customerEmail FROM Customer WHERE customerUsername LIKE (?)");
                    $customerStmt->bind_param("s", $var);
                    $customerStmt->execute();
                    $customerStmt->bind_result($ID, $Name, $Email);
                    $customerStmt->store_result();
                    if($customerStmt->num_rows() == 1){
                        while($customerStmt->fetch()){
                            $customerID = $ID;
                            $customerName = $Name;
                            $customerEmail = $Email;
	                }
                    }
                    $customerTokens = generateTokens($customerID);
                }           




		$result = $dbConnection->query("select User.userUsername,Customer.customerDOB,Customer.customerAddress,Account.accountType,Account.accountBalance,Customer.customerEmail,Customer.customerID  from User,Customer,Account where User.userUsername=Customer.customerUsername and User.userApproved=0 and Account.accountOwner= Customer.customerID") or die(mysql_error());
		while($row = mysqli_fetch_row($result)){
		echo '<tr>';
		echo '<td style="width:25%" >' . $row[0]. '</td>';
		echo '<td style="width:20%" >' . $row[1]. '</td>';
		echo '<td style="width:25%" >' . $row[2]. '</td>';
		//echo '<td style="width:18%" >' . $row[3]. '</td>';
		echo '<td style="width:20%" >' . $row[4]. '</td>';

		echo '<td>';
		
		echo '<form method="post" action="ePendingRegistrations.php">';
		echo '<button  type="submit" name="remove"  class="btn btn-default btn-xs" data-toggle="tooltip" title="Remove" value=' .$row[0]. '>
                      <span class="glyphicon glyphicon-remove"></span>
                      </button>

                      <button type="submit"  name="approve"  class="btn btn-primary btn-xs" data-toggle="tooltip" title="Approve" value=' .$row[0]. '>
                      <span class="glyphicon glyphicon-ok"></span>
                       </button>';
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

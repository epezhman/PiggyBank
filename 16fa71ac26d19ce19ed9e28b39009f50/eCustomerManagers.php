<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="../images/piggyFav.ico">

    <!-- To be Changed!! -->
    <title>
        PiggyBank GmbH - Customer Managers 
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
                    <?php
                        require "../f8d890ce88bd1791b6eaddf06e58ceb5/accesscontrol.php";
                        session_start();
                        if($_SESSION['userrole'] != 'admin')
                            header("Location: ../error.php?id=404");
                    ?>
                    <a class="navbar-brand" href=".."><img src="../images/logo.png" alt="" class="logoStyle" /> Piggy Bank GmbH</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
			<li class="visible-xs"><a href="ePendingRegistrations.php">Pending Registrations</a></li>
                        <li class="visible-xs active"><a href="eCustomerManangers.php">Registered Customers</a></li>
                        <li class="visible-xs"><a href="ePendingTransfers.php">Pending Transfers</a></li>
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
                        <li class="active"><a href="eCustomerManangers.php">Registered Customers</a></li>
                        <li><a href="ePendingTransfers.php">Pending Transfers</a></li>
                        <li><a href="eTransfers.php">All Transfers</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <!-- Beggining of body, above is Layout -->

                    <h1 class="page-header">Registered Customers</h1>

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
    // Check the referer first to deny nosey requests
//    if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
//        header("Location: ../error.php?id=404");
       require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
        if(mysqli_connect_errno()){
            header("Location: ../error.php");
        }
?>

		<div class="table-responsive">
		<table class="table table-striped table-hover ">
		<thead>
			<tr><th>Name</th><th>Date of Birth</th><th>Address</th><th>Email</th><th>Account Type</th><th>Account Balance</th><th>Account Number</th></tr>
		</thead>
	<tbody>	
		<?php
		$result = $dbConnection->query("select User.userUsername,Customer.customerDOB,Customer.customerAddress,Customer.customerEmail,Account.accountType,Account.accountBalance,Account.accountNumber from User,Customer,Account where User.userUsername=Customer.customerUsername and User.userApproved=1 and Account.accountOwner= Customer.customerID") or die(mysql_error());

		while($row = mysqli_fetch_row($result)){
		echo '<tr>';
		echo '<td style="width:15%" >' . $row[0]. '</td>';
		echo '<td style="width:15%" >' . $row[1]. '</td>';
		echo '<td style="width:15%" >' . $row[2]. '</td>';
		echo '<td style="width:15%" >' . $row[3]. '</td>';
		echo '<td style="width:15%" >' . $row[4]. '</td>';
		echo '<td style="width:15%" >' . $row[5]. '</td>';
		}
		?>
	
	</tbody>	

                            <tfoot>
                                <tr>
                                    <td colspan="3">
                                        <span>Count : 20; Page 1 of 2</span>
                                    </td>
                                    <td colspan="4">
                                        <div class="marginPagingHeight30">
                                            <ul class="pagination pagination-sm marginPaging">
                                                <li class="active">
                                                    <a href="javascript:void(0);">1</a>
                                                </li>
                                                <li>
                                                    <a href="#">2</a>
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
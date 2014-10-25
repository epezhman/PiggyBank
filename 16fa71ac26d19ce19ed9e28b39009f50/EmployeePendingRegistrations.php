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
        PB - Employee
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
                    <a class="navbar-brand" href="#"><img src="../images/logo.png" alt="" class="logoStyle" /> Piggy Bank GmbH</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
              
			<li class="visible-xs"><a href="EmployeePendingRegistrations.html">Pending Registrations</a></li>
                        <li class="visible-xs"><a href="EmployeeCustomerMamangers.html">Manage Customers</a></li>
                        <li class="visible-xs active"><a href="EmployeePendingTransfers.html">Pending Transfers</a></li>
                        <li class="visible-xs"><a href="EmployeeTransfers.html">All Transfers</a></li>

                        <li><a href="#">Profile</a></li>
                        <li><a href="#">Help</a></li>
                        <li><a href="#">Log Out</a></li>

                    </ul>
                    
                </div>
            </div>
        </div>

	<div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">                      
                        <li class="active"><a href="EmployeePendingRegistrations.html">Pending Registrations</a></li>
			<li><a href="EmployeeCustomerMamangers.html">Manage Customers</a></li>
			<li><a href="EmployeePendingTransfers.html">Pending Transfers</a></li>                       
			<li><a href="EmployeeTransfers.html">All Transfers</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <!-- Beggining of body, above is Layout -->

                    <h1 class="page-header">Pending Registrations</h1>

                    <fieldset>
			<br></br>	
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-1" for="">Costumer</label>
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
    if (strpos(getenv("HTTP_REFERER"), "/PiggyBank/") === false)
        header("Location: ../error.php?id=404");

        $dbHost= "localhost";
        $dbUser= "piggy";
        $dbPassword= "8aa259f4c7";
        $dbName= "piggybank";

        global $dbConnection;
        $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
        if(mysqli_connect_errno()){
            header("Location: ../error.php");
        }

?>

		<div class="table-responsive">
		<table class="table table-striped table-hover ">
		<thead>
			<tr><th>#</th><th>Costumer ID</th><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr>
		</thead>
		
		<tbody>	
		<?php
		
		mysql_select_db("piggybank") or die(mysql_error());
		$result = mysql_query("select Customer.customerID,Customer.customerName,Customer.Email from User,Customer where User.customerUsername=Customer.customerUsername and User.userApproved=0") or die(mysql_error());
		while($row = mysql_fetch_array($result)){
		echo '<tr>';
		echo '<td>' . '#' . '</td>';
		echo '<td>' . $row['Customer.customerID']. '</td>';
		echo '<td>' . $row['Customer.customerName']. '</td>';
		echo '<td>' . $row['Customer.Email']. '</td>';
		}
		?>	
		</tbody>

		<tbody></tbody>

   			
		</table>
		</div>
			

</body>
</html>

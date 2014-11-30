<html>
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
    <title>
        PiggyBank - Pending Employee Requests 
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
			<li class="visible-xs"><a href="approveEmployees.php">Pending Employee </a></li>

                        <li><a href="#">Help</a></li>-->
                        <li><a href="logout.php">Log Out</a></li>

                    </ul>
                    
                </div>
            </div>
        </div>
<?php
	ob_start();
	require "accesscontrol.php";
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
	<div class="container-fluid">
            <div class="row">
                <div class="col-sm-3 col-md-2 sidebar">
                    <ul class="nav nav-sidebar">                      
                        <li class="active"><a href="approveEmployees.php">Pending Registrations</a></li>
                    </ul>
                </div>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

                    <!-- Beggining of body, above is Layout -->

                    <h1 class="page-header">Pending Employee Registrations</h1>

                    <fieldset>
			<br></br>	<!--
                        <form class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="control-label col-sm-1" for="">Customer</label>
                                <div class="col-sm-10">
                                    <input type="text" name="Sender" />
				    <input type="submit" value="Filter" id="submit" class="btn btn-default" style="margin-left:5%;" />
                                </div>
                            </div>
                    
                        </form>-->
                    </fieldset>
		    <br></br>	

		<div class="table-responsive">
		<table class="table table-striped table-hover ">
		<thead>
			<tr><th>Name</th><th>Date of Birth</th><th>Address</th><th>Email</th><th>Action</th></tr>
		</thead>
		
		<tbody>	
<?php
function getRandomString($length = 8){
    $alphabet = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ._";
    $validCharNumber = strlen($alphabet);
    $result = "";
    for ($i = 0; $i < $length; $i++) {
        $index = mt_rand(0, $validCharNumber - 1);
        $result .= $alphabet[$index];
    }
    return $result;
}

function sendEmail($eAddress, $eSubject, $eMessage){
    // Send the email message via the sendmail MTA
//    mail($eAddress, $eSubject, $eMessage, "From:noreply@piggybank.de");
    try{
        // Pear Mail Library
        require_once "Mail.php";

         $from = "noreply@piggybank.de";
         $to = $eAddress;
         $subject = $eSubject;
         $body = $eMessage;

         $headers = array(
             'From' => $from,
             'To' => $to,
             'Subject' => $subject
         );

        $smtp = Mail::factory('smtp', array(
            'host' => 'ssl://smtp.gmail.com',
            'port' => '465',
            'auth' => true,
            'username' => 'piggybankgmbh@gmail.com',
            'password' => 'optimus_159_prime'
        ));

        $mail = $smtp->send($to, $headers, $body);

        if (PEAR::isError($mail)) 
            echo "<script>alert(\"Error Encountered: " . $mail->getMessage() . "\");</script>" ;
        else 
            echo "<script>alert(\"Successful Operation. Email sent.\");</script>";
           
    }catch(Exception $e){
        header("Location: ../error.php?id=404");
        exit();
    }
}
	// Code Starts here
	require_once("dbconnect.php");
	if(mysqli_connect_errno()){
		header("Location: ../error.php");
		exit();
	}
	
	if(isset($_POST['remove'])){
		$var = $_POST['remove'];
			$employeeStmt = $dbConnection->prepare("SELECT employeeID, employeeName, employeeEmail FROM Employee WHERE employeeUsername LIKE (?)");
			$employeeStmt->bind_param("s", $var);
			$employeeStmt->execute();
			$employeeStmt->store_result();
			if($employeeStmt->num_rows > 0){
				$result = $employeeStmt->bind_result($eID, $eName, $eEmail);
				while($employeeStmt->fetch()){
					$employeeID = $eID;
					$employeeName = $eName;
					$employeeEmail = $eEmail;
				}
			$deleteStmt = $dbConnection->prepare("DELETE FROM User WHERE userUsername=?") or die(mysql_error());
			$deleteStmt->bind_param("s", $var);
			$deleteStmt->execute();
			if($deleteStmt->affected_rows > 0){
				// Disable tokens?!
				$emailMessage = "Dear Staff Member,\n\nWe regret to inform you that your PiggyBank online banking account has been suspended.\n\nSincerely,\nYour PiggyBank GmbH";
				sendEmail($customerEmail, "PiggyBank Online Banking Account Suspended", $emailMessage);
			}
		}
	}

	if(isset($_POST['approve'])){
		$var = $_POST['approve'];
			// This is insecure (SQL Injection hazard). Someone might set this "approve" variable to a SQL query string
			$dbConnection->query("UPDATE User SET userApproved=1 WHERE User.userUsername='$var'")or die(mysql_error());
			// Generate tokens and email customer
			// 1- Retrieve customer details based on username
			$employeeStmt = $dbConnection->prepare("SELECT employeeID, employeeName, employeeEmail FROM Employee WHERE employeeUsername LIKE (?)");
			$employeeStmt->bind_param("s", $var);
			$employeeStmt->execute();
			$employeeStmt->store_result();
			if($employeeStmt->num_rows > 0){
				$result = $employeeStmt->bind_result($eID, $eName, $eEmail);
				while($employeeStmt->fetch()){
					$employeeID = $eID;
					$employeeName = $eName;
					$employeeEmail = $eEmail;
				}
			$eMessage = "Dear Staff,\n\nThank you for choosing PiggyBank GmbH.\n\nYour online banking account is now activated.\n\nWelcome Aboard.\n\nSincerely,\nYour PiggyBank GmbH";
			// Send notification email
			sendEmail($employeeEmail, "Welcome Aboard PiggyBank GmbH", $eMessage);
		}
	}
	// Populate the table of pending requests	           
	$result = $dbConnection->query("SELECT employeeUsername, employeeDOB, employeeAddress,employeeEmail from User,Employee where User.userUsername=Employee.employeeUsername and User.userApproved=0") or die(mysql_error());
	while($row = mysqli_fetch_row($result)){
	echo '<tr>';
	echo '<td style="width:25%" >' . $row[0]. '</td>';
	echo '<td style="width:20%" >' . $row[1]. '</td>';
	echo '<td style="width:25%" >' . $row[2]. '</td>';
	//echo '<td style="width:18%" >' . $row[3]. '</td>';
	echo '<td style="width:20%" >' . $row[3]. '</td>';
	echo '<td>';
	echo '<form method="post" action="approveEmployees.php">';
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

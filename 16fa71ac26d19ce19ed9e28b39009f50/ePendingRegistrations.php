
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
	if($_SESSION["userrole"] != "employee"){
		header("Location: ../error.php?id=404");
		exit();
	}
	// Check for CSRF attempts
	if(count($_POST) > 0){
		// If there is something being POSTed to the page
		if(!isset($_POST["csrfToken"]) or ($_POST["csrfToken"] != $_SESSION["csrfToken"]))
		{
			header("Location: ../error.php?id=404");
			exit();
		}
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
			<br/><br/>
                    </fieldset>
		    <br/><br/>	

		<div class="table-responsive">
		<form method="post" action="ePendingRegistrations.php">
		<table class="table table-striped table-hover ">
		<thead>
			<tr><th>Name</th><th>Date of Birth</th><th>Address</th><th>Account Balance (€)</th><th>Action</th></tr>
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

function createPdf($eMessage,$password){
     try{
        //require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/fpdf.php");
        require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/pdfProtection.php");

       // $pdf = new FPDF();
        $pdf= new FPDF_Protection();
        
        $pdf->SetProtection(array('print'),$password);
        $pdf->AddPage();
        
        $pdf->SetFont('Arial','B',15);
                // Move to the right
                // Title
        $pdf->Cell(0,15,'Piggy Bank GmbH  --  Secret Tokens',1);

                // Line break
        $pdf->Ln(30);
        $pdf->Write(5,$eMessage);
        //$pdfdoc =  $pdf->Output("", "S"); 
        //$attachment = chunk_split(base64_encode($pdfdoc));
        
        $pdf->Output("../f8d890ce88bd1791b6eaddf06e58ceb5/tmp/doc.pdf","F");
        $content = "../f8d890ce88bd1791b6eaddf06e58ceb5/tmp/doc.pdf";
        
    }catch(Exception $e){
        header("Location: ../error.php?id=404");
        exit();
    }
      
      return $content;  
}



function sendEmail($eAddress, $eSubject,$eMessage,$eAttachments){

    
     try{

        // Pear Mail Library
        require_once "Mail.php";
        require_once  ('Mail/mime.php') ;
        
       
         $from = "noreply@piggybank.de";
         $to = $eAddress;
         $subject = $eSubject;
         $body = $eMessage;

         $headers = array(
             'From' => $from,
             'To' => $to,
             'Subject' => $subject
         );

       
        if ($eAttachments!=null){ 
                $crlf = "\n";
                $mime = new Mail_mime($crlf);
                $mime->setTXTBody($eMessage);
                $mime->addAttachment($eAttachments, 'application/pdf');
                $body = $mime->get();
                $headers = $mime->headers($headers);

        }
 
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

function generateTokens($custID){
    // Generates 100 unique tokens and returns them to the caller
    $customerTokens = array();
    $counter = 0;
    // There is a problem with using "require_once" here. For some reason, the function does not see the "$dbConnection"
    // .. variable unless it is redefined as below.
	$dbHost= "localhost";
	$dbUser= "piggy";
	$dbPassword= "8aa259f4c7";
	$dbName= "piggybank";
    $dbConnection = new mysqli($dbHost, $dbUser, $dbPassword, $dbName);
    try{
        while($counter < 100){
            $tempToken =  substr(sha1($custID.$counter+1 .microtime(true).getRandomString()), 0, 15);
            // Now check whether token is already in the Token's table
            $tokenAvailableStmt = $dbConnection->prepare("SELECT * FROM Token WHERE tokenID=? AND tokenUsed=0");
            $tokenAvailableStmt->bind_param("s", $tempToken);
            $tokenAvailableStmt->execute();
            $tokenAvailableStmt->store_result();
            while($tokenAvailableStmt->num_rows > 0){
                $tempToken =  substr(sha1($custID.$counter+1 .microtime(true).getRandomString()), 0, 15);
                $tokenAvailableStmt = $dbConnection->prepare("SELECT * FROM Token WHERE tokenID=? AND tokenUsed=0");
                $tokenAvailableStmt->bind_param("s", $tempToken);
                $tokenAvailableStmt->execute();
                $tokenAvailableStmt->store_results();
            }
            // Add token to the user tokens
            array_push($customerTokens, $tempToken);
            $addTokenStmt = $dbConnection->prepare("INSERT INTO Token VALUES (?,?,0)");
            $addTokenStmt->bind_param("ss", $tempToken, $custID);
            $addTokenStmt->execute();
            $counter += 1;
        }
    }catch(exception $e){echo $e;}
    return $customerTokens;
}
	// Code Starts here
	require_once("../f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
	if(mysqli_connect_errno()){
		header("Location: ../error.php");
		exit();
	}
	
	if(isset($_POST['remove'])){
		$var = $_POST['remove']; 
		
		// Retrieve customer details
		$customerStmt = $dbConnection->prepare("SELECT customerID, customerName, customerEmail FROM Customer WHERE customerUsername LIKE (?)");
		$customerStmt->bind_param("s", $var);
		$customerStmt->execute();
		$customerStmt->store_result();
		if($customerStmt->num_rows > 0){
			$result = $customerStmt->bind_result($cID, $cName, $cEmail);
			while($customerStmt->fetch()){
				$customerID = $cID;
				$customerName = $cName;
				$customerEmail = $cEmail;
			}
			
			$dbConnection->query("delete from User where User.userUsername='$var'")or die(mysql_error());
			
				// Disable tokens?!
				$emailMessage = "Dear Customer,\r\n\r\nWe regret to inform you that your PiggyBank online banking account has been suspended.\n\nSincerely,\nYour PiggyBank GmbH";
				sendEmail($cEmail, "PiggyBank Online Banking Account Suspended", $emailMessage);
			
		}
	}

	if(isset($_POST['approve'])){
		$var = $_POST['approve']; 
                $balance_value = $_POST['balance']; 
			// This is insecure (SQL Injection hazard). Someone might set this "approve" variable to a SQL query string
			$dbConnection->query("UPDATE User SET userApproved=1 WHERE User.userUsername='$var'")or die(mysql_error());

			// Generate tokens and email customer
			// 1- Retrieve customer details based on username
			$customerStmt = $dbConnection->prepare("SELECT customerID, customerName, customerEmail, customerTransferSecurityMethod, customerPIN FROM Customer WHERE customerUsername LIKE (?)");
			$customerStmt->bind_param("s", $var);
			$customerStmt->execute();
			$customerStmt->store_result();
			if($customerStmt->num_rows > 0){
				$result = $customerStmt->bind_result($cID, $cName, $cEmail, $cMethod, $cPIN);
				while($customerStmt->fetch()){
					$customerID = $cID;
					$customerName = $cName;
					$customerEmail = $cEmail;
					$customerMethod = $cMethod;
					$customerPIN = $cPIN;
				}
			 }

$dbConnection->query("UPDATE Account SET accountBalance='$balance_value' WHERE accountOwner='$customerID'")or die(mysql_error());
if($customerMethod == 1)
{
			// Generate TAN's
			$customerTokens = generateTokens($customerID);
			$eMessage = "Dear Customer,\n\nThank you for choosing PiggyBank GmbH.\n\nYour online banking account is now activated.\n\nAttached are your generated TAN's that you can use to transfer money via our online banking system.\n\n Please use your account number and date of birth [YYYY-MM-DD] to unlock the attached file.\n\nSincerely,\nYour PiggyBank GmbH";
			// Retrieve user secret answer
			$passphraseStmt = $dbConnection->prepare("SELECT customerDOB, accountNumber FROM Customer,Account WHERE Customer.customerID = Account.accountOwner AND Customer.customerID LIKE (?)");
			$passphraseStmt->bind_param("s", $customerID);
			$passphraseStmt->execute();
			$passphraseStmt->bind_result($cDOB, $aNumber);
			$passphraseStmt->store_result();
			if($passphraseStmt->num_rows > 0){
				while($passphraseStmt->fetch()){
				   $customerDOB = $cDOB;
				   $accountNumber = $aNumber;
				}
			}
            $passphrase = $accountNumber.$customerDOB;
			// Build email message
			foreach($customerTokens as $token)
                                $bodyText = $eMessage;
				$eMessage = $eMessage.$token."\r\n";
				$pdfFile=createPdf($eMessage,$passphrase);

                        sendEmail($customerEmail, "Welcome to PiggyBank GmbH", $bodyText,$pdfFile);
			// Send notification email
			//sendEmail($customerEmail, "Welcome to PiggyBank GmbH", $eMessage);
}
else if($customerMethod == 2)
{
	$realPin = openssl_decrypt($customerPIN, "AES-128-CBC", "SomeVeryCrappyPassword?!!!WithNum2014");
	$eMessage = "Dear Customer,\r\n\r\nThank you for choosing PiggyBank GmbH.\r\n\r\nYour online banking account is now activated.\r\n\r\nFollowing is your PIN for generating one time password (OTP) to transfer money via our online banking system, Please keep it safe:\r\n\r\n $realPin \r\n\r\n To Download your SCS please sign in to your account and go to \"My Transfers and Accounts\", you can find download link there. \r\n\r\n ";
	
	// Send notification email
	sendEmail($customerEmail, "Welcome to PiggyBank GmbH", $eMessage);
	
}
		}
	// Populate the table of pending requests	           
	$result = $dbConnection->query("select User.userUsername,Customer.customerDOB,Customer.customerAddress,Account.accountType,Account.accountBalance,Customer.customerEmail,Customer.customerID  from User,Customer,Account where User.userUsername=Customer.customerUsername and User.userApproved=0 and Account.accountOwner= Customer.customerID") or die(mysql_error());
	while($row = mysqli_fetch_row($result)){
	echo '<tr>';
	echo '<td style="width:23%" >' . $row[0]. '</td>';
	echo '<td style="width:18%" >' . $row[1]. '</td>';
	echo '<td style="width:23%" >' . $row[2]. '</td>';
	echo '<td  style="width:23%"><input type="text"  name="balance"/></td>';
	echo '<td>';
	echo '<button  type="submit" name="remove"  class="btn btn-default btn-xs" data-toggle="tooltip" title="Remove" value=' .$row[0]. '>
				  <span class="glyphicon glyphicon-remove"></span>
				  </button>

				  <button type="submit"  name="approve"  class="btn btn-primary btn-xs" data-toggle="tooltip" title="Approve" value=' .$row[0]. '>
				  <span class="glyphicon glyphicon-ok"></span>
				   </button>';
        echo '<input id="csrfToken" type="hidden" name="csrfToken" value="'.$_SESSION["csrfToken"].'">';
	echo '</td>';
	}
?>	
		</tbody>
		</table>
		</form>
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

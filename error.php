<!DOCTYPE html>


<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Piggy Bank GmbH">
<meta name="author" content="Alei , Sara , ePezhman">
<link rel="icon" href="/PiggyBank/images/piggyFav.ico">

<style id="antiClickjack">
body {
	display: none !important;
}
</style>
<script src="./js/secure.js"></script>

<!-- To be Changed!! -->
<title>PiggyBank GmbH - Oink!!</title>

<!-- Bootstrap core CSS -->
<link href="/PiggyBank/css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="/PiggyBank/css/framework.css" rel="stylesheet">

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
					<?php
						session_start();
						if($_SESSION["userrole"] == "admin")
							echo "<a class=\"navbar-brand\" href=\"/PiggyBank/16fa71ac26d19ce19ed9e28b39009f50/eCustomerManagers.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> Piggy Bank GmbH</a>";
						else if($_SESSION["userrole"] == "customer")
							echo "<a class=\"navbar-brand\" href=\"/PiggyBank/5e8cb842691cc1b8c7598527b5f2277f/CustomerMyTransfers.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> Piggy Bank GmbH</a>";
						else
							echo "<a class=\"navbar-brand\" href=\"/PiggyBank/signin.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> Piggy Bank GmbH</a>";
						
						?>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<!--                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Language/Sprache <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="langs">
                                <li id="enLang" class="active"><a href="javascript:void(0);" class="EN">English</a></li>
                                <li id="deLang"><a href="javascript:void(0);" class="DE">Deutsch</a></li>
                            </ul>
                        </li> -->
                        <?php
                            if($_SESSION["loginstatus"] == "authenticated"){
                                echo "<li><a href=\"/PiggyBank/f8d890ce88bd1791b6eaddf06e58ceb5/logout.php\">Log out</a></li>";
                            }
                            else
                                echo "<li><a href=\"/PiggyBank/signin.php\">Sign in</a></li>
                        <li><a href=\"/PiggyBank/signup.php\">Sign up</a></li><li><a href=\"joinus.php\">Join us</a></li>";
                        ?>
                    </ul>


				</div>
			</div>
		</div>

		<div class="container-fluid">
			<div class="row">
				<div class="col-md-12">
					<?php
					echo "<br/><br/><br/>";
					echo "<div class=\"form-error\" >";
					echo "<table width=\"800\"><tr><td align=\"center\">";
					echo "<img src=\"/PiggyBank/images/pigerror.png\" alt=\"pigerror.png\" width=\"100\" height=\"100\"/>";
					echo "<h2><b>Oink!! Something went wrong.</h2><br/><br/>";
					echo "</td></tr>";
					$errorID = $_GET["id"];
					if($errorID == "404"){
						echo "<tr><td align=\"center\">";
						echo "<h4><b>The page you are looking for does not exist.</b></h4>";
						echo "</td></tr>";
					}
					else if($errorID == "403"){
						echo "<tr><td align=\"center\">";
						echo "<h4><b>You are not allowed here, yet. Please <a href=\"signin.php\">sign in</a> first.</b></h4>";
						echo "</td></tr>";
					}
					else if($errorID == "440"){
						echo "<tr><td align=\"center\">";
						echo "<h4><b>Your session has already expired. Please <a href=\"signin.php\">sign in</a> again.</b></h4>";
						echo "</td></tr>";
					}
					else if($errorID == "available"){
						echo "<tr><td align=\"center\">";
						echo "<h4><b>The chosen username is already taken. <a href=\"signup.php\">Try</a> again?</b><h4>";
					}
                                        else if($errorID == "captcha"){
                                                echo "<tr><td align=\"center\">";
                                                echo "<h4><b>The CAPTCHA code you entered was invalid. <a href=\"signin.php\">Try</a> again?</b></h4>";
                                        }
					echo "</table>";
					echo "</div>";
					echo "<br/>";
					?>
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
	<script src="/PiggyBank/js/jquery-1.11.1.min.js"></script>
	<script src="/PiggyBank/js/bootstrap.min.js"></script>

</body>
</html>

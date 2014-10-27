<!DOCTYPE html>


<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="Piggy Bank GmbH">
<meta name="author" content="Alei , Sara , ePezhman">
<link rel="icon" href="/PiggyBank/images/piggyFav.ico">

<!-- To be Changed!! -->
<title>PiggyBank GmbH - Oink!!</title>

<!-- Bootstrap core CSS -->
<link href="./css/bootstrap.min.css" rel="stylesheet">

<!-- our CSS -->
<link href="./css/framework.css" rel="stylesheet">

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
					<a class="navbar-brand" href="#"><img src="./images/logo.png"
						alt="" class="logoStyle" /> Piggy Bank GmbH</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav navbar-right">
						<?php
						session_start();
						if($_SESSION["loginstatus"] == "authenticated"){
							echo "<li><a href=\"f8d890ce88bd1791b6eaddf06e58ceb5/logout.php\">Log out</a></li>";
						}
						else
							echo "<li><a href=\"signin.php\">Sign in</a></li>
							<li><a href=\"signup.php\">Sign up</a></li>";
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
					echo "<img src=\"./images/pigerror.png\" alt=\"pigerror.png\" width=\"100\" height=\"100\"/>";
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
	<script src="./js/jquery-1.11.1.min.js"></script>
	<script src="./js/bootstrap.min.js"></script>

</body>
</html>

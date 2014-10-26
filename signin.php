<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="./images/piggyFav.ico">

    <title>
        PiggyBank GmbH- Sign in
    </title>

    <!-- Bootstrap core CSS -->
    <link href="./css/bootstrap.min.css" rel="stylesheet">

    <!-- our CSS -->
    <link href="./css/framework.css" rel="stylesheet">
	<script src="./js/jquery-1.11.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/sha256.js"></script>
    <script type="text/javascript">
    var validated = new Array();
    var flag = true;
    function prepareForm(){
        // This function is meant to prepare the registration form
        $("#usernamespan").hide();
        $("#passwordspan").hide();
        if(document.referrer != "")
            validated["username"] = true;
        else
             validated["username"] = false;
        validated["password"] = false;
        
    }
    function validateElement(e, type){
        // This function is used to validate individual
        if(type == "username")
	    if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Username is required");
                $('#'+e.id+'span').fadeIn('slow');
                validated["username"] = false;
			}
			else{
				$('#'+e.id+'span').fadeOut('slow');
				validated["username"] = true;
			}
                
       else if(type == "password")
           if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Password is required");
                $('#'+e.id+'span').fadeIn('slow');
                validated["password"] = false;
	   }
           else if(e.value.length < 8){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Password too short");
                $('#'+e.id+'span').fadeIn('slow');
                validated["password"] = false;
           }
	   else{
		$('#'+e.id+'span').fadeOut('slow');
		validated["password"] = true;
	   }
			
			validateForm();
	}
	function handlePassword(f){
		f.hashedpassword.value = SHA256(f.password.value);
		f.password.value = "";
	}
    function validateForm(){
        // As the name implies, this function is used to validate form
        if (validated["username"] && validated["password"]){ 
            $('#submit').prop("disabled", false);
            if(flag){
                $('#submit').animate({opacity: "0.5"}, 300);
                $('#submit').animate({opacity: "1.0"}, 300);
                $('#submit').animate({opacity: "0.5"}, 300);
                $('#submit').animate({opacity: "1.0"}, 300);
                flag = false;
            }
        }
        else{
            $('#submit').animate({opacity:"0.5"}, 300);
            $('#submit').prop("disabled", true);
            flag = true;
        }
    }
	</script>
</head>

<body onload="prepareForm()">
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
                    <a class="navbar-brand" href="signin.php"><img src="./images/logo.png" alt="" class="logoStyle" /> Piggy Bank GmbH</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
<!--                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Language/Sprache <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="langs">
                                <li id="enLang" class="active"><a href="javascript:void(0);" class="EN">English</a></li>
                                <li id="deLang"><a href="javascript:void(0);" class="DE">Deutsch</a></li>
                            </ul>
                        </li>
-->
                        <li><a href="signin.php">Sign in</a></li>
                        <li><a href="signup.php">Sign up</a></li>
                    </ul>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-8 hidden-xs">
                    <img src="./images/piggy-banks.jpg" alt="" class="img-responsive" />
                </div>
                <div class="col-sm-4">
                    <div style="padding-top:40px;">
                        <form class="form-signup" role="form" action="f8d890ce88bd1791b6eaddf06e58ceb5/auth.php" method="POST" style="width: 550px" onsubmit="handlePassword(this)">
                            <h2>Sign in</h2>
                            <br />
							<table style="width:500px; table-layout:fixed;">
                            <col width="100"><col width="250"><col width="150" align="center">
						    <?php
                                echo "<tr><td style=\"padding: 10px 0px;\"><label for=\"username\">Username</label></td>";
                                if(isset($_GET["failure"]))
								    echo "<td><input class=\"form-control\" id=\"username\" name=\"username\" type=\"text\" style=\"width: 200px\"  onblur=\"validateElement(this, 'username')\" value=".$_GET["failure"]."></td>";
								else
								    echo "<td><input class=\"form-control\" id=\"username\" name=\"username\" type=\"text\" style=\"width: 200px\"  onblur=\"validateElement(this, 'username')\"></td>";
								echo "<td><span id=\"usernamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>
									</tr><tr>
										<td style=\"padding: 10px 0px;\"><label for=\"password\">Password</label></td>
										<td><input class=\"form-control\" id=\"password\" name=\"password\" type=\"password\" style=\"width: 200px\" onblur=\"validateElement(this, 'password')\" onkeyup=\"validateElement(this, 'password')\"></td>
										<td><span id=\"passwordspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>
									</tr><tr>
										<td style=\"padding: 10px 0px;\"></td>
										<td><input class=\"form-control\" id=\"hashedpassword\" name=\"hashedpassword\" type=\"hidden\" value=\"\"></td>
										<td></td>
									</tr>
									<tr>";
								if(isset($_GET["failure"]))
									echo "<tr><td colspan=\"3\" align=\"center\"><span id=\"errormsgspan\" name=\"errormsgspan\" style=\"border: 2px solid; border-radius: 25px; padding: 10px 10px; background-color: #FFCCCC; color: #800000; border-color:#800000;\">Invalid username and/or password.</span></td></tr>
								<tr><td colspan=\"3\" align=\"right\"><input type=\"submit\" value=\"Sign in\" id=\"submit\" class=\"btn btn-primary\" disabled/></td></tr>";
								else
									echo "<td align=\"right\" colspan=\"3\"><input type=\"submit\" value=\"Sign in\" id=\"submit\" class=\"btn btn-primary\" disabled/></td>";
								echo "</tr><tr><td colspan=\"3\"><h5 >New User? <a href=\"signup.php\">Sign up here!</a></h5></td></tr>";
                            ?>
                            </table>
                                </div>
                            </div>
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
    <script src="./js/jquery-1.11.1.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>

</body>
</html>

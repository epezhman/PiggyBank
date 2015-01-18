<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="./images/piggyFav.ico">
<style id="antiClickjack">
body {
	display: none !important;
}
</style>
<script src="./js/secure.js"></script>
    
    <!-- To be Changed!! -->
    <title>
        PiggyBank GmbH - Sign up for Online Banking
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
        $("#fullnamespan").hide();
        $("#addressspan").hide();
        $("#dobspan").hide();
        $("#usernamespan").hide();
        $("#passwordspan").hide();
        $("#confirmspan").hide();
        $("#emailspan").hide();
        $("#secanswerspan").hide();
        validated["fullname"] = false;
        validated["address"] = false;
        validated["dob"] = false;
        validated["email"] = false;
        validated["username"] = false;
        validated["confirm"] = false;
        validated["password"] = false;
        validated["secanswer"] = false;
        
    }
    
   function validateElement(e, type){
        // This function is used to validate individual
        if(type == "name"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Fullname is required");
                validated["fullname"] = false;
            }
            else
                if(!e.value.match("^[a-zA-Z ]+$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid fullname");
                    validated["fullname"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["fullname"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
        }
        else if(type == "address"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Address is required");
                validated["address"] = false;
            }
            else
                if(!e.value.match("^[a-zA-Z0-9,'-. ]+$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid Address");
                    validated["address"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["address"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
        }
        else if(type == "secanswer"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Security Answer is required");
                validated["secanswer"] = false;
            }
            else
                if(!e.value.match("^[a-zA-Z0-9,'-. ]+$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid Answer");
                    validated["secanswer"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["secanswer"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
        }
        else if (type=="date"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Date of birth is required");
                validated["dob"] = false;
            }
            else
                if(!e.value.match("^(0[0-9]|[1-2][0-9]|3[0-1])\/(0[0-9]|1[0-2])\/(19[0-9][0-9]|20[0-1][0-4])$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid date of birth");
                    validated["dob"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["dob"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
        }
        else if (type=="email"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Email address is required");
                validated["email"] = false;
            }
            else
                if(!e.value.match("^[a-zA-Z0-9_.]+@[a-zA-Z0-9]+\.[a-zA-Z]{2,3}(\.[a-zA-Z]{2})?$")){
                    $('#'+e.id+'span').css("background","#CC0000");
                    $('#'+e.id+'span').html("Invalid email address");
                    validated["email"] = false;
                }
                else{
                    $('#'+e.id+'span').css("background","#00CC00");
                    $('#'+e.id+'span').html("Check");
                    validated["email"] = true;
                }
            $('#'+e.id+'span').fadeIn('slow');
        }
      else if(type=="username"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("A username is required");
                validated["username"] = false;
            }
            else{ // Non-empty username field
                   if(e.value.length<8){
                        $('#'+e.id+'span').css("background","#CC0000");
                        $('#'+e.id+'span').html("Can't be less than 8 characters");
                        validated["username"] = false;
                    } 
                    else if(e.value.length >= 8 && e.value.match("^[a-zA-Z0-9_.]+$")){
                        $('#'+e.id+'span').css("background","#00CC00");
                        $('#'+e.id+'span').html("Strong Password");
                        validated["username"] = true;
                    }
                    else{
                       $('#'+e.id+'span').css("background","#CC0000");
                       $('#'+e.id+'span').html("Invalid Username. Only Numbers, letters, '.', '_' are allowed.");
                       validated["Username"] = false;
                   }
            } 
                  $('#'+e.id+'span').fadeIn('slow'); 
      }
    else if(type=="password"){
            if(e.value == ""){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("A password is required");
                validated["password"] = false;
            }
            else{ // Non-empty password field
                   if(e.value.length<10){
                        $('#'+e.id+'span').css("background","#CC0000");
                        $('#'+e.id+'span').html("Can't be less than 10 characters");
                        validated["password"] = false;
                    } 
                    else if(e.value.length >= 10 && e.value.match("^[a-zA-Z0-9]+$")){
                        $('#'+e.id+'span').css("background","#CC0000");
                        $('#'+e.id+'span').html("Weak Password");
                        validated["password"] = false;
                    }
                    else if(e.value.length >= 10 && e.value.match("^[a-zA-Z0-9_.@!?]+$")){
                        $('#'+e.id+'span').css("background","#00CC00");
                        $('#'+e.id+'span').html("Strong Password");
                        validated["password"] = true;
                    }
                    else{
                       $('#'+e.id+'span').css("background","#CC0000");
                       $('#'+e.id+'span').html("Invalid Password");
                       validated["password"] = false;
                   }
            }
            // Check if confirm password has already been set and update its status
            if(document.getElementById("confirm").value != "" && document.getElementById("confirm").value != e.value && validated["password"]){
                $('#confirmspan').css("background","#CC0000");
                $('#confirmspan').html("Passwords do not match");
                validated["confirm"] = false;
            }     
            $('#'+e.id+'span').fadeIn('slow');
    }
    else if(type=="confirm"){
        if(e.value == ""){
            $('#'+e.id+'span').css("background","#CC0000");
            $('#'+e.id+'span').html("You need to confirm the password");
            validated["confirm"] = false;
        }
        else{
            if(e.value != document.getElementById("password").value){
                $('#'+e.id+'span').css("background","#CC0000");
                $('#'+e.id+'span').html("Passwords do not match");
                validated["confirm"] = false;
            }
            else{
                $('#'+e.id+'span').css("background","#00CC00");
                $('#'+e.id+'span').html("Check");
                validated["confirm"] = true;
            }
        }
        $('#'+e.id+'span').fadeIn('slow');
    }
    validateForm();
}
    function validateForm(){
        // As the name implies, this function is used to validate form
        if (validated["fullname"] && validated["address"] && validated["dob"] &&  validated["email"] && validated["username"] && validated["password"] && validated["confirm"] && validated["secanswer"]){ 
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
    function handleSecrets(f){
        f.hashedPassword.value = SHA256(f.password.value);
        f.password.value = "";
        f.hashedConfirm.value = SHA256(f.confirm.value);
	f.confirm.value = "";
        f.hashedAnswer.value = SHA256(f.secanswer.value);
        f.secanswer.value = "";
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
                    <?php
                        session_start();
                        if($_SESSION["userrole"] == "admin")
                            echo "<a class=\"navbar-brand\" href=\"16fa71ac26d19ce19ed9e28b39009f50/eCustomerManagers.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> PiggyBank GmbH</a>";
                        else if($_SESSION["userrole"] == "customer")
                            echo "<a class=\"navbar-brand\" href=\"16fa71ac26d19ce19ed9e28b39009f50/eCustomerManagers.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> PiggyBank GmbH</a>";
                        else
                            echo "<a class=\"navbar-brand\" href=\"signin.php\"><img src=\"/PiggyBank/images/logo.png\" alt=\"\" class=\"logoStyle\" /> PiggyBank GmbH</a>";
                    ?>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <?php
                            if($_SESSION["loginstatus"] == "authenticated"){
                                echo "<li><a href=\"f8d890ce88bd1791b6eaddf06e58ceb5/logout.php\">Log out</a></li>";
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
        <!--    <div class="row">
                <div class="col-sm-8 hidden-xs ">
                    <img src="./images/piggy-banks.jpg" alt="" class="img-responsive" />
                </div>
        -->
                <div class="col-sm-12">
                    <div style="padding-top:40px;">
                        <form class="form-signup" role="form" action="f8d890ce88bd1791b6eaddf06e58ceb5/register.php" method="POST" onsubmit="handleSecrets(this)">
                            <table width=700px>
                            <tr><td>
                            <h2>Thank you for choosing PiggyBank GmbH</h2>
                            <noscript>Javascript is switched off. Some features will not work properly. Please enable Javascript.</noscript>
                            </td>
                            <td>
                            <img src="./images/piggybankicon.jpeg" alt="piggybankicon.jpeg" style="width:120px; heigh:120px;"/>
                            </td>
                            </tr>
                            </table>
                            <table style="width:700px; table-layout:fixed;">
                            <col width="150"><col width="350"><col width="200">
			<?php
                            session_start();
                            require_once("./f8d890ce88bd1791b6eaddf06e58ceb5/dbconnect.php");
                            global $dbConnection;
			    try{
                            echo "<tr>
                            <td style=\"padding: 10px 0px;\"><label for=\"title\">Title</label></td>
                            <td><select id=\"title\" name=\"title\" class=\"form-control\" style=\"width: 70px;\">
                                <option value=\"no\" selected></option><option value=\"mr\">Mr.</option>
                                <option value=\"mrs\">Mrs.</option><option value=\"dr\">Dr.</value>
                            </select></td>
                            </tr>
                            <tr>";
                            
                            if(isset($_SESSION["invFullname"]))
                                echo "<td style=\"padding: 10px 0px;\"><label for=\"fullname\">Fullname</label></td>
                                    <td><input class=\"form-control\" style=\"width: 300px\" id=\"fullname\" name=\"fullname\" type=\"text\" onload=\"validateElement(this, 'name')\" onblur=\"validateElement(this, 'name')\" placeholder=\"John Doe\" value=\"".htmlspecialchars($_SESSION["invFullname"])."\"></td>
                                   <td><span id=\"fullnamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            else
							   echo "<td style=\"padding: 10px 0px;\"><label for=\"fullname\">Fullname</label></td>
                                    <td><input class=\"form-control\" style=\"width: 300px\" id=\"fullname\" name=\"fullname\" type=\"text\" onblur=\"validateElement(this, 'name')\" placeholder=\"John Doe\"></td>
                                   <td><span id=\"fullnamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invAddress"]))
                                echo "<td style=\"padding: 10px 0px;\"><label for=\"address\">Address</label></td>
                                    <td><input class=\"form-control\" style=\"width:300px\" id=\"address\" name=\"address\" type=\"text\" onload=\"validateElement(this, 'address')\" onblur=\"validateElement(this, 'address')\" placeholder=\"1 Main St.\" value=\"".htmlspecialchars($_SESSION["invAddress"])."\"></td>
                                    <td><span id=\"addressspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            else
							    echo "<td style=\"padding: 10px 0px;\"><label for=\"address\">Address</label></td>
                                    <td><input class=\"form-control\" style=\"width:300px\" id=\"address\" name=\"address\" type=\"text\" onblur=\"validateElement(this, 'address')\" placeholder=\"1 Main St.\"></td>
                                    <td><span id=\"addressspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invDOB"]))
                                    echo "<td style=\"padding: 10px 0px;\"><label for=\"DateOfBirth\">Date of birth</label></td>
                                    <td><input class=\"form-control\" style=\"width:110px\" id=\"dob\" name=\"dob\" placeholder=\"dd/mm/yyyy\" onload=\"validateElement(this, 'date')\" onblur=\"validateElement(this, 'date')\" value=\"".htmlspecialchars($_SESSION["invDOB"])."\"></td>
                                     </td>
                                    <td><span id=\"dobspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            else
                                echo "<td style=\"padding: 10px 0px;\"><label for=\"DateOfBirth\">Date of birth</label></td>
                                    <td><input class=\"form-control\" style=\"width:110px\" id=\"dob\" name=\"dob\" placeholder=\"dd/mm/yyyy\" onblur=\"validateElement(this, 'date')\">
                                     </td>
                                    <td><span id=\"dobspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invEmail"]))
                                    echo "<td style=\"padding: 10px 0px;\"><label for=\"email\">Email</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px\" id=\"email\" name=\"email\" type=\"text\" onload=\"validateElement(this, 'email')\" onblur=\"validateElement(this, 'email')\" placeholder=\"john.doe@piggybank.de\" value=\"".htmlspecialchars($_SESSION["invEmail"])."\"></td>
                                    </td>
                                    <td><span id=\"emailspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            else
								    echo "<td style=\"padding: 10px 0px;\"><label for=\"email\">Email</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px\" id=\"email\" name=\"email\" type=\"text\" onblur=\"validateElement(this, 'email')\" placeholder=\"john.doe@piggybank.de\">
                                    </td>
                                    <td><span id=\"emailspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invUsername"]))
                                    echo "<td style=\"padding: 10px 0px;\"><label for=\"username\">Username</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px\" id=\"username\" name=\"username\" type=\"text\" onload=\"validateElement(this, 'username')\" onblur=\"validateElement(this, 'username')\" placeholder=\"john.doe\" value=\"".htmlspecialchars($_SESSION["invUsername"])."\"></td>
                                    </td>
                                    <td><span id=\"usernamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
							else
							    echo "<td style=\"padding: 10px 0px;\"><label for=\"username\">Username</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px\" id=\"username\" name=\"username\" type=\"text\" onblur=\"validateElement(this, 'username')\" placeholder=\"john.doe\">
                                    </td>
                                    <td><span id=\"usernamespan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invPassword"]))
                                    echo "<td style=\"padding: 10px 0px;\"><label for=\"password\">Password</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px;\" id=\"password\" name=\"password\" type=\"password\" onload=\"validateElement(this, 'password')\" onblur=\"validateElement(this, 'password')\" placeholder=\"epiclysecret\" value=\"".htmlspecialchars($_SESSION["invPassword"])."\"></td>
                                    <td><span id=\"passwordspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
							else
							        echo "<td style=\"padding: 10px 0px;\"><label for=\"password\">Password</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px;\" id=\"password\" name=\"password\" type=\"password\" onload=\"validateElement(this, 'password')\" onblur=\"validateElement(this, 'password')\" placeholder=\"epiclysecret\"></td>
                                    <td><span id=\"passwordspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            if(isset($_SESSION["invConfirm"]))
                                    echo "<td style=\"padding: 10px 0px;\"><label for=\"confirm\">Confirm Password</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px;\" id=\"confirm\" name=\"confirm\" type=\"password\" onload=\"validateElement(this, 'confirm')\" onkeyup=\"validateElement(this, 'confirm')\" placeholder=\"epiclysecret\" value=\"".htmlspecialchars($_SESSION["invConfirm"])."\"></td>
                                    <td><span id=\"confirmspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
							else
							    echo "<td style=\"padding: 10px 0px;\"><label for=\"confirm\">Confirm Password</label></td>
                                    <td><input class=\"form-control\" style=\"width:250px;\" id=\"confirm\" name=\"confirm\" type=\"password\" onkeyup=\"validateElement(this, 'confirm')\" placeholder=\"epiclysecret\"></td>
                                    <td><span id=\"confirmspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr><tr>";
                            echo "<td style=\"padding: 10px 0px;\"><label for=\"secquestion\">Security Question</label></td>
                                    <td colspan=\"2\"><select class=\"form-control\" style=\"width:350px\" id=\"secquestion\" name=\"secquestion\" onblur=\"validateElement(this, 'secquestion')\">";
                            // Retrieve security questions and populate drop down list
                            $securityQuestionsQuery = $dbConnection->prepare("SELECT securityQuestionID, securityQuestionDesc FROM SecurityQuestion");
	                    $securityQuestionsQuery->execute();
	                    $securityQuestionsQuery->bind_result($secQID, $secQDesc);
	                    $securityQuestionsQuery->store_result();
                            if($securityQuestionsQuery->num_rows() > 0){
		                while($securityQuestionsQuery->fetch()){
                                    echo "<option value=\"".$secQID."\">".$secQDesc."</option>";
		                }
	                    } 
                           $securityQuestionsQuery->free_result();
	                   $securityQuestionsQuery->close();
                           echo "</select></td></tr>";
                           echo "<tr><td style=\"padding: 10px 0px;\"><label for=\"secanswer\">Answer</label></td>";
                            if(isset($_SESSION["invSecAnswer"]))
                                echo"<td><input class=\"form-control\" style=\"width:250px;\" id=\"secanswer\" name=\"secanswer\" type=\"text\" onload=\"validateElement(this, 'secanswer')\" onkeyup=\"validateElement(this, 'secanswer')\" placeholder=\"Porsche Carrera 911\" value=\"".htmlspecialchars($_SESSION["invSecAnswer"])."\"></td> <td><span id=\"secanswerspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            else
                                echo"<td><input class=\"form-control\" style=\"width:250px;\" id=\"secanswer\" name=\"secanswer\" type=\"text\" onkeyup=\"validateElement(this, 'secanswer')\" placeholder=\"Porsche Carrera 911\"></td> <td><span id=\"secanswerspan\" class=\"btn btn-primary\" style=\"background: #CC0000; border: #FFFFFF;\">default</span></td>";
                            echo "</tr>";
                            echo "<td style=\"padding: 10px 0px;\"><label for=\"confirm\">Transfer Security</label></td>
                            <td><input type=\"radio\" name=\"secMethod\" value=\"1\" checked=\"checked\"> 100 TAN<br><input type=\"radio\" name=\"secMethod\" value=\"2\"> SCS</td>
                            <td></td>";
                            echo "</tr>";
                            echo "<tr><td style=\"padding: 10px 0px;\"><label for=\"captcha\">CAPTCHA</label></td>";
                            echo "<td><img id=\"captcha\" src=\"./f8d890ce88bd1791b6eaddf06e58ceb5/securimage/securimage_show.php\" alt=\"CAPTCHA Image\"/></td></tr>";
                            echo "<tr><td></td><td><input type=\"text\" name=\"captcha_code\" size=\"10\" maxlength=\"6\" /><a href=\"#\" onclick=\"document.getElementById('captcha').src = './f8d890ce88bd1791b6eaddf06e58ceb5/securimage/securimage_show.php?' + Math.random(); return false\">&nbsp;<img src=\"./f8d890ce88bd1791b6eaddf06e58ceb5/securimage/images/refresh.png\" width=\"32\" height=\"32\"/></a></td></tr>";

                            echo "<tr><td colspan=\"3\" align=\"right\" style=\"padding: 30px 0px;\">
                                    <input type=\"submit\" value=\"Sign up\" id=\"submit\" style=\"width:80px; height:30px;\" class=\"btn btn-primary\" disabled/>
                                </td>
                        </tr>";
					}catch(Exception $e){
					//	echo $e;
                                                session_destroy();
						header("Location: error.php");
					}
                        ?>
                            <tr><td colspan="3"><input id="hashedPassword" type="hidden" name="hashedPassword" value=""></td></tr>
                            <tr><td colspan="3"><input id="hashedConfirm" type="hidden" name="hashedConfirm" value=""></td></tr>
                            <tr><td colspan="3"><input id="hashedAnswer" type="hidden" name="hashedAnswer" value=""></td></tr>
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
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Piggy Bank GmbH">
    <meta name="author" content="Alei , Sara , ePezhman">
    <link rel="icon" href="./images/piggyFav.ico">

    <!-- To be Changed!! -->
    <title>
        Thank you for choosing PiggyBank GmbH
    </title>

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
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Language/Sprache <b class="caret"></b></a>
                            <ul class="dropdown-menu" id="langs">
                                <li id="enLang" class="active"><a href="javascript:void(0);" class="EN">English</a></li>
                                <li id="deLang"><a href="javascript:void(0);" class="DE">Deutsch</a></li>
                            </ul>
                        </li>

                        <li><a href="signin.php">Sign in</a></li>
                        <li><a href="signup.php">Sign up</a></li>
                    </ul>

                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row">
          <!--      <div class="col-sm-8 hidden-xs">
                    <img src="./images/piggy-banks.jpg" alt="" class="img-responsive" />
                </div>
          -->
                <br/><br/><br/>
                <div class="col-sm-12">
                        <div class="form-signout">
                            <table><tr><td align="center">
                                <?php
                                     echo $_SERVER["HTTP_REFERER"];
                                     // Check the referer first to deny nosey requests
                                     if (strpos($_SERVER["HTTP_REFERER"], "/PiggyBank/f8d890ce88bd1791b6eaddf06e58ceb5/register.php") === false)
//                                         header("Location: error.php?id=404");
                                           $x = 5;
                                     else{
                                         if($_GET["mode"] == "success"){
                                             echo "<h2><b>Sign up request sucessfully submitted.</h2>";
                                             echo "</td></tr>";
                                             echo " <tr><td align=\"center\">";
                                             echo "<h4><b>Thank you for banking with us.</b></h4>";
                                             echo "</td></tr>";
                                             echo "<tr><td align=\"center\">";
                                             echo "<h4><b> You will be notified when your request is approved.</b></h4>";
                                             echo "<td/></tr>";
                                         }
                                     }
                             ?>
                            </table>
                       </div>
                            </div>
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

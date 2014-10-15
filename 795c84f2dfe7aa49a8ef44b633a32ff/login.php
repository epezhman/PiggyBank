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
        PiggyBank GmbH- Sign in
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
                <div class="col-sm-8 hidden-xs">
                    <img src="./images/piggy-banks.jpg" alt="" class="img-responsive" />
                </div>
                <div class="col-sm-4">
                    <div style="padding-top:40px;">
                        <form class="form-signin" role="form">
                            <h2>Sign in</h2>
                            <br />
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="username">Username</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="form-control" id="userName" name="userName" type="text">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="password">Password</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="form-control" id="password" name="password" type="password">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                               <!--     <div class="checkbox">
                                        <label>
                                            <input type="checkbox" name="RememberMe"> Remember me?
                                        </label>
                                    </div> -->
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" align="right">
                                    <input type="submit" value="Sign in" id="submit" class="btn btn-primary" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 >New User? <a href="signup.php">Sign up here!</a></h5>
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

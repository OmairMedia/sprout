<?php

//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}


include 'db.php';
include 'db_func.php';
auto_checkout_check();

    session_start();
                if(isset($_SESSION['e-mail']))
                {
                    exit(header('location:home.php')) ;
                }
?>
<html>
<?php
include('header.php');?>
<!--
<head>
    <meta charset="UTF-8">
    <title>Sprout | build your momentum</title>
    <link rel="icon" href="img/Icon.ico" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="css/custom.css "/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="keywords" content="Sprout Attendence Management System">
    

    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/new_style.css" rel="stylesheet" type="text/css" />
    <script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/Director/app.js" type="text/javascript"></script>
    <script src="js/Director/dashboard.js" type="text/javascript"></script>
    <link href="css/iCheck/all.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    
    

</head>
-->
<body class="skin-black" style="background: #d2d6de;">
<!-- header logo: style can be found in header.less -->
<div class="wrapper row-offcanvas row-offcanvas-left" style="position: relative;">
    <!-- Left side column. contains the logo and sidebar -->
    <div class="col-lg-4" style="position: absolute; left: 0;right: 0;top: 50px ;margin: 0px auto; text-align: center">
        <img src="img/sprout02.png" alt="" style="width: 200px;">
        <?php if(isset($_SESSION['emai-sent'])): ?>
            <div class="col-xs-12 alert alert-danger alert-dismissible" style="background-color: #5c7b30;">
                <button style="width:10px;" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p style="font-weight: bold; color: white">
                    <?php
                    echo $_SESSION['emai-sent'];
                    session_destroy();
                    ?>
                </p>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['error_message'])): ?>
        <div class="col-xs-12 alert alert-danger alert-dismissible" style="background-color: #5c7b30;">
            <button style="width:10px;" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p style="font-weight: bold; color: white; padding-left: 11px;">
                <?php
                    echo $_SESSION['error_message'];
                    session_destroy();
                ?>
                </p>
        </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['pass-succuss'])): ?>
            <div class="col-xs-12 alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p style="font-weight: bold; color: red">
                    <?php
                    echo $_SESSION['pass-succuss'];
                    session_destroy();
                    ?>
                </p>
            </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['empty_message'])): ?>
            <div class="col-xs-12 alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <p style="font-weight: bold; color: red">
                    <?php
                    echo $_SESSION['empty_message'];
                    session_destroy();
                    ?>
                </p>
            </div>
        <?php endif; ?>
        <section style="height: initial">
            <header class="panel-heading text-center">
                <?php if(empty($_GET['forget'])){ ?>
                Sign in to employee login
                <?php }else{ ?>
                    Forget Password
                <?php } ?>
            </header>
            <div class="panel-body">
                <?php if(empty($_GET['forget'])){ ?>
                <form class="form-horizontal" role="form" method="post" action="login_process.php">
                    <div class="form-group" style="margin: 0px;">
<!--                        <label for="inputEmail1" class="col-lg-2 col-sm-2 control-label">Email</label>-->
                        <div class="col-lg-12" style="padding: 0px;">
                            <input class="form-control" id="inputEmail1" placeholder="Email" type="email" name="e-mail" value="<?php if(isset($_COOKIE['remember_me'])) {
                                echo $_COOKIE['remember_me'];
                            } ?>">
                        </div>
                    </div>
                    <div class="form-group">
<!--                        <label for="inputPassword1" class="col-lg-2 col-sm-2 control-label">Password</label>-->
                        <div class="col-lg-12" style="position: relative">
                            <input class="form-control" id="inputPassword1" placeholder="Password" type="password" name="password" value="<?php if(isset($_COOKIE['remember_me'])) {
                                echo $_COOKIE['remember_me_pass'];
                            } ?>">
                            <button type="submit" name="submit" id="submit-button">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-6 col-xs-12 text-left">
                            <div class="checkbox" style="padding: 0px; margin-top: 6px; margin-left: 20px;">
                                <label style="display: inline-block">
                                    <input type="checkbox" name="remember" value="on"> <span id="remember">Remember me</span>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-xs-12 text-right">
                            <div class="checkbox">
                                <a href="login.php?forget=true" id="forget-pass">Forget Password</a>
                            </div>
                        </div>
                    </div>
<!--                    <div class="form-group">-->
<!--                        <div class="col-lg-12 text-center">-->
<!--                            <input type="submit" name="submit" value="Log In" class="btn green-btn"/>-->
<!--                        </div>-->
<!--                    </div>-->
                </form>
                <?php }else{ ?>
                <form class="form-horizontal" role="form" method="post" action="passwordchange.php">
                    <div class="form-group">
                        <label for="forgotPasswordEmail" class="col-lg-2 col-sm-2 control-label">Email</label>
                        <div class="col-lg-10" style="position: relative">
                            <input class="form-control" id="forgotPasswordEmail" placeholder="Enter your email" type="email" name="forget-email">
                            <p class="help-block"></p>
                            <button type="submit" name="send" id="submit-button">
                                <i class="fa fa-sign-in" aria-hidden="true"></i>
                            </button>
                        </div>
                    </div>
                </form>
                <?php }?>
            </div>
        </section>
    </div>
<a 
style="position:absolute; z-index:1; bottom:0; left:50%; transform:translateX(-50%); font-size:1.5rem; font-family:sans-serif; Text-decoration:none; color:rgb(72, 99, 47); cursor:pointer;"
href="admin" >Go To Admin Panel ....</a>
</div><!-- ./wrapper -->



</body>
</html>
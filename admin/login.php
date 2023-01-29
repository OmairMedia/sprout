<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}


include('db_func.php');
global $con;

//auto_absent_gen();

auto_checkout_check();
if (chk_adm_login()) {
    header('location: index.php');
    exit;
}

$pagetitle = "Sprout | build your momentum";
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $pagetitle ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="../img/Icon.ico" type="image/x-icon"/>
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="dist/css/new_style.css">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/square/blue.css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition login-page">

<div class="login-box col-lg-4" style="position: absolute; left: 0px; right: 0px;">
    <div class="login-logo">
<!--        <a href="index.php">--><?//= $pagetitle ?><!--</a>-->
        <a href="index.php"><img src="../img/sprout02.png" alt="" width="200px"></a>
        <?php if(isset($_SESSION['error_message'])):?>
        <div class="col-xs-12 alert alert-danger alert-dismissible" style="background-color: #5c7b30 !important;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <p style="font-weight: bold; font-size: initial;padding-left: 19px;">
                <?php echo $_SESSION['error_message'];
                    session_destroy();?>
            </p>
        </div>
        <?php endif; ?>
        <?php if(isset($_SESSION['empty_message'])): ?>
            <div class="col-xs-12 alert alert-danger alert-dismissible">
                <p style="font-weight: bold; font-size: initial">
                    <?php
                    echo $_SESSION['empty_message'];
                    session_destroy();
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div><!-- /.login-logo -->
    <header class="panel-heading text-center">
           <p style="font-family: 'Lato', sans-serif !important;">Sign in to Admin login</p>
    </header>

        <form action="user_login.php" method="post">
            <div class="form-group has-feedback">
                <input type="email" id="email-admin" class="form-control" placeholder="Email" name="email" value="">

            </div>
            <div class="form-group has-feedback" style="position: relative">
                <input type="password" class="form-control" placeholder="Password" name="password" id="inputPassword1">
                <button type="submit" name="submit" id="submit-button">
                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                </button>
            </div>
        </form>
        <a href="../home.php" class="btn btn-default" id="employee-btn">
            <i class="fa fa-users" aria-hidden="true"></i>
            Employee Panel</a>


</div><!-- /.login-box -->

<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="plugins/iCheck/icheck.min.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-blue',
            radioClass: 'iradio_square-blue',
            increaseArea: '20%' // optional
        });
    });
</script>
</body>
</html>

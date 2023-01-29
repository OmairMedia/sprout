<?php

include 'db.php';
include 'header.php';

?>
<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Change Password</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
</head>
<body class="skin-black" style="background: #d2d6de;">
<!-- header logo: style can be found in header.less -->
<div class="wrapper row-offcanvas row-offcanvas-left" style="position: relative">
    <!-- Left side column. contains the logo and sidebar -->
    <div class="col-lg-4" style="position: absolute; left: 0;right: 0;top: 50px ;margin: 0px auto; text-align: center">
        <img src="img/sprout02.png" alt="" style="width: 200px;">
        <?php if(isset($_SESSION['error_message'])): ?>
            <div class="col-xs-12 alert alert-danger alert-dismissible">
                <p style="font-weight: bold; color: red">
                    <?php
                    echo $_SESSION['error_message'];
                    session_destroy();
                    ?>
                </p>
            </div>
        <?php endif; ?>
        <section style="height: initial" class="panel">
            <header class="panel-heading text-center">

            </header>
            <div class="panel-body">
                <form action="new_passowrd.php" method="post">
                    <div class="form-group">
                        <label>New Password</label>
                        <input class="form-control" type="password" name="pasword" value=""/>
                        <input class="form-control" type="hidden" name="email" value="<?= $_GET['email'];?>">
                    </div><!--designation-->
                    <div class="form-group">
                        <button type="submit" class="btn btn-info" name="change_submit">Update</button>
                    </div><!-- /.box-footer -->

                </form>

            </div>
        </section>
    </div>

</div><!-- ./wrapper -->


<!-- jQuery 2.0.2 -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>

<!-- jQuery UI 1.10.3 -->
<script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

<script src="js/plugins/chart.js" type="text/javascript"></script>

<!-- datepicker
<script src="js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>-->
<!-- Bootstrap WYSIHTML5
<script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>-->
<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- calendar -->
<script src="js/plugins/fullcalendar/fullcalendar.js" type="text/javascript"></script>

<!-- Director App -->
<script src="js/Director/app.js" type="text/javascript"></script>

<!-- Director dashboard demo (This is only for demo purposes) -->
<script src="js/Director/dashboard.js" type="text/javascript"></script>

<!-- Director for demo purposes -->


</body>
</html>

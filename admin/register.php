<?php
include('db_func.php');
global $con;


if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

$log_email = $_SESSION['log_adm_email'];
// grab login user data;

global $grab_user_data_fet;

$grab_user_data_qur = "SELECT `Account_type`, `U_id`, `D_id`, `r_id`, `U_name`, `u_email`, `profile_img` FROM `user` WHERE `u_email`='" . $log_email . "'";
$grab_user_data_exc = mysqli_query($con, $grab_user_data_qur) or die (mysqli_error($con));
$grab_user_data_fet = mysqli_fetch_assoc($grab_user_data_exc);


function genWorkingDays($month, $year, $skipDay = ""){
    if(!empty($skipDay)){
        $totalMonthlyDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $countDays = 0;
        for($i = 1; $i<=$totalMonthlyDays; $i++){
            $makeTime = mktime(0, 0, 0, $month, $i, $year);
            $checkDay = date("l", $makeTime);
            if($checkDay == $skipDay){
                continue;
            }else{
                $countDays++;
            }
        }
        return $countDays;
    }else{
        return cal_days_in_month(CAL_GREGORIAN, $month, $year);
    }
}



?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sprout Admin | Dashboard</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="shortcut icon" href="Favicon.ico" type="image/x-icon"/>   

    <link rel="stylesheet" href="dist/css/new_style.css">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include('header.php') ?>
    <?php include('sidebar.php'); ?>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Add New Employee
                <small>Control panel</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Register</li>
            </ol>
        </section>
        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-md-12 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <div class="box-body">
                            <div>
                                <?php
                                /*Enter Values Get*/
                                $uname_val = call_sess('uname_val');
                                $email_val = call_sess('email_val');
                                $pass_val = call_sess('pass_val');
                                $pass1_val = call_sess('pass1_val');
                                $designation_val = call_sess('designation_val');
                                $phoneno_val = call_sess('phoneno_val');
                                $bankAccNo_val = call_sess('bankAccNo_val');
                                $hourly_salary_val = call_sess('hourly_salary_val');
                                $monthly_salary_val = call_sess('monthly_salary_val');
                                $dep_val = call_sess('dep_val');
                                $accType_val = call_sess('setAccountType_val');

                                $all_in_errs = call_sess('all_in_errs');
                                if(!empty($all_in_errs)) {
                                    foreach($all_in_errs as $sing_err){
                                        echo "<p class='alert alert-danger'>".$sing_err."</p>";
                                    }
                                }

                                $failRegErr = call_sess('fail_reg');
                                if(!empty($failRegErr)) {
                                    echo "<p class='alert alert-danger'>".$failRegErr."</p>";
                                }
                                ?>
                            </div>
                            <form action="regproces.php" method="post" enctype="multipart/form-data">
                                <div class="form-group has-feedback">
                                    <input type="text" class="form-control" placeholder="Full name"
                                           name="uname" value="<?php if(!empty($uname_val)){ echo $uname_val; } ?>">
                                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php if(!empty($email_val)){ echo $email_val; } ?>">
                                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback">
                                    <input type="password" class="form-control" placeholder="Password"
                                           name="pass">
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                </div>

                                <div class="form-group has-feedback">
                                    <input type="password" class="form-control" placeholder="Retype password"
                                           name="pass1">
                                    <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control timepicker" type="text" name="designation" placeholder="Designation" value="<?php if(!empty($designation_val)){ echo $designation_val; } ?>"/>

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="text" name="bankAccNo" value="<?=(!empty($bankAccNo_val)) ? $bankAccNo_val:'' ?>" class="form-control" autocomplete="off" placeholder="Enter Bank Account No" />
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"></i>
                                        </div>
                                        <input class="form-control"
                                               data-inputmask="&quot;mask&quot;: &quot;(999) 999-9999&quot;"
                                               data-mask="" type="number" name="phoneno" placeholder="Phone Number" value="<?php if(!empty($phoneno_val)){ echo $phoneno_val; } ?>">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="input-group">
                                        <input class="form-control" type="number" id="monthly_salary" name="hourly_salary" placeholder="Monthly Salary" value="<?php if(!empty($monthly_salary_val)){ echo $monthly_salary_val; } ?>">
                                        <input class="form-control" type="number" id="hourly_salary" name="monthly_salary" disabled placeholder="Hourly Salary" value="<?php if(!empty($hourly_salary_val)){ echo $hourly_salary_val; } ?>">
                                        <button class="btn btn-small" type="button" id="calculateMonthly">Calculate</button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <select name="dep" class="form-control" style="width: auto;">

                                        <option value="" <?php option_chk_sel($dep_val, ''); ?>>-SELECT DEPARTMENT-</option>
                                        <?php
                                        $query = "SELECT * FROM `department` ";
                                        $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
                                        while ($row = mysqli_fetch_array($query_run)){
                                        ?>

                                        <option value="<?php echo $row['id']; ?>" <?php option_chk_sel($dep_val, $row['id']); ?>>
                                            <?php echo $row['department'];
                                            } ?>
                                        </option>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <select class="form-control" style="width:auto;" name="setAccountType" id="setAccountType">
                                        <option value="">Select Account Type</option>
                                        <option value="sub-admin" <?php option_chk_sel($accType_val, "sub-admin"); ?>>Sub Admin</option>
                                        <option value="employee" <?php option_chk_sel($accType_val, "employee"); ?>>Employee</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div class="col-xs-2">
                                        <input type="submit" class="btn btn-primary btn-block btn-flat green-btn" name="submit"
                                               value="Register"/>
                                    </div><!-- /.col -->
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
	<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <script src="dist/js/helpers.js"></script>
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

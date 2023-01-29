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


if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

$get_userid = $_GET['id'];

//confirm id is valid
if (!chk_col_val_exist('user', 'U_id', $get_userid)) {
    header('location: login.php');
    exit;
}


$log_email = $_SESSION['log_adm_email'];
// grab login user data;

global $grab_user_data_fet;

$grab_user_data_qur = "SELECT `Account_type`, `U_id`, `D_id`, `r_id`, `U_name`, `u_email`, `profile_img`, `Active` FROM `user` WHERE `u_email`='" . $log_email . "'";
$grab_user_data_exc = mysqli_query($con, $grab_user_data_qur) or die (mysqli_error($con));
$grab_user_data_fet = mysqli_fetch_assoc($grab_user_data_exc);


/*Get Edit User*/
$query1 = "SELECT u.*,d.department FROM `user` u ,`department` d WHERE u.d_id = d.id AND u.U_ID=" . $get_userid;
$query_run1 = mysqli_query($con, $query1) or die(mysqli_error($con));
$row = mysqli_fetch_array($query_run1);



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
<!DOCTYPE>
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

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <!-- Left side column. contains the logo and sidebar -->

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Edit User
                
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Edit</a></li>
                <li class="active">Edit Employee Info</li>
            </ol>
        </section>
        <section class="content">
            <div class="box">
                <div class="box-header">
                    <i class="fa fa-user"></i>

                    <h3 class="box-title">Users</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <div>
                        <?php
                        /*Enter Values Get*/
                        $uname_val = call_sess('uname_val');
                        $designation_val = call_sess('designation_val');
                        $phoneno_val = call_sess('phoneno_val');
                        $bankAccNo_val = call_sess('bankAccNo_val');
                        $hourly_salary_val = call_sess('hourly_salary_val');
                        $dep_val = call_sess('dep_val');
                        $accType_val = call_sess('setAccountType_val');
                        $accStatus_val = call_sess('setAccountStatus_val');

                        if(empty($uname_val)){
                            $uname_val = $row['U_name'];
                        }
                        if(empty($designation_val)){
                            $designation_val = $row['user_designation'];
                        }
                        if(empty($phoneno_val)){
                            $phoneno_val = $row['Phone_No'];
                        }
                        if(empty($bankAccNo_val)){
                            $bankAccNo_val = $row['bank_account_no'];
                        }
                        if(empty($hourly_salary_val)){
                            $hourly_salary_val = $row['hourly_salary'];
                        }
                        if(empty($accType_val)){
                            $accType_val = $row['Account_type'];
                        }
                        if(empty($accStatus_val)){
                            $accStatus_val = $row['Active'];
                        }

                        $all_in_errs = call_sess('all_in_errs');
                        if(!empty($all_in_errs)) {
                            foreach($all_in_errs as $sing_err){
                                echo "<p class='alert alert-danger'>".$sing_err."</p>";
                            }
                        }

                        $failEditErr = call_sess('fail_reg');
                        if(!empty($failEditErr)){
                            echo "<p class='alert alert-danger'>".$failEditErr."</p>";
                        }
                        ?>
                    </div>
                    <form action="updatemploye.php" method="post" enctype="multipart/form-data">
                        <div class="form-group has-feedback">
                            <label>Change Employe Name</label>
                            <input value="<?=$uname_val?>" type="text" class="form-control"
                                   placeholder="Full name" name="uname"/>
                            <span class="glyphicon glyphicon-user form-control-feedback"></span>
                        </div><!--user name-->

                        <div class="form-group has-feedback">
                            <label>Employe E-mail</label>
                            <input type="email" class="form-control disabled" disabled
                                   value="<?=$row['u_email']?>"/>
                            <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                        </div><!--email-->

                        <div class="form-group">
                            <label>Change Employe Designation</label>

                            <div class="input-group">
                                <input class="form-control timepicker" type="text" name="designation"
                                       value="<?=$designation_val?>"/>

                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                            </div><!-- /.input group -->
                        </div><!--designation-->

                        <div class="form-group">
                            <label for="phoneNo">Change Employe Phone No</label>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-phone"></i>
                                </div>
                                <input value="<?=$phoneno_val?>" class="form-control" autocomplete="off"
                                       data-inputmask="&quot;mask&quot;: &quot;(999) 999-9999&quot;" data-mask=""
                                       type="text" name="phoneno" id="phoneNo">
                            </div><!-- /.input group -->
                        </div><!--phone no-->

                        <div class="form-group">
                            <label for="bankAccNo">Bank Account No</label>
                            <input type="text" class="form-control" value="<?=$bankAccNo_val?>" name="bankAccNo" id="bankAccNo" autocomplete="off">
                        </div>

                        <div class="form-group">
                            <label>Basic Salary</label>

                            <div class="input-group">
                                <input value="<?=$hourly_salary_val?>" class="form-control" type="number" id="monthly_salary2"
                                       name="hourly_salary">
                                
                                <input class="form-control" type="number" id="hourly_salary2" name="monthly_salary" disabled placeholder="Hourly Salary">
                                <button class="btn btn-small calculateHourly" type="button">Calculate</button>
                            </div><!-- /.input group -->
                        </div><!--user salary-->

                        <div class="form-group">
                            <label>Change Employe Department</label>
                            <select class="form-control" style="width: auto;" name="dep">
                                <option value="" >Choose Department</option>
                                <?php
                                $query = "SELECT * FROM `department`";
                                $query_run = mysqli_query($con, $query) or die (mysqli_error($con));
                                while ($dep_row = mysqli_fetch_array($query_run)){
                                ?>
                                <option value="<?php echo $dep_row['id']; ?>" <?php if(!empty($dep_val)){ option_chk_sel($dep_row['id'], $dep_val); }else{ option_chk_sel($dep_row['id'], $row['D_id']); } ?>>
                                    <?php echo $dep_row['department'];
                                    } ?>
                                </option>
                            </select><!--department-->
                        </div>

                        <div class="form-group">
                            <label for="setAccountType">Change Account Type</label>
                            <select class="form-control" style="width:auto;" name="setAccountType" id="setAccountType">
                                <option value="">Select Type</option>
                                <option value="sub-admin" <?php option_chk_sel($accType_val, "sub-admin"); ?>>Sub Admin</option>
                                <option value="employee" <?php option_chk_sel($accType_val, "employee"); ?>>Employee</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="setAccountType">Change Status</label>
                            <select class="form-control" style="width:auto;" name="setAccountStatus" id="setAccountStatus">
                                <option value="">Select Status</option>
                                <option value="active" <?php option_chk_sel($accStatus_val, "active"); ?>>Active</option>
                                <option value="inactive" <?php option_chk_sel($accStatus_val, "inactive"); ?>>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <div class="box-footer">
                                <input type="hidden" name="upd_id" value="<?=$get_userid?>" />
                                <button type="submit" class="btn btn-default green-btn" name="update">Update</button>
                            </div><!-- /.box-footer -->
                        </div>

                    </form>
                </div>
            </div>
        </section>
    </div>
    <!-- Content Wrapper. Contains page content -->
    <!-- /.content-wrapper -->
    

    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="dist/js/helpers.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <script>
        jQuery(function(){
            jQuery("#bankAccNo, #phoneNo").keypress(function (e) {
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
    </script>
    <!-- jQuery UI 1.11.4 -->
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="dist/js/pages/dashboard.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    

</body>
</html>


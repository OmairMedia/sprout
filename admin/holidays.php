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
global $con, $pdo_con;


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


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sprout | build your momentum</title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="../img/Icon.ico" type="image/x-icon"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="dist/css/new_style.css">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="dist/css/style.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
     <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
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
                Set Holidays
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Set Holidays</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                <div class="info-box">
                                    <div class="box-body">
                                        <p class="alert alert-danger" id="addDateErr" style="display: none;"></p>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="sel_date">Select Holiday</label>
                                                    <input type="text" id="sel_date" class="form-control" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="set_userRemarks_ne">User Remarks:</label>
                                                    <textarea id="set_userRemarks_ne" class="form-control resize_hor_none"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <button class="btn btn-default green-btn" id="addDate">Add</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <div class="row">
                            <div class="col-md-12">
                                <p class="alert alert-danger" id="resDateErr" style="display: none;"></p>
                                <table class="table table-bordered table-responsive">
                                    <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Date</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody id="resDates">
                                    <?php
                                    $selDates = $pdo_con->prepare("SELECT * FROM `holidays` ORDER BY `date` ASC");
                                    $selDates->execute();
                                    $loop_inc = 0;
                                    while($dateRes = $selDates->fetch(PDO::FETCH_ASSOC)){
                                        $loop_inc++;
                                        ?>
                                        <tr>
                                            <td><?=$loop_inc?></td>
                                            <td><?=$dateRes["date"]?></td>
                                            <td><?=$dateRes["user_remarks"]?></td>
                                            <td><button class="btn btn-default red-btn btn-sm del-date" data-delId="<?=$dateRes["id"]?>">Delete</button></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <!-- Content Wrapper. Contains page content -->
    <!-- /.content-wrapper -->
    <?php include 'footer.php'; ?>
    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>

    <script>
        function row_del(){
            jQuery(".del-date").click(function(){
                var grabErrContent = jQuery("#resDateErr");
                var grabId = jQuery(this).attr("data-delId");
                var self_click = jQuery(this);
                grabErrContent.hide();
                grabErrContent.html("");
                if(grabId !== ""){
                    jQuery.ajax({
                        url: 'formData/deleteHolidays.php',
                        method: 'post',
                        data: {del_id: grabId},
                        datatype: 'json'
                    }).done(function(res){
                        if(res.err !== ""){
                            grabErrContent.html(res.err);
                            grabErrContent.show();
                        }else{
                            self_click.parent().parent().remove();
                        }
                    });

                }else{
                    location.reload();
                }
            });
        }
        jQuery(function(){
            row_del();
            var today = new Date();
            jQuery("#sel_date").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });
            jQuery("#addDate").click(function(){
                var addDateErr = jQuery("#addDateErr");
                var grabDate = jQuery("#sel_date").val();
                var grabRemarks = jQuery("#set_userRemarks_ne").val();

                addDateErr.hide();
                addDateErr.html("");

                if(grabDate !== "" && grabRemarks !== ""){
                    jQuery.ajax({
                        url: 'formData/addHolidays.php',
                        method: 'post',
                        data: {add_date: grabDate, remarks: grabRemarks},
                        datatype: 'json'
                    }).done(function(res){
                        if(res.err !== ""){
                            addDateErr.html(res.err);
                            addDateErr.show();
                        }else{
                            var res_dates_body = jQuery("#resDates");
                            res_dates_body.html("");
                            var grabRetData = res.retData;
                            for(var i = 0; i < grabRetData.length; i++){
                                var curDataGrab = grabRetData[i];
                                res_dates_body.append("<tr><td>"+(i+1)+"</td><td>"+curDataGrab.date+"</td><td>"+curDataGrab.remarks+"</td><td><button class='btn btn-danger btn-sm del-date' data-delId='"+curDataGrab.id+"'>Delete</button></td></tr>");
                            }
                            jQuery("#sel_date, #set_userRemarks_ne").val("");
                            row_del();
                        }
                    });

                }else{
                    addDateErr.html("Please Fill All Fields!");
                    addDateErr.show();
                }
            });
        });
    </script>
</body>
</html>
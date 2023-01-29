<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}



include 'formdata/hereDBConfig.php';
session_set_cookie_params(86400, "/");
session_start();

if (!isset($_SESSION['U_name'])) {
    header('location:login.php');
};


$pagename = "Sprout | build your momentum";

$sel_all_user = $pdo_con->prepare("SELECT * FROM `user` WHERE `U_name` = '".$_SESSION['U_name']."' ORDER BY `U_name` ASC ");
$sel_all_user->execute();

$sel_uAttYear = $pdo_con->prepare("SELECT `date` FROM `user_attendance` WHERE `uid`='".$_SESSION['U_id']."'");
$sel_uAttYear->execute();
$check_date_arr = [];
while($att_date_res = $sel_uAttYear->fetch(PDO::FETCH_ASSOC)){
    $s_year = date('Y', strtotime($att_date_res['date']));
    if(!in_array($s_year, $check_date_arr)){
        $check_date_arr[] .= $s_year;
    }
}

$sel_allMonths = [];
for($m = 1;$m <= 12;$m++){
    $sel_allMonths[$m] = date('F', mktime(0,0,0,$m,1,date('Y')));
}

?>
<!DOCTYPE>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$pagename?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="./img/Icon.ico" type="image/x-icon"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="admin/plugins/datatables/dataTables.bootstrap.css">
    <script
  src="https://code.jquery.com/jquery-3.4.1.js"
  integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU="
  crossorigin="anonymous"></script>
    <script>
        jQuery(function(){
            jQuery("#setHoursInput, #setMinutesInput").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            jQuery("#search_user_att_btn").click(function(){
                var search_err_sel = jQuery("#searc_err");
                var search_res_sel = jQuery("#search_res");
                search_res_sel.hide();
                search_res_sel.find(".box-body").html("");
                search_err_sel.hide();
                search_err_sel.html('');
                var grab_user = jQuery("#search_sel_user").val(),
                    grab_year = jQuery("#search_sel_year").val(),
                    grab_month = jQuery("#search_sel_month").val();

                if(grab_user !== '' && grab_year !== '' && grab_month !== ''){
                    jQuery.ajax({
                        url: 'formdata/searchUserAtt.php',
                        method: 'post',
                        data: {sel_user: grab_user, sel_year: grab_year, sel_month: grab_month},
                        datatype: 'json',
                        success: function(res){
                            var err = res.err;
                            if(err !== ""){
                                search_err_sel.show();
                                search_err_sel.html(err);
                            }else{
                                search_res_sel.find(".box-body").html("<table id='resTable' class='table table-responsive table-bordered'><thead><tr><th>S.No</th><th>Date</th><th>Check In</th><th>Check Out</th><th>Break</th><th>Total Working Hours</th><th>Remarks</th></tr></thead><tbody class='table_res'></tbody></table>");
                                var retResult = res.retUserInfo;
                                var retResultCount = res.retUserInfo.length;
                                for(var incLoop = 0; incLoop < retResultCount; incLoop++){
                                    var singleRetInfo = retResult[incLoop];
                                    var tot_w_hours = (singleRetInfo.tot_working_hours == "00:00:00") ? "<span class='text-red'>00:00:00</span>" : singleRetInfo.tot_working_hours;
                                    var check_in = (singleRetInfo.check_in == "auto") ? "<span class='text-red'>auto</span>" : singleRetInfo.check_in;
                                    var check_out = (singleRetInfo.check_out == "auto") ? "<span class='text-red'>auto</span>" : singleRetInfo.check_out;
                                    var action_btns = "<button data-idGrab='"+singleRetInfo.id+"' class='btn btn-default green-btn btn-sm attEdit'>Edit</button><button data-idGrab='"+singleRetInfo.id+"' class='btn btn-default red-btn btn-sm attDelete'>Delete</button>";
                                    if(check_out == ""){
                                        check_out = "<span style='color: green;'>Present</span>";
                                        action_btns = "";
                                    }

                                    search_res_sel.find("#resTable .table_res").append("<tr><td>"+(incLoop+1)+"</td><td>"+singleRetInfo.date+"</td><td>"+check_in+"</td><td>"+check_out+"</td><td>"+singleRetInfo.break_time+"</td><td>"+tot_w_hours+"</td><td>"+singleRetInfo.user_remarks+"</td></tr>");

                                }
                                search_res_sel.show();
                            }
                            
                        }
                    });

                }else{
                    search_err_sel.show();
                    search_err_sel.html("Select All Fields");
                }
            });
           

           

            
            jQuery("#sel_whours_ne").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
    </script>
    
</head>
<body>
<?php 
include 'header.php'; 
?>

<?php  include 'content.php'; ?>
<div class="wrapper">
    
  
    <!-- Left side column. contains the logo and sidebar -->
    

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container">
            <h1>
                Previous Records 
            </h1>
            </div>
        </section>
<div class="container">
        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                      
                                    <a class="btn btn-default pull-right" style="margin:1rem;" href="attendence.php">ShowCurrent</a>
                        <h3 class="box-title">User</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <div>
                                        <p class="alert alert-danger" id="searc_err" style="display: none;"></p>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group" style="">                                          
                                                <label for="sel_user">User Select</label>
                                                <select class="form-control" id="search_sel_user">
                                                    <!--<option value="">Select User</option>-->
                                                    <?php
                                                    while($users_res = $sel_all_user->fetch(PDO::FETCH_ASSOC)){
                                                        echo "<option value='{$users_res['U_id']}' selected>{$users_res['U_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sel_year">Select Year</label>
                                                <select class="form-control" id="search_sel_year">
                                                    <option value="">Select Year</option>
                                                    <?php
                                                    foreach($check_date_arr as $s_y){
                                                        echo "<option value='{$s_y}'>{$s_y}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="sel_month">Select Month</label>
                                                <select class="form-control" id="search_sel_month">
                                                    <option value="">Select Month</option>
                                                    <?php
                                                    foreach($sel_allMonths as $this_key => $s_m){
                                                        echo "<option value='{$this_key}'>{$s_m}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-default green-btn" id="search_user_att_btn">Search</button>
                                        

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box" id="search_res" style="display: none;">
                <div class="box box-info">
                    <div class="box-header">

                    </div><!-- /.box-header -->
                    <div class="box-body">

                    </div>
                </div>
            </div>

        </section>
        </div>
    </div>
    <!-- Content Wrapper. Contains page content -->
    <!-- /.content-wrapper -->
    <div style="margin-top:50rem;">
    <?php include 'footer.php'; ?>
    </div>
 
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <script src="admin/plugins/daterangepicker/daterangepicker.js"></script>
    <!-- datepicker -->
    <script src="admin/plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="admin/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="admin/plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="admin/plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="admin/dist/js/app.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="admin/dist/js/demo.js"></script>   


</body>
</html>



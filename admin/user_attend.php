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
global $pdo_con;


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

$pagename = "Sprout | build your momentum";

$sel_all_user = $pdo_con->prepare("SELECT * FROM `user` WHERE `Active`='active' ORDER BY `U_name` ASC");
$sel_all_user->execute();

$sel_uAttYear = $pdo_con->prepare("SELECT `date` FROM `user_attendance`");
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
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?=$pagename?></title>
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
    <!-- AdminLTE Skins. Choose a skin from the css/skins -->
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

    <div id="nextPopupEntry" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">New Entry</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <p class="alert alert-success genNewEntrErr" style="display: none;"></p>
                        <form role="form" class="genNewEntryPros">
                            <div class="form-group">
                                <label for="sel_user_ne">Users:</label>
                                <select id="sel_user_ne" class="form-control">
                                    <option value="">-SELECT User-</option>
                                    <?php
                                    $query = "SELECT * FROM `user` WHERE (Account_type='employee' OR Account_type='sub-admin')";
                                    $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
                                    while ($row = mysqli_fetch_array($query_run)){
                                    ?>

                                    <option value="<?php echo $row['U_id']; ?>">
                                        <?php echo $row['U_name'];
                                        } ?>
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="sel_date_ne">Date:</label>
                                <input type="text" id="sel_date_ne" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="sel_whours_ne">Working Hours:</label>
                                <input type="text" id="sel_whours_ne" class="form-control"/>
                            </div>
                            <div class="form-group">
                                <label for="set_userRemarks_ne">User Remarks:</label>
                                <textarea id="set_userRemarks_ne" class="form-control resize_hor_none"></textarea>
                            </div>
                            <button type="submit" class="btn btn-default green-btn">Submit</button>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default red-btn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="editAttModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <p id="editAttErr" class="alert alert-danger" style="display: none;"></p>
                    <div class="row">
                        <div class="col-md-12">
                            <!--
                            <div class="form-group">
                                <label for="setHoursInput">Set Hours</label>
                                <input type="text" class="form-control" id="setHoursInput" />
                            </div>
                            <div class="form-group">
                                <label for="setMinutesInput">Set Minutes</label>
                                <input type="text" class="form-control" id="setMinutesInput" />
                            </div>
                            -->
                            <div class="form-group">
                                <label for="checkinInput">Checkin Time</label>
                                <input type="time" class="form-control" id="checkinInput"  min="09:00" max="11:59" required/>
                            </div>
                            <div class="form-group">
                                <label for="checkoutInput">Checkout Time</label>
                                <input type="time" class="form-control" id="checkoutInput" min="19:00" max="20:00" required/>
                            </div>
                            <div class="form-group">
                                <label for="breaktimeInput">Total Break Time</label>
                                <input type="text" class="form-control" id="breaktimeInput" />
                            </div>
                            
                            <div class="form-group">
                                <label for="userRemarks">User Remarks</label>
                                <textarea id="userRemarks" class="form-control resize_hor_none"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success" id="subEditAttBtn">Submit</button>
                                <input type="hidden" id="editAttIdInput" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div id="deleteAttModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete Attendance</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="text-warning text-center">Are You Sure! You want to delete this Attendance</h5>
                            <div class="form-group text-center">
                                <button class="btn btn-success" id="delYesAttBtn">Yes</button>
                                <button class="btn btn-danger" id="delNoAttBtn" data-dismiss="modal">No</button>
                                <input type="hidden" id="delAttIdInput" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User attendence
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <button data-toggle="modal" data-target="#nextPopupEntry" class="btn btn-default mg_r_5 pull-right green-btn "><span><i
                                    class="fa fa-plus"></i></span></button>
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
                                            <div class="form-group">
                                                <label for="sel_user">User Select</label>
                                                <select class="form-control" id="search_sel_user">
                                                    <option value="">Select User</option>
                                                    <?php
                                                    while($users_res = $sel_all_user->fetch(PDO::FETCH_ASSOC)){
                                                        echo "<option value='{$users_res['U_id']}'>{$users_res['U_name']}</option>";
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
                                                        if($s_y != '1970') {
                                                            echo "<option value='{$s_y}'>{$s_y}</option>";
                                                        }
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
    <!-- Content Wrapper. Contains page content -->
    <!-- /.content-wrapper -->
    <?php include 'footer.php'; ?>
    <!-- jQuery 2.1.4 -->
    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="plugins/moment.min.js"></script>
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
    <script src="plugins/cleave.min.js"></script> 
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
                        url: 'formData/searchUserAtt.php',
                        method: 'post',
                        data: {sel_user: grab_user, sel_year: grab_year, sel_month: grab_month},
                        datatype: 'json',
                        success: function(res){
                            var err = res.err;
                            if(err !== ""){
                                search_err_sel.show();
                                search_err_sel.html(err);
                            }else{
                                search_res_sel.find(".box-body").html("<table id='resTable' class='table table-responsive table-bordered'><thead><tr><th>S.No</th><th>Date</th><th>Check In</th><th>Check Out</th><th>Break</th><th>Total Working Hours</th><th>Remarks</th><th>Action</th></tr></thead><tbody class='table_res'></tbody></table>");
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

                                    search_res_sel.find("#resTable .table_res").append("<tr><td>"+(incLoop+1)+"</td><td>"+singleRetInfo.date+"</td><td>"+check_in+"</td><td>"+check_out+"</td><td>"+singleRetInfo.break_time+"</td><td>"+tot_w_hours+"</td><td>"+singleRetInfo.user_remarks+"</td><td>"+action_btns+"</td></tr>");

                                }
                                search_res_sel.show();
                            }
                            jQuery(function(){
                                jQuery(".attEdit").click(function(){
                                    var grabModal = jQuery("#editAttModal");
                                    var idGrab = jQuery(this).attr('data-idGrab');
                                    var dateGrab = jQuery(this).parent().parent().find("td:eq(1)").html();
                                    var errAlertGrab = jQuery("#editAttErr");
                                    //Empty Error Or Hide
                                    errAlertGrab.hide();
                                    errAlertGrab.html("");
                                    grabModal.find(".modal-title").html("Date: "+dateGrab);
                                    grabModal.find("#editAttIdInput").val(idGrab);
                                    grabModal.find("#setcheckinInput, #setcheckoutInput,#setbreaktimeInput, #userRemarks").val("");
                                    grabModal.modal('show');
                                });
                                jQuery(".attDelete").click(function(){
                                    var grabModal = jQuery("#deleteAttModal");
                                    var idGrab = jQuery(this).attr('data-idGrab');
                                    grabModal.find("#delAttIdInput").val(idGrab);
                                    grabModal.modal("show");
                                });
                            });
                        }
                    });

                }else{
                    search_err_sel.show();
                    search_err_sel.html("Select All Fields");
                }
            });
            jQuery("#delYesAttBtn").click(function(){
                var grabModal = jQuery("#deleteAttModal");
                var grabEId = jQuery("#delAttIdInput").val();

                if(grabEId !== ""){
                    jQuery.ajax({
                        url: 'formData/attDelete.php',
                        method: 'post',
                        data: {att_id: grabEId},
                        datatype: 'json'
                    }).done(function (res) {
                        if (res.err !== "") {
                            alert(res.err);
                        } else {
                            grabModal.modal("hide");
                            jQuery("#search_user_att_btn").click();
                        }
                    });
                }else{
                    location.reload();
                }
            });

            jQuery("#subEditAttBtn").click(function(){
               
                var grabModal = jQuery("#editAttModal");
                var errAlertGrab = jQuery("#editAttErr");
                var grabingdate = jQuery(".attEdit").parent().parent().find("td:eq(1)").html();
                //Grabing Inputs 
                var grabEId = jQuery("#editAttIdInput").val();
                var grabESetcheckin = jQuery("#checkinInput").val();
                var momentcheckin =moment(grabESetcheckin,"LTS").format("LTS");
                var grabESetcheckout = jQuery("#checkoutInput").val();
                var momentcheckout =moment(grabESetcheckout,"LTS").format("LTS");
                var grabESetbreaktime = jQuery("#breaktimeInput").val();
                var grabUserremarks = jQuery("#userRemarks").val();
                //Empty Error Or Hide
                errAlertGrab.hide();
                errAlertGrab.html("");

                if(grabEId !== "" && grabESetcheckin !== "" && grabESetcheckout !== "" && grabESetbreaktime !== ""){
                   // grabESetMinutes = grabESetMinutes*1;
                   // grabESetHours = grabESetHours*1;
                        jQuery.ajax({
                            url: 'formData/editAttUser.php',
                            method: 'post',
                            data: {att_id: grabEId, set_checkin: momentcheckin, set_checkout: momentcheckout,set_breaktime: grabESetbreaktime, user_remarks: grabUserremarks,sel_date:grabingdate},
                            datatype: 'json'
                        }).done(function(res){
                            if(res.err !== ""){
                                errAlertGrab.html(res.err);
                                errAlertGrab.show();
                            }else{
                                grabModal.modal("hide");
                                jQuery("#search_user_att_btn").click();
                            }
                        });
                    
                }else{
                    errAlertGrab.html("Please Fill All Fields!");
                    errAlertGrab.show();
                }
            });
            jQuery('.genNewEntryPros').submit(function(e){
                e.preventDefault();
                var en_user = jQuery(this).find("#sel_user_ne").val();
                var en_date = jQuery(this).find("#sel_date_ne").val();
                var en_hours = jQuery(this).find("#sel_whours_ne").val();
                var en_userRemarks = jQuery(this).find("#set_userRemarks_ne").val();

                if(en_user !== '' && en_date !== '' && en_hours !== '' && en_userRemarks !== ''){
                    var NewEntryFormData = {
                        en_user_id: en_user,
                        en_date: en_date,
                        en_hours: en_hours,
                        en_userRemarks: en_userRemarks
                    };

                    jQuery.ajax({
                        url: 'saveNewEntryAtt.php',
                        method: 'post',
                        data: NewEntryFormData,
                        datatype: 'json',
                        success: function(data){
                            if(data.err == ''){
                                jQuery("#sel_user_ne").val('');
                                jQuery("#sel_date_ne").val('');
                                jQuery("#sel_whours_ne").val('');
                                jQuery("#set_userRemarks_ne").val('');
                                jQuery(".genNewEntrErr").html(data.succ).fadeIn().delay(3000).fadeOut();
                            }else{
                                alert(data.err);
                            }
                        }
                    });
                }else{
                    alert("Some Field Empty!");
                }
            });

            jQuery("#sel_date_ne").datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });

            jQuery("#sel_whours_ne").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
        
         var cleave = new Cleave('#breaktimeInput', {
                    time: true,
                    timePattern: ['h', 'm', 's']
                });
    </script>
     
</body>
</html>
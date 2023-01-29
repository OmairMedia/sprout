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

$sel_all_user_s = $pdo_con->prepare("SELECT * FROM `user` WHERE `Active`='active' ORDER BY `U_name` ASC");
$sel_all_user_s->execute();


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

    <div id="newDedAdd" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">User Deduction Add</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <p class="alert alert-danger" id="newDedAddErr" style="display: none;"></p>
                        <p class="alert alert-success" id="newDedAddSucc" style="display: none;"></p>
                        <div class="form-group">
                            <label for="sel_user_addDed">Users:</label>
                            <select id="sel_user_addDed" class="form-control">
                                <option value="">SELECT User</option>
                                <?php
                                while ($users_res = $sel_all_user->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$users_res['U_id']}'>{$users_res['U_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group" id="sel_u_loan_con" style="display: none;">
                            <label for="sel_user_l_ids">Select User Loan:</label>
                            <select id="sel_user_l_ids" class="form-control"></select>
                        </div>
                        <div class="form-group">
                            <label for="sel_ded_title">Deduct Title:</label>
                            <input type="text" id="sel_ded_title" class="form-control"/>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="sel_ded_month">Select Month Date:</label>
                                    <input type="text" id="sel_ded_month" class="form-control sel_date_m_y"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="sel_tot_amount">Total Amount:</label>
                            <input type="text" id="sel_tot_amount" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-default green-btn" id="add_userDeduct">Add</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default red-btn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                User Deduction
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <button class="btn btn-warning btn-sm pull-right" id="btnNewDedAdd"><i class="fa fa-plus"></i></button>
                        <h3 class="box-title">User Deductions List</h3>
                    </div><!-- /.box-header -->
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <p class="alert alert-danger" id="sel_ded_res_err" style="display: none;"></p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" id="sel_user" title="Select User">
                                                    <option value="">Select User</option>
                                                    <?php
                                                    while($users_res = $sel_all_user_s->fetch(PDO::FETCH_ASSOC)){
                                                        echo "<option value='{$users_res['U_id']}'>{$users_res['U_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" id="sel_month" class="form-control sel_date_m_y"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" id="btn_s_ded_res" class="btn btn-default green-btn" value="Search">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Return Result -->
        <section class="content" style="display: none;" id="ret_search_res_con">
            <div class="box">
                <div class="box box-info">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <table class="table table-bordered table-striped" id="userDedInfoRetTable">
                                        <thead>
                                        <th>S.NO</th>
                                        <th>Deduct Title</th>
                                        <th>User</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                        <th>Action</th>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
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
        $(function(){
            $("#sel_user_addDed").change(function(){
                var selUserLoanCon = $("#sel_u_loan_con");
                var selUId = $(this).val();
                selUserLoanCon.hide();
                selUserLoanCon.find("#sel_user_l_ids").html("");
                if(selUId != ""){
                    jQuery.ajax({
                        url: 'formData/selUserLoanList.php',
                        method: 'post',
                        data: {user_id: selUId},
                        datatype: 'json'
                    }).done(function (res) {
                        var resLength = res.res.length;
                        if(resLength > 0){
                            selUserLoanCon.find("#sel_user_l_ids").append("<option value=''>Select User Loan</option>");
                            for(var i=0; i<resLength; i++){
                                var curData = res.res[i];
                                selUserLoanCon.find("#sel_user_l_ids").append("<option value='"+curData.l_id+"'>"+curData.l_title+"</option>");
                            }
                            selUserLoanCon.show();
                        }
                    });
                }
            });

            $("#add_userDeduct").click(function(){
                var sel_user = $("#sel_user_addDed").val();
                var sel_month = $("#sel_ded_month").val();
                var sel_title = $("#sel_ded_title").val();
                var sel_u_l_ids = $("#sel_user_l_ids").val();
                sel_u_l_ids = (sel_u_l_ids == "") ? 0 : sel_u_l_ids;
                var sel_totAmount = ($("#sel_tot_amount").val()*1);
                $("#sel_tot_amount").val(sel_totAmount);
                var thisBtn = $(this);
                var errDiv = $("#newDedAddErr");
                var succDiv = $("#newDedAddSucc");
                succDiv.hide();
                succDiv.html("");
                errDiv.hide();
                errDiv.html("");
                thisBtn.attr('disabled', 'disabled');
                if(sel_user != "" && sel_title != "" && sel_month != "" && sel_totAmount != 0){
                    jQuery.ajax({
                        url: 'formData/addUserDeduct.php',
                        method: 'post',
                        data: {user_id: sel_user, selTitle: sel_title, selMonth: sel_month, selLoanIds: sel_u_l_ids, selTotalAmount: sel_totAmount},
                        datatype: 'json'
                    }).done(function (res) {
                        if (res.err !== "") {
                            errDiv.html(res.err);
                            errDiv.show();
                            thisBtn.removeAttr('disabled');
                        } else {
                            var selUserLoanCon = $("#sel_u_loan_con");
                            selUserLoanCon.hide();
                            selUserLoanCon.find("#sel_user_l_ids").html("");
                            $("#sel_user_addDed, #sel_ded_title, #sel_ded_month, #sel_tot_amount").val("");
                            succDiv.html(res.succ);
                            succDiv.show();
                            thisBtn.removeAttr('disabled');
                        }
                    });

                }else{
                    errDiv.html("Some Fields Empty!");
                    errDiv.show();
                    thisBtn.removeAttr('disabled');
                }
            });

            $("#btn_s_ded_res").click(function(){
                var sel_user = $("#sel_user").val();
                var sel_month = $("#sel_month").val();
                var sel_ded_u_res_err = $("#sel_ded_res_err");
                sel_ded_u_res_err.hide();
                sel_ded_u_res_err.html("");

                var resCont = $("#ret_search_res_con");
                resCont.hide();

                var resTable = $("#userDedInfoRetTable");
                resTable.find("tbody").html("");

                if(sel_user != "" && sel_month != ""){
                    $.ajax({
                        url: 'formData/getUserDedList.php',
                        method: 'post',
                        data: {user_id: sel_user, selMonth: sel_month},
                        datatype: 'json'
                    }).done(function(res){
                        var resCount = res.res.length;
                        if(resCount > 0){
                            var username = res.user;
                            for(var i=0; i<resCount; i++){
                                var cur_data = res.res[i];
                                resTable.find("tbody").append("<tr><td>"+(i+1)+"</td><td>"+cur_data.ded_title+"</td><td>"+username+"</td><td>"+cur_data.amount+"</td><td>"+cur_data.ded_month+"</td><td><button class='btn btn-default red-btn del_ded' data-dedId='"+cur_data.id+"'><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>");
                            }
                            resCont.show();
                        }else{
                            sel_ded_u_res_err.html("Result Not Found!");
                            sel_ded_u_res_err.show();
                        }
                        $(function () {
                            $(".del_ded").click(function () {
                                var user_confirm_check = confirm("Are You Sure! You want to delete this item?");
                                var delDedId = $(this).attr("data-dedId");
                                var rowSel = $(this).parent().parent();
                                if (user_confirm_check == true) {
                                    $.ajax({
                                        url: 'formData/delDed.php',
                                        method: 'post',
                                        data: {ded_id: delDedId},
                                        datatype: 'json'
                                    }).done(function (res) {
                                        if (res.err != "") {
                                            alert(res.err);
                                        } else {
                                            rowSel.remove();
                                        }
                                    });
                                }
                            });
                        });
                    });
                }else{
                    sel_ded_u_res_err.html("Some Fields Empty!");
                    sel_ded_u_res_err.show();
                }
            });

            $("#sel_tot_amount").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            $("#btnNewDedAdd").click(function(){
                $("#newDedAddErr, #newDedAddSucc").hide().html("");
                var selUserLoanCon = $("#sel_u_loan_con");
                selUserLoanCon.hide();
                selUserLoanCon.find("#sel_user_l_ids").html("");
                $("#sel_user_addDed, #sel_ded_title, #sel_ded_month, #sel_tot_amount").val("");
                $("#newDedAdd").modal("show");
            });

            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

            $("#sel_ded_month").datepicker({
                format: "yyyy-mm-dd",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            });

            $("#sel_month").datepicker({
                format: "yyyy-mm-dd",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            });

            $("#sel_ded_month, #sel_month").keydown(function(){
                return false;
            });
        });
    </script>
</body>
</html>
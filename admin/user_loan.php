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

$pagename = "Users Loan";

$sel_all_user_f_l = $pdo_con->prepare("SELECT * FROM `user` WHERE `Active`='active' ORDER BY `U_name` ASC");
$sel_all_user_f_l->execute();

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
    <title>Sprout | build your momentum</title>
    <!-- Tell the browser to be responsive to screen width -->
    <link rel="shortcut icon" href="../img/Icon.ico" type="image/x-icon"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="dist/css/new_style.css">
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

    <div id="newLoanAdd" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">User Deduction Add</h4>
                </div>
                <div class="modal-body">
                    <div>
                        <p class="alert alert-danger" id="newLoanAddErr" style="display: none;"></p>
                        <p class="alert alert-success" id="newLoanAddSucc" style="display: none;"></p>
                        <div class="form-group">
                            <label for="sel_user_addLoan">Users:</label>
                            <select id="sel_user_addLoan" class="form-control">
                                <option value="">SELECT User</option>
                                <?php
                                while ($users_res = $sel_all_user_f_l->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<option value='{$users_res['U_id']}'>{$users_res['U_name']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sel_loan_title">Loan Title:</label>
                            <input type="text" id="sel_loan_title" class="form-control"/>
                        </div>
                        <div class="form-group">
                            <label for="sel_loan_month_add">Select Month:</label>
                            <input type="text" id="sel_loan_month_add" class="form-control sel_date_m_y"/>
                        </div>
                        <div class="form-group">
                            <label for="sel_tot_amount_add">Total Amount:</label>
                            <input type="text" id="sel_tot_amount_add" class="form-control"/>
                        </div>
                        <button type="submit" class="btn btn-default green-btn" id="add_userLoan">Add</button>
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
                <?=$pagename?>
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <button class="btn btn-warning btn-sm pull-right" id="btnNewLoanAdd"><i class="fa fa-plus"></i></button>
                        <h3 class="box-title">Select User Loans</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <p class="alert alert-danger" style="display: none;" id="sel_l_res_err"></p>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <select class="form-control" title="Select User" id="sel_user">
                                                    <option value="">Select User</option>
                                                    <?php
                                                    while($users_res = $sel_all_user->fetch(PDO::FETCH_ASSOC)){
                                                        echo "<option value='{$users_res['U_id']}'>{$users_res['U_name']}</option>";
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="text" id="sel_loan_month" placeholder="Select Year Month" class="form-control sel_date_m_y"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="submit" class="btn btn-default green-btn" id="btn_s_l_res" value="Search">
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
                                    <table class="table table-bordered table-striped" id="userLoanInfoRetTable">
                                        <thead>
                                        <th>S.NO</th>
                                        <th>Loan Title</th>
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
            $("#add_userLoan").click(function(){
                var sel_user = $("#sel_user_addLoan").val();
                var sel_title = $("#sel_loan_title").val();
                var sel_month = $("#sel_loan_month_add").val();
                var sel_totAmount = $("#sel_tot_amount_add").val()*1;
                $("#sel_tot_amount_add").val(sel_totAmount);
                var thisBtn = $(this);
                var errDiv = $("#newLoanAddErr");
                var succDiv = $("#newLoanAddSucc");
                succDiv.hide();
                succDiv.html("");
                errDiv.hide();
                errDiv.html("");
                thisBtn.attr('disabled', 'disabled');
                if(sel_user != "" && sel_title != "" && sel_month != "" && sel_totAmount != 0){
                    $.ajax({
                        url: 'formData/addUserLoan.php',
                        method: 'post',
                        data: {user_id: sel_user, selTitle: sel_title, selMonth: sel_month, selTotalAmount: sel_totAmount},
                        datatype: 'json'
                    }).done(function (res) {
                        if (res.err !== "") {
                            errDiv.html(res.err);
                            errDiv.show();
                            thisBtn.removeAttr('disabled');
                        } else {
                            $("#sel_user_addLoan, #sel_loan_month_add, #sel_tot_amount_add, #sel_loan_title").val("");
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

            $("#btn_s_l_res").click(function(){
                var sel_user = $("#sel_user").val();
                var sel_month = $("#sel_loan_month").val();
                var sel_l_u_res_err = $("#sel_l_res_err");
                sel_l_u_res_err.hide();
                sel_l_u_res_err.html("");

                var resCont = $("#ret_search_res_con");
                resCont.hide();

                var resTable = $("#userLoanInfoRetTable");
                resTable.find("tbody").html("");

                if(sel_user != "" && sel_month != ""){
                    $.ajax({
                        url: 'formData/getUserLoanList.php',
                        method: 'post',
                        data: {user_id: sel_user, selMonth: sel_month},
                        datatype: 'json'
                    }).done(function(res){
                        var resCount = res.res.length;
                        if(resCount > 0){
                            var username = res.user;
                            for(var i=0; i<resCount; i++){
                                var cur_data = res.res[i];
                                resTable.find("tbody").append("<tr><td>"+(i+1)+"</td><td>"+cur_data.l_title+"</td><td>"+username+"</td><td>"+cur_data.l_amount+"</td><td>"+cur_data.l_added_at+"</td><td><button class='btn btn-default red-btn del_loan' data-lId='"+cur_data.l_id+"'><i class='fa fa-trash' aria-hidden='true'></i></button></td></tr>");
                            }
                            resCont.show();
                        }else{
                            sel_l_u_res_err.html("Result Not Found!");
                            sel_l_u_res_err.show();
                        }
                        $(function(){
                            $(".del_loan").click(function(){
                                var user_confirm_check = confirm("Are You Sure! You want to delete this item?");
                                var delLoanId = $(this).attr("data-lId");
                                var rowSel = $(this).parent().parent();
                                if(user_confirm_check == true){
                                    $.ajax({
                                        url: 'formData/delLoan.php',
                                        method: 'post',
                                        data: {loan_id: delLoanId},
                                        datatype: 'json'
                                    }).done(function(res){
                                        if(res.err != ""){
                                            alert(res.err);
                                        }else{
                                            rowSel.remove();
                                        }
                                    });
                                }
                            });
                        });
                    });
                }else{
                    sel_l_u_res_err.html("Some Fields Empty!");
                    sel_l_u_res_err.show();
                }
            });

            $("#sel_tot_amount_add").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            $("#btnNewLoanAdd").click(function(){
                $("#newLoanAddErr, #newLoanAddSucc").hide().html("");
                $("#sel_user_addLoan, #sel_loan_month_add, #sel_tot_amount_add, #sel_loan_title").val("");
                $("#add_userLoan").removeAttr("disabled");
                $("#newLoanAdd").modal("show");
            });

            $("#sel_loan_month_add").keydown(function(){
                return false;
            });

            $("#sel_loan_month").keydown(function(){
                return false;
            });

            var nowDate = new Date();
            var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);

            $("#sel_loan_month_add").datepicker({
                format: "yyyy-mm-dd",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            });

            $("#sel_loan_month").datepicker({
                format: "yyyy-mm-dd",
                viewMode: "months",
                minViewMode: "months",
                autoclose: true
            });
        });
    </script>
</body>
</html>
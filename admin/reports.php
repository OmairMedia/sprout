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

$log_email = $_SESSION['log_adm_email'];
// grab login user data;

global $grab_user_data_fet;

$grab_user_data_qur = "SELECT `Account_type`, `U_id`, `D_id`, `r_id`, `U_name`, `u_email`, `profile_img` FROM `user` WHERE `u_email`='" . $log_email . "'";
$grab_user_data_exc = mysqli_query($con, $grab_user_data_qur) or die (mysqli_error($con));
$grab_user_data_fet = mysqli_fetch_assoc($grab_user_data_exc);

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

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Users Reports
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Search Report</h3>
                    </div>
                    <!-- form start -->
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <div>
                                        <p class="alert alert-danger" id="search_err" style="display: none;"></p>
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
            <div class="box" id="show_report" style="display: none">
                <div class="box box-info">
                    <div class="box-header">
                        <a class="btn btn-default buttons-print pull-right" tabindex="0"
                           aria-controls="DataTables_Table_0" onclick="printDiv('print_area')"><span><i
                                    class="fa fa-print"></i></span></a>
                    </div><!-- /.box-header -->
                    <div id="print_area">
                        <div class="box-body">
                            <table class="table table-bordered table-striped cus_table userReportInfo">
                                <tbody>
                                <tr>
                                    <td class="tb_width">Emp.Name:</td>
                                    <td class="tb_width"><span class="emp_name"></span></td>
                                    <td class="tb_width">Emp.Department:</td>
                                    <td class="tb_width"><span class="emp_department"></span></td>
                                    <td class="tb_width">Date</td>
                                    <td class="tb_width"><span class="gen_date"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Monthly Hours</td>
                                    <td class="tb_width"><span class="monthly_hours"></span></td>
                                    <td class="tb_width">Total Working Hours</td>
                                    <td class="tb_width"><span class="total_hour"></span></td>
                                    <td class="tb_width">Rounded Hours</td>
                                    <td class="tb_width"><span class="rounded_hour"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Basic Salary</td>
                                    <td class="tb_width">Rs.<span class="basic_salary"></span></td>
                                    <td class="tb_width">Hourly Salary</td>
                                    <td class="tb_width">Rs.<span class="hourly_sal"></span></td>
                                    <td class="tb_width">Gross Pay</td>
                                    <td class="tb_width">Rs.<span class="cal_hours"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Total Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="totLoan"></span></td>
                                    <td class="tb_width">Less Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="lessLoan"></span></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Balance Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="balLoan"></span></td>
                                    <td class="tb_width">Less Exp. Entertainment</td>
                                    <td class="tb_width">Rs.<span class="lessExpEnter"></span></td>
                                    <td class="tb_width">Total Deduction</td>
                                    <td class="tb_width">Rs.<span class="totDedAmount"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width">Net Pay</td>
                                    <td class="tb_width">Rs.<span class="finalNetPay"></span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr class="cus_hr" />

                        <div class="box-body">
                            <table class="table table-bordered table-striped cus_table userReportInfo">
                                <tbody>
                                <tr>
                                    <td class="tb_width">Emp.Name:</td>
                                    <td class="tb_width"><span class="emp_name"></span></td>
                                    <td class="tb_width">Emp.Department:</td>
                                    <td class="tb_width"><span class="emp_department"></span></td>
                                    <td class="tb_width">Date</td>
                                    <td class="tb_width"><span class="gen_date"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Monthly Hours</td>
                                    <td class="tb_width"><span class="monthly_hours"></span></td>
                                    <td class="tb_width">Total Working Hours</td>
                                    <td class="tb_width"><span class="total_hour"></span></td>
                                    <td class="tb_width">Rounded Hours</td>
                                    <td class="tb_width"><span class="rounded_hour"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Basic Salary</td>
                                    <td class="tb_width">Rs.<span class="basic_salary"></span></td>
                                    <td class="tb_width">Hourly Salary</td>
                                    <td class="tb_width">Rs.<span class="hourly_sal"></span></td>
                                    <td class="tb_width">Gross Pay</td>
                                    <td class="tb_width">Rs.<span class="cal_hours"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Total Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="totLoan"></span></td>
                                    <td class="tb_width">Less Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="lessLoan"></span></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                </tr>
                                <tr>
                                    <td class="tb_width">Balance Loan/Advance</td>
                                    <td class="tb_width">Rs.<span class="balLoan"></span></td>
                                    <td class="tb_width">Less Exp. Entertainment</td>
                                    <td class="tb_width">Rs.<span class="lessExpEnter"></span></td>
                                    <td class="tb_width">Total Deduction</td>
                                    <td class="tb_width">Rs.<span class="totDedAmount"></span></td>
                                </tr>
                                <tr>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width"></td>
                                    <td class="tb_width">Net Pay</td>
                                    <td class="tb_width">Rs.<span class="finalNetPay"></span></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="box-body">
                            <table class="table table-bordered table-striped cus_table">
                                <thead>
                                <tr>
                                    <th>No#</th>
                                    <th>Date</th>
                                    <th>Check In</th>
                                    <th>Check Out</th>
                                    <th>Break Time</th>
                                    <th>Total Working Hours</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody class="retDataResult"></tbody>
                            </table>
                        </div><!-- /.box-body -->
                    </div><!--print area-->
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
    <!-- page script -->
    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });
    </script>
    <script>
        $('.mdatepicker-input').datepicker();


        // Javascript to enable link to tab
        var hash = document.location.hash;
        var prefix = "tab_";
        if (hash) {
            $('.nav-tabs a[href=' + hash.replace(prefix, "") + ']').tab('show');
        }

        // Change hash for page-reload
        $('.nav-tabs a').on('shown', function (e) {
            window.location.hash = e.target.hash.replace("#", "#" + prefix);
        });
        $('.input-daterange').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });
    </script>
    <script>

        $(function () {
            $('#search_user_att_btn').click(function(){
                var errContent = $("#search_err");
                var selYear = $("#search_sel_year").val();
                var selMonth = $("#search_sel_month").val();
                var uId = $("#search_sel_user").val();
                var showReportDiv = $("#show_report");

                errContent.hide();
                errContent.html("");

                showReportDiv.hide();
                showReportDiv.find(".retDataResult").html("");

                if(selMonth !== '' && selYear !== '' && uId !== ''){
                    var formData = {
                        user: uId,
                        month: selMonth,
                        year: selYear,
                        submit: 'submit'
                    };

                    $.ajax({
                        url: "getresults.php",
                        method: "POST",
                        data: formData,
                        datatype: "json",
                        success: function(res){
                            var retError = res.res_status;
                            if(retError != ""){
                                errContent.html(retError);
                                errContent.show();
                            }else{
                                var retInfoLenth = res.retUserInfo.length;
                                var tabIndex = 0;
                                for(var i=0; i < retInfoLenth; i++){
                                    tabIndex++;
                                    var ch_val_checkin = res.retUserInfo[i].check_in;
                                    var ch_val_checkout = res.retUserInfo[i].check_out;
                                    var tot_working_hours = (res.retUserInfo[i].tot_working_hours == "00:00:00") ? "<span class='text-red'>"+res.retUserInfo[i].tot_working_hours+"</span>":res.retUserInfo[i].tot_working_hours;
                                    var userRemarks = res.retUserInfo[i].user_remarks;

                                    if(ch_val_checkin == 'auto'){
                                        ch_val_checkin = '<span class="text-red">'+ch_val_checkin+'</span>';
                                    }
                                    if(ch_val_checkout == 'auto'){
                                        ch_val_checkout = '<span class="text-red">'+ch_val_checkout+'</span>';
                                    }else if(ch_val_checkout == ''){
                                        ch_val_checkout = '<span style="color:green;">Present</span>';
                                    }

                                    if(userRemarks == "Sunday" || userRemarks == "Auto Absent"){
                                        userRemarks = '<span class="text-red">'+userRemarks+"</span>";
                                    }


                                    var _html = '<tr>';
                                    _html += '<td>' + tabIndex + '</td>';
                                    _html += '<td>' + res.retUserInfo[i].date + '</td>';
                                    _html += '<td>' + ch_val_checkin + '</td>';
                                    _html += '<td class="chk_out">' + ch_val_checkout + '</td>';
                                    _html += '<td>' + res.retUserInfo[i].break_time + '</td>';
                                    _html += '<td>' + tot_working_hours + '</td>';
                                    _html += '<td>' + userRemarks + '</td>';
                                    _html += '</tr>';

                                    showReportDiv.find(".retDataResult").append(_html);
                                    showReportDiv.show();
                                }
                                showReportDiv.find(".userReportInfo .emp_name").html(res.retUserReport[0].userName);
                                showReportDiv.find(".userReportInfo .emp_department").html(res.retUserReport[0].userDepartment);
                                showReportDiv.find(".userReportInfo .gen_date").html(res.retUserReport[0].genReportTime);
                                showReportDiv.find(".userReportInfo .total_hour").html(res.retUserReport[0].genTime);
                                showReportDiv.find(".userReportInfo .rounded_hour").html(res.retUserReport[0].roundHours);
                                showReportDiv.find(".userReportInfo .monthly_hours").html(res.retUserReport[0].monthlyHours);
                                showReportDiv.find(".userReportInfo .basic_salary").html(res.retUserReport[0].basicSalary);
                                showReportDiv.find(".userReportInfo .hourly_sal").html(res.retUserReport[0].hourlySalary);
                                showReportDiv.find(".userReportInfo .cal_hours").html(res.retUserReport[0].salaryGenerate);
                                showReportDiv.find(".userReportInfo .totLoan").html(res.retUserReport[0].userTotLoan);
                                showReportDiv.find(".userReportInfo .lessLoan").html(res.retUserReport[0].userLessLoan);
                                showReportDiv.find(".userReportInfo .lessExpEnter").html(res.retUserReport[0].userLessExpDed);
                                showReportDiv.find(".userReportInfo .totDedAmount").html(res.retUserReport[0].userTotDed);
                                showReportDiv.find(".userReportInfo .finalNetPay").html(res.retUserReport[0].userNetPay);
                                showReportDiv.find(".userReportInfo .balLoan").html(res.retUserReport[0].balLoan);
                            }
                        }
                    });
                }else{
                    errContent.html("Some Fields Empty!");
                    errContent.show();
                }

            });
        });
    </script>
    <script>
        function printDiv(print_area) {
            var printContents = document.getElementById('print_area').innerHTML;

            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
            window.location.reload();
        }
    </script>
</body>
</html>
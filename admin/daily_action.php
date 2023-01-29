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

/*$tomDate = (date("d")*1)-1;
$genDate = date("Y-m-d", mktime(0,0,0,date("m"),$tomDate,date("Y")));

if($tomDate == 0){
    $prevMonth = (date("m")*1)-1;
    $year = date("Y");
    $genDate = date("Y-m-d", mktime(0,0,0,$prevMonth,cal_days_in_month(CAL_GREGORIAN,$prevMonth,$year),$year));
    if($prevMonth == 0){
        $prevYear = (date("Y")*1)-1;
        $genDate = date("Y-m-d", mktime(0,0,0,12,cal_days_in_month(CAL_GREGORIAN,12,$prevYear),$prevYear));
    }
}*/

//$sel_auto_userAtt = $pdo_con->prepare("SELECT * FROM `user_attendance` WHERE `swipein_time`!='auto' AND`checkout_time`='auto'  ORDER BY `date` DESC");
$sel_auto_userAtt = $pdo_con->prepare("SELECT * FROM `user_attendance` WHERE `checkout_time`='auto' AND `total_working_hour`='00:00:00' AND `total_office_hours`='00:00:00' AND `user_remarks`='FCO'  ORDER BY `date` DESC");

//AND `total_working_hour`=''
$sel_auto_userAtt->execute();
$userAttCountRows = $sel_auto_userAtt->rowCount();

function userNameGetById($userId){
    global $pdo_con;
    $sel_user = $pdo_con->prepare("SELECT `U_name` FROM `user` WHERE `U_id`=?");
    $sel_user->bindValue(1, $userId);
    $sel_user->execute();
    $fetRow = $sel_user->fetch(PDO::FETCH_ASSOC);
    return $fetRow['U_name'];
}

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

    <div id="editAttModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Set Working Hours</h4>
                </div>
                <div class="modal-body">
                    <p id="editAttErr" class="alert alert-danger" style="display: none;"></p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="grabcheckouttime">Set Checkout Time :</label>
                                <input type="time" class="form-control" id="grabcheckouttime" min="18:00" max="23:00" required/>
                            </div>
                            <div class="form-group">
                                <label for="grabbreakhours">Set Break Time :</label>
                                <input type="text" class="form-control" id="grabbreakhours" required value="00:00:00"/>
                            </div>
                            <div class="form-group">
                                <label for="userRemarks">User Remarks</label>
                                <textarea id="userRemarks" class="form-control resize_hor_none"></textarea>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default green-btn" id="subEditAttBtn">Submit</button>
                                <input type="hidden" id="editAttIdInput" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default red-btn" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>



    <div id="autoAbsentAttModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" >&times;</button>
                    <h4 class="modal-title">Auto Absent Generate</h4>
                </div>
                <div class="modal-body">
                    <p id="genAutoAttErr" class="alert alert-danger" style="display: none;"></p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selAutoYear">Select Year</label>
                                <select id="selAutoYear" class="form-control">
                                    <option value="">Select Year</option>
                                    <?php
                                    foreach($check_date_arr as $s_y){
                                        echo "<option value='{$s_y}'>{$s_y}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="selAutoMonth">Select Month</label>
                                <select id="selAutoMonth" class="form-control">
                                    <option value="">Select Month</option>
                                    <?php
                                    foreach($sel_allMonths as $this_key => $s_m){
                                        echo "<option value='{$this_key}'>{$s_m}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <button class="btn btn-default green-btn btn-sm" id="genAutoSubBtn" >Generate</button>
                            </div>
                        </div>
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
                Attendence Requests
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?=$pagename?></h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <div class="row">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="info-box">
                                <div class="box-body">
                                    <?php
                                    if($userAttCountRows > 0){
                                        ?>
                                        <table class="table table-bordered table-striped" id="userResForm">
                                            <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Check In</th>
                                                <th>Check Out</th>
                                                <th>Break</th>
                                                <th>Total Working Hours</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $sNo = 0;
                                            while($userRes = $sel_auto_userAtt->fetch(PDO::FETCH_ASSOC)){
                                                $sNo++;
                                                ?>
                                                <tr>
                                                    <td><?=$sNo?></td>
                                                    <td><?=userNameGetById($userRes['uid'])?></td>
                                                    <td><?=$userRes['date']?></td>
                                                    <td><?=($userRes['swipein_time'] == 'auto') ? "<span class='text-red'>{$userRes['swipein_time']}</span>":$userRes['swipein_time']?></td>
                                                    <td><?=($userRes['checkout_time'] == 'auto') ? "<span class='text-red'>{$userRes['checkout_time']}</span>":$userRes['checkout_time']?></td>
                                                    <td><?=($userRes['total_break_hours'] == '') ? "<span class='text-red'>00:00:00</span>":$userRes['total_break_hours']?></td>
                                                    <td><?=($userRes['total_working_hour'] == '') ? "<span class='text-red'>00:00:00</span>":$userRes['total_working_hour']?></td>
                                                    <td><?=$userRes['user_remarks']?></td>
                                                    <td>
                                                        <button class="btn btn-default green-btn btn-sm btnEditEntry" data-GrabId="<?=$userRes['id']?>">Edit</button>
                                                        <button class="btn btn-danger btn-sm btnDeleteEntry" data-GrabId="<?=$userRes['id']?>">Delete</button>
                                                    </td>
                                                </tr>

                                                <?php
                                            }
                                            ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    }else{
                                        ?>
                                        <h3 class="text-warning">No Rows Found!</h3>
                                        <?php
                                    }
                                    ?>
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
        function callEditButton(){
            jQuery(".btnEditEntry").click(function(){
                var grabRowId = jQuery(this).attr("data-GrabId");
                jQuery("#editAttModal").modal("show");
                jQuery("#editAttIdInput").val(grabRowId);
            });
        }
        /*jQuery("#userResForm").DataTable({
            "drawCallback": function(settings) {
                callEditButton();
            }
        });*/
        jQuery(function(){
            callEditButton();

            jQuery("#genAutoSubBtn").click(function(){
                var yearSel = jQuery("#selAutoYear").val();
                var monthSel = jQuery("#selAutoMonth").val();
                var autoErrSel = jQuery("#genAutoAttErr");
                var autoModelSel = jQuery("#autoAbsentAttModal");
                var thisBtn = jQuery(this);

                thisBtn.attr("disabled", "disabled");
                autoErrSel.hide();
                autoErrSel.html("");
                if(yearSel != "" && monthSel != ""){
                    jQuery.ajax({
                        url: "formData/autoAbsentGenerate.php",
                        method: 'post',
                        data: {selYear: yearSel, selMonth: monthSel},
                        datatype: 'json'
                    }).done(function(res){
                        if(res.err != ""){
                            autoErrSel.html(res.err);
                            autoErrSel.show();
                        }else{
                            location.reload();
                        }
                        thisBtn.removeAttr("disabled");
                    });
                }else{
                    thisBtn.removeAttr("disabled");
                    autoErrSel.html("Some Field Empty!");
                    autoErrSel.show();
                }
            });
            jQuery("#btnAutoGenOpen").click(function(){
                var modalGrab = jQuery("#autoAbsentAttModal");
                modalGrab.modal("show");
            });
            jQuery("#setHoursInput, #setMinutesInput").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            jQuery("#subEditAttBtn").click(function(){
                var grabModal = jQuery("#editAttModal");
                var errAlertGrab = jQuery("#editAttErr");
                var grabEId = jQuery("#editAttIdInput").val();
                var grabESetHours = jQuery("#setHoursInput").val();
                var grabESetMinutes = jQuery("#setMinutesInput").val();
                var grabCheckout = jQuery("#grabcheckouttime").val();               
                var grabbreak =jQuery("#grabbreakhours").val();
                var grabUserremarks = jQuery("#userRemarks").val();
                //Empty Error Or Hide
                // errAlertGrab.hide();
                // errAlertGrab.html("");

                if(grabEId !== "" && grabCheckout !== "" && grabbreak !== "" && grabUserremarks !== ""){
                   // grabESetMinutes = grabESetMinutes*1;
                   // grabESetHours = grabESetHours*1;

                   /* if(grabESetMinutes > 59){
                        errAlertGrab.html("Set minutes less than 60!");
                        errAlertGrab.show();
                    }else{*/
                        jQuery.ajax({
                            url: 'formData/autoEditUser.php',
                            method: 'post',
                            data: {att_id: grabEId,grabcheckout:grabCheckout,grabbreak:grabbreak, user_remarks: grabUserremarks},
                            datatype: 'json'
                        }).done(function(res){
                            console.log('res -> ',res);
                            alert(res);
                            // alert(res);
                            // grabModal.modal("hide");
                          
                            // if(res.err !== ""){
                            //     errAlertGrab.html(res.err);
                            //     errAlertGrab.show();
                            // }else{
                            //     grabModal.modal("hide");
                            //     location.reload();
                            // }
                        });
                    
                }else{
                    errAlertGrab.html("Please Fill All Fields!");
                    errAlertGrab.show();
                }
            });
            jQuery(".btnDeleteEntry").click(function(){
                var grabRowId = jQuery(this).attr("data-GrabId");
                jQuery.ajax({
                            url: 'formData/deleteDailyEntry.php',
                            method: 'post',
                            data: {
                                att_id: grabRowId
                            },
                        }).done(function(res){
                            alert(res);
                            // location.reload();
                            // location.reload();
                        });
            })
        });
    </script>
</body>
</html>
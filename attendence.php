<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}


include 'db.php';
session_set_cookie_params(86400, "/");
session_start();

if (!isset($_SESSION['U_name'])) {
    header('location:login.php');
}


function addArrTimeStr($strArr)
{
    if (!empty($strArr)) {
        if (strpos($strArr, "|")) {
            $exp_mul_time = explode("|", $strArr);

            $time1 = new DateTime($exp_mul_time[0]);
            for ($i = 1; $i < sizeof($exp_mul_time); $i++) {

                $sel_time_exp = explode(":", $exp_mul_time[$i]);

                $s_h = $sel_time_exp[0];
                $s_h = (($s_h * 1) > 0) ? ($s_h * 1) . "H" : "";

                $s_i = $sel_time_exp[1];
                $s_i = (($s_i * 1) > 0) ? ($s_i * 1) . "M" : "";

                $s_s = $sel_time_exp[2];
                $s_s = ($s_s * 1) . "S";

                $time1->add(new DateInterval("PT" . $s_h . $s_i . $s_s));
            }
            return $time1->format("H:i:s");
        } else {
            return $strArr;
        }
    }
    return $strArr;
}

function subCurrentTime($strStartTime, $strStopTime = null)
{
    $time1 = new DateTime($strStartTime);
    $newDate = $strStopTime ? new DateTime($strStopTime) : new DateTime();
    $time2 = new DateTime($newDate->format("H:i:s"));

    $interval = $time2->diff($time1);

    $h = $interval->h;
    $h = (strlen($h) > 1) ? $h : "0" . $h;

    $i = $interval->i;
    $i = (strlen($i) > 1) ? $i : "0" . $i;

    $s = $interval->s;
    $s = (strlen($s) > 1) ? $s : "0" . $s;

    return $h . ":" . $i . ":" . $s;
}

function allTotTime($timeArr)
{
    $total_hours = 0;
    $total_mins = 0;
    $total_secs = 0;
    foreach ($timeArr as $sinTime) {
        $exp_cur_working_time = explode(":", $sinTime);

        $total_hours += $exp_cur_working_time[0] * 1;
        $total_mins += $exp_cur_working_time[1] * 1;
        $total_secs += $exp_cur_working_time[2] * 1;
    }
    $gen_secs = $total_secs % 60;
    $gen_mins = ($total_mins + intval($total_secs / 60)) % 60;
    $gen_hours = $total_hours + intval(($total_mins + intval($total_secs / 60)) / 60);

    $gen_secs = (strlen($gen_secs) < 2) ? "0" . $gen_secs : $gen_secs;
    $gen_mins = (strlen($gen_mins) < 2) ? "0" . $gen_mins : $gen_mins;
    $gen_hours = (strlen($gen_hours) < 2) ? "0" . $gen_hours : $gen_hours;

    return $gen_hours . ":" . $gen_mins . ":" . $gen_secs;
}

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

function monthTotalHours(){
    $month = date("m");
    $year = date("Y");
    $totalWorkingDays = genWorkingDays($month, $year, "Sunday");
    $totalMonthlyHours = round($totalWorkingDays*8);
    return $totalMonthlyHours;
}


//NEW FUNCTIONALITY *************
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

<!DOCTYPE html>
<html>
<?php
include('header.php'); ?>

<body>
<!-- header logo: style can be found in header.less -->
<?php include('content.php'); ?>


<div class="container">
    <!--THIS MONTH ATTENDENCE TABLE -->
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>PRESENT ATTENDENCE</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 zero-padd">
            <div class="panel" style="height: auto">
                <div class="panel-body table-responsive"
                     style="padding: 0px;box-shadow: 7px 9px 5px 0px rgba(0,0,0,0.13);">
                    <div>
                        <table class="table table-hover">
                            <tbody>
                            <tr style="background-color: #90b833; color: white" id="first-row">
                                <th>Sr #</th>
                                <th>Date</th>
                                <th>Check-in Time</th>
                                <th>Check-out Time</th>
                                <th>Total Office Hours</th>
                                <th>Break Hours</th>
                                <th>Total Working Hours</th>
                                <th>Remarks</th>
                                <th>Actions</th>
                                
                            </tr>
                            <?php
                            $curYM = date("Y-m");
                            $query = "select a.*,u.`U_name`,u.`is_status` from `user_attendance` a,`user` u where a.`uid`=u.`U_id` and u.`U_id`='{$_SESSION['U_id']}' and a.`date` like '{$curYM}%' ORDER BY a.`date` DESC";
                            $query_run = mysqli_query($con, $query) or die (mysqli_error($con));

                            date_default_timezone_set("Asia/Karachi");

                            $total_hours = 0;
                            $total_mins = 0;
                            $total_secs = 0;

                            $collectTimes = [
                                'totOfficeH' => [],
                                'totBreakH' => [],
                                'totWorkH' => []
                            ];

                            $user_inc = 0;

                            while ($row = mysqli_fetch_assoc($query_run)) {
                                $currentMonth = date("m");
                                $thisMontnDateCheck = date("m", strtotime($row['date']));
                                if ($currentMonth == $thisMontnDateCheck) {

                                    $user_inc++;

                                    $totBreakTime = "00:00:00";
                                    $countBreak = $row['break_count'];

                                    if (!empty($countBreak)) {
                                        if (!empty($row['breakin_time']) && empty($row['breakout_time'])) {
                                            $totBreakTime = addArrTimeStr(addArrTimeStr($countBreak) . "|" . subCurrentTime($row['breakin_time']));
                                        } else {
                                            $totBreakTime = addArrTimeStr($countBreak);
                                        }
                                    }

                                    if (!empty($row['breakin_time']) && empty($row['breakout_time']) && empty($countBreak)) {
                                        $totBreakTime = subCurrentTime($row['breakin_time']);
                                    }

                                    $totOfficTime = !empty($row['total_office_hours']) ? $row['total_office_hours'] : "00:00:00";
                                    if (empty($row['checkout_time'])) {
                                        $totOfficTime = subCurrentTime($row['swipein_time']);
                                    }

                                    $totWorkingTime = !empty($row['total_working_hour']) ? $row['total_working_hour'] : "00:00:00";
                                    if (empty($row['checkout_time'])) {
                                        $totWorkingTime = subCurrentTime(subCurrentTime($row['swipein_time']), $totBreakTime);
                                    }

                                    //here all month total time plus

                                    array_push($collectTimes['totOfficeH'], $totOfficTime);
                                    array_push($collectTimes['totBreakH'], $totBreakTime);
                                    array_push($collectTimes['totWorkH'], $totWorkingTime);

                                    ?>
                                    <tr>
                                        <td><?= $user_inc ?></td>
                                        <td><?= $row['date'] ?></td>
                                        <td><?= $row['swipein_time'] ?></td>
                                        <td><?= $row['checkout_time'] ?></td>
                                        <td><?= $totOfficTime ?></td>
                                        <td><?= $totBreakTime ?></td>
                                        <td><?= $totWorkingTime ?></td>
                                        <td><?= $row['user_remarks'] ?></td>
                                        <td>
                                            <?php  if($row['user_remarks'] !== 'Absent'){  ?>
                                             <a class="btn btn-default btn-sm breakbtn" data-toggle="modal" data-target="#RequestBreak" data-userid="<?=$row['uid']?>" data-date="<?= $row['date'] ?>">Forgot Break ?</a>
                                             <?php } ?>
                                             
                                             <?php if($row['user_remarks'] !== 'Sunday'){ ?>
                                             <a class="btn btn-default btn-sm reqbtn" data-toggle="modal" data-target="#Checkoutrequest" data-userid="<?=$row['uid']?>" data-date="<?= $row['date'] ?>" data-grabCheckin="<?= $row['swipein_time'] ?>" data-grabCheckout="<?= $row['checkout_time'] ?>" data-totalBreakhours="<?= $row['totBreakTime'] ?>">Request</a>
                                             <?php }?>

                                             <?php if($row['user_remarks'] === 'Absent'){ ?>
                                             <a class="btn btn-default btn-sm leaverequestbtn" data-toggle="modal" data-target="#Leaverequest" data-userid="<?=$row['uid']?>" data-date="<?= $row['date'] ?>">Leave</a>
                                             <?php }?>

                                             
                                        </td>
                                         
                                    </tr>
                                    <?php
                                } else {
                                    continue;
                                }

                            }
                            ?>


                            </tbody>
                            <tfoot>
                            <tr>
                                <?php
                                $AllTotBreakTime = allTotTime($collectTimes['totBreakH']);
                                $AllTotWorkTime = allTotTime($collectTimes['totWorkH'])." / ".monthTotalHours()." H";
                                $AllTotOfficeTime = allTotTime($collectTimes['totOfficeH']);
                                ?>
                                <td colspan="5"></td>
                                <td><h4>Total:</h4></td>
                                <td><h4><?=$AllTotWorkTime?></h4></td>
                    
                               
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    
    <!--SELECT YEAR , MONTH ,  PREVIOUS ATTENDENCE RECORD -->
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>PREVIOUS ATTENDENCE</h2>
        </div>
    </div>
    <div class="row">
        <section class="content">
            <div class="box">
                <div class="box box-info">
                    <div class="box-header with-border">
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
                                                <label for="sel_user">User</label>
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
                <div class="total-stats" style="padding:1rem;"></div>
            </div>
        </section>
    </div>
    
</div>
<!--Request Break Model-->
<div class="modal fade" id="RequestBreak" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Your Break Time</h4>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5>Date : <span class="dateforbreak"></span></h5>
                        <h5>Checkin : <span class="currentcheckin"></span></h5>
                        <h5>Checkout : <span class="currentcheckout"></span></h5>
                        <h5>Break Hours : <span class="currentbreakhours"></span></h5>
                        <h5>Working Hours : <span class="currentworkinghours"></span></h5>
                        <h5>Total Office Hours : <span class="totalofficehours"></span></h5>
                        <hr/>
                    </div>
                    <div class="form-group">
                        <label for="breaktime">Enter Break Time</label>
                        <input class="form-control input-time" type="text" name="breaktime" id="grabbreak" placeholder="HH:MM:SS"/>
                    </div>
                    <div class="form-group">
                        <label for="breakremarks">Remarks</label>
                        <input class="form-control input-time" type="text" name="breakremarks" id="grabbreakremarks"/>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary reqbreakbtn"  name="reqsend" id="sendbrk">Send</button>
                        <button type="button" class="btn btn-default closebreakbtn"  data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>

<!--Checkout Request Model-->
<div class="modal fade" id="Checkoutrequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Your Checkout Time </h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5>Date : <span class="dateforbreak"></span></h5>
                        <h5>Current Checkin : <span class="checkinuser"></span></h5>
                        <h5>Current Breakhours : <span class="currentbreakhours"></span></h5>
                        <h5>Current Checkout : <span class="currentcheckout"></span></h5>
                     </div>
                     <div class="form-group">
                        <label for="checkin-time">Checkin :</label>
                        <input class="form-control checkintime" type="time" name="checkin-time" id="grabcheckin" required/>
                    </div>
                    <div class="form-group">
                        <label for="grabbreakhours">Total Break Time :</label>
                        <input type="text" class="form-control grabbreakhours" name="grabbreakhours" required value="00:00:00"/>
                    </div>
                    <div class="form-group">
                        <label for="checkout-time">Checkout Time:</label>
                        <input class="form-control checkouttime" type="time" name="checkout-time" id="grabcheckout" required/>
                    </div>
                    <div class="form-group">
                        <label for="remarks">Enter Remarks :</label>
                        <input class="form-control" type="text" name="userremarks" id="grabremarks" placeholder="Forget Checkout...etc"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary reqcheckbtn"  name="checkoutrequestbtn" id="sendchkout">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!--Leave Request Model-->
<div class="modal fade" id="Leaverequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Your Leave Request</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5>Date : <span class="dateforleave"></span></h5>   
                     </div>
                    
                    <div class="form-group">
                        <label for="leaveRemarks">Enter Remarks :</label>
                        <input class="form-control" type="text" name="leaveRemarks" id="leaveRemarks" placeholder="Sick...etc"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary reqleavebtn"  name="sendleaverequest" id="sendleaverequest">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<!--SCRIPTS -->
<script type="text/javascript">
var cleave = new Cleave('.grabbreakhours', {
    time: true,
    timePattern: ['h', 'm', 's']
});


var cleave1 = new Cleave('.input-time', {
    time: true,
    timePattern: ['h', 'm', 's']
});
</script>
<script type="text/javascript">
   jQuery(function(){
            jQuery("#setHoursInput, #setMinutesInput").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
            jQuery("#search_user_att_btn").click(function(){
                try {
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
                                var totalWorkingHoursCombined = [];
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
                                    totalWorkingHoursCombined.push(singleRetInfo.tot_working_hours);

                                    search_res_sel.find("#resTable .table_res").append("<tr><td>"+(incLoop+1)+"</td><td>"+singleRetInfo.date+"</td><td>"+check_in+"</td><td>"+check_out+"</td><td>"+singleRetInfo.break_time+"</td><td>"+tot_w_hours+"</td><td>"+singleRetInfo.user_remarks+"</td></tr>");
                                    


                                }

                            
                                const sum = totalWorkingHoursCombined.reduce((accumulator, value) => {
                                    let initialHours = parseInt(accumulator.split(':')[0]);
                                    let initialMinutes = parseInt(accumulator.split(':')[1]);
                                    let initialSeconds = parseInt(accumulator.split(':')[2]);

                                    let valueHours = parseInt(value.split(':')[0]);
                                    let valueMinutes = parseInt(value.split(':')[1]);
                                    let valueSeconds = parseInt(value.split(':')[2]);

                                    // let addHours = initialHours + valueHours;
                                    // let addMinutes = (initialMinutes + valueMinutes) > 60 ? addHours + 1 : (initialMinutes + valueMinutes);
                                    // let addSeconds = (initialSeconds + valueSeconds) > 60 ? addMinutes + 1 : initialSeconds + valueSeconds;
                                    // let seconds = 
                                    // let hours = valueHours;
                                    // let hoursFromMinutes = Math.abs(valueMinutes / 60);
                                    // let hoursFromSecond = Math.abs(valueSeconds / 60 / 1000);

                                    let afterAddition = `${initialHours + valueHours}:${initialMinutes + valueMinutes}:${initialSeconds + valueSeconds}`;
                                    return afterAddition;
                                }, "00:00:00");

                                if(sum) {
                                   let totalInSeconds = 0; 
                                   let hours = parseInt(sum.split(':')[0]);
                                   let minutes = parseInt(sum.split(':')[1]);
                                   let seconds = parseInt(sum.split(':')[2]);
                                   
                                   secondsFromHour = Math.abs((hours * 60) * 60);
                                   secondsFromMinutes = minutes * 60;
                                   secondsFromSeconds = seconds;
                                   
                                   totalInSeconds = Math.abs(secondsFromHour + secondsFromMinutes + secondsFromSeconds)
                                   console.log('total in seconds -> ',totalInSeconds);

                                   totalInHours = Math.ceil(totalInSeconds / 60 / 60);
                                   // console.log('total in hours -> ', totalInHours.toFixed(2));
                                   console.log('totalInHours -> ',totalInHours);

                                   search_res_sel.find(".total-stats").html(`<div class='card'><h2>Total:${totalInHours}/208 H</h2></div>`);
                                }

                                console.log('sum -> ',sum);
                                search_res_sel.show();
                            }
                            
                        }
                    });

                }else{
                    search_err_sel.show();
                    search_err_sel.html("Select All Fields");
                }
                } catch (err) {
                    console.log('err -> ',err);
                }
            });
            jQuery("#sel_whours_ne").keypress(function(e){
                if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                    return false;
                }
            });
        });
</script>
<script type="text/javascript">
    jQuery(function(){
       jQuery(".breakbtn").click(function(){
           
           var requestdate =jQuery(this).attr("data-date");
           var dateGrab = jQuery(this).parent().parent().find("td:eq(1)").html();
           var checkingrab = jQuery(this).parent().parent().find("td:eq(2)").html();
           var checkoutgrab = jQuery(this).parent().parent().find("td:eq(3)").html();
           var breakhoursgrab = jQuery(this).parent().parent().find("td:eq(5)").html();
           var workinghoursgrab = jQuery(this).parent().parent().find("td:eq(6)").html();
           var grabModal = jQuery("#RequestBreak");
           grabModal.find(".modal-body").find(".dateforbreak").html(dateGrab);

           //VALIDATION TIME 
           var totalofficehours = jQuery(this).parent().parent().find("td:eq(4)").html();
           grabModal.find(".modal-body").find(".totalofficehours").html(totalofficehours);

           var momenttoh = moment(totalofficehours,"hh:mm:ss");
           
           grabModal.find(".modal-body").find(".currentcheckin").html(checkingrab);
           grabModal.find(".modal-body").find(".currentbreakhours").html(breakhoursgrab);
           grabModal.find(".modal-body").find(".currentworkinghours").html(workinghoursgrab);
           grabModal.find(".modal-body").find(".currentcheckout").html(checkoutgrab);
           
           let data = {
            checkingrab,
            checkoutgrab,
            breakhoursgrab,
            workinghoursgrab
           };

           console.log('data -> ',data);
       });
       //BREAK REQUEST AJAX 
       jQuery('#sendbrk').click(function(){
                var grab_date = jQuery(".dateforbreak").text();
                var grab_ohours = jQuery(".totalofficehours").text();
                var momenttoh = moment(grab_ohours,"HH:mm:ss");
                console.log(`Total Working Hours Of That Day = ${momenttoh.format("HH:mm:ss")} `);
                var grab_break = jQuery("#grabbreak").val();
                var grab_remarks = jQuery("#grabbreakremarks").val();
                var momentbrk =moment(grab_break,"HH:mm:ss");
                console.log(`Your Requested BreakTime = ${momentbrk.format("HH:mm:ss")} `);

                var checkingrab = jQuery(".currentcheckin").text();
                var checkoutgrab = jQuery(".currentcheckout").text();
                var breakhoursgrab = jQuery(".currentbreakhours").text();
                var workinghoursgrab = jQuery(".currentworkinghours").text();


                console.log('grab_remarks -> ',grab_remarks);   

                if(momentbrk > momenttoh)
                {
                    console.log("Requested Hours Exceeded Your Total Office Hours");
                } else{ 
                    var dataString_break = 'breakrequest';
                    jQuery.ajax({
                        type: "POST",
                        url: "Request.php",
                        data: { 
                            data:dataString_break,
                            grab_date:grab_date,
                            grab_break:grab_break,
                            grab_remarks: grab_remarks,
                            checkingrab: checkingrab,
                            checkoutgrab: checkoutgrab,
                            breakhoursgrab: breakhoursgrab,
                            workinghoursgrab: workinghoursgrab
                        },
                        cache: true,
                        success: function(html)
                        {
                            
                        }
                    })
                }
                
        });
       
       jQuery(".reqbtn").click(function(){
           //var requestdate =jQuery(this).attr("data-date");
           var dateGrab = jQuery(this).parent().parent().find("td:eq(1)").html();
           var currentcheckingrab = jQuery(this).parent().parent().find("td:eq(2)").html();
           var currentcheckoutgrab = jQuery(this).parent().parent().find("td:eq(3)").html();
           var breakhoursgrab = jQuery(this).parent().parent().find("td:eq(5)").html();
           var workinghoursgrab = jQuery(this).parent().parent().find("td:eq(6)").html();
           var grabModal = jQuery("#Checkoutrequest");

        //    var checkin = jQuery(this).attr("data-grabCheckin");
        //    var breakin = jQuery(this).attr("data-breakIn");
        //    var totbreakhours = jQuery(this).attr("data-totalBreakhours");
        //    var checkoutuser = jQuery(this).attr("data-grabCheckout");

           grabModal.find(".modal-body").find(".dateforbreak").html(dateGrab);
           grabModal.find(".modal-body").find(".checkinuser").html(currentcheckingrab);
           grabModal.find(".modal-body").find(".currentbreakhours").html(breakhoursgrab);
           grabModal.find(".modal-body").find(".currentcheckout").html(currentcheckoutgrab);

           if(currentcheckingrab) {
               const mcheckin = moment(currentcheckingrab, "hh:mm:ss a").format("HH:mm");
               jQuery("#grabcheckin").val(mcheckin);
           }

           if(currentcheckoutgrab) {
               const mcheckout = moment(currentcheckoutgrab, "hh:mm:ss a").format("HH:mm");
               jQuery("#grabcheckout").val(mcheckout);
           }
          

           let data = {
            currentcheckingrab,
            currentcheckoutgrab,
            breakhoursgrab,
            workinghoursgrab
           };

           console.log('data -> ',data);
           
       });
        jQuery('#sendchkout').click(function(){  
                var grab_date = jQuery(".dateforbreak").text();
                var grab_checkin = jQuery("#grabcheckin").val();
                var grab_break = jQuery(".grabbreakhours").val();
                var grab_checkout = jQuery("#grabcheckout").val();
                var grab_remarks = jQuery("#grabremarks").val();
                
                const currentCheckin = jQuery(".checkinuser").text();
                const currentBreakhours = jQuery(".currentbreakhours").text();
                const currentCheckout = jQuery(".currentcheckout").text();

                let mcheckin;
                let mcheckout;

                if(grab_checkin) {
                    mcheckin = moment(grab_checkin, "HH:mm").format("hh:mm:ss a");
                }

                if(grab_checkout) {
                    mcheckout = moment(grab_checkout, "HH:mm").format("hh:mm:ss a");
                }

                console.log('mcheckin -> ',mcheckin);
                console.log('mcheckout -> ',mcheckout);

                console.log('data -> ',{
                        grab_date:grab_date,
                        grab_break:grab_break,
                        grab_checkout:mcheckout,
                        grab_remarks:grab_remarks,
                        checkintime:mcheckin, 
                        currentCheckin:currentCheckin, 
                        currentBreakhours:currentBreakhours, 
                        currentCheckout:currentCheckout
                });

                
                
              

               

                var dataString_break = 'request';

                if( grab_checkout !=='' && grab_break !=='' && grab_remarks !=='')
                {
                    jQuery.ajax({
                    type: "POST",
                    url: "CheckoutReq.php",
                    data: {
                        data:dataString_break,
                        grab_date:grab_date,
                        grab_break:grab_break,
                        grab_checkout:grab_checkout,
                        grab_remarks:grab_remarks,
                        checkintime:mcheckin, 
                        currentCheckin:currentCheckin, 
                        currentBreakhours:currentBreakhours, 
                        currentCheckout:currentCheckout
                    },
                    success:function(html){
                        alert(html);
					}
                  });
                }else{
                    Swal.fire("Please Fill in All The Fields");   
                }                
              
        }); 
        
    //    Leave Request
       jQuery(".leaverequestbtn").click(function(){
          
           var dateGrab = jQuery(this).parent().parent().find("td:eq(1)").html();   
           var grabModal = jQuery("#Leaverequest");

           grabModal.find(".modal-body").find(".dateforleave").html(dateGrab);
           
       });
    
       jQuery('#sendleaverequest').click(function(){  
                var grab_date = jQuery(".dateforleave").text();
                var grab_remarks = jQuery("#leaveRemarks").val();
                
                let data =  {
                        grab_date:grab_date,
                        grab_remarks:grab_remarks,
                }

                console.log('data -> ',data);

                

                if(grab_remarks !=='')
                {
                    jQuery.ajax({
                    type: "POST",
                    url: "LeaveReq.php",
                    data: {
                        grab_date:grab_date,
                        grab_remarks:grab_remarks,
                    },
                    success:function(html){
                        alert(html);
					}
                  });
                }else{
                    Swal.fire("Please Fill in All The Fields");   
                }                
              
        }); 
    });
</script>


<?php include('footer.php'); ?>

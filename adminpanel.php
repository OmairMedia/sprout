<?php

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
<head>

<script src="./js/adminpanelFunctionality.js"></script>

</head>
<body>
<!-- header logo: style can be found in header.less -->
<?php include('content.php'); ?>
<div class="container">
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>ATTENDENCE SUMMARY</h2>
            <button onclick="location.reload()">Refresh</button>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 zero-padd">
            <div class="panel" style="height:auto;">
                <div class="panel-body table-responsive "
                     style="padding: 0px;box-shadow: 7px 9px 5px 0px rgba(0,0,0,0.13);">
                    <div>
     
                        <table class="table table-bordered " id="table" style="padding:0rem;">
                            <thead>
                                 <tr style="background-color: #90b833; color: white" id="first-row">
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Account_Type</th>
                                <th>Checkin</th>
                                <th>Breakin</th>
                                <th>Breakout</th>
                                <th>Total Break</th>
                                <th>Checkout</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                           
                            <?php
                            $now =date("Y-m-d");
                            $query = "Select * from `user` as u JOIN `user_attendance` as a on u.U_id = a.uid where `Active`='active' AND `date`='".$now."' ORDER BY `uid`";
                            $query_run = mysqli_query($con, $query) or die (mysqli_error($con));
                            while ($row = mysqli_fetch_assoc($query_run)) {
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
                                    if(empty($countBreak))
                                    {
                                        $totBreakTime = null;
                                    }
                                    ?>
                                    <tr>
                                        <td><?= $row['U_id'] ?></td>
                                        <td><?= $row['U_name'] ?></td>
                                        <td><?= $row['Account_type'] ?></td>
                                        <td><?= $row['swipein_time'] ?></td>
                                        <td><?= $row['breakin_time'] ?></td>
                                        <td><?= $row['breakout_time'] ?></td>
                                        <td><?= $totBreakTime; ?></td>
                                        <td><?= $row['checkout_time'] ?></td>
                                        <td><?= $row['is_status'] ?></td>
                                        <td>
                                            <div class="btn-group col-sm-2">                                           
                                                <button type="button" class="btn btn-primary updateUserRecordBtn" data-idGrab="<?=$row['U_id']?>" data-checkinGrab="<?=$row['swipein_time']?>" data-breakIn="<?=$row['breakin_time']?>" data-totbreakGrab="<?=$totBreakTime?>" data-checkoutGrab="<?=$row['checkout_time']?>" data-target="#updateUserRecordModal" data-toggle="modal">
                                                    Update
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                            </tbody>
                            <tfoot>
                               
                            </tfoot>
                            
                        </table>
                        
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>

<!--CHECKIN  MODEL-->
<div class="modal" id="Checkin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Checkin Time For This User</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5 style="display:inline; float:right;">User id : <span class="iduser"></span></h5>
                        <h5>Date : Today<span class="dateforbreak"></span></h5>
                        <h5>Checkin : <span class="checkinuser"></span></h5>
                        
                    </div>
                    
                    <div class="form-group">
                        <label for="checkin-time">Check-In Time:</label>
                        <input class="form-control checkouttime" type="time" name="checkin-time" id="grabcheckin" required/>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary "  name="checkoutrequestbtn" id="checkinyesbtn">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>

<!--BREAKKIN  MODEL-->
<div class="modal" id="Breakin" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter  Break In Time For This User</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5 style="display:inline; float:right;">User id : <span class="iduser"></span></h5>
                        <h5>Date : Today<span class="dateforbreak"></span></h5>
                        <h5>Checkin : <span class="checkinuser"></span></h5>
                        
                    </div>
                    
                    <div class="form-group">
                        <label for="breakin-time">Break-In Time:</label>
                        <input class="form-control checkouttime" type="time" name="breakin-time" id="grabbreakin" required/>
                    </div>

                    
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary "  name="checkoutrequestbtn" id="breakinyesbtn">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>

<!--BREAKOUT  MODEL-->
<div class="modal" id="Breakout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Breakout Time For This User</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5 style="display:inline; float:right;">User id : <span class="iduser"></span></h5>
                        <h5>Date : Today<span class="dateforbreak"></span></h5>
                        <h5>Breakkin : <span class="breakinuser"></span></h5>
                    </div>
                    <div class="form-group">
                        <label for="breakout-time">Break-out Time:</label>
                        <input class="form-control checkouttime" type="time" name="breakout-time" id="grabbreakout" required/>
                    </div> 
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary" name="checkoutrequestbtn" id="breakoutyesbtn">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn" data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>

<!--CHECKOUT  MODEL-->
<div class="modal" id="Checkout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Enter Checkout Time For This User</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h5 style="">Date : Today<span class="dateforbreak"></span></h5>             
                        <h5 style="">User id : <span class="iduser"></span></h5>
                        <h5 style="">Total Break Hours : <span class="totalbreakhoursuser"></span></h5>
                        <h5 style="">Checkin : <span class="checkinuser"></span></h5>    
                        <h5 style="">Checkout : <span class="checkoutuser"></span></h5>                      
                    </div>
                    
                    <div class="form-group">
                        <label for="checkouttime">Check-out Time:</label>
                        <input class="form-control checkouttime" type="time" name="checkout-time" id="grabcheckout" required/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"  name="checkoutrequestbtn" id="checkoutyesbtn">Send</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Update User Record Modal -->
<div class="modal" id="updateUserRecordModal" tabindex="-1" role="dialog" aria-labelledby="updateUserAttendenceLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Update User Record</h4>
            </div>
            <form  method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <h2>Current Data</h2>
                        <h5 style=""><span class="font-weight-bold">Date :</span> Today<span class="dateforbreak"></span></h5>             
                        <h5 style=""><span class="font-weight-bold">User id :</span> <span class="iduser"></span></h5>
                        <h5 style=""><span class="font-weight-bold">Checkin :</span> <span class="checkinuser"></span></h5>
                        <h5 style=""><span class="font-weight-bold">Breakin :</span> <span class="breakinuser"></span></h5>    
                        <h5 style=""><span class="font-weight-bold">Total Breaktime :</span> <span class="totalbreakhoursuser"></span></h5>    
                        <h5 style=""><span class="font-weight-bold">Checkout :</span> <span class="checkoutuser"></span></h5>                      
                    </div>   

                    <div class="form-group">
                        <label for="checkin-time">Check-In Time:</label>
                        <input class="form-control checkintime-field" type="time" name="checkin-time" id="grabcheckin2" required/>
                    </div>
                    <div class="form-group">
                        <label for="breakin-time">Break-In Time:</label>
                        <input class="form-control grabbreakin" type="time" name="breakin-time" required/>
                    </div>
                    <div class="form-group">
                        <label for="breakout-time">Break-out Time:</label>
                        <input class="form-control grabbreakout" type="time" name="breakout-time" required/>
                    </div> 
                    <div class="form-group">
                        <label for="checkouttime">Check-Out Time:</label>
                        <input class="form-control checkouttime-field" type="time" name="checkouttime" id="grabcheckout2"/>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"  name="updateRecordBtn" id="updateRecordBtn">Update</button>
                        <button type="button" class="btn btn-default closecheckbtn"  data-dismiss="modal">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>

</body>
</html>

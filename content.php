<?php
include 'db.php';
$query = "select * from `user` WHERE `U_id`='".$_SESSION['U_id']."'";
$result = mysqli_query($con, $query) or die (mysqli_error($con));
if(mysqli_num_rows($result)>0){
    $row = mysqli_fetch_array($result);
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

?>

<header class="header">
    <!-- Header Navbar: style can be found in header.less -->
    <nav id="navbar-first" class="navbar navbar-default" style="height: auto; padding-bottom: 25px; position: relative; z-index: 1">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="login.php" class="logo-head">
                    <img class="image_logo" src="img/sprout01.png">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav" id="second-nav">
                    <li><a href="home.php"><span class="glyphicon glyphicon-home"></span>home</a><span class="white">(available)</span></li>
<?php 
if($row['Account_type'] == 'sub-admin'){
?>
                    <li><a href="adminpanel.php"><span class="glyphicon glyphicon-th-list"></span>AdminPanel</a><span class="white" style="visibility:hidden;">(available)</span></li>
<?php } ?>                    
                    
                    <!--<li><a href="#"><span class="glyphicon glyphicon-th-list"></span>task</a><span class="danger">(Coming soon)</span></li>-->
                    <li><a href="attendence.php"><span class="glyphicon glyphicon-th"></span>attendence</a><span class="white">(available)</span></li>
                    <!--<li><a href="Previousattendence.php"><span class="glyphicon glyphicon-th"></span>Prev.Records</a><span class="white">(available)</span></li>-->
                    <li><a href="Madedrequests.php"><span class="glyphicon glyphicon-file"></span>Requests</a><span class="white">(available)</span></li>
                    <li><a href="desk.php"><span class="glyphicon glyphicon-calendar"></span>holidays</a><span class="white">(available)</span></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-off"></span>logout</a><span class="white">(availabe)</span></li>
                </ul>
                <ul class="nav navbar-nav navbar-right" id="rightnav">
                    <div id="showlastlogin" style="float:left; margin-right:1rem;">
                        <p></p>
                    </div>
                    <div class="user-portfolio-img">
                        <?php if(!empty($row['profile_img'])){ ?>
                            <img src="<?= $row['profile_img']; ?>" class="img-circle" alt="">
                        <?php }else{ ?>
                            <img src="img/default-avatar.png" class="img-circle" alt="">
                        <?php } ?>
                    </div>
                    <li class="dropdown">
                        <a id="dropdown-name"  class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span><?php echo $_SESSION['U_name']; ?> <i class="caret"></i></span>
                        </a>
                        <ul class="dropdown-menu" id="dropdown-name">
                            <li class="dropdown-header text-center">ACCOUNT</li>
                            <li class="divider"></li>
                            <li style="display: inline-block;width: 100%">
                                <a href="#" data-toggle="modal" id="set-pass" data-target="#myModal2" class="btn btn-default" style="color:#666;">Set Password</a>
                            </li>
                            <li class="divider"></li>
                            <li class="form-group">
                                <a id="upload-btn" href="" class="btn btn-default" data-toggle="modal" data-target="#myModal">Picture</a>
                                <form action="logout.php" method="post" style="text-align: right;">
                                    <input name="logout" type="submit" value="Logout" id="logout" class="btn btn-default"/>
                                    <!-- logout button using form-->
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <nav id="bottom-nav" class="navbar navbar-default toggle-down">
        <div class="col-md-12">
           
            <div id="container" class="col-xs-12">
                <!--<div id="timer"></div>-->
                <?php
                $log_in_user = $_SESSION['U_name'];
                $sel_user_data = "select U_id from user where U_name='$log_in_user'";
                $sel_q_run = mysqli_query($con, $sel_user_data) or die (mysqli_error($con));
                $user_id_get = '';
                $get_res = '';
                foreach ($sel_q_run as $sing_user) {
                    $user_id_get = $sing_user['U_id'];
                };
                if (!empty($user_id_get)) {
                    $cur_date = date('Y-m-d');
                    $get_user_info = "select * from `user_attendance` where `uid`='$user_id_get' AND `date`='$cur_date'";
                    $get_user_info_q_run = mysqli_query($con, $get_user_info) or die (mysqli_error($con));
                    $row = $get_user_info_q_run->fetch_assoc();
                    if (mysqli_num_rows($get_user_info_q_run) > 0 && empty($row['checkout_time'])) {
                        $get_user_check_in_time = $row['swipein_time'];
                        $get_res = $cur_date . '|' . $get_user_check_in_time;
                    } else {
                        $chk_checkout_time = $row['checkout_time'];            
                        if (!empty($chk_checkout_time)) {
                            $get_res = "working_hours|" . $row['total_office_hours'];
                        } else {
                          $get_res = '';    
                        }
                        $get_res = '';
                    }

                    
                   
                    // Total Working Hour Calculation
                    if(!empty($row['swipein_time'])) {
                        if(empty($row['checkout_time'])) {
                            date_default_timezone_set("Asia/Karachi");
                            $start = $row['swipein_time'];
                            $stop = date('h:i:s a');
                            $exc_time1 = strtotime($start) - strtotime("00:00:00");
                            $total_working_hours = date("H:i:s", strtotime($stop) - $exc_time1);
                        } else {
                            date_default_timezone_set("Asia/Karachi");
                            $start = $row['swipein_time'];
                            $stop = $row['checkout_time'];
                            $exc_time1 = strtotime($start) - strtotime("00:00:00");
                            $total_working_hours = date("H:i:s", strtotime($stop) - $exc_time1);
                        }
                    } else {
                        $total_working_hours = '00:00:00';
                    }

                    // Total Break Hours Calculation
                    $user_break_in_time = $row['breakin_time'];
                    $user_total_break_hours = $row['break_count'];

                    if(!empty($user_break_in_time)) {
                        date_default_timezone_set("Asia/Karachi");
                        $start = $user_break_in_time;
                        $stop = date('h:i:s a');
                        $exc_time1 = strtotime($start) - strtotime("00:00:00");
                        $total_break_hours = date("H:i:s", strtotime($stop) - $exc_time1);
                    } else {
                        if(!empty($user_total_break_hours)) {
                            $total_break_hours = $user_total_break_hours;
                        } else {
                            $total_break_hours = '00:00:00';
                        }
                    }


                    $totWorkingTime = subCurrentTime(subCurrentTime($row['swipein_time']), $totBreakTime);






                }
                ?>
                <div class="col-xs-12 col-sm-4">
                    <h4 class="p-0 m-0 text-left"> <span style="font-weight:bold;">Working Hours:</span> <?=$total_working_hours ?> </h4>
                    <h4 class="p-0 m-0 text-left"> <span style="font-weight:bold;">Break Hours:</span> <?=$total_break_hours ?> </h4>
                </div>
                <div class="col-xs-12 col-sm-8" id="attendence-action">
                    <button id="start" class="waves-effect waves-light btn green accent-4 submit_in"><span><img src="img/Checkin.png"
                                                                                                                alt=""></span>CheckIn</button>
                    <button id="stop" class="waves-effect waves-light btn green accent-4 submit_pause"><span><img src="img/Breakin.png"
                                                                                                                  alt=""></span>Break In</button>

                    <button id="resume" class="waves-effect waves-light btn green accent-4 submit_resume"><span><img src="img/Breakout.png"
                                                                                                                     alt=""></span>Break Out
                    </button>
                    <button id="stop_to" class="waves-effect waves-light btn green accent-4 submit_out"><span><img src="img/Checkout.png"
                                                                                                                   alt=""></span>Check Out
                    </button>
                    <!--Request Break button -->
                    <input type="hidden" name="timein" id="currenttime_in">
                    <input type="hidden" name="time" id="currenttimeout">
                    <input type="hidden" name="time_resume" id="currenttime_resume">
                    <input type="hidden" name="time_stop" id="currenttime_stop">
                </div>
            </div>
        </div>
    </nav>
    <div class="col-xs-12 pannel-down" style="text-align: center">
        <button type="button" id="toggle-button" class="btn btn-default"><span class="glyphicon glyphicon-chevron-up"></span></button>
    </div>
</header>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Picture</h4>
            </div>
            <form action="fileupload.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="fileToUpload">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default updatebtn"   name="submit">Upload</button>
                    <button type="button" class="btn btn-default closebtn"  data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change Password</h4>
            </div>
            <form action="passwordchange.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <input class="form-control" type="password" name="pasword" value=""/>
                    </div><!--designation-->
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-default updatebtn"  name="submit">Update</button>
                        <button type="button" class="btn btn-default closebtn" id="" data-dismiss="modal">Close</button>
                    </div><!-- /.box-footer -->
                </div>

            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
 function GetCurDatePhp(AjaxGrabData){
        jQuery.ajax({
            type: "POST",
            url: "action.php",
            data: 'chk_current_date=cur',
            cache: true,
            success: function(res) {
                var cur_date = res;
                AjaxGrabData(res);
            }
        });
    }
    jQuery(function () {
     
        loop_chk = false;
        loop = false;


        jQuery('#start').click(function () {
            var dataString_timein = 'currenttime_in=true';

            jQuery.ajax({
                type: "POST",
                url: "action.php",
                data: dataString_timein,
                cache: true,
                success: function (html) {
                    if (html.indexOf('|') !== -1) {
                        var split_res = html.split('|');
                        var chkin_date_tm = split_res[1].split(';');
                        var get_c_date = chkin_date_tm[0];
                        var get_c_time = chkin_date_tm[1];
                        var create_c_dt = new Date(get_c_date + ' ' + get_c_time);

                        loop = setInterval(function () {
                            GetCurDatePhp(function(get_date){
                                var new_dt_split = get_date.split('|');
                                var new_date = new_dt_split[0];
                                var new_time = new_dt_split[1];
                                var cr_new_date = new Date(new_date + ' ' + new_time);

                                var diff = Math.abs(cr_new_date - create_c_dt);
                                var get_dif_time = diff / (1000 * 60 * 60);
                                var get_dif_time_exp = get_dif_time.toString().split('.');
                                var get_h = get_dif_time_exp[0];
                                var get_m_s = "0." + get_dif_time_exp[1];
                                get_m_s = get_m_s * 1 * 60;
                                get_m_s = get_m_s.toString().split('.');
                                var get_m = get_m_s[0];
                                var get_s = "0." + get_m_s[1];
                                get_s = (get_s * 1) * 60;
                                get_s = get_s.toString().split('.');
                                get_s = get_s[0];

                                if (get_h.length == 1) {
                                    get_h = '0' + get_h;
                                }
                                if (get_m.length == 1) {
                                    get_m = '0' + get_m;
                                }
                                if (get_s.length == 1) {
                                    get_s = '0' + get_s;
                                }

                                if(isNaN(get_h)){
                                    get_h = '00';
                                }

                                if(isNaN(get_m)){
                                    get_m = '00';
                                }

                                if(isNaN(get_s)){
                                    get_s = '00';
                                }

                                grab_timer.show();
                                grab_timer.html(get_h + ':' + get_m + ':' + get_s);
                            });
                        }, 10000);
                        loop_chk = true;
                        //alert(split_res[0]);
                            Swal.fire(split_res[0],'Good job!','success');
                    } else {
                        if(html == 'You are already checked in !')
                        {
                         Swal.fire({
                          icon: 'error',
                          title: 'You are already checked in !'
                        
                        });
                        }elseif(html == 'You are now checked in!')
                        {
                            Swal.fire(html,'Good job!','success');
                        }
                        
                    }
                }
            });
        });
        jQuery('#stop_to').click(function () {
            if (loop_chk == false) {
                var dataString_timeout = 'currenttime_stop=true';
                jQuery.ajax({
                    type: "POST",
                    url: "action.php",
                    data: dataString_timeout,
                    cache: true,
                    success: function (html) {
                        if (html.indexOf('|') !== -1) {
                            var grab_data_split = html.split('|');
                            if(grab_data_split[1] == 'true'){
                                clearInterval(loop);
                                loop_chk = false;
                               // alert(grab_data_split[0]);
                                Swal.fire(grab_data_split[0],'Good job!','success');
                            }else{
                                //alert(grab_data_split[0]);
                                //Swal.fire('Good job!',grab_data_split[0],'success');
                                Swal.fire({
                                icon: 'error',
                                title:  grab_data_split[0],
                                footer: 'Issue: You have to click checkin button first , thank you !'
                        });
                            }
                        }
                    }
                });
            }else{
               Swal.fire({
                          icon: 'error',
                          title: 'You Are Not Check In',
                          footer: 'Issue: You have to click checkin button first , thank you!'
                        });
            }
        });
        jQuery('#stop').click(function(){
            if (loop_chk == false) {
                var dataString_break = 'currenttimeout=true';
                jQuery.ajax({
                    type: "POST",
                    url: "action.php",
                    data: dataString_break,
                    cache: true,
                    success: function (html) {
                        if(html == 'Your break starts now!')
                        {
                          Swal.fire(html,'Good job!','success');
                        }else if(html == 'You are already on break !')
                        {
                        Swal.fire({
                          icon: 'error',
                          title: 'You are already on break !',
                          footer: 'Issue: You have to click checkin button first , thank you !'
                        });
                        }
                        //alert(html);
                       
                    }
                });
            }else{
                //alert('You Are Not Check In');
                Swal.fire({
                          icon: 'error',
                          title: 'You Are Not Check In',
                          footer: 'Issue: You have to click checkin button first , thank you !'
                        });
            }
        });
        jQuery('#resume').click(function(){
            if (loop_chk == false) {
                var dataString_break = 'currenttime_resume=true';
                jQuery.ajax({
                    type: "POST",
                    url: "action.php",
                    data: dataString_break,
                    cache: true,
                    success: function (html) {
                        if(html == 'Your break has ended sucessfully . You are now in working mode !')
                        {
                          Swal.fire(html,'Good job!','success');  
                        }else if(html == 'You are not on break !')
                        {
                        Swal.fire({
                          icon: 'error',
                          title: 'You are not on break !',
                          footer: 'Issue: You have to click breakin button first , thank you !'
                        }); 
                        }
                        
                    }
                });
            }else{
                 Swal.fire({
                          icon: 'error',
                          title: 'You Are Not Check In',
                          footer: 'Issue: You have to click checkin button first , thank you !'
                        });
            }
        });
    });
</script>





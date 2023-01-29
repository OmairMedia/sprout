<?php
include('db.php');
session_set_cookie_params(86400, "/");
session_start();
$query = "select * from user";
$query_run = mysqli_query($con, $query) or die (mysqli_error($con));
if (mysqli_num_rows($query_run)) {
    $row = mysqli_fetch_assoc($query_run);


}

if (isset($_POST['chk_current_date'])) {
    if ($_POST['chk_current_date'] == 'cur') {
        date_default_timezone_set("Asia/Karachi");
        $date = date("Y-m-d");
        $time = date('h:i:s a');
        echo $date . '|' . $time;
    }
    die;
}

//Check in
if (isset($_POST['currenttime_in']) && $_POST['currenttime_in'] == 'true') {
    date_default_timezone_set("Asia/Karachi");
    $date = date("Y-m-d");
    $current_time = date('h:i:s a');

    /* CHECK DATA To already check in */
    $get_data_chk = "Select * From user_attendance Where uid='" . $_SESSION['U_id'] . "' AND date='" . $date . "'";
    $get_qu_chk = mysqli_query($con, $get_data_chk) or die (mysqli_error($con));
    $get_count_rows = mysqli_num_rows($get_qu_chk);

    if ($get_count_rows > 0) {
        $chk_rows = $get_qu_chk->fetch_assoc();
        $chk_checkout_time = $chk_rows['checkout_time'];
        if (!empty($chk_checkout_time)) {
            echo "Your today's attendance is already checked in!";
        } else
        {
            echo "You are already checked in !";
        }
    } else {
        
        if(empty($get_count_rows)){
            $remarks = 'Present';
            $check_insert = "INSERT INTO `user_attendance`(`uid`,`date`,`swipein_time`,`user_remarks`)  VALUES('" . $_SESSION['U_id'] . "','" . $date . "','" . $current_time . "','".$remarks."')";
            $queryrun_insert = mysqli_query($con, $check_insert) or die (mysqli_error($con));
        }

        $last_id = mysqli_insert_id($con);
        $_SESSION['last_id'] = $last_id;

        $checkin = 'online';

        if ($queryrun_insert) {
            echo "You are now checked in!.|" . $date . ";" . $current_time;
        }
        $updatstartstatus = "UPDATE `user` SET `is_status`='" . $checkin . "' WHERE `U_id`='" . $_SESSION['U_id'] . "'";
        $updatestatus_query = mysqli_query($con, $updatstartstatus);
    }
    die;
}
//break in
if (isset($_POST['currenttimeout']) && $_POST['currenttimeout'] == 'true') {

    date_default_timezone_set("Asia/Karachi");
    $date = date("Y-m-d");
    $current_time = date('h:i:s a');
    $selec = "SELECT * FROM `user_attendance` WHERE `date`='" . $date . "' AND `uid`='" . $_SESSION['U_id'] . "'";
    $selec_query = mysqli_query($con, $selec);
    $result = mysqli_fetch_assoc($selec_query);
    $count_row = mysqli_num_rows($selec_query);

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $_SESSION['U_id'] . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];

    if ($cur_status == 'online') {
        $break_in = 'break';


        $insert = "UPDATE `user_attendance` SET `breakin_time`='" . $current_time . "' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
        $queryrun = mysqli_query($con, $insert) or die (mysqli_error($con));


        if ($queryrun) {
            echo "Your break starts now !";
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $break_in . "' WHERE `U_id`='" . $_SESSION['U_id'] . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);
        }

    } else {
        echo "You are already on break !";
    }
    die;
}

//break resume
if (isset($_POST['currenttime_resume']) && $_POST['currenttime_resume'] == 'true') {

    date_default_timezone_set("Asia/Karachi");
    $date = date("Y-m-d");
    $exacttime = date('h:i:s a');

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $_SESSION['U_id'] . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];

    if ($cur_status == 'break') {
        $multi_break_chk_stm = "SELECT * FROM `user_attendance` WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
        $multi_break_chk_qur = mysqli_query($con, $multi_break_chk_stm);
        $multi_break_chk_res = mysqli_fetch_assoc($multi_break_chk_qur);

        if (!empty($multi_break_chk_res['break_count'])) {
            $break_start_time = strtotime($multi_break_chk_res['breakin_time']) - strtotime('00:00:00');
            $break_count_res = date('H:i:s', strtotime($exacttime) - $break_start_time);
            $add_b_time = $multi_break_chk_res['break_count'].'|'.$break_count_res;

            $st = "UPDATE `user_attendance` SET `breakin_time`='', `breakout_time`='', `break_count`='" . $add_b_time . "' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $break_query2 = mysqli_query($con, $st) or die (mysqli_error($con));
            if ($break_query2) {
                $on = 'online';
                $updatstartstatus = "UPDATE `user` SET `is_status`='" . $on . "' WHERE `U_id`='" . $_SESSION['U_id'] . "'";
                $updatestatus_query = mysqli_query($con, $updatstartstatus);
                echo "Your break has ended sucessfully . You are now in working mode !";
            }

        } else {
            $break_start_time = strtotime($multi_break_chk_res['breakin_time']) - strtotime('00:00:00');
            $break_count_res = date('H:i:s', strtotime($exacttime) - $break_start_time);

            $st = "UPDATE `user_attendance` SET `breakin_time`='', `breakout_time`='', `break_count`='" . $break_count_res . "' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $break_query2 = mysqli_query($con, $st) or die (mysqli_error($con));
            if ($break_query2) {
                $on = 'online';
                $updatstartstatus = "UPDATE `user` SET `is_status`='" . $on . "' WHERE `U_id`='" . $_SESSION['U_id'] . "'";
                $updatestatus_query = mysqli_query($con, $updatstartstatus);
                echo "Your break has ended sucessfully . You are now in working mode !";
            }
        }
    } else {
        echo "You are not on break !";
    }
    die;
}

//Check out means timeout
if (isset($_POST['currenttime_stop']) && $_POST['currenttime_stop'] == 'true') {

    date_default_timezone_set("Asia/Karachi");
    $date = date("Y-m-d");
    $exacttime = date('h:i:s a');

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $_SESSION['U_id'] . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];

    if ($cur_status == 'online') {
        $stop = "UPDATE `user_attendance` SET `checkout_time`='" . $exacttime . "' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
        $break_query2 = mysqli_query($con, $stop) or die (mysqli_error($con));

        if ($break_query2) {

            $sql = "SELECT * From `user_attendance` where `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
            $sql_fetch = mysqli_fetch_assoc($sql_query);
            $start = $sql_fetch['swipein_time'];
            $stop = $sql_fetch['checkout_time'];

            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);

            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);

            $sql = "SELECT * From `user_attendance` WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
            $sql_fetch = mysqli_fetch_assoc($sql_query);
            $start = $sql_fetch['break_count'];
            if(!empty($start)) {
                if(strpos($start,'|')){
                    $stop = $sql_fetch['total_office_hours'];
                    $exp_multi_break  = explode('|',$start);
                    $grab_time = strtotime('00:00:00');
                    $sub_time = strtotime($stop);
                    foreach($exp_multi_break as $sing_time){
                        $grab_time += strtotime($sing_time) - strtotime('00:00:00');
                        $sub_time -= strtotime($sing_time) - strtotime('00:00:00');
                    }

                    $tot_break_hours = date('H:i:s', $grab_time);
                    $result = date("H:i:s", $sub_time);
                }else{
                    $stop = $sql_fetch['total_office_hours'];
                    $secs = strtotime($start) - strtotime("00:00:00");
                    $result = date("H:i:s", strtotime($stop) - $secs);
                    $tot_break_hours = $start;
                }
            }else{
                $result = $sql_fetch['total_office_hours'];
                $tot_break_hours = '';
            }

            $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `uid`='" . $_SESSION['U_id'] . "' AND `date`='" . $date . "'";
            $update_sql_query = mysqli_query($con, $update_total_working_hour);

            $of = 'offline';
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $of . "' WHERE `U_id`='" . $_SESSION['U_id'] . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);//update stop time and counter

            echo "You have successfully checked out..!|true";
        }
    } else {
        echo "You are on break. Please end your break first..!|false";
    }
    die;
}

?>

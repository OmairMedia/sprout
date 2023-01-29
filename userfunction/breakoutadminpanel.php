<?php
include '../db.php';

date_default_timezone_set("Asia/Karachi");
$date = date("Y-m-d");
$userid = $_POST['grab_id'];

   //BREAK OUT STARTS HERE -------------------------------------------------------------------------------
 if(isset($_POST['data']) && $_POST['data']=='Breakout')
 {
     /* CHECK DATA To already check in */
     $get_data_chk = "Select * From user_attendance Where uid='" . $userid . "' AND date='" . $date . "'";
     $get_qu_chk = mysqli_query($con, $get_data_chk) or die (mysqli_error($con));
     $get_count_rows = mysqli_num_rows($get_qu_chk);
 
     if(empty($get_count_rows)){
        echo "User is not checked in !";
     }else
     {

    
    $exacttime = $_POST['grab_breakoutadmin'];

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $userid . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];

    if ($cur_status == 'break') {
        $multi_break_chk_stm = "SELECT * FROM `user_attendance` WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
        $multi_break_chk_qur = mysqli_query($con, $multi_break_chk_stm);
        $multi_break_chk_res = mysqli_fetch_assoc($multi_break_chk_qur);

        if (!empty($multi_break_chk_res['break_count'])) {
            $break_start_time = strtotime($multi_break_chk_res['breakin_time']) - strtotime('00:00:00');
            $break_count_res = date('H:i:s', strtotime($exacttime) - $break_start_time);
            $add_b_time = $multi_break_chk_res['break_count'].'|'.$break_count_res;

            $st = "UPDATE `user_attendance` SET `breakin_time`='', `breakout_time`='', `break_count`='" . $add_b_time . "' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
            $break_query2 = mysqli_query($con, $st) or die (mysqli_error($con));
            if ($break_query2) {
                $on = 'online';
                $updatstartstatus = "UPDATE `user` SET `is_status`='" . $on . "' WHERE `U_id`='" . $userid . "'";
                $updatestatus_query = mysqli_query($con, $updatstartstatus);
                echo "Break has been successfully ended . The user is now in working mode !";
            }

        } else {
            $break_start_time = strtotime($multi_break_chk_res['breakin_time']) - strtotime('00:00:00');
            $break_count_res = date('H:i:s', strtotime($exacttime) - $break_start_time);

            $st = "UPDATE `user_attendance` SET `breakin_time`='', `breakout_time`='', `break_count`='" . $break_count_res . "' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
            $break_query2 = mysqli_query($con, $st) or die (mysqli_error($con));
            if ($break_query2) {
                $on = 'online';
                $updatstartstatus = "UPDATE `user` SET `is_status`='" . $on . "' WHERE `U_id`='".$userid."'";
                $updatestatus_query = mysqli_query($con, $updatstartstatus);
                echo "Break has been successfully ended.The user is now in working mode !";
            }
        }
    } else {
        echo "User is not on break !";
    }
    die;
 }
  //BREAK OUT ENDS HERE -------------------------------------------------------------------------------
}


?>
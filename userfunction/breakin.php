<?php
include '../db.php';


$userid = $_POST['ID'];
date_default_timezone_set("Asia/Karachi");
$date = date("Y-m-d");

   //BREAK IN STARTS HERE -------------------------------------------------------------------------------
 if(isset($_POST['module']) && $_POST['module']=='breakin')
 {
    /* CHECK DATA To already check in */
    $get_data_chk = "Select * From user_attendance Where uid='" . $userid . "' AND date='" . $date . "'";
    $get_qu_chk = mysqli_query($con, $get_data_chk) or die (mysqli_error($con));
    $get_count_rows = mysqli_num_rows($get_qu_chk);

    if(empty($get_count_rows)){
       echo "User is not checked in !";
    }else
    {
    
    $current_time = date('h:i:s a');
    $selec = "SELECT * FROM `user_attendance` WHERE `date`='" . $date . "' AND `uid`='" . $userid . "'";
    $selec_query = mysqli_query($con, $selec);
    $result = mysqli_fetch_assoc($selec_query);
    $count_row = mysqli_num_rows($selec_query);

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $userid . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];

    if ($cur_status == 'online') {
        $break_in = 'break';


        $insert = "UPDATE `user_attendance` SET `breakin_time`='" . $current_time . "' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
        $queryrun = mysqli_query($con, $insert) or die (mysqli_error($con));


        if ($queryrun) {
            echo "User is now on break !";
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $break_in . "' WHERE `U_id`='" .$userid . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);
        }

    } else {
        echo "This User is already on break !";
    }
    die; 
 
 }
  //BREAK IN ENDS HERE -------------------------------------------------------------------------------
}

 
?>
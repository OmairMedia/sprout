<?php

include "db_func.php";

/*global $con;
$sel_all_user = "SELECT * FROM `user` WHERE `Account_type`='employee'";
$sel_all_user_ex = mysqli_query($con, $sel_all_user) or die (mysqli_error($con));
$l = 0;
while($u_data = mysqli_fetch_assoc($sel_all_user_ex)){
    // Check User Attendance with user id
    $sel_att_user = "SELECT * from `user_attendance` WHERE `checkout_time`='' AND `uid`='{$u_data['U_id']}'";
    $sel_att_user_ex = mysqli_query($con, $sel_att_user) or die (mysqli_error($con));
    $cur_date = date('Y-m-d');
    $grab_dates = array();
    while($res = mysqli_fetch_assoc($sel_att_user_ex)){
        $str_date = date('Y-m-d', strtotime($res['date']));
        $grab_dates[] .= $str_date;
        if($str_date !== $cur_date){
            $upd_stm = "UPDATE `user_attendance` SET `checkout_time`='auto' WHERE `id`='{$res['id']}'";
            $upd_stm_ex = mysqli_query($con, $upd_stm) or die (mysqli_error($con));
        }
    }
    if(in_array($cur_date, $grab_dates)){
        $upd_status_stm = "UPDATE `user` SET `is_status`='online' WHERE `U_id`={$u_data['U_id']}";
    }else{
        $upd_status_stm = "UPDATE `user` SET `is_status`='offline' WHERE `U_id`={$u_data['U_id']}";
    }
    $upd_status_stm_ex = mysqli_query($con, $upd_status_stm) or die (mysqli_error($con));
}*/

/*$dates = array(
    '2016-06-16',
    '2016-06-21',
    '2016-06-17',
    '2016-06-21',
    '2016-06-13'
);

foreach($dates as $date){
    $str_date = date('Y-m-d', strtotime($date));
    $cur_date = date('Y-m-d');
    if($str_date !== $cur_date){
        echo "old: ".$date;
        echo "<br />";
    }
}*/

//echo date('h:m:i', strtotime('0000-00-00 00:00:00'));
?>
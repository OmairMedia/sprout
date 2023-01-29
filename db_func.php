<?php
include('db.php');
global $pdo_con;

function auto_check_out_dates() {
    global $pdo_con;

    $sel_all_user = $pdo_con->prepare("SELECT * FROM `user` WHERE (`Account_type`='employee' OR `Account_type`='sub-admin')");
    $sel_all_user->execute();

    while($u_data = $sel_all_user->fetch(PDO::FETCH_ASSOC)){
        $sel_att_user = $pdo_con->prepare("SELECT * from `user_attendance` WHERE `checkout_time`='' AND `uid`='{$u_data['U_id']}'");
        $sel_att_user->execute();
        $cur_date = date('Y-m-d');
        $grab_dates = array();
        while($res = $sel_att_user->fetch(PDO::FETCH_ASSOC)){
            $str_date = date('Y-m-d', strtotime($res['date']));
            $grab_dates[] .= $str_date;
            if($str_date !== $cur_date){
                $upd_stm = $pdo_con->prepare("UPDATE `user_attendance` SET `checkout_time`='auto' WHERE `id`='{$res['id']}'");
                $upd_stm->execute();
            }
        }
        if(!in_array($cur_date, $grab_dates)){
            $upd_status_stm = $pdo_con->prepare("UPDATE `user` SET `is_status`='offline' WHERE `U_id`={$u_data['U_id']}");
            $upd_status_stm->execute();
        }
    }
}

function auto_checkout_check(){
    global $pdo_con;
    $curDate = date('Y-m-d');

    $sel_autoCheckTable = $pdo_con->prepare("SELECT * FROM `auto_check` WHERE `date`='{$curDate}'");
    $sel_autoCheckTable->execute();
    $rowsCheck = $sel_autoCheckTable->rowCount();
    if($rowsCheck > 0){
        $getCheckOutEntry = $sel_autoCheckTable->fetch(PDO::FETCH_ASSOC);
        if($getCheckOutEntry['auto_checkout'] == 0){
            auto_check_out_dates();
            $updateAutoCheckOutEntry = $pdo_con->prepare("UPDATE `auto_check` SET `auto_checkout`='1' WHERE `id`='{$getCheckOutEntry['id']}'");
            $updateAutoCheckOutEntry->execute();
        }
    }else{
        auto_check_out_dates();
        $setAutoCheckOutEntry = $pdo_con->prepare("INSERT INTO `auto_check`(`date`, `auto_checkout`) VALUES ('{$curDate}', '1')");
        $setAutoCheckOutEntry->execute();
    }
}
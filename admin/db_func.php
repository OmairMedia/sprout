<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}



include('db.php');
session_start();

function chk_adm_login(){
    global $con;
    if(!empty($_SESSION['log_adm_email'])){
        $sess_email = $_SESSION['log_adm_email'];
        $grab_admin_db = "SELECT `u_email`, `Account_type` FROM `user` WHERE `u_email`='" . $sess_email . "' AND (`Account_type`='admin' OR `Account_type`='sub-admin')";
        $grab_admin_db_ex = mysqli_query($con, $grab_admin_db) or die (mysqli_error($con));
        $row = mysqli_num_rows($grab_admin_db_ex);
        if($row == 1){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

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

function adm_logout(){
    if(chk_adm_login()){
        unset($_SESSION['log_adm_email']);
        return true;
    }else{
        return false;
    }
}

function tbl_rows_count($table_name){
    global $con;
    if(!empty($table_name)){
        $grab_tbl_qur = "SELECT * FROM `".$table_name."`";
        $grab_tbl_exc = mysqli_query($con, $grab_tbl_qur) or die (mysqli_error($con));
        $row = mysqli_num_rows($grab_tbl_exc);
        return $row;
    }else{
        return false;
    }
}

function chk_col_val_exist($table_name, $col_name, $col_val){
    global $con;
    if(!empty($table_name) && !empty($col_name) && !empty($col_val)){
        $grab_tbl_qur = "SELECT * FROM `".$table_name."` WHERE `".$col_name."`='".$col_val."'";
        $grab_tbl_exc = mysqli_query($con, $grab_tbl_qur) or die (mysqli_error($con));
        $row = mysqli_num_rows($grab_tbl_exc);
        if($row > 0){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

// attendance_admin
// 4slash1234!@#$
function uni_chk_tab($table_name, $uni_col, $uni_value){
    if(!empty($table_name) && !empty($uni_col) && !empty($uni_value)){
        $adm_db = new PDO('mysql:host=localhost;dbname=attendance_main_test', 'attendance_admin_test', '4slash1234!@#$');
        $stm = $adm_db->prepare('Select '.$uni_col.' From '.$table_name.' where '.$uni_col.'=?');
        $stm->bindValue(1, $uni_value);
        $stm->execute();
        $count_row = $stm->rowCount();
        if($count_row > 0){
            return true;
        }else{
            return false;
        }
    }else{
        return 'err';
    }
}

// attendance_admin
// 4slash1234!@#$
function att_auto_opti(){
    $adm_db = new PDO('mysql:host=localhost;dbname=attendance_main_test', 'attendance_admin_test', '4slash1234!@#$');
    $stm = $adm_db->prepare("Select * From user_attendence WHERE checkout_time=''");
    $stm->execute();
    return $stm->rowCount();
}

function set_sess($ss_name, $ss_val){
    if(!empty($ss_name) && !empty($ss_val)){
        $_SESSION[$ss_name] = $ss_val;
    }
}

function call_sess($ss_name){
    if(!empty($_SESSION[$ss_name])){
        $ret_val = $_SESSION[$ss_name];
        unset($_SESSION[$ss_name]);
        return $ret_val;
    }else{
        return false;
    }
}

function option_chk_sel($chk_val1, $chk_val2){
    if($chk_val1 == $chk_val2){
        echo 'selected';
    }
}

?>
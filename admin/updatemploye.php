<?php
include('db_func.php');
global $con;
// attendance_admin
// 4slash1234!@#$
$adm_db = new PDO('mysql:host=localhost;dbname=attendance_main_test', 'attendance_admin_test', '4slash1234!@#$');

if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

if (isset($_POST['update']) && !empty($_POST['upd_id'])) {
    $up_id = $_POST['upd_id'];

    $valid_set_arr = array();
    $valid_bol_chk = array();
    $valid_err_msges = array();

    $valid_set_arr['uname'] = array('Full name','required','min' => 4,'max' => 30);
    $valid_set_arr['designation'] = array('Designation','max' => 30);
    $valid_set_arr['phoneno'] = array('Phone No','max' => 15);
    $valid_set_arr['bankAccNo'] = array('Bank Account No','max' => 60);
    $valid_set_arr['hourly_salary'] = array('Hourly Salary','max' => 20);
    $valid_set_arr['dep'] = array('Department','required');
    $valid_set_arr['setAccountType'] = array('Account Type','required','matchValue'=>array('sub-admin','employee'));
    $valid_set_arr['setAccountStatus'] = array('Status','required','matchValue'=>array('active','inactive'));

    /* Validation check loop */
    foreach($_POST as $post_k => $post_val){
        if($post_k == 'update' || $post_k == 'upd_id'){
            continue;
        }
        $chk_valid_err_arr = $valid_set_arr[$post_k];
        foreach($chk_valid_err_arr as $v_key => $v_val){
            if($v_val === 'required'){
                if(empty($post_val)){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is required.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
            if($v_key === 'matchValue'){
                if (!in_array($post_val, $v_val)) {
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0] . ' is not valid account type.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
            if($v_key === 'min'){
                if(strlen($post_val) < $v_val){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is too short please Enter min-'.$v_val.' Characters.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
            if($v_key === 'max'){
                if(strlen($post_val) > $v_val){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is too long please Enter max-'.$v_val.' Characters.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
        }
        set_sess($post_k.'_val',$post_val);
    }

    if(!in_array(false,$valid_bol_chk)){
        /* if success validation to insert data */
        foreach ($_SESSION as $sess_key => $sess_val){
            if($sess_key == 'log_adm_email'){
                continue;
            }
            unset($_SESSION[$sess_key]);
        }
        $reg_stm = $adm_db->prepare('UPDATE `user` SET `D_id`=?,`U_name`=? ,`Phone_No`=?,`bank_account_no`=?, `user_designation`=?, `hourly_salary`=?, `Account_type`=?,`Active`=?  WHERE `U_id`=?');
        $reg_stm->bindValue(1, $_POST['dep']);
        $reg_stm->bindValue(2, $_POST['uname']);
        $reg_stm->bindValue(3, $_POST['phoneno']);
        $reg_stm->bindValue(4, $_POST['bankAccNo']);
        $reg_stm->bindValue(5, $_POST['designation']);
        $reg_stm->bindValue(6, $_POST['hourly_salary']);
        $reg_stm->bindValue(7, $_POST['setAccountType']);
        $reg_stm->bindValue(8, $_POST['setAccountStatus']);
        $reg_stm->bindValue(9, $up_id);
        if($reg_stm->execute()){
            set_sess('succes_msg', 'Successfully User is Updated.');
            header('location: login.php');
        }else{
            set_sess('fail_reg', 'Error! Insertion Problem.');
            header('location: edituser.php?id='.$up_id);
        }
    }else{
        /* if not validation fields to redirect */
        set_sess('all_in_errs',$valid_err_msges);
        header('location: edituser.php?id='.$up_id);
    }
    exit;
}

header('location: login.php');
?>
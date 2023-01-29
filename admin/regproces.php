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

if (isset($_POST['submit'])) {
    $valid_set_arr = array();
    $valid_bol_chk = array();
    $valid_err_msges = array();

    $valid_set_arr['uname'] = array('Full name','required','min' => 4,'max' => 30);
    $valid_set_arr['email'] = array('Email','required', 'unique'=>array('user','u_email'), 'v_email','min' => 8,'max' => 60);
    $valid_set_arr['pass'] = array('Password','required','min' => 6,'max' => 20);
    $valid_set_arr['pass1'] = array('Confirm Password','required', 'match' => 'pass','min' => 6,'max' => 20);
    $valid_set_arr['designation'] = array('Designation','max' => 30);
    $valid_set_arr['phoneno'] = array('Phone No','max' => 15);
    $valid_set_arr['bankAccNo'] = array('Bank Account No','max' => 60);
    $valid_set_arr['hourly_salary'] = array('Hourly Salary','max' => 20);
    $valid_set_arr['dep'] = array('Department','required');
    $valid_set_arr['setAccountType'] = array('Account Type','required','matchValue'=>array('sub-admin','employee'));


    /* Validation check loop */
    foreach($_POST as $post_k => $post_val){
        if($post_k == 'submit'){
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
            if($v_key === 'unique'){
                if(uni_chk_tab($v_val[0],$v_val[1],$post_val) === true){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is already exist.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
            if($v_val === 'v_email'){
                if(!filter_var($post_val, FILTER_VALIDATE_EMAIL)){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is not valid.';
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
            if($v_key === 'match'){
                if($post_val !== $_POST[$v_val]){
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0].' is not match.';
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
        /*
        $reg_stm = $adm_db->prepare('INSERT INTO `user`(`Account_type`, `D_id`, `U_name`, `u_email`, `u_password`, `Phone_No`, `bank_account_no`, `user_designation`, `hourly_salary`) VALUES (?,?,?,?,?,?,?,?,?)');
        $reg_stm->bindValue(1, $_POST['setAccountType']);
        $reg_stm->bindValue(2, $_POST['dep']);
        $reg_stm->bindValue(3, $_POST['uname']);
        $reg_stm->bindValue(4, $_POST['email']);
        $reg_stm->bindValue(5, md5($_POST['pass']));
        $reg_stm->bindValue(6, $_POST['phoneno']);
        $reg_stm->bindValue(7, $_POST['bankAccNo']);
        $reg_stm->bindValue(8, $_POST['designation']);
        $reg_stm->bindValue(9, $_POST['hourly_salary']);
        */
        $setAccountType = $_POST['setAccountType'];
        $dep = $_POST['dep'];
        $uname = $_POST['uname'];
        $email = $_POST['email'];
        $pass = md5($_POST['pass']);
        $phone = $_POST['phoneno'];
        $bankacc = $_POST['bankAccNo'];
        $designation =  $_POST['designation'];
        $salary = $_POST['hourly_salary'];
        $user_insert = "INSERT INTO `user`(`Account_type`, `D_id`, `U_name`, `u_email`, `u_password`, `Phone_No`, `bank_account_no`, `user_designation`, `hourly_salary`) VALUES ('".$setAccountType."','".$dep."','". $uname."','".$email."','".$pass."','".$phone."','".$bankacc."','".$designation."','".$salary."')";
        $queryrun_insert = mysqli_query($con, $user_insert) or die (mysqli_error($con));
        if($queryrun_insert)
        {
            set_sess('succes_msg', 'Successfully User is Registered.');
            header('location: login.php');
        }else
        {
            set_sess('fail_reg', mysqli_error($con));
            header('location: register.php'); 
        }
        /*
        if($reg_stm->execute()){
            set_sess('succes_msg', 'Successfully User is Registered.');
            header('location: login.php');
        }else{
            set_sess('fail_reg', $reg_stm->errorinfo());
            header('location: register.php');
        }
        */
    }else{
        /* if not validation fields to redirect */
        set_sess('all_in_errs',$valid_err_msges);
        header('location: register.php');
    }
    exit;
}

header('location: login.php');


?>
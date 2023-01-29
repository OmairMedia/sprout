<?php
include('db_func.php');
global $con;

$adm_db = new PDO('mysql:host=localhost;dbname=attendance_main_test', 'attendance_admin_test', '4slash1234!@#$');


if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $valid_set_arr = array();
    $valid_bol_chk = array();
    $valid_err_msges = array();

    $valid_set_arr['depart'] = array('Department Name', 'required', 'max' => 40);

    /* Validation check loop */
    foreach ($_POST as $post_k => $post_val) {
        if ($post_k == 'submit') {
            continue;
        }
        $chk_valid_err_arr = $valid_set_arr[$post_k];
        foreach ($chk_valid_err_arr as $v_key => $v_val) {
            if ($v_val === 'required') {
                if (empty($post_val)) {
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0] . ' is required.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
            if ($v_key === 'max') {
                if (strlen($post_val) > $v_val) {
                    $valid_err_msges[$post_k] .= $valid_set_arr[$post_k][0] . ' is too long please Enter max-' . $v_val . ' Characters.';
                    $valid_bol_chk[] .= false;
                    break 1;
                }
            }
        }
        set_sess($post_k . '_val', $post_val);
    }

    if (!in_array(false, $valid_bol_chk)) {
        /* if success validation to insert data */
        foreach ($_SESSION as $sess_key => $sess_val) {
            if ($sess_key == 'log_adm_email') {
                continue;
            }
            unset($_SESSION[$sess_key]);
        }
        $reg_stm = $adm_db->prepare('INSERT INTO `department`(`department`) VALUES (?)');
        $reg_stm->bindValue(1, $_POST['depart']);
        if ($reg_stm->execute()) {
            set_sess('succes_msg', 'Successfully Department is Insert.');
            header('location: viewdep.php');
        } else {
            set_sess('fail_reg', 'Error! Insertion Problem.');
            header('location: viewdep.php');
        }
    } else {
        /* if not validation fields to redirect */
        set_sess('all_in_errs', $valid_err_msges);
        header('location: viewdep.php');
    }
    exit;
}

header('location: login.php');
?>
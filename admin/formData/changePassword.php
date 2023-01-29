<?php
include "../db.php";
include "../db_func.php";
global $pdo_con;

if(isset($_POST['userId']) && isset($_POST['newPass1']) && isset($_POST['newPass2'])){
    if(!empty($_POST['userId']) && !empty($_POST['newPass1']) && !empty($_POST['newPass2'])){
        $pass1 = $_POST['newPass1'];
        $pass2 = $_POST['newPass2'];
        $userId = $_POST['userId'];
        if($pass1 == $pass2){
            $changePassword = $pdo_con->prepare("UPDATE `user` SET `u_password`=? WHERE `U_id`=?");
            $changePassword->bindValue(1, md5($pass1));
            $changePassword->bindValue(2, $userId);
            $checkExec = $changePassword->execute();
            if(!$checkExec){
                set_sess("errMsg", "Password Not Change Please Check Database Connection or Query!");
            }else{
                set_sess("succMsg", "Password Successfully Changed!");
            }
        }else{
            set_sess("errMsg", "Re-type Password Not Matched!");
        }
    }else{
        set_sess("errMsg", "Fields Required!");
    }

    header("Location: ../change_password.php");
    exit;
}
header("Location: ../login.php");
exit;
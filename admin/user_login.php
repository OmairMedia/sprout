<?php

include('db_func.php');
global $con;

if(chk_adm_login()){
    header('location: login.php');
    exit;
}

if (isset($_POST['submit'])) {
    if(!empty($_POST['email']) && !empty($_POST['password'])){
        $email = $_POST['email'];
        $pass = md5($_POST['password']);

        $query = "SELECT `u_email`, `Account_type`, `U_name` FROM `user` WHERE `u_email`='" . $email . "' AND `u_password`='" . $pass . "' AND (`Account_type`='admin' OR `Account_type`='sub-admin')";
        $result = mysqli_query($con, $query) or die (mysqli_error($con));
        $row = mysqli_num_rows($result);
        if($row == 1){
            $fet_row = mysqli_fetch_assoc($result);
            $_SESSION['log_adm_email'] = $fet_row['u_email'];
            header('location: login.php');
            exit;
        }else{
            $_SESSION['error_message'] = "Oops.. ! Wrong email or password :(";
            header('location:login.php');
        }
    }else{
        $_SESSION['empty_message'] = "E-mail or Password Empty.";
        header('location:login.php');
    }
    exit;
}

header('location: login.php');

?>
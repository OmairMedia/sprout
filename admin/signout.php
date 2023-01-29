<?php

include('db_func.php');
global $con;

if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}


if (isset($_POST['signout'])) {
    if(adm_logout()){
        header('location: login.php');
        exit;
    }else{
        echo "Your request is expire.!!";
        exit;
    }
}

header('location: login.php');

?>
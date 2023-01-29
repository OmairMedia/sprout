<?php
include('db_func.php');
global $con;

if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

if (isset($_POST['submit'])) {
    $department = $_POST['dep'];
    $update = $_POST['update'];

    if (empty($department)) {
        header('location:viewdep.php');
        exit;
    } else {
        if ($update) {
            $query = (" UPDATE `department` SET `department`='" . $department . "' WHERE `id`='" . $update . "' ");
            $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
            header('location:viewdep.php');
            exit;
        }
    }
    header('location:viewdep.php');
    exit;
}

header('location:viewdep.php');

?>
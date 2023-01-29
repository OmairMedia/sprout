<?php
session_start();
include('db.php');
if(isset($_POST['change_submit'])) {


    $password = md5($_POST['pasword']);
    $email = $_POST['email'];

    if (empty($password))
    {
        header("location:login.php");

    }

    else {
        $query = ("UPDATE `user` SET `u_password`='" . $password . "' WHERE `u_email`='" . $email . "'");
        $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
        $_SESSION['pass-succuss'] = "Password updated successfully";
        header('location:login.php');

    }

}
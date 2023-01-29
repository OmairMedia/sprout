<?php
// session_set_cookie_params(86400,"/");

// session_start();
include('db.php');
include_once('forget-password.php');

$email = $_POST['forget-email'];
    $token = md5(rand(1,100));
    $query = "SELECT * from `user` WHERE `u_email`='".$email."'";
    $result = mysqli_query($con, $query) or die (mysqli_error($con));
    if(mysqli_num_rows($result)>0){
        // forget_mail2($email,$token);
        if(function_exists('forget_mail')){
            // echo "User exists!";
            if(forget_mail($email,$token)) {
                $query = "INSERT INTO `forget_password`( `forget_email`,`token`) VALUES ('".$email."','".$token."')";
                $result = mysqli_query($con,$query);
                $_SESSION['emai-sent'] = "Reset link has been sent to your email account";
                echo 'Email has been sent';
                header('location:login.php');
                
            }else{
                echo "error";
                exit;

            }
        }else{
            echo "not sent";
            exit;

        }
    }else{
        echo "User did not exists!";
        exit;

    }


// if(isset($_POST['send'])){
    
// }else{
//     echo "very bad - send not found!";
//     exit;

// }


if(isset($_POST['submit'])) {


    $password = md5($_POST['pasword']);
    
    if (empty($password))
    {
        header("location:login.php");
        exit;
        
    }

    else {
            $query = ("UPDATE `user` SET `u_password`='" . $password . "' WHERE `u_email`='" . $_SESSION['e-mail'] . "'");
            $query_run = mysqli_query($con, $query) or die(mysqli_error($con));
        
        header('location:login.php');
        exit;


    }

}

?>
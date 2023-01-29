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
if (isset($_POST['submit'])) {
    if(!empty($_POST['e-mail']) && !empty($_POST['password'])){
        $email = $_POST['e-mail'];
        $password = md5($_POST['password']);
        $query = "SELECT * FROM `user` WHERE `u_email`='" . $email . "' AND `u_password`='" . $password . "'";
        $result = mysqli_query($con, $query) or die (mysqli_error($con));
        $row = mysqli_fetch_array($result);
        if (mysqli_num_rows($result) > 0) {
            $_SESSION['IsValid'] = true;
            $_SESSION['U_id'] = $row['U_id'];
            $_SESSION['U_name'] = $row['U_name'];
            $_SESSION['u_email'] = $row['u_email'];
            $_SESSION['user_designation'] = $row['user_designation'];
            $_SESSION['Account_type'] =$row['Account_type'];

            if($row['Active'] === 'inactive') {
                $_SESSION['error_message'] = "You are now inactive! Please contact admin for more info!";
                header('location:login.php');
            } else {
                if (empty($email) || empty($password)) {
                    header('location:login.php');
                } else {
                    if (mysqli_num_rows($result) == 1) {
    
                        $_SESSION['e-mail'] = $email;
                        if(isset($_POST['remember'])) {
                            $year = time() + 31536000;
                            setcookie('remember_me', $_POST['e-mail'], $year);
                            setcookie('remember_me_pass', $_POST['password'], $year);
                        }
                        header('location:home.php');
                    }
    
                }
            }
            
        }else{
            $_SESSION['error_message'] = "Oops.. ! Wrong email or password :(";
            header('location:login.php');
        }
    }else{
        $_SESSION['empty_message'] = "E-mail or Password Empty.";
        header('location:login.php');
    }
}
//AND (`Account_type`='employee' OR `Account_type`='sub-admin')
?>
<?php
include '../db.php';

$userid = $_POST['ID'];


//CHECK IN TIME FOR USER 
date_default_timezone_set("Asia/Karachi");
$date = date("Y-m-d");
$current_time = date('h:i:s a');

/* CHECK DATA To already check in */
$get_data_chk = "Select * From user_attendance Where uid='" . $userid . "' AND date='" . $date . "'";
$get_qu_chk = mysqli_query($con, $get_data_chk) or die (mysqli_error($con));
$get_count_rows = mysqli_num_rows($get_qu_chk);
    
if(empty($get_count_rows)){
  $check_insert = "INSERT INTO `user_attendance`(`uid`,`date`,`swipein_time`,`user_remarks`)  VALUES('" . $userid . "','" . $date . "','" . $current_time . "','Present')";
  $queryrun_insert = mysqli_query($con, $check_insert) or die (mysqli_error($con));
    }
    /*else
    {
        $check_in = "UPDATE `user_attendance` SET `uid` = '" . $userid . "' AND `date`='" . $date . "' AND `swipein_time`='" . $current_time . "' AND `user_remarks` ='Present'";
        $queryrun_in = mysqli_query($con, $check_in) or die (mysqli_error($con));
    }
     */
    if ($get_count_rows > 0) {
        $chk_rows = $get_qu_chk->fetch_assoc();
        $chk_checkout_time = $chk_rows['checkout_time'];
        if (!empty($chk_checkout_time)) {
            echo "User's Today attendance already checked in !";
        } else {
            echo "User has already checked in.";
        }
    } else {      
        $last_id = mysqli_insert_id($con);
        $_SESSION['last_id'] = $last_id;
        $checkin = 'online';
        if ($check_in) {
            echo "User is now checked in successfully!";
        }
        $updatstartstatus = "UPDATE `user` SET `is_status`='" . $checkin . "' WHERE `U_id`='" . $userid . "'";
        $updatestatus_query = mysqli_query($con, $updatstartstatus);
    }

 //CHECK IN ENDS HERE ----------------------------------------------------------------------------------------
 


?>
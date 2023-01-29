<?php
include '../db.php';

$userid = $_POST['grab_id'];


//CHECK IN TIME FOR USER 
date_default_timezone_set("Asia/Karachi");
$date = date("Y-m-d");
$current_time = date('h:i:s a');
$checkin =$_POST['grab_checkinadmin'];
$checkintime =strtotime($checkin);
$formatedcheckin =date("h:i:s a", $checkintime);

if(isset($_POST['data']) && $_POST['data'] == 'Checkin')
{
/* CHECK DATA To already check in */
$get_data_chk = "Select * From user_attendance Where uid='" . $userid . "' AND date='" . $date . "'";
$get_qu_chk = mysqli_query($con, $get_data_chk) or die (mysqli_error($con));
$get_count_rows = mysqli_num_rows($get_qu_chk);
    
    if ($get_count_rows > 0) {
        $chk_rows = $get_qu_chk->fetch_assoc();
        $chk_checkout_time = $chk_rows['checkout_time'];
        if (!empty($chk_checkout_time)) {
            $check_in = "UPDATE `user_attendance` SET `swipein_time`='" . $formatedcheckin. "',`user_remarks` ='Present' WHERE `uid`='".$userid."' AND `date`='".$date."' ";
            $queryrun_in = mysqli_query($con, $check_in) or die (mysqli_error($con));
           
            $last_id = mysqli_insert_id($con);
            $_SESSION['last_id'] = $last_id;
            $checkin = 'online';
            if ($queryrun_in) {
                header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
                echo "Checkin Time Updated Successfully";
            }
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $checkin . "' WHERE `U_id`='" . $userid . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);
        } else {
            $check_in = "UPDATE `user_attendance` SET `swipein_time`='" . $formatedcheckin. "',`user_remarks` ='Present' WHERE `uid`='".$userid."' AND `date`='".$date."' ";
            $queryrun_in = mysqli_query($con, $check_in) or die (mysqli_error($con));
           
            $last_id = mysqli_insert_id($con);
            $_SESSION['last_id'] = $last_id;
            $checkin = 'online';
            if ($queryrun_in) {
                header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
                echo "Checkin Time Updated Successfully";
            }
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $checkin . "' WHERE `U_id`='" . $userid . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);
        }
    } 

 //CHECK IN ENDS HERE ----------------------------------------------------------------------------------------
}


?>
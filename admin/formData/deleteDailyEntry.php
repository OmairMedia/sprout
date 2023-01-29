<?php
include "hereDBConfig.php";
global $pdo_con;
date_default_timezone_set("Asia/Karachi");


$sel_id = $_POST['att_id'];
echo $sel_id;
    
if(!empty($sel_id)) {
     //CALCULATIONS 
$sql1 ="SELECT * from `user_attendance` where `id`='".$sel_id."'";
$sql_query1 =mysqli_query($con,$sql1) or die(mysqli_error($con));
$sql_fetch1 = mysqli_fetch_assoc($sql_query1);

$set_checkin = 'auto';
$set_checkout = 'auto';
$set_remarks = 'Deleted By Admin';

if(!empty($sql_fetch1['swipein_time']))
{
    $update_user_auto = "UPDATE `user_attendance` SET `swipein_time`='".$set_checkin."',`checkout_time`='".$set_checkout."',`total_working_hour`='',`user_remarks`='".$set_remarks."' WHERE `id`='".$sel_id."' ";
    $update_user_auto_query = mysqli_query($con, $update_user_auto);
    
    echo 'Deleted!';
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
}
} else {
    echo 'att_id error !';
}


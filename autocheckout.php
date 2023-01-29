<?php
include 'db.php';
date_default_timezone_set("Asia/Karachi");
$now = date("Y-m-d");
$d = date("D",strtotime($now));
$nowtime =date("h:i:s a");
$test = "INSERT INTO `test`(`Status`) VALUES('".$d.$nowtime."')";
$test_query = mysqli_query($con, $test);


// Get All Active Users 
$activeUsersQuery = "Select * from `user` where `Active`!='inactive'";
$checkactiveUsersQuery =mysqli_query($con,$activeUsersQuery) or die(mysqli_error($con));
$activeUsers = mysqli_num_rows($checkactiveUsersQuery);
echo($activeUsers);

$i = 0;
while($ck_online_data = mysqli_fetch_assoc($checkactiveUsersQuery))
{
    // Check Present Today
    $checkPresentQuery = "Select * from `user_attendance` where `uid`=".$ck_online_data['U_id']." and date='".$now."'";
    $checcheckPresentQuery =mysqli_query($con,$checkPresentQuery) or die(mysqli_error($con));
    $presentData = mysqli_fetch_assoc($checcheckPresentQuery);

    if(empty($presentData)) {
        // Generate A Absent Record
        $absentInsertQuery = "INSERT INTO `user_attendance`(`uid`,`date`,`checkout_time`,`total_working_hour`,`total_break_hours`,`user_remarks`)  VALUES ('" . $ck_online_data['U_id'] . "','" . $now . "','auto','00:00:00','00:00:00','Absent')";
        $checkabsentInsertQuery =mysqli_query($con,$absentInsertQuery) or die(mysqli_error($con));
    } 
    echo '<br>';
    print_r($ck_online_data['U_id']);
    echo '<br>';
    $i++;
}





$check_online = "Select * from `user` as u JOIN `user_attendance` as a on u.U_id = a.uid where `is_status`!='offline' and date='".$now."'";

$check_online_query =mysqli_query($con,$check_online) or die(mysqli_error($con));
$count_rows = mysqli_num_rows($check_online_query);

$i = 0;
while($ck_online_data = mysqli_fetch_assoc($check_online_query))
{
$arr[$i] = $ck_online_data['uid'];
$i++;
}


for($a=0; $a<count($arr);$a++)
{
$checkout = "UPDATE `user_attendance` SET `checkout_time`='auto' , `total_working_hour`='00:00:00',`breakin_time`='',`break_count`='',`total_break_hours`='00:00:00',`total_office_hours`='00:00:00',`user_remarks`='FCO' where `uid`='".$arr[$a]."' and `date`='".$now."' ";
$checkout_query =mysqli_query($con,$checkout) or die(mysqli_error($con));
$status_clear = "UPDATE `user` SET `is_status`='offline' WHERE `U_id`='".$arr[$a]."'";
$status_query =mysqli_query($con,$status_clear) or die(mysqli_error($con)); 

if($_SESSION['U_id'] == $arr[$a]){
     session_destroy();
}
}

session_destroy();





?>
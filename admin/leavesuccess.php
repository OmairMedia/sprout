<?php
//If the HTTPS is not found to be "on"
include('db_func.php');
date_default_timezone_set("Asia/Karachi");
global $con;
$id = $_POST['reqid'];


$selectquery="SELECT * FROM `leave_request` where `ID` = '".$id."'";
$runquery=mysqli_query($con,$selectquery) or die (mysqli_error($con));
if(mysqli_num_rows($runquery)>0){
 $row = mysqli_fetch_array($runquery);
}
$userid = $row['User_id'];
$dateforreq = $row['Req_Made_For'];
$checkin_time = '10:00:00 am';
$checkout_time = '06:00:00 pm';
$total_office_time = '08:00:00';
$total_working_time = '08:00:00';
$remarks = $row['Remarks'];

if($row['Is_Approved'] == 'No_Action'){
    $true = 'Approved';
    $updatequery="UPDATE `leave_request` SET `Is_Approved` = '".$true."' where ID = '".$id."'";
    $run_update=mysqli_query($con,$updatequery) or die (mysqli_error($con));
    }
    if($run_update)
    {
        $sql ="SELECT * from `user_attendance` where `uid`='".$userid."' and `date`='".$dateforreq."' ";
        $sql_query =mysqli_query($con,$sql) or die(mysqli_error($con));
        $sql_fetch = mysqli_fetch_assoc($sql_query);
       
        $coffinmew1 = "UPDATE `user_attendance` SET `swipein_time`='" . $checkin_time . "',`checkout_time`='" . $checkout_time . "',`total_working_hour`='" . $total_working_time . "',`total_office_hours`='" . $total_working_time . "',`user_remarks`='".$remarks."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
        $coffin_query1 = mysqli_query($con, $coffinmew1) or die (mysqli_error($con)); 

        echo "Request Has Been Approved";
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    }

echo "Request Has Been Approved";
?>



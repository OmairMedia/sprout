<?php
include 'db.php';
session_set_cookie_params(86400, "/");
session_start();
date_default_timezone_set("Asia/Karachi");

$approval = 'No_Action';
$remark = 'Absent';
$userid = $_SESSION['U_id'];
$username = $_SESSION['U_name'];
$curY = date("Y");

$formRemarks = $_POST['grab_remarks'];
$dateforreq = $_POST['grab_date'];

$query_validate = "select * from `user_attendance` where `date` = '".$dateforreq."' AND `uid` ='".$userid."'  AND `user_remarks` = '".$remark."' ";
$result_validate = mysqli_query($con, $query_validate) or die (mysqli_error($con));
$count_validate = mysqli_num_rows($result_validate);

if($count_validate > 0) {
    $req = "INSERT INTO `leave_request`( `User_id`,`User_Name`,`Req_Made_For`,`Req_Made_On`,`Is_Approved`,`Remarks`) VALUES (".$userid.",'".$username."','".$dateforreq."',now(),'".$approval."','".$formRemarks."')";
    $queryrun= mysqli_query($con, $req) or die (mysqli_error($con));
    if($queryrun)
    {
    echo "Your Request Has Been Sent";
    exit;
    }  
} else {
    echo "This record does not have absent remarks!";
}

?>
 
 

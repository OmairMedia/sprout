<?php
include 'db.php';
session_set_cookie_params(86400, "/");
session_start();

if(isset($_POST['data']) && $_POST['data'] == 'breakrequest')
{
$totalbreaktime = $_POST['grab_break'];
$remarks = $_POST['grab_remarks'];
$approval = 'No_Action';
date_default_timezone_set("Asia/Karachi");
$sessionid = $_SESSION['U_id'];
$curYM = date("Y-m");

$query = "select * from `user_attendance` where `uid`='".$sessionid."' and `date` like '{$curYM}%' ORDER BY `date` DESC";
$result = mysqli_query($con, $query) or die (mysqli_error($con));
if(mysqli_num_rows($result)>0){
 $row = mysqli_fetch_array($result);
};

$userid = $_SESSION['U_id'];
$dateforreq =$_POST['grab_date'];
$username =$_SESSION['U_name'];
$timestamp = date('h:i:s a');

$current_checkin = $_POST['checkingrab'];
$current_checkout = $_POST['checkoutgrab'];
$current_working_hours = $_POST['workinghoursgrab'];
$current_break_hours = $_POST['breakhoursgrab'];


//Checking if Request Already made for That Date 
$req_validate = "select * from `break_request` where `Req_Made_For` = '".$dateforreq."' AND `User_id` ='".$userid."' AND `Is_Approved`='No_Action' ";
$result_req_validate = mysqli_query($con, $req_validate) or die (mysqli_error($con));
$count_req_validate = mysqli_num_rows($result_req_validate);


if($count_req_validate <= 0)
{
    $query_validate = "select * from `user_attendance` where `date` = '".$dateforreq."' AND `uid` ='".$userid."' AND NOT `user_remarks`='Absent'";
    $result_validate = mysqli_query($con, $query_validate) or die (mysqli_error($con));
    $count_validate = mysqli_num_rows($result_validate);

if($count_validate > 0)
{
 $req = "INSERT INTO `break_request`(`User_id`,`User_Name`,`Total_Breaktime`,`Req_Made_On`,`Req_Made_For`,`Is_Approved`,`current_checkout`,`current_working_hours`,`current_break_hours`,`current_checkin`,`remarks`) VALUES ('".$userid."','".$username."','".$totalbreaktime."',now(),'".$dateforreq."','".$approval."','".$current_checkout."','".$current_working_hours."','".$current_break_hours."','".$current_checkin."','".$remarks."')";
 $queryrun= mysqli_query($con, $req) or die (mysqli_error($con));
    if($queryrun) {
          echo "Your Request has been sent"; 
          header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 200);
    }
} else {
        echo "You Already Have Break in Choosen Record";
}

} else
    {
        echo "You Already Made Request For This Date";
}


}

?>
 
 












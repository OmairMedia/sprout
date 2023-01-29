<?php
include 'db.php';
session_set_cookie_params(86400, "/");
session_start();
date_default_timezone_set("Asia/Karachi");

if (isset($_POST['data'])) {

// Current Checkin
    $current_checkin = $_POST['currentCheckin'];


// Current Checkout
    $current_checkout = $_POST['currentCheckout'];
    if (empty($current_checkout)) {
        $current_checkout = '00:00:00';
    }
// if(empty($gcheckin)) {
//     $current_checkout = 'auto';
// }

    $gbreak = $_POST['grab_break'];
    $gcheckin = $_POST['checkintime'];
    if (empty($gcheckin)) {
        $gcheckin = $current_checkin;
    }
    $gcheckout = $_POST['grab_checkout'];
    $checkouttime = strtotime($gcheckout);
    $formatedcheckout = date("h:i:s a", $checkouttime);

    $approval = 'No_Action';

    $sessionid = $_SESSION['U_id'];
    $curY = date("Y");

    $query = "select a.*,u.`U_name`,u.`is_status` from `user_attendance` a,`user` u where a.`uid`=u.`U_id` and u.`U_id`='{$_SESSION['U_id']}' and a.`date` like '{$curY}%' ORDER BY a.`date` DESC";
    $result = mysqli_query($con, $query) or die (mysqli_error($con));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
    } else {
        echo "get user error!";
    }
    $userid = $_SESSION['U_id'];
    $dateforreq = $_POST['grab_date'];
    $username = $_SESSION['U_name'];
    $remarks = $_POST['grab_remarks'];

//Checking if Request Already made for That Date 
    $req_validate = "select * from `requests` where `Req_Made_For` = '" . $dateforreq . "' AND `User_id` ='" . $userid . "' AND `Is_Approved` !='Disapproved' ";
    $result_req_validate = mysqli_query($con, $req_validate) or die (mysqli_error($con));
    $count_req_validate = mysqli_num_rows($result_req_validate);

    if ($count_req_validate <= 0) {
//Checking if Record Exist
        $query_validate = "select * from `user_attendance` where `date` = '" . $dateforreq . "' AND `uid` ='" . $userid . "' ";
        $result_validate = mysqli_query($con, $query_validate) or die (mysqli_error($con));
        $count_validate = mysqli_num_rows($result_validate);

        if ($count_validate > 0) {

            $req = "INSERT INTO `requests`( `User_id`,`User_Name`,`Req_Made_For`,`Req_Made_On`,`Is_Approved`,`Remarks`,`checkout_time`,`breaktime`,`checkin_time`,`current_checkout`) VALUES (" . $userid . ",'" . $username . "','" . $dateforreq . "',now(),'" . $approval . "','" . $remarks . "','" . $formatedcheckout . "','" . $gbreak . "','" . $gcheckin . "','" . $current_checkout . "')";
            $queryrun = mysqli_query($con, $req) or die (mysqli_error($con));
            if ($queryrun) {
                echo "Your Request Has Been Sent";
            } else {
                echo "Insert Statement Error!";
            }

        } else {
            echo "No Record Exist On This Date";
        }

    } else {
        echo "You Have Already Made Request For This Date";
    }
}
?>
 
 

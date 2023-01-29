<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}

include 'db.php';

session_start();
date_default_timezone_set("Asia/Karachi");

$logindiff = $_POST["login_difference"];
$previousday = $_POST["then"];
$now = date("Y-m-d");

$differencedays = abs($logindiff);


for($i=0;$i<$differencedays;$i++)
{       
    $cenvertedTime = date('Y-m-d',strtotime('+1 day',strtotime($previousday)));
       // $start = strtotime($cenvertedTime);
       // $now = date("Y-m-d");
       // $end = strtotime($now);
    if(date("Y-m-d") > $cenvertedTime)
    {
        if($cenvertedTime === date("Y-m-d"))
        {
            die;
            exit;
        }
         //Checking If Record Has Been Generated Already
        $check_record = "SELECT * FROM `user_attendance` WHERE `uid`='".$_SESSION['U_id']."' AND `date`='".$cenvertedTime."' ";
        $queryrunvalidate = mysqli_query($con, $check_record);
        $validate_count =mysqli_num_rows($queryrunvalidate);
        if($validate_count == 0)
        {
            $d = date('D',strtotime($cenvertedTime));
            
         if(strtolower($d) == ('sun')) { 
            $check_in = "INSERT INTO `user_attendance`(`uid`, `date`,`swipein_time`,`total_working_hour`,`total_office_hours`,`user_remarks`) VALUES ('" . $_SESSION['U_id'] . "','" . $cenvertedTime . "',null,'00:00:00','00:00:00','Sunday')";
            $queryrun = mysqli_query($con, $check_in);
         }else {
            $check_in = "INSERT INTO `user_attendance`(`uid`, `date`,`swipein_time`,`total_working_hour`,`total_office_hours`,`user_remarks`) VALUES ('" . $_SESSION['U_id'] . "','" . $cenvertedTime . "',null,'00:00:00','00:00:00','Absent')";
            $queryrun = mysqli_query($con, $check_in);
         }    
        
        }else
        {
            continue;
        }
        
        $previousday =date('Y-m-d',strtotime('+1 day',strtotime($previousday)));
     
    }
    else
    {
       continue;
    }
}
?>
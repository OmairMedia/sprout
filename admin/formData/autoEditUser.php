<?php
include "hereDBConfig.php";
global $pdo_con;
date_default_timezone_set("Asia/Karachi");


if(isset($_POST['att_id']) && isset($_POST['grabcheckout']) && isset($_POST['grabbreak']) && isset($_POST['user_remarks']) ){
    $sel_id = $_POST['att_id'];
    
    $set_remarks = $_POST['user_remarks'];
    $set_remarks2 = "(Edited By Admin)" . $set_remarks;
    $set_break = $_POST['grabbreak'];
    $gcheckout = $_POST['grabcheckout'];    
    $checkouttime = strtotime($gcheckout);
    $set_checkout = date("h:i:s a",$checkouttime);
    $data = ['err' => ""];
    
     //CALCULATIONS 
    $sql1 ="SELECT * from `user_attendance` where `id`='".$sel_id."'";
    $sql_query1 =mysqli_query($con,$sql1) or die(mysqli_error($con));
    $sql_fetch1 = mysqli_fetch_assoc($sql_query1);
    if(!empty($sql_fetch1['swipein_time']))
    {
        $update_user_auto = "UPDATE `user_attendance` SET `checkout_time`='".$set_checkout."',`break_count`='".$set_break."', `user_remarks`='".$set_remarks2."' WHERE `id`='".$sel_id."' ";
        $update_user_auto_query = mysqli_query($con, $update_user_auto);
        

       //Calculations

           $sql = "SELECT * From `user_attendance` where `id`='" . $sel_id . "' ";
           $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
           $sql_fetch = mysqli_fetch_assoc($sql_query);
           $start = $sql_fetch['swipein_time'];
           $stop = $sql_fetch['checkout_time'];

           $exc_time1 = strtotime($start) - strtotime("00:00:00");
           $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);

           $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "'";
           $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
           $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id . "'";
           $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
           $sql_fetch = mysqli_fetch_assoc($sql_query);
           $start = $sql_fetch['break_count'];
           if(!empty($start)) {
               if(strpos($start,'|')){
                   $stop = $sql_fetch['total_office_hours'];
                   $exp_multi_break  = explode('|',$start);
                   $grab_time = strtotime('00:00:00');
                   $sub_time = strtotime($stop);
                   foreach($exp_multi_break as $sing_time){
                       $grab_time += strtotime($sing_time) - strtotime('00:00:00');
                       $sub_time -= strtotime($sing_time) - strtotime('00:00:00');
                   }

                   $tot_break_hours = date('H:i:s', $grab_time);
                   $result = date("H:i:s", $sub_time);
               }else{
                   $stop = $sql_fetch['total_office_hours'];
                   $secs = strtotime($start) - strtotime("00:00:00");
                   $result = date("H:i:s", strtotime($stop) - $secs);
                   $tot_break_hours = $start;
               }
           }else{
               $result = $sql_fetch['total_office_hours'];
               $tot_break_hours = '';
           }

           $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "'";
           $update_sql_query = mysqli_query($con, $update_total_working_hour);
           if($update_sql_query)
           {
            $data['err'] = '';
           }
           
           echo "Changes Are Been Made !";
           header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
       }
   
         
       echo "Changes Are Been Made !";    
}

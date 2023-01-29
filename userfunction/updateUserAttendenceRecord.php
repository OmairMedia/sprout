<?php
include '../db.php';
date_default_timezone_set("Asia/Karachi");
$date = date("Y-m-d");
$id = $_POST['id'];


$grab_checkin = $_POST['grab_checkin'];
$grab_checkout = $_POST['grab_checkout'];


if(!empty($_POST['breakin'])){
    $breakin = $_POST['breakin'];
} else {
    $breakin = "";
}

if(!empty($_POST['breakout'])) {
    $breakout = $_POST['breakout'];
} else {
    $breakout = "";
}



$get_user_data = "Select * From user_attendance Where uid='" . $id . "' AND date='" . $date . "'";
$get_user_data_check = mysqli_query($con, $get_user_data) or die (mysqli_error($con));
$get_data_rows = mysqli_fetch_assoc($get_user_data_check);

$tot_break_hours = "";

if(empty($get_data_rows)) {
    echo "User Data Not Found!";
} else {

    if(!empty($grab_checkin)) {
        // Update Break
        if(!empty($breakin) && !empty($breakout)) {
            
            $break_start_time = strtotime($breakin) - strtotime('00:00:00');
            $tot_break_hours = date('H:i:s', strtotime($breakout) - $break_start_time);
            $stop = $get_data_rows['total_office_hours'];
            $secs = strtotime($tot_break_hours) - strtotime("00:00:00");
            $total_working_hour = date("H:i:s", strtotime($stop) - $secs);

            $update_query = "UPDATE `user_attendance` SET `swipein_time`='" . $grab_checkin . "',`checkout_time`='" . $grab_checkout . "', `total_working_hour`='" . $total_working_hour . "',`break_count`='" . $tot_break_hours . "',`total_break_hours`='" . $tot_break_hours . "' WHERE `uid`='" . $id . "' AND `date`='" . $date . "' ";
            $update_query_check = mysqli_query($con, $update_query);

            echo "Break Updated Successfully!";
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 200);

        } 
        // Update Checkout Also
        if(!empty($grab_checkout)) {
           if(empty($grab_checkin)) {
             echo 'Please provide checkin time!';
           } else {
                
                $calculate_working_hours = strtotime($grab_checkin) - strtotime("00:00:00");
                $total_working_hour = date("H:i:s", strtotime($grab_checkout) - $calculate_working_hours);


                if(!empty($get_data_rows['break_count'])) {
                    if(strpos($get_data_rows['break_count'],'|')){
                        $stop = $get_data_rows['total_office_hours'];
                        $exp_multi_break  = explode('|',$start);
                        $grab_time = strtotime('00:00:00');
                        $sub_time = strtotime($stop);
                        foreach($exp_multi_break as $sing_time){
                            $grab_time += strtotime($sing_time) - strtotime('00:00:00');
                            $sub_time -= strtotime($sing_time) - strtotime('00:00:00');
                        }
            
                        $tot_break_hours = date('H:i:s', $grab_time);
                        $total_working_hour = date("H:i:s", $sub_time);
                    }else{
                        $stop = $get_data_rows['total_office_hours'];
                        $secs = strtotime($get_data_rows['break_count']) - strtotime("00:00:00");
                        $total_working_hour = date("H:i:s", strtotime($stop) - $secs);
                        $tot_break_hours = $get_data_rows['break_count'];
                    }
                     
                    $update_query = "UPDATE `user_attendance` SET `swipein_time`='" . $grab_checkin . "',`breakin_time`='',`checkout_time`='" . $grab_checkout . "', `total_working_hour`='" . $total_working_hour . "',`break_count`='" . $tot_break_hours . "',`total_break_hours`='" . $tot_break_hours . "' WHERE `uid`='" . $id . "' AND `date`='" . $date . "' ";
                    $update_query_check = mysqli_query($con, $update_query);
                    
                    $of = 'offline';
                    $updatstartstatus = "UPDATE `user` SET `is_status`='" . $of . "' WHERE `U_id`='" . $id . "' ";
                    $updatestatus_query = mysqli_query($con, $updatstartstatus);
                    
                    echo "User checked out successfully!";
                    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 200);
        
                    
                } else {
            
                        $update_query = "UPDATE `user_attendance` SET `swipein_time`='" . $grab_checkin . "',`checkout_time`='" . $grab_checkout . "', `total_working_hour`='" . $total_working_hour . "',`break_count`='" . $tot_break_hours . "',`total_break_hours`='" . $tot_break_hours . "' WHERE `uid`='" . $id . "' AND `date`='" . $date . "' ";
                        $update_query_check = mysqli_query($con, $update_query);
                
                        $of = 'offline';
                        $updatstartstatus = "UPDATE `user` SET `is_status`='" . $of . "' WHERE `U_id`='" . $id . "' ";
                        $updatestatus_query = mysqli_query($con, $updatstartstatus);
                        echo "User checked out successfully!"; 
                        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 200);
        
                }  
           } 

          
        } else {
            $update_query = "UPDATE `user_attendance` SET `swipein_time`='" . $grab_checkin . "' WHERE `uid`='" . $id . "' AND `date`='" . $date . "' ";
            $update_query_check = mysqli_query($con, $update_query);
            echo "Checkin time updated!";
            header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 200);
        }
    } else {
        echo 'Checkin cannot empty!';
    }
}
?>
<?php
 include '../db.php';
    date_default_timezone_set("Asia/Karachi");
    $date = date("Y-m-d");
    $userid = $_POST['grab_id'];
    $checkin =$_POST['grab_checkin'];
    $checkout =$_POST['grab_checkout'];
    $checkouttime =strtotime($checkout);
    $formatedcheckout =date("h:i:s a",$checkouttime);

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}



 

 //CHECK OUT STARTS HERE -------------------------------------------------------------------------------
if(isset($_POST['data']) && $_POST['data'] == 'Checkout' )
 {
     /* CHECK DATA To already check in */
     $get_attendence_data = "Select * From user_attendance Where uid='" . $userid . "' AND date='" . $date . "'";
     $get_attendence_chk = mysqli_query($con, $get_attendence_data) or die (mysqli_error($con));
     $get_attendence = mysqli_fetch_assoc($get_attendence_chk);
 
    if(empty($get_attendence)){
                alert("User is not checked in!");
                // echo "User is not checked in!";
                debug_to_console('User not checkin!')
    } else {

    
    
    // if User Dont Have Checkout
    if(empty($get_attendence['checkout_time'])) {
      
    } else {
      // If User Have Checkout
    }

    // if User Currently On Break
    if($get_attendence['breakin_time']) {
       
    } else {
       
    }


    
    $exacttime = date('h:i:s a');

    $sel_user_status_stm = "SELECT `is_status` FROM `user` WHERE `U_id`='" . $userid . "'";
    $sel_user_status_qur = mysqli_query($con, $sel_user_status_stm);
    $sel_user_status_res = mysqli_fetch_assoc($sel_user_status_qur);
    $cur_status = $sel_user_status_res['is_status'];
    // Update Checkout Time
    $stop = "UPDATE `user_attendance` SET `checkout_time`='" . $formatedcheckout . "' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
    $break_query2 = mysqli_query($con, $stop) or die (mysqli_error($con));

        if ($break_query2) {

            $sql = "SELECT * From `user_attendance` where `uid`='" . $userid . "' AND `date`='" . $date . "'";
            $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
            $sql_fetch = mysqli_fetch_assoc($sql_query);
            $start = $sql_fetch['swipein_time'];
            $stop = $sql_fetch['checkout_time'];
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            // After Calculating & Inserting Total Working Hours
            $sql = "SELECT * From `user_attendance` WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
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

            $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `uid`='" . $userid . "' AND `date`='" . $date . "'";
            $update_sql_query = mysqli_query($con, $update_total_working_hour);

            $of = 'offline';
            $updatstartstatus = "UPDATE `user` SET `is_status`='" . $of . "' WHERE `U_id`='" . $userid . "'";
            $updatestatus_query = mysqli_query($con, $updatstartstatus);//update stop time and counter
            
            // header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
            alert("User checked out successfully!");
            debug_to_console('User checked out successfully!')
        } else {
            print('ASdiojnaoi dnsaio das!');
            debug_to_console('ASdiojnaoi dnsaio das!!')
        }

    // if ($cur_status == 'online') {
    // } else {
    //     header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //     echo "User is in break. Please first of all -Break Out- button is click to change the status";
    // }
   
 }
 //CHECK OUT ENDS HERE -------------------------------------------------------------------------------   
}

?>
<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['att_id']) && isset($_POST['set_checkin']) && isset($_POST['set_checkout']) && isset($_POST['set_breaktime']) && isset($_POST['user_remarks'])){
   
   
    $sel_id = $_POST['att_id'];
    $set_checkin = $_POST['set_checkin'];
    $set_checkout = $_POST['set_checkout'];
    $set_breaktime = $_POST['set_breaktime'];
    $set_remarks = "(Edited By Admin)".$_POST['user_remarks'];
    $sel_date = $_POST['sel_date'];
    $data = ['err' => ""];
    
    if(empty($set_checkin))
    {
            $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `checkout_time`=?, `break_count`=?, `user_remarks`=? WHERE `id`=?");
            $selUserData->bindValue(1, $set_checkout);
            $selUserData->bindValue(2, $set_breaktime);
            $selUserData->bindValue(3, $set_remarks);
            $selUserData->bindValue(4, $sel_id);
            $check = $selUserData->execute();
             //CALCULATING TOTAL OFFICE HOURS **************************************************************************
    if($check){ 
            $sqlcheckin = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
            $sql_query_checkin = mysqli_query($con, $sqlcheckin) or die (mysqli_error($con));
            $sql_fetch = mysqli_fetch_assoc($sql_query_checkin);
            $start = $sql_fetch['swipein_time'];
            $stop = $set_checkout;
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            //CALCULATING TOTAL OFFICE HOURS ENDS **************************************************************************

          
          
            //Calculating TOTAL WORKING HOURS *************************************************************
            $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$set_breaktime."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
            if($sql_query_select){
             // After Calculating & Inserting Total Working Hours
             $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
             $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
             $sql_fetch = mysqli_fetch_assoc($sql_query);
             $start = $set_breaktime;
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
         
             if($tot_break_hours >= $result)
             {
                 $nullify = '00:00:00';
             $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
             $update_sql_query = mysqli_query($con, $update_total_working_hour1);
             }
             else{
             $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "' ";
             $update_sql_query = mysqli_query($con, $update_total_working_hour);
             }
             }
               $data['err'] = '';
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit; 
             
        }else
        {
               $data['err'] = $selUserData->errorinfo();
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit;  
        }
    }else if(empty($set_checkout))
    {
            $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `swipein_time`=?, `break_count`=?, `user_remarks`=? WHERE `id`=?");
            $selUserData->bindValue(1, $set_checkin);
            $selUserData->bindValue(2, $set_breaktime);
            $selUserData->bindValue(3, $set_remarks);
            $selUserData->bindValue(4, $sel_id);
            $check = $selUserData->execute();
            //CALCULATING TOTAL OFFICE HOURS **************************************************************************
    if($check){ 
            $sqlcheckout = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
            $sql_query_checkout = mysqli_query($con, $sqlcheckout) or die (mysqli_error($con));
            $sql_fetch = mysqli_fetch_assoc($sql_query_checkout);
            $start = $set_checkin;
            $stop = $sql_fetch['checkout_time'];
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            //CALCULATING TOTAL OFFICE HOURS ENDS **************************************************************************

          
          
            //Calculating TOTAL WORKING HOURS *************************************************************
            $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$set_breaktime."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
            if($sql_query_select){
             // After Calculating & Inserting Total Working Hours
             $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
             $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
             $sql_fetch = mysqli_fetch_assoc($sql_query);
             $start = $set_breaktime;
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
         
             if($tot_break_hours >= $result)
             {
                 $nullify = '00:00:00';
             $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
             $update_sql_query = mysqli_query($con, $update_total_working_hour1);
             }
             else{
             $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "' ";
             $update_sql_query = mysqli_query($con, $update_total_working_hour);
             }
             }
               $data['err'] = '';
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit; 
             
        }else
        {
               $data['err'] = $selUserData->errorinfo();
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit;  
        }
    }else if(empty($set_breaktime))
    {
            $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `swipein_time`=?, `checkout_time`=?, `user_remarks`=? WHERE `id`=?");
            $selUserData->bindValue(1, $set_checkin);
            $selUserData->bindValue(2, $set_checkout);
            $selUserData->bindValue(3, $set_remarks);
            $selUserData->bindValue(4, $sel_id);
            $check = $selUserData->execute();
             //CALCULATING TOTAL OFFICE HOURS **************************************************************************
    if($check){ 
           
            $start = $set_checkin;
            $stop = $set_checkout;
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            //CALCULATING TOTAL OFFICE HOURS ENDS **************************************************************************

          
          
            //Calculating TOTAL WORKING HOURS *************************************************************
           // $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$set_breaktime."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
           // $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
            if($update_sql_query){
             // After Calculating & Inserting Total Working Hours
             $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
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
         
             if($tot_break_hours >= $result)
             {
                 $nullify = '00:00:00';
             $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
             $update_sql_query = mysqli_query($con, $update_total_working_hour1);
             }
             else{
             $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "' ";
             $update_sql_query = mysqli_query($con, $update_total_working_hour);
             }
             }
               $data['err'] = '';
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit; 
             
        }else
        {
               $data['err'] = $selUserData->errorinfo();
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit;  
        }
    }else if(empty($set_remarks))
    {
            $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `swipein_time`=?, `checkout_time`=?, `break_count`=? WHERE `id`=?");
            $selUserData->bindValue(1, $set_checkin);
            $selUserData->bindValue(2, $set_checkout);
            $selUserData->bindValue(3, $set_breaktime);
            $selUserData->bindValue(4, $sel_id);
            $check = $selUserData->execute();
            if(!$check){
               $data['err'] = $selUserData->errorinfo();
            }
            
            //CALCULATING TOTAL OFFICE HOURS **************************************************************************
    if($check){ 
           
            $start = $set_checkin;
            $stop = $set_checkout;
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            //CALCULATING TOTAL OFFICE HOURS ENDS **************************************************************************

          
          
            //Calculating TOTAL WORKING HOURS *************************************************************
            $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$set_breaktime."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
            if($sql_query_select){
             // After Calculating & Inserting Total Working Hours
             $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
             $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
             $sql_fetch = mysqli_fetch_assoc($sql_query);
             $start = $set_breaktime;
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
         
             if($tot_break_hours >= $result)
             {
                 $nullify = '00:00:00';
             $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
             $update_sql_query = mysqli_query($con, $update_total_working_hour1);
             }
             else{
             $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "' ";
             $update_sql_query = mysqli_query($con, $update_total_working_hour);
             }
             }
               $data['err'] = '';
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit; 
             
        }else
        {
               $data['err'] = $selUserData->errorinfo();
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit;  
        }
    }else
    {
           
            
            //Calculate Break Time 
            $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `swipein_time`=?, `checkout_time`=?, `break_count`=?,`user_remarks`=? WHERE `id`=?");
            $selUserData->bindValue(1, $set_checkin);
            $selUserData->bindValue(2, $set_checkout);
            $selUserData->bindValue(3, $set_breaktime);
            $selUserData->bindValue(4, $set_remarks);
            $selUserData->bindValue(5, $sel_id);
            $check = $selUserData->execute();
           
           //CALCULATING TOTAL OFFICE HOURS **************************************************************************
    if($check){ 
             
            $start = $set_checkin;
            $stop = $set_checkout;
            //Swipein time 
            $exc_time1 = strtotime($start) - strtotime("00:00:00");
            //Checkout Time
            $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
            $update_total_office_hours = "UPDATE `user_attendance` SET `total_office_hours`='" . $tot_off_hours . "' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
            //CALCULATING TOTAL OFFICE HOURS ENDS **************************************************************************

          
          
            //Calculating TOTAL WORKING HOURS *************************************************************
            $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$set_breaktime."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
            $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
            if($sql_query_select){
             // After Calculating & Inserting Total Working Hours
             $sql = "SELECT * From `user_attendance` WHERE `id`='" . $sel_id. "' OR `date`='" . $sel_date . "'";
             $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
             $sql_fetch = mysqli_fetch_assoc($sql_query);
             $start = $set_breaktime;
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
         
             if($tot_break_hours >= $result)
             {
                 $nullify = '00:00:00';
             $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "'";
             $update_sql_query = mysqli_query($con, $update_total_working_hour1);
             }
             else{
             $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `id`='" . $sel_id . "' OR `date`='" . $sel_date . "' ";
             $update_sql_query = mysqli_query($con, $update_total_working_hour);
             }
             }
               $data['err'] = '';
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit; 
             
        }else
        {
               $data['err'] = $selUserData->errorinfo();
                     
               header('Content-Type: application/json');
               echo json_encode($data);
               exit;  
        }
   //CALCULATION TOTAL WORKING HOURS ENDS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
}    
       
         

   
/*
    $selUserData = $pdo_con->prepare("UPDATE `user_attendance` SET `checkout_time`=?, `total_working_hour`=?, `user_remarks`=? WHERE `id`=?");
    $selUserData->bindValue(1, 'auto');
    $selUserData->bindValue(2, $set_tot_working_hours);
    $selUserData->bindValue(3, $set_remarks);
    $selUserData->bindValue(4, $sel_id);
    $check = $selUserData->execute();
    if(!$check){
        $data['err'] = "Updating Problem Check Your Connection or Database!";
    }
*/
/*
    header('Content-Type: application/json');
    echo json_encode($data);

    exit;
    */
}
header("Location: ../login.php");
exit;
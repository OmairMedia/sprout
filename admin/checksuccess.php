<?php
//If the HTTPS is not found to be "on"
include('db_func.php');
date_default_timezone_set("Asia/Karachi");
global $con;
$id;

if(empty($_POST['reqid'])) {
    echo "Changes have been made! Please reload the page!";
} else {
    $id = $_POST['reqid'];
    $selectquery="SELECT * FROM `requests` where `ID` = '".$id."'";
    $runquery=mysqli_query($con,$selectquery) or die (mysqli_error($con));
    if(mysqli_num_rows($runquery)>0){
     $row = mysqli_fetch_array($runquery);
    }
    $userid = $row['User_id'];
    $checkintime = $row['checkin_time'];
    $dateforreq = $row['Req_Made_For'];
    $breaktime =$row['breaktime'];
    $checkout_time =$row['checkout_time'];
    $remarks = $row['Remarks'];

    if ( date("Y-m-d") == $dateforreq) {
         echo "User is not Offline";
         exit;
        }else{
            if($row['Is_Approved'] == 'No_Action'){
                $true = 'Approved';
                $updatequery="UPDATE `requests` SET `Is_Approved` = '".$true."' where ID = '".$id."'";
                $run_update=mysqli_query($con,$updatequery) or die (mysqli_error($con));
                }
                if($run_update)
                {
                    $sql ="SELECT * from `user_attendance` where `uid`='".$userid."' and `date`='".$dateforreq."' ";
                    $sql_query =mysqli_query($con,$sql) or die(mysqli_error($con));
                    $sql_fetch = mysqli_fetch_assoc($sql_query);
                    
                    $coffinmew1 = "UPDATE `user_attendance` SET `swipein_time`='".$checkintime."',`checkout_time`='" . $checkout_time . "',`break_count`='".$breaktime."',`user_remarks`='".$remarks."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
                    $coffin_query1 = mysqli_query($con, $coffinmew1) or die (mysqli_error($con)); 
                   
                    //Calculations
                       $sql = "SELECT * From `user_attendance` where `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
                       $sql_query = mysqli_query($con, $sql) or die (mysqli_error($con));
                       $sql_fetch = mysqli_fetch_assoc($sql_query);
                       $start = $sql_fetch['swipein_time'];
                       $stop = $sql_fetch['checkout_time'];
            
                       $exc_time1 = strtotime($start) - strtotime("00:00:00");
                       $tot_off_hours = date("H:i:s", strtotime($stop) - $exc_time1);
            
                       $update_total_office_hours = "UPDATE `user_attendance` SET `swipein_time`='".$checkintime."',`total_office_hours`='" . $tot_off_hours . "' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
                       $update_sql_query = mysqli_query($con, $update_total_office_hours);
            
                       $sql = "SELECT * From `user_attendance` WHERE `uid`='" .$userid . "' AND `date`='" . $dateforreq . "'";
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
            
                       $update_total_working_hour = "UPDATE `user_attendance` SET `swipein_time`='".$checkintime."',`total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
                       $update_sql_query = mysqli_query($con, $update_total_working_hour);
                       
                       
                       echo "Changes Are Been Made !";
                       header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
                    
                    }
                
                }
      
        
   
       
    
}

echo "Changes Are Been Made !";
?>



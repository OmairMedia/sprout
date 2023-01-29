<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}
include('db_func.php');
global $con;

$get_userid = $_POST['reqid'];


if(isset($_POST['modulefor']) && $_POST['modulefor'] == 'breaksuccess')
{

$selectquery="SELECT * FROM `break_request` where ID = '".$get_userid."'";
$runquery=mysqli_query($con,$selectquery) or die (mysqli_error($con));
if(mysqli_num_rows($runquery)>0){
    $row = mysqli_fetch_array($runquery);
}

if($row['Is_Approved'] == 'No_Action'){
$true = 'Approved';
$updatequery="UPDATE `break_request` SET `Is_Approved` = '".$true."' where ID = '".$get_userid."'";
$query_run=mysqli_query($con,$updatequery) or die (mysqli_error($con));
}

$userid = $row['User_id'];
$dateforreq = $row['Req_Made_For'];
$breaktimerequested = $row['Total_Breaktime'];
$sqlselect = "SELECT `break_count` FROM `user_attendance` WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
$sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
$sql_fetch_select = mysqli_fetch_assoc($sql_query_select);

if($sql_fetch_select['break_count'] == '00:00:00' || empty($sql_fetch_select['break_count']))
{
    $sqlselect = "UPDATE `user_attendance` SET `break_count` = '".$breaktimerequested."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
    $sql_query_select = mysqli_query($con, $sqlselect) or die (mysqli_error($con));
    if($sql_query_select){
     // After Calculating & Inserting Total Working Hours
     $sql = "SELECT * From `user_attendance` WHERE `uid`='" . $userid. "' AND `date`='" . $dateforreq . "'";
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
     
     if($tot_break_hours > $result)
     {
         $nullify = '00:00:00';
     $update_total_working_hour1 = "UPDATE `user_attendance` SET `total_working_hour`='" . $nullify. "', `total_break_hours`='".$tot_break_hours."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
     $update_sql_query = mysqli_query($con, $update_total_working_hour1);
     }
     else{
     $update_total_working_hour = "UPDATE `user_attendance` SET `total_working_hour`='" . $result . "', `total_break_hours`='".$tot_break_hours."' WHERE `uid`='" . $userid . "' AND `date`='" . $dateforreq . "'";
     $update_sql_query = mysqli_query($con, $update_total_working_hour);
     echo "Record Updated Successfully";
     }
    }
   
}else
{
    echo "User Already have Break";
    
    
}


}
?>






<?php
include('db.php');
if(isset($_POST['submit']))
{

    $leave_subject=$_POST['leave_sub'];
    $leave_days=$_POST['day_leave'];
    $leave_detail=$_POST['detail'];
    $leave_availed=$_POST['leave_avail'];
    $leave_date=$_POST['leave_date'];
    $user_id=$_POST['user_id'];

            $query="INSERT INTO `leave`(`leave_date`, `leave_detail`, `leave_availed_already`, `leave_subject`, `leave_days`, `uid` ) VALUES ('".$leave_date."','".$leave_detail."','".$leave_availed."','".$leave_subject."','".$leave_days."', '".$user_id."')";
    $result=@mysqli_query($con,$query) or die (mysqli_error($con));

    if(empty($leave_subject) || empty($leave_days)|| empty($leave_detail)||empty($leave_availed)||empty($leave_date))
    {
        header('location:login.php?unsuccess=true');
    }
    else
    {





            header('location:login.php?success=true');


    }


}
?>


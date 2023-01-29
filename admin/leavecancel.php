<?php
include('db_func.php');
global $con;


$get_userid = $_POST['reqid'];
$selectquery="SELECT * FROM `leave_request` where ID = '".$get_userid."'";
$runquery=mysqli_query($con,$selectquery) or die (mysqli_error($con));
if(mysqli_num_rows($runquery)>0){
    $row = mysqli_fetch_array($runquery);
}

if($row['Is_Approved'] == 'No_Action'){
    $disapproved = 'Disapproved';
    $updatequery="UPDATE `leave_request` SET `Is_Approved` = '".$disapproved."' where ID = '".$get_userid."'";
    $query_run=mysqli_query($con,$updatequery) or die (mysqli_error($con));
    if($query_run)
    {
        echo "Request Has Been Deleted";
        header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    }
}

echo "Request Has Been Deleted";
?>





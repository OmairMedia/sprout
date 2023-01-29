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

if(isset($_POST['modulefor']) && $_POST['modulefor'] == 'breakcancel')
{

$selectquery="SELECT * FROM `break_request` where ID = '".$get_userid."'";
$runquery=mysqli_query($con,$selectquery) or die (mysqli_error($con));
if(mysqli_num_rows($runquery)>0){
    $row = mysqli_fetch_array($runquery);
}


if($row['Is_Approved'] == 'No_Action'){
$disapproved = 'Disapproved';
$updatequery="UPDATE `break_request` SET `Is_Approved` = '".$disapproved."' where ID = '".$get_userid."'";
$query_run=mysqli_query($con,$updatequery) or die (mysqli_error($con));
}

}
?>


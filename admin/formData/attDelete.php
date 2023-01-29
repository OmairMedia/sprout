<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['att_id'])){
    $sel_id = $_POST['att_id'];
    $data['err'] = '';
    $delAtt = $pdo_con->prepare("UPDATE `user_attendance` SET `swipein_time`='auto',`checkout_time`='auto',`total_working_hour`='',`user_remarks`='(Deleted By Admin)'  WHERE `id`=?");
    $delAtt->bindValue(1, $sel_id);
    $check = $delAtt->execute();
    if(!$check){
        $data['err'] = "Deleting Problem Check Your Connection or Database!";
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
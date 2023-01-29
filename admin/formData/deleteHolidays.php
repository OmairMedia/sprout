<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['del_id'])){
    $del_id = $_POST['del_id'];
    $data = ["err" => ""];

    $check_dates = $pdo_con->prepare("SELECT * FROM `holidays` WHERE `id`='{$del_id}'");
    $check_dates->execute();
    $num_rows = $check_dates->rowCount();

    if($num_rows > 0){
        $del_date = $pdo_con->prepare("DELETE FROM `holidays` WHERE `id`='{$del_id}'");
        $check = $del_date->execute();
        if(!$check){
            $data["err"] = "Deleting Problem! Check Your Connection or Database!";
        }
    }else{
        $data["err"] = "This Date Already Deleted!";
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
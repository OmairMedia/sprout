<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['add_date']) && isset($_POST['remarks'])){
    $set_date = $_POST['add_date'];
    $set_remarks = $_POST['remarks'];
    $data = ["err" => ""];

    $check_dates = $pdo_con->prepare("SELECT * FROM `holidays` WHERE `date`='{$set_date}'");
    $check_dates->execute();
    $num_rows = $check_dates->rowCount();

    if($num_rows > 0){
        $data["err"] = "This Date Already Exist!";
    }else{
        $insert_date = $pdo_con->prepare("INSERT INTO `holidays`(`date`, `user_remarks`) VALUES (?, ?)");
        $insert_date->bindValue(1, $set_date);
        $insert_date->bindValue(2, $set_remarks);
        $check = $insert_date->execute();
        if(!$check){
            $data["err"] = "Insert Date Problem! Check Your Connection or Database!";
        }else{
            $sel_dates = $pdo_con->prepare("SELECT * FROM `holidays` ORDER BY `date` ASC");
            $sel_dates->execute();
            $save_arr = [];
            while($retDataRes = $sel_dates->fetch(PDO::FETCH_ASSOC)){
                $cur_data = [
                    "id"=>$retDataRes['id'],
                    "date"=>$retDataRes['date'],
                    "remarks"=>$retDataRes['user_remarks']
                ];
                array_push($save_arr, $cur_data);
            }
            $data["retData"] = $save_arr;
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
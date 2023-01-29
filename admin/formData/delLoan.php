<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['loan_id'])){
    $sel_id = $_POST['loan_id'];
    $data['err'] = '';

    $checkDeduct = $pdo_con->prepare("SELECT * FROM `users_deduction` WHERE `loan_id`='{$sel_id}'");
    $checkDeduct->execute();

    if($checkDeduct->rowCount() > 0){
        $data['err'] = "This row use in deduction table.";
    }else{
        $delLoan = $pdo_con->prepare("DELETE FROM `users_loan` WHERE `l_id`=?");
        $delLoan->bindValue(1, $sel_id);
        $check = $delLoan->execute();
        if(!$check){
            $data['err'] = "Deleting Problem Check Your Connection or Database!";
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
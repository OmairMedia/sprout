<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['user_id']) && isset($_POST['selTitle']) && isset($_POST['selMonth']) && isset($_POST['selLoanIds']) && isset($_POST['selTotalAmount'])){
    $data = ["err" => ""];

    $selTitle = $_POST['selTitle'];
    $userId = $_POST['user_id'];
    $selMonth = $_POST['selMonth'];
    $selLoanId = $_POST['selLoanIds'];
    $selTotalAmount = $_POST['selTotalAmount'];

    $loan_id_chk_table = false;
    if($selLoanId != 0){
        $chkUserDeduct = $pdo_con->prepare("SELECT * FROM `users_deduction` WHERE `uid`='{$userId}' AND `loan_id`='{$selLoanId}' AND `ded_month`='{$selMonth}'");
        $chkUserDeduct->execute();
        if($chkUserDeduct->rowCount() > 0){
            $loan_id_chk_table = true;
        }
    }



    $selectLoanAmount = $pdo_con->prepare("SELECT * FROM `users_loan` WHERE `l_id`='{$selLoanId}'");
    $selectLoanAmount->execute();

    $selAmountList = $selectLoanAmount->fetch(PDO::FETCH_ASSOC);
    $selAmount = $selAmountList['l_amount'];

    if(!empty($selAmount) && ($selAmount*1) < ($selTotalAmount*1)){
        $data["err"] = "Total Amount Higher Than Loan Amount. Please Enter Less Or Equal Amount";
    }else{
        if($loan_id_chk_table){
            $data["err"] = "This loan already deduct this month!";
        }else{
            $insertDeduct = $pdo_con->prepare("INSERT INTO `users_deduction`(`ded_title`, `uid`, `loan_id`, `ded_month`, `amount`) VALUES (?, ?, ?, ?, ?)");
            $insertDeduct->bindValue(1, $selTitle);
            $insertDeduct->bindValue(2, $userId);
            $insertDeduct->bindValue(3, $selLoanId);
            $insertDeduct->bindValue(4, $selMonth);
            $insertDeduct->bindValue(5, $selTotalAmount);
            $checkDataInsert = $insertDeduct->execute();

            if($checkDataInsert){
                $data["succ"] = "Successfully Inserted.";
            }else{
                $data["err"] = "Insertions Problem!";
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
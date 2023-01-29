<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['user_id']) && isset($_POST['selMonth'])){
    $userId = $_POST['user_id'];
    $selMonth = $_POST['selMonth'];

    $selectLoanList = $pdo_con->prepare("SELECT * FROM `users_loan` WHERE `l_u_id`='{$userId}' AND `l_added_at`='{$selMonth}'");
    $selectLoanList->execute();

    $selUsername = $pdo_con->prepare("SELECT U_name From `user` WHERE `U_id`='{$userId}'");
    $selUsername->execute();
    $userName = $selUsername->fetch(PDO::FETCH_ASSOC);

    $data["user"] = $userName['U_name'];
    $data["res"] = $selectLoanList->fetchAll(PDO::FETCH_ASSOC);


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
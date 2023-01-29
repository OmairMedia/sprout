<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['user_id'])){
    $userId = $_POST['user_id'];

    $selectLoanList = $pdo_con->prepare("SELECT * FROM `users_loan` WHERE `l_u_id`='{$userId}'");
    $selectLoanList->execute();

    $data["res"] = $selectLoanList->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['user_id']) && isset($_POST['selMonth'])){
    $userId = $_POST['user_id'];
    $selMonth = $_POST['selMonth'];

    $selectDedList = $pdo_con->prepare("SELECT * FROM `users_deduction` WHERE `uid`='{$userId}' AND `ded_month`='{$selMonth}'");
    $selectDedList->execute();

    $selUsername = $pdo_con->prepare("SELECT U_name From `user` WHERE `U_id`='{$userId}'");
    $selUsername->execute();
    $userName = $selUsername->fetch(PDO::FETCH_ASSOC);

    $data["user"] = $userName['U_name'];
    $data["res"] = $selectDedList->fetchAll(PDO::FETCH_ASSOC);


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
<?php
include "hereDBConfig.php";
global $pdo_con;

if (isset($_POST['user_id']) && isset($_POST['selTitle']) && isset($_POST['selMonth']) && isset($_POST['selTotalAmount'])) {
    $data = ["err" => ""];

    $userId = $_POST['user_id'];
    $selTitle = $_POST['selTitle'];
    $selMonth = $_POST['selMonth'];
    $selTotalAmount = $_POST['selTotalAmount'];


    $insertLoan = $pdo_con->prepare("INSERT INTO `users_loan`(`l_u_id`, `l_title`, `l_amount`, `l_added_at`) VALUES (?, ?, ?, ?)");
    $insertLoan->bindValue(1, $userId);
    $insertLoan->bindValue(2, $selTitle);
    $insertLoan->bindValue(3, $selTotalAmount);
    $insertLoan->bindValue(4, $selMonth);
    $checkDataInsert = $insertLoan->execute();

    if ($checkDataInsert) {
        $data["succ"] = "Successfully Inserted.";
    } else {
        $data["err"] = "Insertions Problem!";
    }


    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
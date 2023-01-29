<?php
include "hereDBConfig.php";
global $pdo_con;

if (isset($_POST['ded_id'])) {
    $sel_id = $_POST['ded_id'];
    $data['err'] = '';

    $delDed = $pdo_con->prepare("DELETE FROM `users_deduction` WHERE `id`=?");
    $delDed->bindValue(1, $sel_id);
    $check = $delDed->execute();
    if (!$check) {
        $data['err'] = "Deleting Problem Check Your Connection or Database!";
    }

    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}
header("Location: ../login.php");
exit;
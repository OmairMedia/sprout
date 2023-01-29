<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['sel_user']) && isset($_POST['sel_year']) && isset($_POST['sel_month'])){
    $sel_user = $_POST['sel_user'];
    $sel_year = $_POST['sel_year'];
    $sel_month = $_POST['sel_month'];

    $selUserData = $pdo_con->prepare("SELECT * FROM `user_attendance` WHERE `uid`=? ORDER BY `date` ASC");
    $selUserData->bindValue(1, $sel_user);
    $selUserData->execute();

    $numRows = $selUserData->rowCount();
    if($numRows > 0){
        $retUserData = [];
        while($userAllData = $selUserData->fetch(PDO::FETCH_ASSOC)){
            $checkYear = date('Y', strtotime($userAllData['date']));
            $checkMonth = date('m', strtotime($userAllData['date']));
            if($checkYear == $sel_year && $checkMonth == $sel_month){
                $info_date = $userAllData['date'];
                $info_check_in = $userAllData['swipein_time'];
                $info_check_out = $userAllData['checkout_time'];
                $info_break_time = ($userAllData['total_break_hours'] == "") ? "00:00:00" : $userAllData['total_break_hours'];
                $info_tot_working_hours = ($userAllData['total_working_hour'] == "") ? "00:00:00" : $userAllData['total_working_hour'];

                $sel_data = [
                    'id' => $userAllData['id'],
                    'date' => $info_date,
                    'check_in' => $info_check_in,
                    'check_out' => $info_check_out,
                    'break_time' => $info_break_time,
                    'tot_working_hours' => $info_tot_working_hours,
                    'user_remarks' => $userAllData['user_remarks']
                ];
                array_push($retUserData, $sel_data);
            }
        }

        if(!empty($retUserData)){
            $data = ['err' => "", 'retUserInfo' => $retUserData];
        }else{
            $data = ['err' => "No Result Found!"];
        }
    }else{
        $data = ['err' => "No Result Found!"];
    }




    /*$data = ['user'=>$sel_user, 'year'=>$sel_year, 'month'=>$sel_month];*/
    header('Content-Type: application/json');
    echo json_encode($data);
    //var_dump($data);
    exit;
}
header("Location:../login.php");
exit;
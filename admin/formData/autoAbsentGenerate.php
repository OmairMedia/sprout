<?php
include "hereDBConfig.php";
global $pdo_con;

if(isset($_POST['selYear']) && isset($_POST['selMonth'])){
    $selYear = $_POST['selYear'];
    $selMonth = $_POST['selMonth'];

    $data = ['err' => ""];

    $selAllUser = $pdo_con->prepare("SELECT * FROM `user` WHERE (`Account_type`='employee' OR `Account_type`='sub-admin')");
    $selAllUser->execute();
    while($selUserRes = $selAllUser->fetch(PDO::FETCH_ASSOC)){
        $grabUserId = $selUserRes['U_id'];
        $curDate = date("d")*1;
        //$thisMonthDays = cal_days_in_month(CAL_GREGORIAN, $selMonth, $selYear);
        for($loopI = 1; $loopI < $curDate; $loopI++){
            $createDate = mktime(0,0,0,$selMonth, $loopI, $selYear);
            $mkDate = date("Y-m-d", $createDate);
            $selSunday = date("l", $createDate);

            $selUserAtt = $pdo_con->prepare("SELECT * FROM `user_attendance` WHERE `uid`='{$grabUserId}' AND `date`='{$mkDate}' AND NOT `user_remarks`='Absent' ");
            $selUserAtt->execute();
            $selUserAttRowCheck = $selUserAtt->rowCount();
            if($selUserAttRowCheck < 1){
                $selHolidaysCheck = $pdo_con->prepare("SELECT * FROM `holidays` WHERE `date`='{$mkDate}'");
                $selHolidaysCheck->execute();
                $holidayRowCheck = $selHolidaysCheck->rowCount();
                if($holidayRowCheck < 1){
                    $userRemarks = "";
                    $totWorkingHours = "";
                    if($selSunday == "Sunday"){
                        $userRemarks = "Sunday";
                        $totWorkingHours = "00:00:00";
                    }else{
                        $userRemarks = "Auto Absent";
                    }
                    $insertUserAtt = $pdo_con->prepare("INSERT INTO `user_attendance`(`uid`, `date`, `swipein_time`, `checkout_time`, `total_working_hour`, `user_remarks`) VALUES ('$grabUserId', '$mkDate', 'auto', 'auto', '$totWorkingHours', '$userRemarks')");
                    $insertUserAtt->execute();
                }
            }
        }
    }

    header('Content-Type: application/json');
    echo json_encode($data);

    exit;
}
header("Location: ../login.php");
exit;
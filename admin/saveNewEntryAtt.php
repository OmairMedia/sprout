<?php

//include('db.php');


if(isset($_POST['en_user_id']) && isset($_POST['en_date']) && isset($_POST['en_hours']) && isset($_POST['en_userRemarks'])){
    $con = new PDO("mysql:host=localhost;dbname=attendance_main_test", 'attendance_admin_test', '4slash1234!@#$');
    $data = array();
    $en_user_id = $_POST['en_user_id'];
    $en_date = $_POST['en_date'];
    $en_hours = $_POST['en_hours'];
    $en_userRemarks = $_POST['en_userRemarks'];

    if(!empty($_POST['en_user_id']) && !empty($_POST['en_date']) && !empty($_POST['en_hours']) && !empty($_POST['en_userRemarks'])){
        $data['err'] = '';
        if(strlen($en_hours) < 2){
            $en_hours = "0".$en_hours;
        }

        $chkUserAttStm = $con->prepare("SELECT * FROM `user_attendance` WHERE `uid`=? AND `date`=?");
        $chkUserAttStm->bindValue(1, $en_user_id);
        $chkUserAttStm->bindValue(2, $en_date);
        $chkUserAttStm->execute();
        if($chkUserAttStm->rowCount() > 0){
            $data['err'] = 'This user attendance already submitted.';
        }else{
            $inNewUserEntry = $con->prepare("INSERT INTO `user_attendance`(`uid`, `date`, `swipein_time`, `checkout_time`, `total_working_hour`, `total_office_hours`, `user_remarks`) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $inNewUserEntry->bindValue(1, $en_user_id);
            $inNewUserEntry->bindValue(2, $en_date);
            $inNewUserEntry->bindValue(3, 'auto');
            $inNewUserEntry->bindValue(4, 'auto');
            $inNewUserEntry->bindValue(5, $en_hours.':00:00');
            $inNewUserEntry->bindValue(6, $en_hours.':00:00');
            $inNewUserEntry->bindValue(7, $en_userRemarks);
            if($inNewUserEntry->execute()){
                $data['succ'] = 'Successfully! Insert Date.';
            }else{
                $data['err'] = 'Error! Insertion Problem.';
            }
        }


        header('Content-Type: application/json');
        //var_dump($_POST);
        echo json_encode($data);
        exit;
    }else{
        $data['err'] = 'Some Fields Empty!';
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
}

header('Location: reports.php');


?>
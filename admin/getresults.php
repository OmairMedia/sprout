<?php
include('db.php');
global $con, $pdo_con;
if (isset($_POST['submit'])) {
    header('Content-Type: application/json');
    function genWorkingDays($month, $year, $skipDay = ""){
        if(!empty($skipDay)){
            $totalMonthlyDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            $countDays = 0;
            for($i = 1; $i<=$totalMonthlyDays; $i++){
                $makeTime = mktime(0, 0, 0, $month, $i, $year);
                $checkDay = date("l", $makeTime);
                if($checkDay == $skipDay){
                    continue;
                }else{
                    $countDays++;
                }
            }
            return $countDays;
        }else{
            return cal_days_in_month(CAL_GREGORIAN, $month, $year);
        }
    }
    function cmp($a, $b){
        return strcmp($a['date'], $b['date']);
    }

    //here this file global variables
    $data = [];
    $totLoan = 0;
    $lessLoan = 0;
    $lessExpEnter = 0;
    $balLoan = 0;

    $user_id = $_POST['user'];
    $sel_month = $_POST['month'];
    $sel_year = $_POST['year'];

    $genDate = date("Y-m-d", strtotime($sel_year."-".$sel_month."-01"));

    //here select user all loans
    $selLoansQuery = $pdo_con->prepare("SELECT * FROM `users_loan` WHERE `l_u_id`='{$user_id}'");
    $selLoansQuery->execute();
    while ($selLoan = $selLoansQuery->fetch(PDO::FETCH_ASSOC)){
        $totLoan += ($selLoan["l_amount"]*1);
    }

    //here this month deduct loan
    $selLoanDedQuery = $pdo_con->prepare("SELECT * FROM `users_deduction` WHERE `uid`='{$user_id}' AND `ded_month`='{$genDate}' AND `loan_id`!='0'");
    $selLoanDedQuery->execute();
    while($selLoanDed = $selLoanDedQuery->fetch(PDO::FETCH_ASSOC)){
        $lessLoan += ($selLoanDed['amount']*1);
    }

    //here this month deduct Exp Entertainment
    $selExpDedQuery = $pdo_con->prepare("SELECT * FROM `users_deduction` WHERE `uid`='{$user_id}' AND `ded_month`='{$genDate}' AND `loan_id`='0'");
    $selExpDedQuery->execute();
    while($selExpDed = $selExpDedQuery->fetch(PDO::FETCH_ASSOC)){
        $lessExpEnter += ($selExpDed['amount']*1);
    }


    $selUserAtt = $pdo_con->prepare("SELECT * FROM `user_attendance` WHERE `uid`=? ORDER BY `date` ASC");
    $selUserAtt->bindValue(1, $user_id);
    $selUserAtt->execute();

    $selUserData = $pdo_con->prepare("SELECT `U_name`, `D_id`, `hourly_salary` FROM `user` WHERE `U_id`=?");
    $selUserData->bindValue(1, $user_id);
    $selUserData->execute();
    $fetUserData = $selUserData->fetch(PDO::FETCH_ASSOC);

    $selUserDepartment = $pdo_con->prepare("SELECT `department` FROM `department` WHERE `id`={$fetUserData['D_id']}");
    $selUserDepartment->execute();
    $fetUserDepartment = $selUserDepartment->fetch(PDO::FETCH_ASSOC);

    $numRows = $selUserAtt->rowCount();
    if($numRows > 0){
        $total_hours = 0;
        $total_mins = 0;
        $total_secs = 0;

        $retUserData = [];
        $retUserReport = [];

        while($userAllData = $selUserAtt->fetch(PDO::FETCH_ASSOC)){
            $checkYear = date('Y', strtotime($userAllData['date']));
            $checkMonth = date('m', strtotime($userAllData['date']));
            if($checkYear == $sel_year && $checkMonth == $sel_month){
                //user report generate
                $chk_tot_w_hours = ($userAllData['total_working_hour'] == "") ? "00:00:00" : $userAllData['total_working_hour'];
                $tot_w_hours_explode = explode(":", $chk_tot_w_hours);
                $total_hours += $tot_w_hours_explode[0]*1;
                $total_mins += $tot_w_hours_explode[1]*1;
                $total_secs += $tot_w_hours_explode[2]*1;

                //user info history
                $info_date = $userAllData['date'];
                $info_check_in = $userAllData['swipein_time'];
                $info_check_out = $userAllData['checkout_time'];
                $info_break_time = ($userAllData['total_break_hours'] == "") ? "00:00:00" : $userAllData['total_break_hours'];
                $info_tot_working_hours = ($userAllData['total_working_hour'] == "") ? "00:00:00" : $userAllData['total_working_hour'];
                $sel_data = [
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

        $setHolidaysDates = $pdo_con->prepare("SELECT * FROM `holidays`");
        $setHolidaysDates->execute();
        $check_rows = $setHolidaysDates->rowCount();
        if($check_rows>0){
            $asp_hours = 8;
            while($retData = $setHolidaysDates->fetch(PDO::FETCH_ASSOC)){
                $checkYear = date('Y', strtotime($retData['date']));
                $checkMonth = date('m', strtotime($retData['date']));

                if($checkYear == $sel_year && $checkMonth == $sel_month){
                    $checkBool = false;
                    foreach($retUserData as $singleCheck){
                        if(in_array($retData['date'], $singleCheck)){
                            $checkBool = false;
                            break 1;
                        }else{
                            $checkBool = true;
                        }
                    }
                    if($checkBool){
                        $total_hours += $asp_hours;
                        $sel_data = [
                            'date' => $retData["date"],
                            'check_in' => "auto",
                            'check_out' => "auto",
                            'break_time' => "00:00:00",
                            'tot_working_hours' => "0".$asp_hours.":00:00",
                            'user_remarks' => $retData['user_remarks']
                        ];
                        array_push($retUserData, $sel_data);
                    }
                }
            }
        }

        usort($retUserData, "cmp");

        if(!empty($retUserData)){
            $gen_secs = $total_secs%60;
            $gen_mins = ($total_mins+intval($total_secs/60))%60;
            $gen_hours = $total_hours+intval(($total_mins+intval($total_secs/60))/60);
            $gen_roundedHours = $gen_hours;

            if($gen_mins > 29){
                $gen_roundedHours++;
            }

            $gen_secs = (strlen($gen_secs) < 2) ? "0".$gen_secs: $gen_secs;
            $gen_mins = (strlen($gen_mins) < 2) ? "0".$gen_mins: $gen_mins;
            $gen_hours = (strlen($gen_hours) < 2) ? "0".$gen_hours: $gen_hours;

            $genTime = $gen_hours.":".$gen_mins.":".$gen_secs;
            $perDayHours = 8;
//
//            if($user_id == "200" || $user_id == "202" || $user_id == "206" || $user_id == "207" || $user_id == "208"){
//                $perDayHours = 5;
//            }

            $basicSalary = $fetUserData['hourly_salary'];
            $monthlyHours = round(genWorkingDays($sel_month, $sel_year, "Sunday")*$perDayHours);
            $perHoursSalary = number_format(($basicSalary*1)/$monthlyHours, 4);

            if($monthlyHours >= $gen_roundedHours){
                $salaryGenerate = round($perHoursSalary*$gen_roundedHours);
            }else{
                $salaryGenerate = round($perHoursSalary*$monthlyHours);
            }


            $userTotDedAmount = $lessExpEnter+$lessLoan;
            $balLoan = ($totLoan-$lessLoan);

            $retUserReport[] = array(
                'genTime' => $genTime,
                'genReportTime' => date("Y-m-d"),
                'roundHours' => $gen_roundedHours,
                'userName' => $fetUserData['U_name'],
                'userDepartment' => $fetUserDepartment['department'],
                'monthlyHours' => $monthlyHours,
                'basicSalary' => $basicSalary,
                'hourlySalary' => $perHoursSalary,
                'salaryGenerate' => $salaryGenerate,
                'userTotLoan' => $totLoan,
                'userLessExpDed' => $lessExpEnter,
                'userTotDed' => $userTotDedAmount,
                'userLessLoan' => $lessLoan,
                'userNetPay' => ($salaryGenerate-$userTotDedAmount),
                'balLoan' => $balLoan
            );

            $data = [
                'res_status' => "",
                'retUserInfo' => $retUserData,
                'retUserReport' => $retUserReport
            ];

        }else{
            $data = ['res_status' => "No Result Found!"];
        }
    }else{
        $data['res_status'] = "No Result Found!";
    }

    echo json_encode($data);
    exit;
}

header('Location: reports.php');


?>
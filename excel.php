
<?php
include('db.php');
session_start();
$query="select * from user";
$query_run=@mysqli_query($con,$query) or die (mysqli_error($con));
if(mysqli_num_rows($query_run)) {



    if (isset($_POST['submit'])) {

        $_SESSION['U_id'];


        $filename = 'uploads/' . strtotime("now") . '.csv';



        $sql = "SELECT * FROM `user_attendance` where `uid`='" . $_SESSION['U_id'] . "' ";
        $sql_query = @mysqli_query($con, $sql) or die (mysqli_error($con));
        $num_row = mysqli_num_rows($sql_query);
        $row = mysqli_fetch_assoc($sql_query);

        if ($num_row >= 1) {
            $fp = fopen($filename, "w");

            mysqli_data_seek($sql_query, 0);
           fputcsv($fp, array('id', 'uid', 'date','swipein', 'swipe in time', 'break in','break in time','breakout','breakout time', 'check out','check out time','total working hour'));
           // fputs($fp,$seprator);
           while($row = mysqli_fetch_assoc($sql_query))
           {

           $seprator = "";
            $comma = "";
            foreach ($row as $name => $value) {


                $seprator .= $comma . '' . str_replace('', '""', $value);
                // $row = mysqli_fetch_array($sql_query);


                $comma = ",";

            }
            $seprator .= "\n";

                fputs($fp,$seprator);
           }
            }
            fclose($fp);
            header('location:login.php?file=true');

        }


    }
?>
<?php
$servername = "localhost";
$username = "attendance_admin_test";
// attendance_admin
$password = "4slash1234!@#$";
// 4slash1234!@#$

// Create connection
$con=mysqli_connect($servername,$username,$password,'attendance_main_test');

$pdo_con = new PDO("mysql:host={$servername};dbname=attendance_main_test",$username,$password);

?>  
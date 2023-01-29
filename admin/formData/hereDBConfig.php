<?php
$servername = "localhost";
$username = "attendance_admin_test";
$password = "4slash1234!@#$";
// $username = "attendance_admin";
// $password = "4slash1234!@#$";

// Create connection
$con=mysqli_connect($servername,$username,$password,'attendance_main_test');

$pdo_con = new PDO("mysql:host=localhost;dbname=attendance_main_test",'attendance_admin_test','4slash1234!@#$');


?>
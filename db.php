<?php
$servername = "localhost";
$username = "root";
// attendance_admin
$password = "";
// 4slash1234!@#$

// Create connection
$con=mysqli_connect($servername,$username,$password,"attendence_main") or die (mysqli_error($con));
$pdo_con = new PDO("mysql:host={$servername};dbname=attendence_main",$username,$password);

?>
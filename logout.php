<?php

include('db.php');
session_set_cookie_params(86400,"/");
session_start();



    session_destroy();

    header("location:login.php");


?>
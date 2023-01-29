<?php
include('db.php');
session_start();
$target_dir = "uploads/img/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    }else{
        $query = "UPDATE `user` SET `profile_img`='".$target_file."'WHERE `U_id`='".$_SESSION['U_id']."'";
        $result = mysqli_query($con, $query) or die (mysqli_error($con));
        move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file);
            header('location:home.php');


    }
}
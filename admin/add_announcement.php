<?php
include('db.php');


try{
    $pdo_con = new PDO('mysql:host=localhost;dbname=attendance_main', 'attendance_admin_test', '4slash1234!@#$');
}catch (PDOException $e)
{
    die($e->getMessage());
}

if(isset($_POST['delAnnDt']) && !empty($_POST['delAnnDt'])){
    $get_id = $_POST['delAnnDt'];
    $check_ann = $pdo_con->prepare("SELECT * FROM announcement WHERE id=?");
    $check_ann->bindValue(1, $get_id);
    $check_ann->execute();

    $row_check = $check_ann->rowCount();

    if($row_check == 1){
        $del_ann = $pdo_con->prepare("DELETE FROM announcement WHERE id=?");
        $del_ann->bindValue(1, $get_id);
        $del_ann->execute();
    }
    header('location: login.php');
    exit;
}

if (isset($_POST['submit']) && !empty($_POST['title']) && !empty($_POST['content'])) {
    $title = $_POST['title'];
    $body = $_POST['content'];

    $sql = "INSERT INTO announcement (title, message) VALUES ('" . $title . "', '" . $body . "')";
    $result = mysqli_query($con, $sql);
    if ($result) {
        header('Location: login.php');
    } else {
        echo "Error";
    }
    header('location: login.php');
    exit;
}
header('location: login.php');
?>
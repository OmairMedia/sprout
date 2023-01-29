<?php
include('db_func.php');
global $con, $pdo_con;

if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

if(!empty($_GET['id'])){
    $user_id = $_GET['id'];
    if(chk_col_val_exist('user', 'U_id', $user_id)){
        if(isset($_POST['del_confirm']) && !empty($_POST['del_confirm'])){
            $del_conf = $_POST['del_confirm'];
            if($del_conf == 'Yes'){
                $del_qury = $pdo_con->prepare("DELETE FROM `user` WHERE `U_id`='".$user_id."'");
                $del_qury_run = $del_qury->execute();
                if($del_qury_run){
                    $del_user_att = $pdo_con->prepare("DELETE FROM `user_attendance` WHERE `uid`='".$user_id."'");
                    $del_user_att_check = $del_user_att->execute();
                    if($del_user_att_check){
                        header("location: login.php");
                    }else{
                        echo "Deleting User Attendance Problem Please Check Code.";
                        exit;
                    }
                }else{
                    echo "Deleting User Problem Please Check Code.";
                    exit;
                }
            }else{
                header('location: login.php');
                exit;
            }
            exit;
        }else{
            ?>
            <p>Are you sure to delete this user?</p>
            <form action="deleteuser.php?id=<?php echo $user_id; ?>" method="post">
                <input type="submit" value="Yes" name="del_confirm" />
                <input type="submit" value="No" name="del_confirm" />
            </form>
            <?php
        }
    }else{
        echo "invalid";
    }
    exit;
}

header('location: login.php');
?>
<?php
include('db_func.php');
global $con;

if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

if(!empty($_GET['id'])){
    $dep_id = $_GET['id'];
    if(chk_col_val_exist('department', 'id', $dep_id)){
        if(isset($_POST['del_confirm']) && !empty($_POST['del_confirm'])){
            $del_conf = $_POST['del_confirm'];
            if($del_conf == 'Yes'){
                $del_qury = "DELETE FROM `department` WHERE `id`='".$dep_id."'";
                $del_qury_run = mysqli_query($con,$del_qury)or die("Error1: ".mysqli_error($con));
                header("location: viewdep.php");
            }else{
                header('location: viewdep.php');
            }
            exit;
        }else{
            if(!chk_col_val_exist('user', 'D_id', $dep_id)){
                ?>
                <p>Are you sure to delete this Department?</p>
                <form action="deldep.php?id=<?php echo $dep_id; ?>" method="post">
                    <input type="submit" value="Yes" name="del_confirm" />
                    <input type="submit" value="No" name="del_confirm" />
                </form>
                <?php
            }else{
                ?>
                <p>This Department not deleted because this department used in the database.</p>
                <?php
            }
        }
    }else{
        echo "invalid";
    }
    exit;
}

header('location: viewdep.php');
?>

<?php
include 'db.php';
if (isset($_GET['users'])){
$user = "Select * from `user` where (`Account_type`='employee' OR `Account_type`='sub-admin')";
$users_query = mysqli_query($con, $user);
$stat_img = array(
    'online' => '<img src="img/online.png" style="width:10%"> Online',
);
?>

<header>
    <h3>Desk Status</h3>
</header>
<div style="background:white;
; border: 2px #55830c solid; padding: 15px;">
    <table>
        <tr>
            <th>Employe</th>
            <th style="padding-left: 10px;">status</th>
            <th>Employe</th>
            <th style="padding-left: 10px;">status</th>
            <th>Employe</th>
            <th style="padding-left: 10px;">status</th>
        </tr>
        <tr>
            <?php
            $max_row = 1;
            while ($row = mysqli_fetch_assoc($users_query)) {
            $max_row++;
            ?>
            <td>
                <?php echo $row['U_name']; ?>
            </td>

            <td style="padding-left: 10px;">
                <?php
                if(!empty($row['is_status']) && $row['is_status'] == "online"){
                    echo $stat_img[$row['is_status']];
                }
                ?>
            </td>

            <?php
            if($max_row > 3){
            ?>
        </tr><tr>
            <?php
            $max_row = 1;
            }
            }
            ?>
        </tr>
        <?php }
        ?>
    </table>
</div>
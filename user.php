<?php
include 'db.php';


if (isset($_GET['users'])){
/*$user = "Select * from `user` where (`Account_type`='employee' OR `Account_type`='sub-admin' AND `is_status`='online')";*/
$user = "Select * from `user` as u JOIN `department` as d on u.D_id = d.id where `is_status`='online' and `Active`='active'";
$user_offline = "Select * from `user` as u JOIN `department` as d on u.D_id = d.id where `is_status`='offline' and `Active`='active'";
$user_break = "Select * from `user` as u JOIN `department` as d on u.D_id = d.id where `is_status`='break' and `Active`='active'";

$users_query = mysqli_query($con, $user);
$users_query_offline = mysqli_query($con, $user_offline);
$users_query_break = mysqli_query($con, $user_break);




$stat_img = array(
    'online' => '<img src="img/online.png" style="width:10%"> Online',
    'offline' => '<img src="img/offline.png" style="width:10%"> Offline',
    'break' => '<img src="img/break.png" style="width:10%"> Break'
);


?>

<div style="background:white; padding: 15px; font-family: 'Lato', sans-serif">
    <a onclick="users()" style="float:right; cursor:pointer;">Refresh</a>
    <table>
        <tbody>
        <tr>
            <th>Online</th>
            <th></th>
            <th></th>
<!--            <th><span class="glyphicon glyphicon-chevron-down"></span></th>-->
        </tr>
        <?php
        $max_row = 1;
        while ($row_online = mysqli_fetch_assoc($users_query)) {
            $max_row++;
            ?>
            <tr>
                <td style="text-align: center">
                    <?php if(!empty($row_online['profile_img'])){ ?>
                    <img src="<?php echo $row_online['profile_img']; ?>" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                    <?php }else{ ?>
                        <img src="img/default-avatar.png" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                    <?php } ?>
                </td>
                <td>
                    <?php echo $row_online['U_name']; ?>
                </td>
                <td>
                    <?php echo $row_online['department']; ?>
                </td>
                <!--ACTIONS-->
             
               
            
           
               
                
                
               
<?php 
// }
?>
                 
<!--                <td style="padding-left: 10px;">-->
<!--                    --><?php
//                    if(!empty($row_online['is_status']) && $row_online['is_status'] == "online"){
//                        echo $stat_img[$row_online['is_status']];
//                    }
//                    ?>
<!--                </td>-->
            </tr>
            <?php
            if($max_row > 3){
            ?>
        <tr>
            <?php
            $max_row = 1;
            }
        }
        ?>
        </tr>
        <tr>
            <th>Break</th>
<!--            <th><span class="glyphicon glyphicon-chevron-down"></span></th>-->
        </tr>
        <?php
        $max_row = 1;
        while ($row_break = mysqli_fetch_assoc($users_query_break)) {
        $max_row++;
        ?>
        <tr>
            <td style="text-align: center">
                <?php if(!empty($row_break['profile_img'])){ ?>
                    <img src="<?php echo $row_break['profile_img']; ?>" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                <?php }else{ ?>
                    <img src="img/default-avatar.png" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                <?php } ?>
            </td>
            <td>
                <?php echo $row_break['U_name']; ?>
            </td>
            <td>
                <?php echo $row_break['department']; ?>
            </td>
           
            
        </tr>
        <?php
        if($max_row > 3){
        ?>
        <tr>
            <?php
            $max_row = 1;
            }
        }
            ?>
        </tr>
        <tr>
            <th>Offline</th>
<!--            <th><span class="glyphicon glyphicon-chevron-down"></span></th>-->
        </tr>

            <?php
            $max_row = 1;
            while ($row_offline = mysqli_fetch_assoc($users_query_offline)) {
                
            $max_row++;
            ?>
            <tr>
                <td style="text-align: center">
                    <?php if(!empty($row_offline['profile_img'])){ ?>
                        <img src="<?php echo $row_offline['profile_img']; ?>" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                    <?php }else{ ?>
                        <img src="img/default-avatar.png" alt="user_pic" style="width: 20px; height: 20px; border-radius: 10px;">
                    <?php } ?>
                </td>
            <td>
                <?php echo $row_offline['U_name']; ?>
            </td>
            <td>
                <?php echo $row_offline['department']; ?>
            </td>
            <!--ACTIONS-->
           
            
                 
            </tr>
            <?php
            if($max_row > 3){
            ?>
        <tr>
            <?php
            $max_row = 1;
            }
            
            }
            ?>
        </tr>
        </tbody>
    </table>
</div>

    <?php
}
?>
<!--<script>
    jQuery(function(){
       jQuery(".chkin").click(function(){
           alert("Checkin Clicked");
       });
    });
</script>-->
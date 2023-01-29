<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}



include('db_func.php');
global $con;


if (!chk_adm_login()) {
    header('location: login.php');
    exit;
}

$log_email = $_SESSION['log_adm_email'];
// grab login user data;

global $grab_user_data_fet;

$grab_user_data_qur = "SELECT `Account_type`, `U_id`, `D_id`, `r_id`, `U_name`, `u_email`, `profile_img` FROM `user` WHERE `u_email`='" . $log_email . "'";
$grab_user_data_exc = mysqli_query($con, $grab_user_data_qur) or die (mysqli_error($con));
$grab_user_data_fet = mysqli_fetch_assoc($grab_user_data_exc);

$pagename = "Logo Upload";
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/html">
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Sprout | build your momentum</title>
        <!-- Tell the browser to be responsive to screen width -->
        <link rel="shortcut icon" href="../img/Icon.ico" type="image/x-icon"/>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
         <link rel="stylesheet" href="dist/css/new_style.css">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- DataTables -->
        <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
        <!-- AdminLTE Skins. Choose a skin from the css/skins
             folder instead of downloading all of them to reduce the load. -->
        <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
        
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <?php include 'header.php'; ?>
    <?php include 'sidebar.php'; ?>
    <!-- Left side column. contains the logo and sidebar -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            
        </section>
        <!-- Content Header (Page header) -->
        <section class="content">

         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Logo Upload</h3>
             </div><!-- /.box-header -->
             <div class="box-body table-responsive">
                 <div class="">
                     <h5>Upload A Logo For Sprout .....</h5>
                     </br>
                      <a id="upload-btn" href="" class="btn btn-default" data-toggle="modal" data-target="#myModal">Logo</a>
                 </div>
                 
                 <div class="" width="400" height="200">
                     <?php
                        $query2="select * from `logo` WHERE `ID`=1";
                         $result2 = mysqli_query($con, $query2) or die (mysqli_error($con));
                         if(mysqli_num_rows($result2)>0){
                                 $row2 = mysqli_fetch_array($result2);
                              }  
                        ?>
                     <?php if(!empty($row2['Logoimage'])){ ?>
                    <img class="image_logo" src="<?= $row2['Logoimage']; ?>" height="50" width="50"/>
                   <?php }else{ ?>  
                    <img class="image_logo" src="img/sprout01.png"/>
                    <?php }?>


                 
                 </div>
            </div>
        </div><!-- /.box-body -->
     </section>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Change logo</h4>
            </div>
            <form action="logoupload.php" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="file" name="logoup" class="form-control">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default" id="update" name="submit">Upload</button>
                    <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- jQuery 2.1.4 -->
<script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
<!-- Bootstrap 3.3.5 -->
<script src="bootstrap/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="plugins/datatables/jquery.dataTables.min.js"></script>

<script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="plugins/fastclick/fastclick.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/app.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>


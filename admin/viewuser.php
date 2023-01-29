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

$pagename = "User Attendance";
?>
<!DOCTYPE html>
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
            <h1>Users</h1>
        </section>
        <!-- Content Header (Page header) -->
        <section class="content">

         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Employes Detail</h3>
             </div><!-- /.box-header -->
             <div class="box-body table-responsive">
                 <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Id</th>
                                <th>Department</th>
                                <th>Employe Name</th>
                                <th>Employe E-Mail</th>
                                <th>Employee Phone</th>
                                <th>Employee Designation</th>
                                <th>Salary</th>
                                <th>Last Login</th>
                                <th>Active User</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query="SELECT u.*,d.department FROM `user` u ,`department` d WHERE u.d_id = d.id ";
                            $query_run=mysqli_query($con,$query) or die (mysqli_error($con));
                            while($row=mysqli_fetch_assoc($query_run)){
                            ?>
                             <tr>
                                <td><?php echo $row['U_id'];?></td>
                                <td><?php echo $row['department'];?></td>
                                <td><?php echo $row['U_name'];?></td>
                                <td><?php echo $row['u_email'];?></td>
                                <td><?php echo $row['Phone_No'];?></td>
                                <td><?php echo $row['user_designation'];?></td>
                                 <td><?php echo $row['hourly_salary'];?></td>
                                <td><?php echo $row['Last_login'];?></td>
                                <td><?php echo $row['is_status'];?></td>
                                <td><a href="edituser.php?id=<?php echo $row['U_id'];?>" class="btn btn-default green-btn"><i class="fa fa-pencil"></i></a></td>
                            </tr>
                            <?php }?>
                         </tbody>
                 </table>
            </div>
        </div><!-- /.box-body -->
     </section>
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
<!-- page script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<?php include 'footer.php'; ?>
</body>
</html>


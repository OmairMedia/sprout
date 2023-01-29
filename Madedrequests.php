<?php
//If the HTTPS is not found to be "on"
if(!isset($_SERVER["HTTPS"]) || $_SERVER["HTTPS"] != "on")
{
    //Tell the browser to redirect to the HTTPS URL.
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"], true, 301);
    //Prevent the rest of the script from executing.
    exit;
}



include 'db.php';
session_set_cookie_params(86400, "/");
session_start();

if (!isset($_SESSION['U_name'])) {
    header('location:login.php');
}

?>

<!DOCTYPE html>
<html>
<?php
include('header.php'); ?>
    
    <!--<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
     
    <!-- DataTables -->
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"/>
<body>
<!-- header logo: style can be found in header.less -->
<?php include('content.php'); ?>
<div class="container">
    <!-- Break Requests -->
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>Break Requests</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 zero-padd">
            <div class="panel" style="height: auto">
                <div class="panel-body table-responsive"
                     style="padding: 15px;box-shadow: 7px 9px 5px 0px rgba(0,0,0,0.13);">
                    <div class="box">
                       <table class="table table-bordered table-striped tables">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>For</th>
                                <th>Current Break</th>
                                <th>Requested</th>
                                <th>Action</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query="SELECT * FROM `break_request` WHERE `User_id`='".$_SESSION['U_id']."'";
                            $query_run=mysqli_query($con,$query) or die (mysqli_error($con));
                            while($row=mysqli_fetch_assoc($query_run)){
                            ?>
                             <tr>
                                <td><?php echo $row['ID'];?></td>
                                <td><?php echo $row['Req_Made_For'];?></td>
                                <td><?php echo $row['current_break_hours'];?></td>
                                <td><?php echo $row['Total_Breaktime'];?></td>
                                <td><?php echo $row['Is_Approved'];?></td>
                            </tr>
                            <?php }?>
                         </tbody>
                         
                 </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
    
    <!--Checkout Requests -->
    
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>Checkout Requests</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 zero-padd">
            <div class="panel" style="height: auto">
                <div class="panel-body table-responsive"
                     style="padding: 15px;box-shadow: 7px 9px 5px 0px rgba(0,0,0,0.13);">
                    <div class="box">
                       <table class="table table-bordered table-striped tables">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Checkin</th>
                                <th>For</th>
                                <th>Breakhours</th>
                                <th>Checkout</th>
                                <th>Remarks</th>
                                <th>Action</th> 
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query="SELECT * FROM `requests` WHERE `User_id`='".$_SESSION['U_id']."'";
                            $query_run=mysqli_query($con,$query) or die (mysqli_error($con));
                            while($row=mysqli_fetch_assoc($query_run)){
                            ?>
                             <tr>
                                <td><?php echo $row['ID'];?></td>
                                <td><?php echo $row['checkin_time'];?></td>
                                <td><?php echo $row['Req_Made_For'];?></td>
                                <td><?php echo $row['breaktime'];?></td>
                                <td><?php echo $row['checkout_time'];?></td>
                                <td><?php echo $row['Remarks'];?></td>
                                <td><?php echo $row['Is_Approved'];?></td>
                            </tr>
                            <?php }?>
                         </tbody>
                 </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>

    <!--Leave Requests -->
    
    <div class="row">
        <div class="col-xs-12 desk-head">
            <h2>Leave Requests</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 zero-padd">
            <div class="panel" style="height: auto">
                <div class="panel-body table-responsive"
                     style="padding: 15px;box-shadow: 7px 9px 5px 0px rgba(0,0,0,0.13);">
                    <div class="box">
                       <table class="table table-bordered table-striped tables">
                            <thead>
                            <tr>
                                <th>S.no</th>
                                <th>For</th> 
                                <th>Remarks</th>
                                <th>Action</th> 
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query="SELECT * FROM `leave_request` WHERE `User_id`='".$_SESSION['U_id']."'";
                            $query_run=mysqli_query($con,$query) or die (mysqli_error($con));
                            while($row=mysqli_fetch_assoc($query_run)){
                            ?>
                             <tr>
                                <td><?php echo $row['ID'];?></td>
                                <td><?php echo $row['Req_Made_For'];?></td>
                                <td><?php echo $row['Remarks'];?></td>
                                <td><?php echo $row['Is_Approved'];?></td>
                            </tr>
                            <?php }?>
                         </tbody>
                 </table>
                    </div>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    </div>
</div>

    <script src="admin/bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="//cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <!-- SlimScroll -->
    <script src="admin/plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="admin/plugins/fastclick/fastclick.min.js"></script>
    
<script>
  jQuery(function(){
     $(document).ready( function () {
            $('.tables').DataTable({
                 "order": [[ 3, "desc" ]]
            });
    } ); 
  });
</script>
<?php include('footer.php'); ?>





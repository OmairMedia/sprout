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

$pagename = "Requests";
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
           
        </section>
        <!-- Content Header (Page header) -->
        <section class="content">

         <div class="box">
            <div class="box-header">
              <h3 class="box-title">Checkout Requests</h3>
             </div><!-- /.box-header -->
             <div class="box-body table-responsive">
                 <table id="example1" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>For</th>
                                <th>Current <br> Checkin</th>
                                <th>Current <br> Checkout</th>
                                <th>Requested <br> Break</th>
                                <th>Requested <br> Checkout</th>
                                <th>Remarks</th>
                                <th>Action</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $query="SELECT * FROM `requests` WHERE `Is_Approved`='No_Action'";
                            $query_run=mysqli_query($con,$query) or die (mysqli_error($con));
                            while($row=mysqli_fetch_assoc($query_run)){
                            ?>
                             <tr>
                                
                                <td><?php echo $row['User_id'];?></td>
                                <td><?php echo $row['User_Name'];?></td>
                                <td><?php echo $row['Req_Made_For'];?></td>
                                <td><?php echo $row['checkin_time'];?></td>
                                <td><?php echo $row['current_checkout'];?></td>
                                 <td><?php echo $row['breaktime'];?></td>
                                 <td><?php echo $row['checkout_time'];?></td>
                                 <td><?php echo $row['Remarks'];?></td>
                               
                                <td><a data-idreq="<?=$row['ID']?>" class="btn btn-default green-btn reqsuccessbtn" ><i class="fa fa-check"></i></a>
                                <a data-idreq="<?=$row['ID']?>" class="btn btn-danger reqcancelbtn"><i class="fa fa-ban"></i></a>
                                </td>
                            </tr>
                            <?php }?>
                         </tbody>
                 </table>
            </div>
        </div><!-- /.box-body -->
     </section>
    </div>
</div>
<!--
<div class="modal fade" id="Successmodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
                <h4 class="modal-title" id="myModalLabel">You Are Approving This Request</h4>
            </div>
            <form  method="post" action="Request.php">
                <div class="modal-body">
                    <div class="form-group">
                        <p>Are You Sure You Want To Approve It ?</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="box-footer">
                        <button type="submit" class="btn btn-default" id="breakrequest" name="SuccessSure">Yes,Im Sure</button>
                        <button type="button" class="btn btn-default" id="close" data-dismiss="modal">Cancel</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="../js/moment.min.js" type="text/javascript"></script>
<!-- page script -->
<script>
    $(function () {
        $("#example").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<script>
jQuery(function(){

$(document).ready(function(){
      //CHECK REQUEST SUCCESS MODULE
    jQuery(".reqsuccessbtn").click(function(){
        var idGrab = jQuery(this).attr('data-idreq');
        var checkingrab = jQuery(this).parent().parent().find("td:eq(4)").html();
        var breakgrab = jQuery(this).parent().parent().find("td:eq(5)").html();
        var checkoutgrab = jQuery(this).parent().parent().find("td:eq(6)").html();
        
        let data = {
            checkingrab,
            breakgrab,
            checkoutgrab
        }
        console.log('data -> ',data);

        var mcheckin  = moment(checkingrab,"LTS");
        var mcheckout = moment(checkoutgrab,"LTS");
        console.log('mcheckin -> ',mcheckin);
        console.log('mcheckout -> ',mcheckout);
        
        var duration = moment.duration(mcheckout.diff(mcheckin));
        var secs = duration.as('seconds');
        

        var toHHMMSS = (secs) => {
                var sec_num = parseInt(secs, 10)
                var hours   = Math.floor(sec_num / 3600)
                var minutes = Math.floor(sec_num / 60) % 60
                var seconds = sec_num % 60

                return [hours,minutes,seconds]
                    .map(v => v < 10 ? "0" + v : v)
                    .filter((v,i) => v !== "00" || i > 0)
                    .join(":")
            }
       var convertion = moment(toHHMMSS(secs),"HH:mm:ss").format("HH:mm:ss");
       var brkcon = moment(breakgrab,"HH:mm:ss").format("HH:mm:ss");


       if(convertion === '00:00:00') {
        var module = 'checksuccess';
        
            
        Swal.fire({
            title: 'Are you sure , you want to approve it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it?'
            }).then((result) => {
                if (result.value) {
					try{
					    jQuery.ajax({
                        type: "POST", 
                        url: "checksuccess.php",
                        data: {modulefor:module,reqid:idGrab},
                        cache: true,
                        success:function(html){
                            alert(html)
                        //  Swal.fire(html);
                        
                        //  setInterval('location.reload()', 2000);  
                        }
                    });
					}catch(err) {
						console.log(err);
					}
                                 
                }
            });   
        
     
       } else {
        if (brkcon > convertion) {
            Swal.fire({
                icon: 'error',
                title: 'Office Hours of this user is Less Than Requested Break Time !',
                footer: '<h6>You are Requested To Delete This Request !</h6>'
            }); 
         } else {
            var module = 'checksuccess';
      
        
            Swal.fire({
            title: 'Are you sure , you want to approve it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Approve it?'
            }).then((result) => {
                if (result.value) {
					try {
					   jQuery.ajax({
                        type: "POST", 
                        url: "checksuccess.php",
                        data: {modulefor:module,reqid:idGrab},
                        cache: true,
                        success:function(html){
                            alert(html)
                        //  Swal.fire(html);
                        
                        //  setInterval('location.reload()', 2000);  
                        }
                    });
					} catch(err) {
					  console.log(err);
					}
                                  
                }
            });  
        }
      }
    });
});
   
$(document).ready(function(){
//CHECK REQUEST CANCEL MODULE
jQuery(".reqcancelbtn").click(function(){
        var idGrab = jQuery(this).attr('data-idreq');
        var module = 'checkcancel';
     
        Swal.fire({
            title: 'Are you sure , you want to cancel it?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, Delete it?'
            }).then((result) => {
            if (result.value) {
                jQuery.ajax({
                    type: "POST",
                    url: "checkcancel.php",
                    data: {modulefor:module,reqid:idGrab},
                    cache: true,
                    success:function(html){   
                        alert(html)  
                        // Swal.fire(
                        // html,
                        // 'You have deleted the request',
                        // 'success'
                        // );
                       
                        // setInterval('location.reload()', 2000);
					}
                }); 
            }
            });
   
    }); 
});
    
});         
</script>
<?php include 'footer.php'; ?>
</body>
</html>


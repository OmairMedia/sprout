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
//Getting Last Login Day
/*
$curYM=date("Y-M");
$sessionid = $_SESSION['U_id'];
$query = "select `Last_Login` from `user` where `U_id`='".$sessionid."'";
$result = mysqli_query($con, $query) or die (mysqli_error($con));
if(mysqli_num_rows($result)>0){
 $row = mysqli_fetch_array($result);
};

$lastlogin = $row['Last_Login'];
<input type="text" id="lastlog" style="margin-left:4rem;" value="<?=$lastlogin?>"/> 
//echo '<div id="lastlog" style="margin-left:4rem;">'.$lastlogin.'</div>';
*/

?>

<!DOCTYPE html>
<html>
<link rel="shortcut icon" href="Favicon.ico" type="image/x-icon"/>   
<?php
include 'header.php'; 
?>


<body onload="users()">
   
    
<!-- header logo: style can be found in header.less -->
<?php include('content.php'); ?>
<div class="container">
    <div id="announcment-box" class="col-sm-3 toggle-right">
        <div class="col-xs-12 zero">
            <section class="panel" style="overflow:auto; border: 2px solid #55830c;margin-left: 15px;margin-right: 15px;border-radius: 0; max-width: 277px;">
                <header class="panel-heading">
                    <h4><b>Announcement</b></h4>
                </header>
                <div class="single-item">
                <?php
                $sql = "SELECT * FROM announcement";
                $res = mysqli_query($con, $sql);
                if (mysqli_num_rows($res) > 0) {
                    while($row = mysqli_fetch_assoc($res)) {
                        ?>
                        <div class="announce-shift" style="padding: 20px;">
                            <h4 style="padding: 0px 10px;"><?php echo $row['title']; ?></h4>
                            <div class="panel-body">
                                <?php echo $row['message']; ?>
                            </div>
                        </div>
                    <?php }
                } ?>
                </div>
            </section>

        </div>
        <button type="button" id="announcment-show" class="btn btn-default">
            <span class="glyphicon glyphicon-chevron-right"></span>

        </button>
    </div>
    <div class="col-sm-8">
        <div id='calendar'></div>
    </div>
    <div class="col-sm-4">
            <div id="dvData" class="col-xs-12">
                
            </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var date = new Date();
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: date,
            businessHours: true, // display business hours
            editable: true,
            events: [
                <?php foreach($r as $value): ?>
                {

                    title: '<?php echo $value[2]; ?>',
                    start: '<?php echo $value[1]; ?>',
                    constraint: 'businessHours'
                },
                <?php endforeach ?>
                {
                    title: 'Meeting',
                    start: '2016-06-13T11:00:00',
                    constraint: 'availableForMeeting', // defined below
                    color: '#257e4a'
                },
                {
                    title: 'Conference',
                    start: '2016-06-18',
                    end: '2016-06-20'
                },
                {
                    title: 'Party',
                    start: '2016-06-29T20:00:00'
                },

                // areas where "Meeting" must be dropped
                {
                    id: 'availableForMeeting',
                    start: '2016-06-11T10:00:00',
                    end: '2016-06-11T16:00:00',
                    rendering: 'background'
                },
                {
                    id: 'availableForMeeting',
                    start: '2016-06-13T10:00:00',
                    end: '2016-06-13T16:00:00',
                    rendering: 'background'
                },

                // red areas where no events can be dropped
                {
                    start: '2016-06-24',
                    end: '2016-06-28',
                    overlap: false,
                    rendering: 'background',
                    color: '#ff9f89'
                },
                {
                    start: '2016-06-06',
                    end: '2016-06-08',
                    overlap: false,
                    rendering: 'background',
                    color: '#ff9f89'
                }
            ]
        });
        $(".dropdown-toggle").click(function () {
            $(".dropdown-menu").toggle();
        })
    });

       

</script>
<script type="text/javascript">
    function users() {
        xtp = new XMLHttpRequest();
        if (xtp) {
            xtp.onreadystatechange = function () {
                if (xtp.readyState == 4) {
                    document.getElementById('dvData').innerHTML = xtp.responseText;
                }
            };
            xtp.open("GET", "user.php?users=''");
            xtp.send(null);

        }
    }
</script>
<!--User Functions-->


<?php include('footer.php');?>


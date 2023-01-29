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

<body>
<!-- header logo: style can be found in header.less -->
<?php include('content.php'); ?>
<div class="container">
    <div id="announcment-box1" class="col-sm-3">
        <div class="col-xs-12 zero">
            <section class="panel" style="overflow:auto;border: 2px solid #90b833;border-radius: 0;">
                <header class="panel-heading">
                    <h4><b>HOLIDAYS</b></h4>
                </header>
                    <?php
                    $date = date("Y-m-d");
                    $sql = "SELECT * FROM holidays WHERE `date`>='{$date}'";
                    $res = mysqli_query($con, $sql);
                    if (mysqli_num_rows($res) > 0) {
                    ?>
                        <table class="table table-hover">
                            <thead>
                            <tr style="background-color: #90b833;">
                                <th style="color: white;">Sr.</th>
                                <th style="color: white;">Date</th>
                                <th style="color: white;">Remarks</th>
                            </tr>
                            </thead>
                        <tbody>

                    <?php
                        while($row = mysqli_fetch_assoc($res)) {
                            ?>
                    <tr>
                                        <td><?php echo $row['id'] ?></td>
                                        <td><?php echo $row['date'] ?></td>
                                        <td><?php echo $row['user_remarks'] ?></td>
                    </tr>
                        <?php }
                    } ?>

                        </tbody>
                        </table>
            </section>

        </div>
<!--        <button type="button" id="announcment-show" class="btn btn-default">-->
<!--            <span class="glyphicon glyphicon-chevron-right"></span>-->
<!--        </button>-->
    </div>
    <div class="col-sm-9">
        <div id='calendar'></div>
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
       
    });

      

</script>

<?php
include('footer.php'); ?>
<!-- custom scrollbar plugin -->


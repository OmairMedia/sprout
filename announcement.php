<?php
include 'db.php';
session_set_cookie_params(86400,"/");
session_start();

if(!isset($_SESSION['e-mail']))
{
    header('location:login.php') ;

}
?>
<!DOCTYPE html>
<html>
<?php
include('header.php');?>

<body class="skin-black">
<!-- header logo: style can be found in header.less -->
<?php include('content.php');?>
<div class="wrapper row-offcanvas row-offcanvas-left">
    <!-- Left side column. contains the logo and sidebar -->
    <?php include('sidebar.php');?>







<!-- jQuery 2.0.2 -->

<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
<script src="js/jquery.min.js" type="text/javascript"></script>

<!-- jQuery UI 1.10.3 -->
<script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
<!-- Bootstrap -->
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- daterangepicker -->
<script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>

<script src="js/plugins/chart.js" type="text/javascript"></script>

<!-- datepicker
<script src="js/plugins/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>-->
<!-- Bootstrap WYSIHTML5
<script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>-->
<!-- iCheck -->
<script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
<!-- calendar -->
<script src="js/plugins/fullcalendar/fullcalendar.js" type="text/javascript"></script>

<!-- Director App -->
<script src="js/Director/app.js" type="text/javascript"></script>

<!-- Director dashboard demo (This is only for demo purposes) -->
<script src="js/Director/dashboard.js" type="text/javascript"></script>

<!-- Director for demo purposes -->
<script type="text/javascript">
    $('input').on('ifChecked', function(event) {
        // var element = $(this).parent().find('input:checkbox:first');
        // element.parent().parent().parent().addClass('highlight');
        $(this).parents('li').addClass("task-done");
        console.log('ok');
    });
    $('input').on('ifUnchecked', function(event) {
        // var element = $(this).parent().find('input:checkbox:first');
        // element.parent().parent().parent().removeClass('highlight');
        $(this).parents('li').removeClass("task-done");
        console.log('not');
    });

</script>
<script>
    $('#noti-box').slimScroll({
        height: '400px',
        size: '5px',
        BorderRadius: '5px'
    });

    $('input[type="checkbox"].flat-grey, input[type="radio"].flat-grey').iCheck({
        checkboxClass: 'icheckbox_flat-grey',
        radioClass: 'iradio_flat-grey'
    });
</script>
<script type="text/javascript">
    $(function() {
        "use strict";
        //BAR CHART
        var data = {
            labels: ["January", "February", "March", "April", "May", "June", "July"],
            datasets: [
                {
                    label: "My First dataset",
                    fillColor: "rgba(220,220,220,0.2)",
                    strokeColor: "rgba(220,220,220,1)",
                    pointColor: "rgba(220,220,220,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: [65, 59, 80, 81, 56, 55, 40]
                },
                {
                    label: "My Second dataset",
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(151,187,205,1)",
                    data: [28, 48, 40, 19, 86, 27, 90]
                }
            ]
        };
        new Chart(document.getElementById("linechart").getContext("2d")).Line(data,{
            responsive : true,
            maintainAspectRatio: false,
        });

    });
    // Chart.defaults.global.responsive = true;
</script>
<script type="text/javascript" src="js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="js/Timer.js"></script>
<script type="text/javascript" src="js/useTimer.js"></script>
<script type="text/javascript" src="js/materialize.min.js"></script>
<script type="text/javascript">
    function init() {
        initTimer('timer');
    }
    window.addEventListener("load", init);
</script>
<script>
    $(document).ready(function(){
        $('#stop').click(function(){
            var time=$("#timer").html();
            $('#currenttimeout').val($("#timer").html());

        })
    })
</script>
<script>
    $(document).ready(function(){
        $('#start').click(function(){
            var timein=$("#timer").html();
            $('#currenttime_in').val($("#timer").html());

        })
    })
</script>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
    $(function() {
        $(".submit_out").click(function() {
            var time = $("#currenttimeout").val();
            var dataString = 'currenttimeout='+ time;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString,
                cache: true,
                success: function(html){
                    alert(html);

//                                document.getElementById('content').value='';

                }
            });

            return false;
        });
    });
</script>
<script type="text/javascript">
    $(function() {
        $(".submit_in").click(function()
        {

            var timein = $("#currenttime_in").val();
            var dataString_timein = 'currenttime_in='+ timein;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString_timein,
                cache: true,
                success: function(html){
                    alert(html);

                    // document.getElementById('content').value='';

                }
            });

            return false;
        });
    });
</script>


</body>
</html>
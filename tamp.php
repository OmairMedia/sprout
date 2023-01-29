<!--<div class="col-md-12">
    <section class="panel" style="width:400px; float: left;

    border: 3px solid #73AD21;
    padding: 0px;margin-left:15px  ">
        <header class="panel-heading">
            Submit Your Leave
        </header>
        <?php
/*        if( isset($_GET['success'])){

            */ ?>

            <div class="success">Leave Submitted Successfully</div>

            <?php
/*
        }*/ ?>
        <?php
/*        if( isset($_GET['unsuccess'])){

            */ ?>

            <div class="success">Please Fill Up Form</div>

            <?php
/*
        }*/ ?>
        <div class="panel-body">
            <form class="form-horizontal tasi-form" method="post" action="leave_process.php">
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Leave Subject</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="leave_sub">
                    </div>
                </div>
                <input class="form-control" type="text" name="user_id" value="<?php /*$_SESSION['id'] */ ?>">
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">How much days required</label>
                    <div class="col-sm-10">
                        <input class="form-control" type="text" name="day_leave">
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Detail</label>
                    <div class="col-sm-10">
                        <input class="form-control round-input" type="text" name="detail">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Already Availed Leave</label>
                    <div class="col-sm-10">
                        <input class="form-control" id="focusedInput" name="leave_avail" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-2 col-sm-2 control-label">Date</label>
                    <div class="col-sm-10">
                        <input class="form-control" id="focusedInput" name="leave_date" type="date">
                    </div>
                </div>
                <input type="submit" class="btn btn-danger" name="submit" value="Submit" style="  ">


            </form>
        </div>
    </section>

</div>-->


<div class="row" style=" float:right; margin-right:200px;">

</div>




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
    $(document).ready(function () {
        $('#stop').click(function () {
            var time = $("#timer").html();
            $('#currenttimeout').val(time);


        })
    })
</script>

<script>
    $(document).ready(function () {
        $('#start').click(function () {
            var timein = $("#timer").html();
            $('#currenttime_in').val($("#timer").html());
        })
    })
</script>
<script>
    $(document).ready(function () {
        $('#resume').click(function () {
            var time_resume = $("#timer").html();
            $('#currenttime_resume').val($("#timer").html());

        })
    })
</script>
<script>
    $(document).ready(function () {
        $('#stop_to').click(function () {
            var time_stop = $("#timer").html();
            $('#currenttime_stop').val($("#timer").html());

        })
    })
</script>

<script type="text/javascript" src="js/jquery.min.js"></script>


<script type="text/javascript">
    $(function () {
        $(".submit_in").click(function () {

            var timein = $("#currenttime_in").val();
            var dataString_timein = 'currenttime_in=' + timein;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString_timein,
                cache: true,
                success: function (html) {
                    var split_res = html.split('|');
                    var cur_date_tm = split_res[1].replace(';', '|');
                    jQuery('#timer2').html(cur_date_tm);
                    alert(split_res[0]);

                }
            });

            return false;
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $(".submit_pause").click(function () {
            var time = $("#currenttimeout").val();
            var dataString = 'currenttimeout=' + time;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString,
                cache: true,
                success: function (html) {
                    alert(html);

                    //document.getElementById('content').value='';

                }
            });

            return false;
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $(".submit_resume").click(function () {

            var time_resume = $("#currenttime_resume").val();
            var dataString_timeresume = 'currenttime_resume=' + time_resume;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString_timeresume,
                cache: true,
                success: function (html) {
                    alert(html);

                    // document.getElementById('content').value='';

                }
            });

            return false;
        });
    });
</script>
<script type="text/javascript">
    $(function () {
        $(".submit_out").click(function () {
            var time_stop = $("#currenttime_stop").val();
            var dataString_stop = 'currenttime_stop=' + time_stop;

            $.ajax({
                type: "POST",
                url: "action.php",
                data: dataString_stop,
                cache: true,
                success: function (html) {
                    alert(html);
                    Timer.init('reset');
                    // document.getElementById('content').value='';

                }
            });

            return false;
        });
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
            }
            xtp.open("GET", "user.php?users=''");
            xtp.send(null);


        }
    }
    setInterval("users()", 1000);


    /*var get_dt_split = cur_date_tm.split('|');
     var get_date = get_dt_split[0];
     var get_time = get_dt_split[1];
     if (get_time.indexOf('am') !== -1) {
     get_time = get_time.replace('am', ' am');
     } else if (get_time.indexOf('pm') !== -1) {
     get_time = get_time.replace('pm', ' pm');
     }

     loop = setInterval(function () {
     var user_chkin_time_date = new Date(get_date + ' ' + get_time);
     var cur_time_date = new Date();
     var diff = Math.abs(cur_time_date - user_chkin_time_date);
     var get_dif_time = diff / (1000 * 60 * 60);
     var get_dif_time_exp = get_dif_time.toString().split('.');

     var get_h = get_dif_time_exp[0];
     var get_m_s = "0." + get_dif_time_exp[1];
     get_m_s = get_m_s * 1 * 60;
     get_m_s = get_m_s.toString().split('.');
     var get_m = get_m_s[0];
     var get_s = "0." + get_m_s[1];
     get_s = (get_s * 1) * 60;
     get_s = get_s.toString().split('.');
     get_s = get_s[0];

     if (get_h.length == 1) {
     get_h = '0' + get_h;
     }
     if (get_m.length == 1) {
     get_m = '0' + get_m;
     }
     if (get_s.length == 1) {
     get_s = '0' + get_s;
     }
     if(grab_timer.css('display') == 'none'){
     grab_timer.show();
     }
     grab_timer.html(get_h + ':' + get_m + ':' + get_s);
     }, 1000);
     loop_chk = true;*/
</script>


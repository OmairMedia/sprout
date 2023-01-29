<head>
    <meta charset="UTF-8">
    <title>Sprout | build your momentum</title>
    <link rel="icon" href="img/Icon.ico" type="image/x-icon" />
    
    <link rel="stylesheet" type="text/css" href="css/custom.css "/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="keywords" content="Sprout Attendence Management System">
    
    <!-- bootstrap 3.0.2 -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <!-- font Awesome -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <!-- Ionicons -->
    <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
    <!-- Morris chart -->
    <link href="css/morris/morris.css" rel="stylesheet" type="text/css" />
    <!-- jvectormap -->
    <!--<link href="css/jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" type="text/css" />-->
    <!-- Date Picker -->
    <link href="css/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
    <!-- fullCalendar -->
    <!-- <link href="css/fullcalendar/fullcalendar.css" rel="stylesheet" type="text/css" /> -->
    <!-- Daterange picker -->
    <link href="css/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <!-- iCheck for checkboxes and radio inputs -->
    <!--<link href="css/iCheck/all.css" rel="stylesheet" type="text/css" />-->
    <link href='css/fullcalendar.css' rel='stylesheet' />
    <link href='css/fullcalendar.print.css' rel='stylesheet' media='print' />
    <link href='https://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
    
    <!-- Theme style -->
    <link href="css/style.css" rel="stylesheet" type="text/css" />
    <link href="css/new_style.css" rel="stylesheet" type="text/css" />
    <!--<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>-->
    <!--<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>-->
    <link rel="stylesheet" type="text/css" href="css/slick.css"/>
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    <script src="js/cleave.min.js" type="text/javascript"></script> 
    <script src="admin/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!--<script src="js/jquery.min.js" type="text/javascript"></script>-->
    <script src="js/jquery-ui-1.10.3.min.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js" type="text/javascript"></script>
    <script src="js/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
    <!--<script src="js/plugins/chart.js" type="text/javascript"></script>-->
    <script src="js/plugins/iCheck/icheck.min.js" type="text/javascript"></script>
    <script src="js/fullcalendar.min.js" type="text/javascript"></script>
    <script src="js/moment.min.js" type="text/javascript"></script>
    <script src="js/Director/app.js" type="text/javascript"></script>
    <script src="js/Director/dashboard.js" type="text/javascript"></script>
    <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
    <script type="text/javascript" src="js/slick.min.js"></script>
    <!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>-->
    <!--<script src="alertifyjs/alertify.min.js"></script>
    <script src="https://kit.fontawesome.com/009071870e.js" crossorigin="anonymous"></script>-->


<script type="text/javascript">
    $(document).ready(function () {
        $("#toggle-button").click(function () {
//            $("#bottom-nav").slideToggle();
//            $("#timer2").toggle();
//            $("#attendence-action").toggle();
            if($(this).hasClass("toggle-up-btn")) {
                $(this).removeClass("toggle-up-btn");
                $(this).addClass("toggle-down-btn");
            }else if($(this).hasClass("toggle-down-btn")){
                $(this).addClass("toggle-up-btn");
                $(this).removeClass("toggle-down-btn");
            }else{
                $(this).addClass("toggle-up-btn");
            }
            $("#bottom-nav").toggleClass("toggle-up toggle-down");
            $(".pannel-down button span").toggleClass("glyphicon glyphicon-chevron-up glyphicon glyphicon-chevron-down");
        });
        $("#announcment-show").click(function(){
           $("#announcment-box").toggleClass('toggle-left toggle-right');
            $("#announcment-show span").toggleClass("glyphicon glyphicon-chevron-right glyphicon glyphicon-chevron-left");
        });
        
         $('.single-item').slick({
            arrows: true,
        });
    });
    
    
</script>


     

    
</head>
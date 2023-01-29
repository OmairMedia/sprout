 $(document).ready(function () {
        $("#toggle-button").click(function () {
            console.log('Panel Toggle Clicked ...!');
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
    });
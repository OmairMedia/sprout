<div id="footer">
    <div class="col-xs-12 col-sm-4" id="social-icons">
        <p style="padding: 0px;">
            <!--<iframe
                src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F4slash&tabs=timeline&width=340&height=70&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId=1634639690128178"
                width="340" height="70" style="border:none;overflow:hidden" scrolling="no" frameborder="0"
                allowTransparency="true"></iframe>-->
        </p>
        <p style="padding: 0px;"><span style="position: relative;top:4px;">A Product of</span><img src="img/4slash.png"
                                                                                                   style="width: 60px; margin:0px;">
        </p>
        <!--            <span><a href=""><i class="fa fa-twitter" aria-hidden="true"></i></a></span>-->
        <!--            <span><a href=""><i class="fa fa-linkedin" aria-hidden="true"></i></a></span>-->
    </div>
    <div class="col-xs-12 col-sm-4">
        <img src="img/sprout01.png" alt="" width="200px;" style="margin-top: 10px;">
    </div>
    <div class="col-xs-12 col-sm-4">
        <p style="font-size: 13px; margin-top: 30px;">24/7 Support info@4slash.com<br>
            Don’t worry we've got you covered</p>
        <span id="yourlastlogin"></span>    
            
    </div>
</div>
<?php
$query =  "SELECT * from `holidays`";
$result = mysqli_query($con,$query);
$r = mysqli_fetch_all($result);
foreach($r as $value){
    $data = $value;
}
?>

</body>
</html>

<script>
    //  const fetch = document.getElementById("lastlog").innerHTML;
    // document.getElementById("yourlastlogin").appendChild(fetch)
</script>
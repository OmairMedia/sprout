<?php
global $grab_user_data_fet;

?>
<header class="main-header">
    <!-- Logo -->
    <a href="index.php" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><img src="../img/logo-side-mini.png" alt="logo" width="30px;"></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg" style="padding: 5px 0px;"><img src="../img/sprout-side.png" alt="" width="160px"></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                
                <!-- User Account: style can be found in dropdown.less -->
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="dist/img/avatar.png" class="user-image" alt="User Image">
                        <span class="hidden-xs"><?php echo $grab_user_data_fet['u_email']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="dist/img/avatar.png" class="img-circle" alt="User Image">
                            <p>
                                <?=$grab_user_data_fet['u_email']?>
                                <small><?=$grab_user_data_fet['U_name'];?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="change_password.php" class="btn btn-default btn-flat">Change Password</a>
                            </div>
                            <form action="signout.php" method="post">
                            <div class="pull-right">
                                <input type="submit" name="signout" value="Sign Out" class="btn btn-default btn-flat">
                            </div>
                            </form>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>
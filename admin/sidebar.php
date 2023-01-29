<?php
global $grab_user_data_fet;

?>
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header"><h4>MAIN NAVIGATION</h4></li>
            <li class="treeview">
                <a href="index.php">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <!-- <li class="treeview">
                <a href="viewuser.php">
                    <i class="fa fa-user"></i>
                    <span>All users</span>
                </a>
            </li> -->
            <li class="treeview">
                <a href="viewdep.php">
                    <i class="fa fa-users" aria-hidden="true"></i>
                    <span>All Departments</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Attendence</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="user_attend.php">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            <span>User attendence</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="reports.php">
                            <i class="fa fa-book" aria-hidden="true"></i>
                            <span>Generate Reports</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="user_deduct.php">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Users Deduction</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="user_loan.php">
                            <i class="fa fa-money" aria-hidden="true"></i>
                            <span>Users Loan</span>
                        </a>
                    </li>
                    <li class="treeview">
                        <a href="daily_action.php">
                            <i class="fa fa-paperclip" aria-hidden="true"></i>
                            <span>Attendence Requests</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="treeview">
                <a href="holidays.php">
                    <i class="fa fa-list-alt icon"></i>
                    <span>Holidays</span>
                </a>
            </li>
            <!--
            <li class="treeview">
                <a href="#" style="color: #ffb62c;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <span>Task <span class="danger" style="font-size: 10px; color: red">(comming soon)</span></span>
                </a>
            </li>
            <li class="treeview">
                <a href="#" style="color: #ffb62c;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <span>Leave requests <span class="danger" style="font-size: 10px; color: red">(comming soon)</span></span>
                </a>
            </li>
            -->
            <li class="treeview">
                <a href="Userrequests.php" style="color: #ffb62c;">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    <span>Break Requests</span>
                </a>
            </li>
            <li class="treeview">
                <a href="Checkoutrequests.php" style="color: #ffb62c;">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    <span>Checkout Requests</span>
                </a>
            </li>
            <li class="treeview">
                <a href="Leaverequests.php" style="color: #ffb62c;">
                    <i class="fa fa-list" aria-hidden="true"></i>
                    <span>Leave Requests</span>
                </a>
            </li>
            <!--
            <li class="treeview">
                <a href="#" style="color: #ffb62c;">
                    <i class="fa fa-paperclip" aria-hidden="true"></i>
                    <span>Upload Logo<span class="danger" style="font-size: 10px; color: red">(comming soon)</span></span>
                </a>
            </li>
            -->
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
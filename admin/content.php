<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            DASHBOARD
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
<!--        <div class="row">-->
<!--            <div class="col-md-3"></div>-->
<!--            <div class="col-md-3 col-sm-6 col-xs-12">-->
<!--                <div class="info-box">-->
<!--                    <span class="info-box-icon bg-aqua"><i class="fa fa-sitemap fa-inverse"></i>-->
<!--						</span>-->
<!---->
<!--                    <div class="info-box-content">-->
<!--                        <span class="info-box-text">Department</span>-->
<!--                        <span class="info-box-number">--><?php //echo tbl_rows_count('department'); ?><!--</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="col-md-3 col-sm-6 col-xs-12">-->
<!--                <div class="info-box">-->
<!--                    <span class="info-box-icon bg-yellow"><i class="fa fa-users fa-inverse"></i></span>-->
<!---->
<!--                    <div class="info-box-content">-->
<!--                        <span class="info-box-text">Staff</span>-->
<!--                        <span class="info-box-number">--><?php //echo tbl_rows_count('user'); ?><!--</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
        <!-- /.row -->
        <div class="row" id="print_area">
            <div class="box box-info">
                <?php
                $succ_msg = call_sess('succes_msg');
                if (!empty($succ_msg)) {
                    echo "<p class='alert alert-success' style='margin-top: 10px;margin-right: 10px;margin-left: 10px;'>" . $succ_msg . "</p>";
                }
                ?>
                <div class="box-header">
                    <h3 class="box-title">Employes Detail</h3>
                    <a href="register.php" class="btn btn-default btn-sm pull-right add-details"><span
                            class="fa fa-plus"></span></a>
                </div><!-- /.box-header -->
                <div class="box-body">
<!--                    <a class="btn btn-default buttons-print pull-right" tabindex="0" aria-controls="DataTables_Table_0"-->
<!--                       onclick="printDiv('print_area')"><span><i class="fa fa-print"></i></span></a>-->
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Id</th>
                            <th>Department</th>
                            <th>Employe Name</th>
                            <th>Employe Account Type</th>
                            <th>Employee Phone</th>
                            <th>Employee Designation</th>
                            <th>Salary</th>
                            <th>Last Login</th>
                            <th>Active User</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $query = "SELECT u.*,d.department FROM `user` u ,`department` d WHERE u.d_id = d.id ";
                        $query_run = mysqli_query($con, $query) or die (mysqli_error($con));
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                            <tr>
                                <td><?php echo $row['U_id']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td><?php echo $row['U_name']; ?></td>
                                <td><?php echo $row['Account_type']; ?></td>
                                <td><?php echo $row['Phone_No']; ?></td>
                                <td><?php echo $row['user_designation']; ?></td>
                                <td><?php echo $row['hourly_salary']; ?></td>
                                <td><?php echo $row['Last_login']; ?></td>
                                <td><?php echo $row['is_status']; ?></td>
                                <td><?php echo $row['Active']; ?></td>
                                <td><a href="edituser.php?id=<?php echo $row['U_id']; ?>" class="btn btn-default green-btn"><i
                                            class="fa fa-pencil"></i> </a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.box-body -->
        <!-- Main row -->
        <div class="row">
            <!-- Left col -->
            <section class="col-lg-6 connectedSortable" style="padding-left: 0px;">
                <!-- quick email widget -->
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">ANNOUNCEMENT
                        </h3>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                            <button type="button" class="btn btn-default btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-default btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                        <!-- /. tools -->
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body pad">
                        <form action="add_announcement.php" method="post">
                            <div class="form-group">
                                <input type="text" class="form-control" name="title" placeholder="Enter Title:">
                            </div>
                            <!--<div class="form-group">
                                <input type="text" class="form-control" name="subject" placeholder="Subject">
                            </div>-->
                            <div>
                                <textarea name="content" class="textarea" placeholder="Content"
                                          style="width: 100%; height: 125px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
                            </div>
                            <div class="box-footer clearfix">
                                <button class="pull-right btn btn-default green-btn" name="submit">Add <i
                                        class="fa fa-arrow-circle-right"></i></button>
                            </div>
                        </form>
                    </div>
                </div>

            </section><!-- /.Left col -->
            <!-- right col (We are only adding the ID to make the widgets sortable)-->
            <section class="col-lg-6 connectedSortable" style="padding-right: 0px;">

                <!-- Calendar -->
                <div class="box box-solid ann-box">
                    <div class="box-header">
                        <h1 class="box-title" style="text-align: center; display: block;">ANNOUNCEMENT</h1>
                        <!-- tools box -->
                        <div class="pull-right box-tools">
                        </div><!-- /. tools -->
                    </div><!-- /.box-header -->
                    <?php

                    $sql = "SELECT * FROM announcement ORDER BY created_at DESC LIMIT 10";
                    $result = mysqli_query($con, $sql);
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <hr>
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" action="add_announcement.php">
                                            <input type="hidden" name="delAnnDt" value="<?php echo $row['id'] ?>">
                                            <button type="submit" class="btn btn-default red-btn cancel pull-right"><i class="fa fa-times" aria-hidden="true"></i></button>
                                        </form>
                                    </div>
                                </div>
                                <h2 class="box-title"><?php echo $row['title']; ?></h2>

                                <div class="pull-left"><?php echo $row['message']; ?></div>
                                <div
                                    class="pull-right"><?php echo date('d.m.Y', strtotime($row['created_at'])); ?></div>
                            </div><!-- /.box-body -->

                        <?php }
                    }else{
                        ?>
                        <hr>
                        <div class="box-body">
                            <h4>No Rows Found!</h4>
                        </div>
                        <?php
                    } ?>
                </div><!-- /.box -->

            </section><!-- right col -->
        </div><!-- /.row (main row) -->
    </section><!-- /.content -->
</div>

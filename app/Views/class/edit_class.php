<?php echo view('header');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                <div class="col-sm-6">
                    <h2>Class</h2>
                </div>
                <div class="col-sm-6">
                    
                </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

            <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Edit Class</h3>
                    </div>
                    <!-- /.card-header -->
                    <!-- form start -->
                    <form method="post" action="<?= base_url('/update-class') ?>">
                        <div class="card-body">
                        <div class="form-group">
                        <input type="hidden" name="class_id" id="id" value="<?php echo $class['class_id']; ?>">
                            <label for="name">Class Name</label>
                            <input type="text" name="class_name" class="form-control" value="<?php echo $class['class_name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="name">Section Name</label>
                            <input type="text" name="section_name" class="form-control" value="<?php echo $class['section_name']; ?>">
                        </div>
                        
                        <!-- /.card-body -->

                        <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                        <a href="<?php echo base_url('/classes');?>" type="cancel" class="btn btn-primary">Cancel</a>
                        </div>
                    </form>
                    </div>
                    <!-- /.card -->

                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">
                    <!-- Form Element sizes -->
                    
                    <!-- /.card -->
                </div>
                <!--/.col (right) -->
                </div>
                <!-- /.row -->
                </div><!-- /.container-fluid -->
        </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php echo view('footer');?>
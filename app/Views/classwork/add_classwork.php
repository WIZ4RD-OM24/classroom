<?= view('header'); ?>   
<div class="content-wrapper">
   <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          
          <div class="col-md-11">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title">Upload Classwork</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <form method="post" action="<?= base_url('/add-classwork')?>" enctype="multipart/form-data">
                <div class="form-group">
                <label for="inputStatus">Select Class</label>
                <select id="inputStatus" name ="class_name" class="form-control custom-select">
                  <option selected disabled>Select Class</option>
                  <?php foreach($classes as $class):  ?>
                        <?php if($class['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                        <option value="<?php echo $class['class_id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php endif;?>  
                        <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="inputStatus">Select Subject</label>
                <select id="inputStatus" name ="subject_name" class="form-control custom-select">
                  <option selected disabled>Select Subject</option>
                  <?php foreach($subjects as $subject):  ?>
                        <?php if($subject['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                        <option value="<?php echo $subject['subject_id']; ?>"><?php echo $subject['subject_name']; ?></option>
                        <?php endif;?>  
                        <?php endforeach; ?>
                </select>
              </div>
                <div class="form-group">
                  <input name="classwork_title" class="form-control" placeholder="Title">
                </div>
                <div class="form-group">
                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Attachment
                    <input type="file" name="classwork_file">
                  </div>
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <div class="float-right">
                  <input type="submit" class="btn btn-primary" value="Post"></input>
                </div>
                <button type="reset" class="btn btn-default"><i class="fas fa-times"></i> Discard</button>
              </div>
</form>
              <!-- /.card-footer -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

<?= view('footer'); ?>
<?php echo view('header');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>SUBJECT</h1>
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
                <h3 class="card-title">Edit Subject</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form method ="get" action="<?php echo base_url('SubjectController/edit_subject')?>">
                <div class="card-body">
                  <div class="form-group">
                    <label for="exampleInputEmail1">Subject Name</label>
                    <input type="text" name="subject_name" class="form-control" id="exampleInputEmail1" value="<?= $subject['subject_name'];?>">
                  </div>
                  <div class="form-group">
                    <label for="exampleInputPassword1">Assigned Teacher</label>
                    <select class="form-control select2" name="teacher_id" style="width: 100%;">
                    <option  selected disabled>Select one</option>
                        <?php foreach($teachers as $teacher):  ?>
                        <?php if($teacher['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                        <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['teacher_name']; ?></option>
                        <?php endif;?>  
                        <?php endforeach; ?>
                    </select>
                  </div>
                  
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                  <a href="<?php echo base_url('/subjects');?>" type="cancel" class="btn btn-primary">Cancel</a>
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
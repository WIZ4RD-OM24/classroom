<?php echo view('header');?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>STUDENTS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="d-flex justify-content-end">
              <a class="btn btn-danger">Back</a>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Manage Students</h3>
              </div>
              
              <!-- ./card-header -->
              
              <div class="card-body">
                <div class="d-flex justify-content-end">
                  <a href="  <?php echo base_url('/view-add-student');?>" class="btn btn-primary mb-2">Add Single Student</a>&nbsp;&nbsp;
                  <a href="  <?php echo base_url('/view-add-bulk-student');?>" class="btn btn-success mb-2">Add Bulk  Student</a>
                </div>
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Roll No.</th>
                      <th>Profile Image</th>
                      <th>Name</th>
                      <th>Email</th>
                      <th>Action</th>
                        
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($students as $student):?>
                     <?php if($student['admin_id']==$_SESSION['admin']['admin_id'] ):?>
                    <tr>
                      <td><?php echo $student['student_roll_no']; ?></td>
                      <td><?= $student['student_id']?></td>
                      <td><?php echo $student['student_name']; ?></td>
                      <td><?php echo $student['student_email']; ?></td>
                      <td><a href="<?php echo base_url('view-student/'.$student['student_id']);?>" class="btn btn-primary btn-sm"><i class="fas fa-eye"></i></a>
                      <a href="<?php echo base_url('delete-student/'.$student['student_id']);?> " class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash"></i></a>
                    </td>
                    <?php endif;?>
                      <?php endforeach;?>   
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
        
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php echo view('footer');?>
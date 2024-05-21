<?php echo view('header');?>
<?php use App\Models\TeacherModel;?>
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>SUBJECTS</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Manage Subjects</li>
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
                <h3 class="card-title">Manage Subjects</h3>
              </div>
              
              <!-- ./card-header -->
              
              <div class="card-body">
                <div class="d-flex justify-content-end">
                  <a href="<?php echo base_url('/view-add-subject');?>" class="btn btn-success mb-2">Add Subjects</a>
                </div>
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <th>Subject Name</th>
                      <th>Teacher Name</th>
                      <th>Action</th>
                        
                    </tr>
                  </thead>
                  <tbody>
                  <?php foreach($subjects as $subject):  ?>
                  <?php if($subject['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                  <tr>
                  <td><?php echo $subject['subject_name']; ?></td>
                  <?php $TeacherModel = new TeacherModel();?>
                  <?php $teacher = $TeacherModel->where('teacher_id', $subject['teacher_id'])->first();?>
                  <td><?php echo $teacher['teacher_name']; ?></td>
                  <td><a href="<?php echo base_url('edit-subject/'.$subject['subject_id']);?>" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-edit"></i></a>
                  <a href="<?php echo base_url('delete-subject/'.$subject['subject_id']);?> " class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash"></i></a>
                      </tr>
                      <?php endif;?>  
                      <?php endforeach; ?>
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
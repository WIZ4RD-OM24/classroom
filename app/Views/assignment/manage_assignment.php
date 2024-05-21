<?php echo view('header');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Assignments</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Add  Assignment</li>
            </ol>
          </div>
          
      </div><!-- /.container-fluid -->
      <div>
        <div class="d-flex justify-content-end">
        <a class="btn btn-success mb-2" href="<?= base_url('/view-post-assignment')?>"> Add Assignment </a>
          
        </div>
        </div>
    </section>
    <div class="card-body">

              
            <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Assignments</h3>
            </div>
            <div class="card-body p-0">
              <table class="table" >
                <thead>
                  <tr>
                    <th>Assignment Title</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Due Date</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                <?php use App\Models\ClassModel;
                  use App\Models\SubjectModel;?>
                <?php foreach($assignments as $assignment):  ?>
                  <?php if($assignment['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                  <tr>
                  <td><?php echo $assignment['assignment_post_title']; ?></td>
                  <?php $ClassModel = new ClassModel(); $SubjectModel = new SubjectModel();?>
                  <?php $subject = $SubjectModel->where('subject_id', $assignment['subject_id'])->first();?>
                  <?php $class = $ClassModel->where('class_id', $assignment['class_id'])->first();?>
                  <td><?php echo $class['class_name']; ?></td>
                  <td><?php echo $subject['subject_name']; ?></td>
                  <td><?php echo $assignment['assignment_post_due_date']; ?></td>
                  <td><a href="<?php echo base_url('edit-assignment/'.$assignment['assignment_post_id']);?>" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-eye"></i></a>
                  <a href="<?php echo base_url('delete-assignment/'.$assignment['assignment_post_id']);?> " class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash"></i></a>
                      </tr>
                      <?php endif;?>  
                      <?php endforeach; ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
              </div>

          </div>
        </div>
       
        <?php echo view('footer');?>

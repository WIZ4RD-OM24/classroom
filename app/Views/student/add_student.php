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
              <a href="<?php  echo base_url('/students');?>" class="btn btn-danger">Back</a>
            </ol>
          <div class="col-sm-6">
            
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <?php if(isset($validation)):?>
                <div class="alert alert-warning">
                   <?php echo $validation->listErrors(); ?>
                </div>
                <?php endif;?>
                <form method="get" action="<?php echo base_url('StudentController/add_student')?>">
      <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add Student</h3>

            <div class="card-tools">
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
              <form method="get" action="<?php echo base_url('StudentController/add_student')?>">
              <div class="form-group">
                  <label>Roll No</label>
                  <input type="text"  required name="student_roll_no" class="form-control" id="exampleInputEmail1" placeholder="Enter Roll no">
                </div>
                <div class="form-group">
                  <label>Name</label>
                  <input type="text"   required name="student_name" class="form-control" id="exampleInputEmail1" placeholder="Enter name">
                </div>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text"   required name="student_email" class="form-control" id="exampleInputEmail1" placeholder="Enter email   ">
                    
                </div>
                  <div class="form-group">
                                    <label for="exampleInputPassword1">Class</label>
                                    <select class="form-control select2" name="class_id" style="width: 100%;">
                                    <option  selected disabled>Select one</option>
                                        <?php foreach($classes as $class):  ?>
                                        <?php if($class['admin_id'] == $_SESSION['admin']['admin_id']):?>
                                        <option value="<?php echo $class['class_id']; ?>"><?php echo $class['class_name']; ?></option>
                                        <?php endif;?>
                                        <?php endforeach; ?>
                                    </select>
                                  </div>
                
                <ol class="d-flex justify-content-start">
                  <input type="submit" class="btn btn-success" value="Add Student"></input>
                </ol>
                <!-- /.form-group -->
                
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
              <div class="col-md-6">
                
               
                
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->

            <div class="row">
              <div class="col-12 col-sm-6">
                
                <!-- /.form-group -->
              </div>
              
              <!-- /.col -->
              <div class="col-12 col-sm-6">
                
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          
        </div>
        <!-- /.card -->

        

            
              
              
            
            <!-- /.card -->
          </div>
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php echo view('footer');?>

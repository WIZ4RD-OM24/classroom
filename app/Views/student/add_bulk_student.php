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
          <div class="container-fluid">
        <!-- SELECT2 EXAMPLE -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Add Bulk Student</h3>

            <div class="card-tools">
              
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-md-6">
            <form method="post" action="<?=base_url('/add-bulk-student') ?>" enctype="multipart/form-data">
			<div class="form-group mb-2">
				<input type="file" name="file" id="file" class="form-control">
			</div>
			<div class="form-group">
				<input type="submit" name="submit" value="Add " class="btn btn-primary" />
			</div>
		</form>
                    
                </div>
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

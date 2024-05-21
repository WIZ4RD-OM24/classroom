<?= view('header');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 >Manage Notices</h1>
            <div class="d-flex justify-content-end">
                  <a href="<?php echo base_url('/view-add-notice');?>" class="btn btn-success mb-2">Add Notice</a>
            </div>
          </div>
          
          
        </div>
      </div><!-- /.container-fluid -->
    </section>

   
 
     

        <!-- /.row -->
        <div class="row" >
          <div class="col-sm-12">
            <div class="card">
              <!-- ./card-header -->
              
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>

      <div class="wrapper">
        <!-- /.row -->
       
    
        <!-- Main content -->
        <section class="content" style="width:90%;margin-left: 30px;">
          <div class="row">
            <div class="col-md-12">
            <?php foreach($notices as $notice): ?>   
              <div class="card ">
                <div class="card-header">
                  <h3 class="card-title">
                    <?= $notice['notice_title']; ?>
                  </h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body card-outline card-primary">
                  
                   <?php echo $notice['notice_content'];?>   
                  
                </div>  
                <div class="card-footer inline">
                  Uploaded by Omkar at <?= $notice['created_at'];?>
                  <div class="d-flex justify-content-end">
                  <a href="<?php echo base_url('/view-add-notice');?>" class="btn btn-primary mb-2"><i class="fas fa-eye"></i></a>
                  <a href="<?php echo base_url('delete-notice/'.$notice['notice_id']);?>" class="btn btn-danger mb-2"><i class="fas fa-trash"></i></a>
            </div>
                </div>
              </div>
                <?php endforeach;?>
            </div>
          </div>
      </div>  

<?= view('footer');?>

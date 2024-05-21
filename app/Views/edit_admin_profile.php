<?php echo view('header');?>
<div class="content-wrapper">
  <div class="container bootstrap snippets bootdey">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Profile</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Profile</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <hr>
    <div class="col md-3 text-center">
        <!-- left column -->
        <div class="col md-3">


            <div class="text-center">
            <img src="<?php echo base_url('uploads/pfp/'.$admin['admin_image']);?>"  height="200px" width="200px" class="avatar img-circle img-thumbnail" alt="avatar">
            </div>
            <?= form_open_multipart('AdminController/edit_profile') ?>
            <div class="form-group">
                  <div class="btn btn-default btn-file">
                    <i class="fas fa-paperclip"></i> Upload Profile Image 
                    <input type="file" name="admin_image">
                  </div>
                  <p class="help-block">Max. 32MB</p>
            </div>
        </div>
    
    <!-- edit form column -->
    <div class="col md-3 text-center">
      <input type="hidden" name="admin_id" id="id" value="<?php echo $admin['admin_id']; ?>">
      <div class="form-group">
          <label class="col-lg-4 control-label justify-content-start">Name:</label>
          <div class="col-lg-8">
            <input class="form-control" name="admin_name" type="text" value="<?php echo $admin['admin_name'];?>">
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Email:</label>
          <div class="col-lg-8">
            <input  class="form-control" type="text" value="<?php echo $admin['admin_email'];?>" disabled>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Mobile No.:</label>
          <div class="col-lg-8">
            <input class="form-control" name="admin_mobile" type="text" value="<?php echo $admin['admin_mobile'];?>" >
          </div>
        </div>
        
        <div class="form-group">
          <label class="col-lg-3 control-label">Designation:</label>
          <div class="col-lg-8">
            <input class="form-control" name="admin_designation" type="text" value="<?php echo $admin['admin_designation'];?>" >
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">Organisation:</label>
          <div class="col-lg-8">
            <input class="form-control" name="admin_organisation" type="text" value="<?php echo $admin['admin_organisation'];?>" >
          </div>
        </div>
        <div class="d-flex justify-content-start">
          <input type = "submit" class = "btn btn-success" value="Save Changes"></button>
        </div>
      </form>
    </div>
</div>
</div>
</div>
<?php echo view('footer');?>
				        	              


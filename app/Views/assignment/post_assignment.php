<?= view('header');?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Add Assignments</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active"> Add  Assignment</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="card-body">
      <form method="post" action ="<?= base_url('/post-assignment');?>" enctype="multipart/form-data">
    <div class="form-group">
                <select id="inputStatus" name="class_id" class="form-control custom-select" style="width:50%">
                  <option selected disabled>Select Class</option>
                  <?php foreach($classes as $class):  ?>
                        <?php if($class['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                        <option value="<?php echo $class['class_id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php endif;?>  
                        <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <select id="inputStatus" name="subject_id" class="form-control custom-select" style="width:50%">
                  <option selected disabled>Select Subject</option>
                  <?php foreach($subjects as $subject):  ?>
                        <?php if($subject['admin_id'] == $_SESSION['admin']['admin_id']):?>   
                        <option value="<?php echo $subject['subject_id']; ?>"><?php echo $subject['subject_name']; ?></option>
                        <?php endif;?>  
                        <?php endforeach; ?>
                </select>
              </div>
              <div class="form-group">
                <label for="inputClientCompany">Title</label>
                <input type="text"  required name="title" id="inputClientCompany" class="form-control"></input>
              </div>
              <div class="form-group">
                <label for="inputClientCompany">Description</label>
                <textarea type="text" name="description" id="inputClientCompany" class="form-control"></textarea>
              </div>
              <div class="form-group">
                <label for="inputClientCompany">Upload</label>
                <input type="file" name="file">
                <label for="inputClientCompany">Due Date -  </label>
                <input type="date" name="due_date" required><br><br>
                
                </div>
                <div class="form-group">
                <input type="submit" class="btn btn-primary btn-sm"/>
                <input type="reset" class="btn btn-danger btn-sm"/>
              </form>
              
              </div>
              
            </div>
            
          </div>
              </div>

          </div>
        </div>
       <?= view('footer');?>
    



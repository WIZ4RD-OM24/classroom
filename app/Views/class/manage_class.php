
<?php echo view('header');?>
 <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Manage Class</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Manage Class</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Classes</h3>

                <div class="card-tools">
                    
                </div>
              </div>
 
              <div class="card-body">
                <div class="d-flex justify-content-end">
                  <a href="<?php echo base_url('/view-add-class');?>" class="btn btn-success mb-2">Add Class</a>
                </div>
                <table class="table table-hover text-nowrap">

                  <thead> 
                    <tr>
                      <th>Sr No</th>
                      <th>Classes Name</th>
                      <th>Section</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1;?>
                    <?php foreach($classes as $class):?>
                     <?php if($class['admin_id']==$_SESSION['admin']['admin_id'] ):?> 
                    <tr>
                      <th><?php echo $i?></th>
                      <td><?php echo $class['class_name']; ?></td>
                      <td><?php echo $class['section_name']; ?></td>
                      <td><a href="<?php echo base_url('edit-class/'.$class['class_id']);?>" class="btn btn-primary btn-sm"><i class="nav-icon fas fa-edit"></i></a>
                      <a href="<?php echo base_url('delete-class/'.$class['class_id']);?> " class="btn btn-danger btn-sm"><i class="nav-icon fas fa-trash"></i></a>
                    </td>
                    <?php $i++;?>
                    <?php endif;?>
                      <?php endforeach;?>    
                    </tr>
                      
                  </tbody>
                </table>
              </div>

<?php echo view('footer');?>
<?= view('header');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 style="margin-left:260px;">Teacher Profile</h1>
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
    <section class="content">
      <div class="container-fluid">
        <div class="row" style="margin-left:250px;">
          <div class="col-md-9">

            <!-- Profile Image -->
            <div class="card card-primary card-outline">
              <div class="card-body box-profile">
                <div class="text-center">
                  <img class="profile-user-img img-fluid img-circle"
                       src="<?php echo base_url('../dist/img/user4-128x128.jpg');?>"
                       alt="User profile picture">
                </div>

                <h3 class="profile-username text-center"><?php echo $teacher['teacher_name'];?></h3>


                <ul class="list-group list-group-unbordered mb-3">
                  <li class="list-group-item">
                    <b style="margin-left:210px;">Email :</b> <a class="text-center"><?php echo $teacher['teacher_email'];?></a>
                  </li>
                  <li class="list-group-item">
                    <b style="margin-left:210px;">Mobile :</b> <a class="text-center"><?php echo $teacher['teacher_mobile'];?></a>
                  </li>
                  <li class="list-group-item">
                    <b style="margin-left:170px;">Organization :</b> <a class="text-center"><?php echo $teacher['teacher_organisation'];?></a>
                  </li>
                </ul>

              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->

            <!-- About Me Box -->

<?= view('footer');?>
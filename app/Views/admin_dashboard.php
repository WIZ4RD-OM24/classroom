<?= view('header');?>
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Admin</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Admin</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><sub style="color: White; font-size: 30px;"><?= $class_count;?></sub></h3>

                <p><sub style="color: White; font-size: 20px;">Classes</sub></p>
              </div>
              <div class="icon">
               <i class="fa-solid fa-school"></i>
              </div>
              <a href="<?= base_url('/classes');?>" class="small-box-footer"><span style="color: White; font-size: 20px;">More info</span> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><sub style="color: White; font-size: 30px;"><?= $teacher_count;?></sub></h3>

                <p><sub style="color: White; font-size: 20px;">Teachers</sub></p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-chalkboard-user"></i>
              </div>
              <a href="<?= base_url('/teachers');?>" class="small-box-footer"><span style="color: White; font-size: 20px;">More info</span> <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><sub style="color: White; font-size: 30px;"><?= $student_count;?></sub></h3>

                <p><sub style="color: White; font-size: 20px;">Students</sub></p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-graduation-cap"></i>
              </div>
              <a href="<?= base_url('/students');?>" class="small-box-footer"><span style="color: White; font-size: 20px;">More info</span><i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><sub style="color: White; font-size: 30px;"><?= $subject_count;?></sub></h3>

                <p><sub style="color: White; font-size: 20px;">Subjects</sub></p>
              </div>
              <div class="icon">
                <i class="fa-solid fa-book"></i>
              </div>
              <a href="<?= base_url('/subjects');?>" class="small-box-footer"> <span style="color: White; font-size: 20px;">More info</span>
               <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
<?= view('footer');?>
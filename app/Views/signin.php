<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>AdminLTE 3 | Log in</title>
  <style>
body {
  background-image: url('bg.jpg');
  background-size: cover;
}
</style>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url('plugins/fontawesome-free/css/all.min.css');?>">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="<?= base_url('plugins/icheck-bootstrap/icheck-bootstrap.min.css');?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url('dist/css/adminlte.min.css');?>">
</head>
<body class=" login-page">
<?php if(session()->getFlashdata('msg')):?>
    <div class="alert alert-danger " role="alert">
    <?= session()->getFlashdata('msg'); ?>
    </div>
<?php endif;?>
<div class="login-box">
 
  <!-- /.login-logo -->
  <div class="card">
  
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in </p>

      <form action="<?php echo base_url('AdminController/loginAuth') ?>" method="post">
        <div class="input-group mb-3">
          <input type="email"name = "email" class="form-control" placeholder="Email">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name = "password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
          </div>
          <!-- /.col -->
          <br><br>
          
            <button type="submit" class="btn btn-primary btn-block">Sign In</button>
          
          <!-- /.col -->
        </div>
      </form>

     
      <!-- /.social-auth-links -->
      <br>
      <p class="mb-1" style="align:center">
        <a href="#" >Email doesn't exist? Contact your college!</a>
      </p>
     
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?= base_url('plugins/jquery/jquery.min.js');?>"></script>
<!-- Bootstrap 4 -->
<script src="<?= base_url('plugins/bootstrap/js/bootstrap.bundle.min.js');?>"></script>
<!-- AdminLTE App -->
<script src="<?= base_url('dist/js/adminlte.min.js');?>"></script>
</body>
</html>
<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<h1>Sign in</h1>
<p class="auth__sub">Administrators, teachers and students all sign in here.</p>

<form method="post" action="<?= route_to('login') ?>">
  <?= csrf_field() ?>

  <div class="field">
    <label for="email">Email address</label>
    <input class="input" type="email" id="email" name="email" autocomplete="username"
           required autofocus value="<?= esc(field_value('email'), 'attr') ?>"
           <?= field_error('email') ? 'aria-invalid="true"' : '' ?>>
    <?php if ($message = field_error('email')): ?>
      <span class="field__error"><?= esc($message) ?></span>
    <?php endif; ?>
  </div>

  <div class="field">
    <label for="password">Password</label>
    <input class="input" type="password" id="password" name="password"
           autocomplete="current-password" required
           <?= field_error('password') ? 'aria-invalid="true"' : '' ?>>
    <?php if ($message = field_error('password')): ?>
      <span class="field__error"><?= esc($message) ?></span>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn--primary">Sign in</button>
</form>

<div class="auth__foot">
  Setting up a new school? <a href="<?= route_to('register') ?>">Create a workspace</a>
</div>

<?php if (ENVIRONMENT !== 'production'): ?>
  <div class="demo-note">
    <strong>Demo accounts</strong> (after <code>php spark db:seed DemoSeeder</code>)<br>
    Admin <code>admin@classroom.test</code> / <code>admin@123</code><br>
    Teacher <code>anil@classroom.test</code> / <code>teacher@123</code><br>
    Student <code>rahul@classroom.test</code> / <code>student@123</code>
  </div>
<?php endif; ?>

<?= $this->endSection() ?>

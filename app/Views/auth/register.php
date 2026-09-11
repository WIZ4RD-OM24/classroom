<?= $this->extend('layouts/auth') ?>

<?= $this->section('content') ?>

<h1>Create a workspace</h1>
<p class="auth__sub">This sets up your school and its first administrator account.</p>

<form method="post" action="<?= route_to('register') ?>">
  <?= csrf_field() ?>

  <?php
  $fields = [
      ['admin_name', 'Your name', 'text', 'name', true],
      ['admin_organisation', 'School or college', 'text', 'organization', true],
      ['admin_designation', 'Your role', 'text', 'organization-title', false],
      ['admin_email', 'Email address', 'email', 'username', true],
      ['admin_mobile', 'Mobile number', 'tel', 'tel', false],
  ];
  ?>

  <?php foreach ($fields as [$name, $label, $type, $autocomplete, $required]): ?>
    <div class="field">
      <label for="<?= esc($name, 'attr') ?>"><?= esc($label) ?></label>
      <input class="input" type="<?= esc($type, 'attr') ?>" id="<?= esc($name, 'attr') ?>"
             name="<?= esc($name, 'attr') ?>" autocomplete="<?= esc($autocomplete, 'attr') ?>"
             <?= $required ? 'required' : '' ?>
             value="<?= esc(field_value($name), 'attr') ?>"
             <?= field_error($name) ? 'aria-invalid="true"' : '' ?>>
      <?php if ($message = field_error($name)): ?>
        <span class="field__error"><?= esc($message) ?></span>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>

  <div class="field">
    <label for="admin_password">Password</label>
    <input class="input" type="password" id="admin_password" name="admin_password"
           autocomplete="new-password" minlength="8" required
           <?= field_error('admin_password') ? 'aria-invalid="true"' : '' ?>>
    <span class="hint">At least 8 characters.</span>
    <?php if ($message = field_error('admin_password')): ?>
      <span class="field__error"><?= esc($message) ?></span>
    <?php endif; ?>
  </div>

  <div class="field">
    <label for="confirm_password">Confirm password</label>
    <input class="input" type="password" id="confirm_password" name="confirm_password"
           autocomplete="new-password" required
           <?= field_error('confirm_password') ? 'aria-invalid="true"' : '' ?>>
    <?php if ($message = field_error('confirm_password')): ?>
      <span class="field__error"><?= esc($message) ?></span>
    <?php endif; ?>
  </div>

  <button type="submit" class="btn btn--primary">Create workspace</button>
</form>

<div class="auth__foot">
  Already have an account? <a href="<?= route_to('login') ?>">Sign in</a>
</div>

<?= $this->endSection() ?>

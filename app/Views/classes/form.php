<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$editing = $class !== null;
$action  = $editing ? route_to('classes.update', $class['class_id']) : route_to('classes.store');
?>

<div class="page-head">
  <div class="page-head__text">
    <h1><?= esc($title) ?></h1>
    <p><?= $editing ? 'Rename this class or change its section.' : 'Name the class and, if you use them, its section.' ?></p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('classes') ?>">Back to classes</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= esc($action, 'attr') ?>">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field">
          <label for="class_name">Class name</label>
          <input class="input" type="text" id="class_name" name="class_name" required
                 placeholder="MCA First Year"
                 value="<?= esc(field_value('class_name', $class['class_name'] ?? ''), 'attr') ?>"
                 <?= field_error('class_name') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('class_name')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="section_name">Section</label>
          <input class="input" type="text" id="section_name" name="section_name" placeholder="A"
                 value="<?= esc(field_value('section_name', $class['section_name'] ?? ''), 'attr') ?>"
                 <?= field_error('section_name') ? 'aria-invalid="true"' : '' ?>>
          <span class="hint">Optional.</span>
          <?php if ($message = field_error('section_name')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">
          <?= $editing ? 'Save changes' : 'Add class' ?>
        </button>
        <a class="btn btn--ghost" href="<?= route_to('classes') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

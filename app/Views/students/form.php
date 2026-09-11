<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$editing = $student !== null;
$action  = $editing ? route_to('students.update', $student['student_id']) : route_to('students.store');
?>

<div class="page-head">
  <div class="page-head__text">
    <h1><?= esc($title) ?></h1>
    <p><?= $editing ? 'Update this student\'s details.' : 'Add a single student to a class.' ?></p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('students') ?>">Back to students</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= esc($action, 'attr') ?>">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field">
          <label for="student_roll_no">Roll number</label>
          <input class="input" type="text" id="student_roll_no" name="student_roll_no" required
                 value="<?= esc(field_value('student_roll_no', $student['student_roll_no'] ?? ''), 'attr') ?>"
                 <?= field_error('student_roll_no') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('student_roll_no')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="student_name">Full name</label>
          <input class="input" type="text" id="student_name" name="student_name" required
                 value="<?= esc(field_value('student_name', $student['student_name'] ?? ''), 'attr') ?>"
                 <?= field_error('student_name') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('student_name')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="student_email">Email address</label>
          <input class="input" type="email" id="student_email" name="student_email" required
                 value="<?= esc(field_value('student_email', $student['student_email'] ?? ''), 'attr') ?>"
                 <?= field_error('student_email') ? 'aria-invalid="true"' : '' ?>>
          <span class="hint">This is the address they sign in with.</span>
          <?php if ($message = field_error('student_email')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="student_mobile">Mobile number</label>
          <input class="input" type="tel" id="student_mobile" name="student_mobile"
                 value="<?= esc(field_value('student_mobile', $student['student_mobile'] ?? ''), 'attr') ?>">
        </div>

        <div class="field">
          <label for="class_id">Class</label>
          <select class="select" id="class_id" name="class_id">
            <option value="">Not assigned</option>
            <?php $selected = field_value('class_id', $student['class_id'] ?? ''); ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= esc((string) $class['class_id'], 'attr') ?>"
                <?= (string) $selected === (string) $class['class_id'] ? 'selected' : '' ?>>
                <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' — ' . esc($class['section_name']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($classes === []): ?>
            <span class="hint">No classes exist yet. <a href="<?= route_to('classes.new') ?>">Create one first.</a></span>
          <?php endif; ?>
        </div>
      </div>

      <?php if (! $editing): ?>
        <p class="hint mt-2">
          The student is given the temporary password <code>student@123</code>.
        </p>
      <?php endif; ?>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">
          <?= $editing ? 'Save changes' : 'Add student' ?>
        </button>
        <a class="btn btn--ghost" href="<?= route_to('students') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

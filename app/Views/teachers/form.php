<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$editing = $teacher !== null;
$action  = $editing ? route_to('teachers.update', $teacher['teacher_id']) : route_to('teachers.store');
?>

<div class="page-head">
  <div class="page-head__text">
    <h1><?= esc($title) ?></h1>
    <p><?= $editing ? 'Update this teacher\'s details.' : 'Give a member of staff access to the classroom.' ?></p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('teachers') ?>">Back to teachers</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= esc($action, 'attr') ?>">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field">
          <label for="teacher_name">Full name</label>
          <input class="input" type="text" id="teacher_name" name="teacher_name" required
                 value="<?= esc(field_value('teacher_name', $teacher['teacher_name'] ?? ''), 'attr') ?>"
                 <?= field_error('teacher_name') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('teacher_name')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="teacher_email">Email address</label>
          <input class="input" type="email" id="teacher_email" name="teacher_email" required
                 value="<?= esc(field_value('teacher_email', $teacher['teacher_email'] ?? ''), 'attr') ?>"
                 <?= field_error('teacher_email') ? 'aria-invalid="true"' : '' ?>>
          <span class="hint">This is the address they sign in with.</span>
          <?php if ($message = field_error('teacher_email')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="teacher_mobile">Mobile number</label>
          <input class="input" type="tel" id="teacher_mobile" name="teacher_mobile"
                 value="<?= esc(field_value('teacher_mobile', $teacher['teacher_mobile'] ?? ''), 'attr') ?>">
        </div>

        <div class="field">
          <label for="class_id">Class teacher of</label>
          <select class="select" id="class_id" name="class_id">
            <option value="">Not assigned</option>
            <?php $selected = field_value('class_id', $teacher['class_id'] ?? ''); ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= esc((string) $class['class_id'], 'attr') ?>"
                <?= (string) $selected === (string) $class['class_id'] ? 'selected' : '' ?>>
                <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' — ' . esc($class['section_name']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <?php if (! $editing): ?>
        <p class="hint mt-2">The teacher is given the temporary password <code>teacher@123</code>.</p>
      <?php endif; ?>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">
          <?= $editing ? 'Save changes' : 'Add teacher' ?>
        </button>
        <a class="btn btn--ghost" href="<?= route_to('teachers') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

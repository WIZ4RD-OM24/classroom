<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$editing = $subject !== null;
$action  = $editing ? route_to('subjects.update', $subject['subject_id']) : route_to('subjects.store');
?>

<div class="page-head">
  <div class="page-head__text">
    <h1><?= esc($title) ?></h1>
    <p>Subjects connect a class to the teacher who takes it.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('subjects') ?>">Back to subjects</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= esc($action, 'attr') ?>">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field">
          <label for="subject_name">Subject name</label>
          <input class="input" type="text" id="subject_name" name="subject_name" required
                 placeholder="Data Structures"
                 value="<?= esc(field_value('subject_name', $subject['subject_name'] ?? ''), 'attr') ?>"
                 <?= field_error('subject_name') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('subject_name')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="class_id">Class</label>
          <select class="select" id="class_id" name="class_id">
            <option value="">All classes</option>
            <?php $selectedClass = field_value('class_id', $subject['class_id'] ?? ''); ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= esc((string) $class['class_id'], 'attr') ?>"
                <?= (string) $selectedClass === (string) $class['class_id'] ? 'selected' : '' ?>>
                <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' — ' . esc($class['section_name']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="teacher_id">Teacher</label>
          <select class="select" id="teacher_id" name="teacher_id">
            <option value="">Unassigned</option>
            <?php $selectedTeacher = field_value('teacher_id', $subject['teacher_id'] ?? ''); ?>
            <?php foreach ($teachers as $teacher): ?>
              <option value="<?= esc((string) $teacher['teacher_id'], 'attr') ?>"
                <?= (string) $selectedTeacher === (string) $teacher['teacher_id'] ? 'selected' : '' ?>>
                <?= esc($teacher['teacher_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if ($teachers === []): ?>
            <span class="hint">No teachers yet. <a href="<?= route_to('teachers.new') ?>">Add one first.</a></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">
          <?= $editing ? 'Save changes' : 'Add subject' ?>
        </button>
        <a class="btn btn--ghost" href="<?= route_to('subjects') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

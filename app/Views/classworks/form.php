<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Add classwork</h1>
    <p>Share notes, slides or worksheets with a class.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('classworks') ?>">Back to classwork</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= route_to('classworks.store') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field field--full">
          <label for="classwork_title">Title</label>
          <input class="input" type="text" id="classwork_title" name="classwork_title" required
                 placeholder="Week 3 — Linked list exercises"
                 value="<?= esc(field_value('classwork_title'), 'attr') ?>"
                 <?= field_error('classwork_title') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('classwork_title')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="class_id">Class</label>
          <select class="select" id="class_id" name="class_id" required>
            <option value="">Select a class</option>
            <?php $selectedClass = field_value('class_id'); ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= esc((string) $class['class_id'], 'attr') ?>"
                <?= $selectedClass === (string) $class['class_id'] ? 'selected' : '' ?>>
                <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' — ' . esc($class['section_name']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="subject_id">Subject</label>
          <select class="select" id="subject_id" name="subject_id">
            <option value="">General</option>
            <?php $selectedSubject = field_value('subject_id'); ?>
            <?php foreach ($subjects as $subject): ?>
              <option value="<?= esc((string) $subject['subject_id'], 'attr') ?>"
                <?= $selectedSubject === (string) $subject['subject_id'] ? 'selected' : '' ?>>
                <?= esc($subject['subject_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field field--full">
          <label for="classwork_file">File</label>
          <input class="file-input" type="file" id="classwork_file" name="classwork_file">
          <span class="hint">PDF, Word, images, text or zip, up to 8 MB.</span>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Add classwork</button>
        <a class="btn btn--ghost" href="<?= route_to('classworks') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Post assignment</h1>
    <p>Students in the selected class see this on their dashboard immediately.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('assignments') ?>">Back to assignments</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= route_to('assignments.store') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field field--full">
          <label for="assignment_post_title">Title</label>
          <input class="input" type="text" id="assignment_post_title" name="assignment_post_title" required
                 placeholder="Binary Search Tree implementation"
                 value="<?= esc(field_value('assignment_post_title'), 'attr') ?>"
                 <?= field_error('assignment_post_title') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('assignment_post_title')): ?>
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
            <option value="">No subject</option>
            <?php $selectedSubject = field_value('subject_id'); ?>
            <?php foreach ($subjects as $subject): ?>
              <option value="<?= esc((string) $subject['subject_id'], 'attr') ?>"
                <?= $selectedSubject === (string) $subject['subject_id'] ? 'selected' : '' ?>>
                <?= esc($subject['subject_name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="assignment_post_due_date">Due date</label>
          <input class="input" type="date" id="assignment_post_due_date" name="assignment_post_due_date"
                 min="<?= esc(date('Y-m-d'), 'attr') ?>"
                 value="<?= esc(field_value('assignment_post_due_date'), 'attr') ?>"
                 <?= field_error('assignment_post_due_date') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('assignment_post_due_date')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="file">Attachment</label>
          <input class="file-input" type="file" id="file" name="file">
          <span class="hint">Optional. PDF, Word, images, text or zip, up to 8 MB.</span>
        </div>

        <div class="field field--full">
          <label for="assignment_post_description">Instructions</label>
          <textarea class="textarea" id="assignment_post_description" name="assignment_post_description"
                    placeholder="What should students hand in, and how will it be marked?"
          ><?= esc(field_value('assignment_post_description')) ?></textarea>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Post assignment</button>
        <a class="btn btn--ghost" href="<?= route_to('assignments') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

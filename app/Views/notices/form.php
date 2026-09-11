<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Post notice</h1>
    <p>Announce something to one class, or to the whole school.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('notices') ?>">Back to notices</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= route_to('notices.store') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field field--full">
          <label for="notice_title">Title</label>
          <input class="input" type="text" id="notice_title" name="notice_title" required
                 placeholder="Mid-semester examination schedule"
                 value="<?= esc(field_value('notice_title'), 'attr') ?>"
                 <?= field_error('notice_title') ? 'aria-invalid="true"' : '' ?>>
          <?php if ($message = field_error('notice_title')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>

        <div class="field">
          <label for="class_id">Audience</label>
          <select class="select" id="class_id" name="class_id">
            <option value="">Everyone</option>
            <?php $selected = field_value('class_id'); ?>
            <?php foreach ($classes as $class): ?>
              <option value="<?= esc((string) $class['class_id'], 'attr') ?>"
                <?= $selected === (string) $class['class_id'] ? 'selected' : '' ?>>
                <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' — ' . esc($class['section_name']) : '' ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="field">
          <label for="notice_file">Attachment</label>
          <input class="file-input" type="file" id="notice_file" name="notice_file">
          <span class="hint">Optional. PDF, Word or an image, up to 8 MB.</span>
        </div>

        <div class="field field--full">
          <label for="notice_content">Message</label>
          <textarea class="textarea" id="notice_content" name="notice_content"
          ><?= esc(field_value('notice_content')) ?></textarea>
          <?php if ($message = field_error('notice_content')): ?>
            <span class="field__error"><?= esc($message) ?></span>
          <?php endif; ?>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Post notice</button>
        <a class="btn btn--ghost" href="<?= route_to('notices') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

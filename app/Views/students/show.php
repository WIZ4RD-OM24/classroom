<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <div class="identity">
      <span class="avatar avatar--lg">
        <?= view('partials/avatar', [
            'user' => ['name' => $student['student_name'], 'image' => $student['student_image']],
        ]) ?>
      </span>
      <span>
        <h1><?= esc($student['student_name']) ?></h1>
        <p><?= esc($student['student_email']) ?></p>
      </span>
    </div>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('students') ?>">Back</a>
    <a class="btn btn--primary" href="<?= route_to('students.edit', $student['student_id']) ?>">
      <?= view('partials/icon', ['name' => 'edit']) ?> Edit
    </a>
  </div>
</div>

<div class="grid grid--split">
  <section class="card">
    <div class="card__head"><span class="card__title">Details</span></div>
    <div class="card__body">
      <dl class="details">
        <div>
          <dt>Roll number</dt>
          <dd><?= esc($student['student_roll_no']) ?></dd>
        </div>
        <div>
          <dt>Class</dt>
          <dd>
            <?php if ($class !== null): ?>
              <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' · ' . esc($class['section_name']) : '' ?>
            <?php else: ?>
              <span class="subtle">Unassigned</span>
            <?php endif; ?>
          </dd>
        </div>
        <div>
          <dt>Mobile</dt>
          <dd><?= esc($student['student_mobile'] ?: '—') ?></dd>
        </div>
        <div>
          <dt>Added</dt>
          <dd><?= esc(display_date($student['created_at'])) ?></dd>
        </div>
      </dl>
    </div>
  </section>

  <section class="card">
    <div class="card__head"><span class="card__title">Submitted work</span></div>
    <?php if ($submissions === []): ?>
      <?= view('partials/empty', [
          'icon'    => 'assignment',
          'heading' => 'No submissions',
          'message' => 'This student has not submitted any assignments yet.',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach ($submissions as $submission): ?>
          <div class="list__item">
            <div class="list__body">
              <div class="list__title"><?= esc($submission['assignment_post_title']) ?></div>
              <div class="list__meta">
                Submitted <?= esc(display_date($submission['created_at'])) ?>
              </div>
            </div>
            <?php if ($submission['assignment_upload_file']): ?>
              <a class="btn btn--sm btn--ghost"
                 href="<?= route_to('file.show', 'submissions', $submission['assignment_upload_file']) ?>">
                <?= view('partials/icon', ['name' => 'download']) ?> Open
              </a>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<?= $this->endSection() ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$auth      = service('auth');
$isStudent = $auth->is('student');
$badge     = due_badge($assignment['assignment_post_due_date']);
$closed    = $assignment['assignment_post_due_date'] !== null
    && $assignment['assignment_post_due_date'] < $today;
?>

<div class="page-head">
  <div class="page-head__text">
    <h1><?= esc($assignment['assignment_post_title']) ?></h1>
    <p>
      <?= esc($subject['subject_name'] ?? 'No subject') ?>
      <span class="dot">·</span>
      <?= esc($class['class_name'] ?? 'All classes') ?>
    </p>
  </div>
  <div class="page-head__actions">
    <span class="<?= esc($badge['class'], 'attr') ?>"><?= esc($badge['label']) ?></span>
    <a class="btn btn--ghost" href="<?= route_to('assignments') ?>">Back</a>
  </div>
</div>

<div class="grid grid--split">
  <section class="card">
    <div class="card__head"><span class="card__title">Instructions</span></div>
    <div class="card__body">
      <?php if (trim((string) $assignment['assignment_post_description']) !== ''): ?>
        <p class="prose"><?= esc($assignment['assignment_post_description']) ?></p>
      <?php else: ?>
        <p class="subtle">No instructions were provided.</p>
      <?php endif; ?>

      <?php if ($assignment['assignment_post_file']): ?>
        <a class="btn btn--ghost mt-2"
           href="<?= route_to('file.show', 'assignments', $assignment['assignment_post_file']) ?>">
          <?= view('partials/icon', ['name' => 'download']) ?> Download attachment
        </a>
      <?php endif; ?>

      <dl class="details mt-3">
        <div>
          <dt>Due</dt>
          <dd><?= esc(display_date($assignment['assignment_post_due_date'])) ?></dd>
        </div>
        <div>
          <dt>Posted</dt>
          <dd><?= esc(display_date($assignment['created_at'])) ?></dd>
        </div>
      </dl>
    </div>
  </section>

  <?php if ($isStudent): ?>
    <section class="card">
      <div class="card__head"><span class="card__title">Your submission</span></div>
      <div class="card__body">
        <?php if ($mine !== null): ?>
          <div class="row row--between">
            <div>
              <div class="strong">Submitted</div>
              <div class="muted text-sm"><?= esc(display_date($mine['updated_at'] ?? $mine['created_at'], 'j M Y, g:ia')) ?></div>
            </div>
            <?php if ($mine['assignment_upload_file']): ?>
              <a class="btn btn--sm btn--ghost"
                 href="<?= route_to('file.show', 'submissions', $mine['assignment_upload_file']) ?>">
                <?= view('partials/icon', ['name' => 'download']) ?> Your file
              </a>
            <?php endif; ?>
          </div>
          <?php if ($mine['assignment_upload_received_grades'] !== null): ?>
            <p class="mt-2">
              <span class="badge badge--success">
                Marked <?= esc((string) $mine['assignment_upload_received_grades']) ?>
                <?= $mine['assignment_upload_grades'] ? '/ ' . esc((string) $mine['assignment_upload_grades']) : '' ?>
              </span>
            </p>
          <?php endif; ?>
          <hr style="border:0;border-top:1px solid var(--border);margin:18px 0">
        <?php endif; ?>

        <?php if ($closed): ?>
          <p class="subtle">The due date has passed, so submissions are closed.</p>
        <?php else: ?>
          <form method="post" action="<?= route_to('assignments.submit', $assignment['assignment_post_id']) ?>"
                enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="field">
              <label for="file"><?= $mine !== null ? 'Replace your file' : 'Your work' ?></label>
              <input class="file-input" type="file" id="file" name="file" required>
              <span class="hint">PDF, Word, images, text or zip, up to 8 MB.</span>
            </div>

            <div class="field mt-2">
              <label for="remarks">Notes for your teacher</label>
              <textarea class="textarea" id="remarks" name="remarks" style="min-height:90px"></textarea>
            </div>

            <div class="form-actions">
              <button type="submit" class="btn btn--primary">
                <?= view('partials/icon', ['name' => 'upload']) ?>
                <?= $mine !== null ? 'Resubmit' : 'Submit work' ?>
              </button>
            </div>
          </form>
        <?php endif; ?>
      </div>
    </section>
  <?php else: ?>
    <section class="card">
      <div class="card__head">
        <span class="card__title">Submissions</span>
        <span class="badge" style="margin-left:auto"><?= esc((string) count($submissions)) ?></span>
      </div>
      <?php if ($submissions === []): ?>
        <?= view('partials/empty', [
            'icon'    => 'inbox',
            'heading' => 'Nothing submitted yet',
            'message' => 'Submissions from students appear here as they arrive.',
        ]) ?>
      <?php else: ?>
        <div class="list">
          <?php foreach ($submissions as $submission): ?>
            <div class="list__item">
              <div class="list__body">
                <div class="list__title"><?= esc($submission['student_name']) ?></div>
                <div class="list__meta">
                  <span><?= esc($submission['student_roll_no']) ?></span>
                  <span class="dot">·</span>
                  <span><?= esc(display_date($submission['created_at'])) ?></span>
                </div>
                <?php if ($excerpt = excerpt_text($submission['assignment_upload_remarks'])): ?>
                  <p class="list__excerpt"><?= esc($excerpt) ?></p>
                <?php endif; ?>
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
  <?php endif; ?>
</div>

<?= $this->endSection() ?>

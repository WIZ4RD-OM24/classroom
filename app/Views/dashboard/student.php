<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Hello, <?= esc(service('auth')->name()) ?></h1>
    <p>
      <?= count($assignments) ?> open <?= count($assignments) === 1 ? 'assignment' : 'assignments' ?>
      <?php if ($overdue > 0): ?>
        · <span class="badge badge--danger"><?= esc((string) $overdue) ?> past due</span>
      <?php endif; ?>
    </p>
  </div>
</div>

<div class="grid grid--split">
  <section class="card">
    <div class="card__head">
      <span class="card__title">Assignments due</span>
      <a class="btn btn--sm btn--ghost" style="margin-left:auto"
         href="<?= route_to('assignments') ?>">View all</a>
    </div>
    <?php if ($assignments === []): ?>
      <?= view('partials/empty', [
          'icon'    => 'check',
          'heading' => 'All caught up',
          'message' => 'You have no assignments due right now.',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach ($assignments as $assignment): ?>
          <?php $badge = due_badge($assignment['assignment_post_due_date']); ?>
          <div class="list__item">
            <div class="list__body">
              <a class="list__title" href="<?= route_to('assignments.show', $assignment['assignment_post_id']) ?>">
                <?= esc($assignment['assignment_post_title']) ?>
              </a>
              <div class="list__meta">
                <span><?= esc($assignment['subject_name'] ?? 'No subject') ?></span>
              </div>
            </div>
            <span class="<?= esc($badge['class'], 'attr') ?>"><?= esc($badge['label']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="card">
    <div class="card__head">
      <span class="card__title">Notices</span>
      <a class="btn btn--sm btn--ghost" style="margin-left:auto"
         href="<?= route_to('notices') ?>">View all</a>
    </div>
    <?php if ($notices === []): ?>
      <?= view('partials/empty', [
          'icon'    => 'notice',
          'heading' => 'No notices',
          'message' => 'Announcements from your school will appear here.',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach (array_slice($notices, 0, 5) as $notice): ?>
          <div class="list__item">
            <div class="list__body">
              <div class="list__title"><?= esc($notice['notice_title']) ?></div>
              <div class="list__meta"><?= esc(display_date($notice['created_at'])) ?></div>
              <?php if ($excerpt = excerpt_text($notice['notice_content'])): ?>
                <p class="list__excerpt"><?= esc($excerpt) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<section class="card mt-2">
  <div class="card__head">
    <span class="card__title">Recent classwork</span>
    <a class="btn btn--sm btn--ghost" style="margin-left:auto"
       href="<?= route_to('classworks') ?>">View all</a>
  </div>
  <?php if ($classworks === []): ?>
    <?= view('partials/empty', [
        'icon'    => 'classwork',
        'heading' => 'No classwork yet',
        'message' => 'Material shared by your teachers will appear here.',
    ]) ?>
  <?php else: ?>
    <div class="list">
      <?php foreach ($classworks as $classwork): ?>
        <div class="list__item">
          <div class="list__body">
            <div class="list__title"><?= esc($classwork['classwork_title']) ?></div>
            <div class="list__meta">
              <span><?= esc($classwork['subject_name'] ?? 'General') ?></span>
              <span class="dot">·</span>
              <span><?= esc(display_date($classwork['created_at'])) ?></span>
            </div>
          </div>
          <?php if ($classwork['classwork_file']): ?>
            <a class="btn btn--sm btn--ghost"
               href="<?= route_to('file.show', 'classworks', $classwork['classwork_file']) ?>">
              <?= view('partials/icon', ['name' => 'download']) ?> Download
            </a>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

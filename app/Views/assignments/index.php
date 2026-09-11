<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php $canManage = service('auth')->canManage(); ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Assignments</h1>
    <p><?= esc((string) count($assignments)) ?> posted</p>
  </div>
  <?php if ($canManage): ?>
    <div class="page-head__actions">
      <a class="btn btn--primary" href="<?= route_to('assignments.new') ?>">
        <?= view('partials/icon', ['name' => 'plus']) ?> Post assignment
      </a>
    </div>
  <?php endif; ?>
</div>

<section class="card">
  <?php if ($assignments === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'assignment',
        'heading'     => 'No assignments',
        'message'     => $canManage
            ? 'Post an assignment and it will appear on the dashboard of every student in the class.'
            : 'Nothing has been set for your class yet.',
        'actionHref'  => $canManage ? route_to('assignments.new') : null,
        'actionLabel' => $canManage ? 'Post assignment' : null,
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
              <span class="dot">·</span>
              <span><?= esc($assignment['class_name'] ?? 'All classes') ?></span>
              <span class="dot">·</span>
              <span>Posted <?= esc(display_date($assignment['created_at'])) ?></span>
            </div>
            <?php if ($excerpt = excerpt_text($assignment['assignment_post_description'])): ?>
              <p class="list__excerpt"><?= esc($excerpt) ?></p>
            <?php endif; ?>
          </div>
          <div class="row">
            <span class="<?= esc($badge['class'], 'attr') ?>"><?= esc($badge['label']) ?></span>
            <?php if ($canManage): ?>
              <?= view('partials/delete_form', [
                  'action'  => route_to('assignments.delete', $assignment['assignment_post_id']),
                  'confirm' => 'Delete "' . $assignment['assignment_post_title'] . '"? Submissions stay but the assignment is removed.',
              ]) ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

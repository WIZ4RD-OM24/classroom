<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php $canManage = service('auth')->canManage(); ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Classwork</h1>
    <p>Notes, slides and material shared with a class</p>
  </div>
  <?php if ($canManage): ?>
    <div class="page-head__actions">
      <a class="btn btn--primary" href="<?= route_to('classworks.new') ?>">
        <?= view('partials/icon', ['name' => 'plus']) ?> Add classwork
      </a>
    </div>
  <?php endif; ?>
</div>

<section class="card">
  <?php if ($classworks === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'classwork',
        'heading'     => 'No classwork yet',
        'message'     => $canManage
            ? 'Share notes or slides with a class and they appear here and on student dashboards.'
            : 'Material shared by your teachers will appear here.',
        'actionHref'  => $canManage ? route_to('classworks.new') : null,
        'actionLabel' => $canManage ? 'Add classwork' : null,
    ]) ?>
  <?php else: ?>
    <div class="list">
      <?php foreach ($classworks as $classwork): ?>
        <div class="list__item">
          <span class="avatar" style="background:var(--surface-3);color:var(--text-muted)">
            <?= view('partials/icon', ['name' => 'file']) ?>
          </span>
          <div class="list__body">
            <div class="list__title"><?= esc($classwork['classwork_title']) ?></div>
            <div class="list__meta">
              <span><?= esc($classwork['subject_name'] ?? 'General') ?></span>
              <span class="dot">·</span>
              <span><?= esc($classwork['class_name'] ?? 'All classes') ?></span>
              <span class="dot">·</span>
              <span><?= esc(display_date($classwork['created_at'])) ?></span>
            </div>
          </div>
          <div class="row">
            <?php if ($classwork['classwork_file']): ?>
              <a class="btn btn--sm btn--ghost"
                 href="<?= route_to('file.show', 'classworks', $classwork['classwork_file']) ?>">
                <?= view('partials/icon', ['name' => 'download']) ?> Download
              </a>
            <?php endif; ?>
            <?php if ($canManage): ?>
              <?= view('partials/delete_form', [
                  'action'  => route_to('classworks.delete', $classwork['classwork_id']),
                  'confirm' => 'Delete "' . $classwork['classwork_title'] . '"?',
              ]) ?>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

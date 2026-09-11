<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php $canManage = service('auth')->canManage(); ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Notices</h1>
    <p><?= esc((string) count($notices)) ?> posted</p>
  </div>
  <?php if ($canManage): ?>
    <div class="page-head__actions">
      <a class="btn btn--primary" href="<?= route_to('notices.new') ?>">
        <?= view('partials/icon', ['name' => 'plus']) ?> Post notice
      </a>
    </div>
  <?php endif; ?>
</div>

<section class="card">
  <?php if ($notices === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'notice',
        'heading'     => 'No notices',
        'message'     => $canManage
            ? 'Notices reach a single class, or everyone if you leave the class blank.'
            : 'Announcements from your school will appear here.',
        'actionHref'  => $canManage ? route_to('notices.new') : null,
        'actionLabel' => $canManage ? 'Post notice' : null,
    ]) ?>
  <?php else: ?>
    <div class="list">
      <?php foreach ($notices as $notice): ?>
        <div class="list__item">
          <div class="list__body">
            <div class="list__title"><?= esc($notice['notice_title']) ?></div>
            <div class="list__meta">
              <span class="badge<?= $notice['class_name'] ? ' badge--brand' : '' ?>">
                <?= esc($notice['class_name'] ?? 'Everyone') ?>
              </span>
              <span><?= esc(display_date($notice['created_at'])) ?></span>
            </div>
            <?php if (trim((string) $notice['notice_content']) !== ''): ?>
              <p class="list__excerpt"><?= esc($notice['notice_content']) ?></p>
            <?php endif; ?>
            <?php if ($notice['notice_file']): ?>
              <a class="btn btn--sm btn--ghost mt-1"
                 href="<?= route_to('file.show', 'notices', $notice['notice_file']) ?>">
                <?= view('partials/icon', ['name' => 'download']) ?> Attachment
              </a>
            <?php endif; ?>
          </div>
          <?php if ($canManage): ?>
            <?= view('partials/delete_form', [
                'action'  => route_to('notices.delete', $notice['notice_id']),
                'confirm' => 'Delete "' . $notice['notice_title'] . '"?',
            ]) ?>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

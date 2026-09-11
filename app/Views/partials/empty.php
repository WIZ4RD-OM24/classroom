<?php
/**
 * Empty-state block shown instead of a bare table with no rows.
 *
 * @var string      $heading
 * @var string      $message
 * @var string|null $icon
 * @var string|null $actionHref
 * @var string|null $actionLabel
 */
?>
<div class="empty">
  <div class="empty__icon"><?= view('partials/icon', ['name' => $icon ?? 'inbox']) ?></div>
  <h3><?= esc($heading) ?></h3>
  <p><?= esc($message) ?></p>
  <?php if (! empty($actionHref) && ! empty($actionLabel)): ?>
    <a class="btn btn--primary" href="<?= esc($actionHref, 'attr') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> <?= esc($actionLabel) ?>
    </a>
  <?php endif; ?>
</div>

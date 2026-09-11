<?php
/**
 * A delete control.
 *
 * Deletion is a POST form carrying a CSRF token, not the GET link it used to
 * be — a link that any crawler, prefetcher or forged <img> tag could fire.
 *
 * @var string $action
 * @var string $confirm
 */
?>
<form method="post" action="<?= esc($action, 'attr') ?>" class="inline-form"
      data-confirm="<?= esc($confirm, 'attr') ?>">
  <?= csrf_field() ?>
  <button type="submit" class="btn btn--sm btn--danger" aria-label="Delete">
    <?= view('partials/icon', ['name' => 'trash']) ?>
  </button>
</form>

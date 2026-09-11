<?php

/**
 * Flash messages.
 *
 * The old code used four different flash keys — successmsg, errormsg, msg and
 * message — and only the login screen ever rendered any of them, so almost
 * every "X added successfully!" message was set and silently discarded.
 */
$session = session();

$errors = $session->getFlashdata('errors');
$errors = is_array($errors) ? $errors : [];

$blocks = [];

foreach ([
    'success' => ['tone' => 'success', 'icon' => 'check'],
    'error'   => ['tone' => 'error', 'icon' => 'alert'],
    'warning' => ['tone' => 'warn', 'icon' => 'warn'],
] as $key => $meta) {
    $text = $session->getFlashdata($key);

    if ($text) {
        $blocks[] = [
            'tone'   => $meta['tone'],
            'icon'   => $meta['icon'],
            'text'   => $text,
            // Field-level errors belong with the first failure message, not
            // with a success message that happens to be on the same request.
            'errors' => $key === 'success' ? [] : $errors,
        ];

        if ($key !== 'success') {
            $errors = [];
        }
    }
}

if ($errors !== []) {
    $blocks[] = [
        'tone'   => 'error',
        'icon'   => 'alert',
        'text'   => 'Please correct the following:',
        'errors' => $errors,
    ];
}

if ($blocks === []) {
    return;
}
?>
<div class="alerts" role="status" aria-live="polite">
<?php foreach ($blocks as $block): ?>
  <div class="alert alert--<?= esc($block['tone'], 'attr') ?>">
    <?= view('partials/icon', ['name' => $block['icon']]) ?>
    <div>
      <div><?= esc($block['text']) ?></div>
      <?php if ($block['errors'] !== []): ?>
        <ul>
          <?php foreach ($block['errors'] as $error): ?>
            <li><?= esc($error) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

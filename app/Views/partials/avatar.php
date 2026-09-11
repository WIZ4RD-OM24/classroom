<?php
/**
 * Avatar image, falling back to the person's initials.
 *
 * @var array $user
 */
$name  = (string) ($user['name'] ?? '?');
$image = $user['image'] ?? null;

$initials = '';
foreach (preg_split('/\s+/', trim($name)) ?: [] as $part) {
    if ($part !== '') {
        $initials .= mb_strtoupper(mb_substr($part, 0, 1));
    }
    if (mb_strlen($initials) === 2) {
        break;
    }
}
?>
<?php if ($image): ?>
  <img src="<?= esc(route_to('file.show', 'avatars', $image), 'attr') ?>" alt="">
<?php else: ?>
  <?= esc($initials !== '' ? $initials : '?') ?>
<?php endif; ?>

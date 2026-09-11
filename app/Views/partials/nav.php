<?php

/**
 * Sidebar navigation.
 *
 * The old sidebar showed every administrative link to every role, including
 * students, and had dead entries pointing at "#" and at "index3.html".
 * Links here are filtered by role, matching the route filters exactly.
 *
 * @var string $active
 */
$auth   = service('auth');
$manage = $auth->canManage();

$item = static function (string $key, string $label, string $href, string $icon) use ($active) {
    $current = $active === $key ? ' aria-current="page"' : '';

    return '<a class="sidebar__link" href="' . esc($href, 'attr') . '"' . $current . '>'
        . view('partials/icon', ['name' => $icon])
        . '<span>' . esc($label) . '</span></a>';
};
?>

<?= $item('dashboard', 'Dashboard', route_to('dashboard'), 'dashboard') ?>

<div class="sidebar__section">Classroom</div>
<?= $item('assignments', 'Assignments', route_to('assignments'), 'assignment') ?>
<?= $item('classworks', 'Classwork', route_to('classworks'), 'classwork') ?>
<?= $item('notices', 'Notices', route_to('notices'), 'notice') ?>

<?php if ($manage): ?>
  <div class="sidebar__section">Manage</div>
  <?= $item('classes', 'Classes', route_to('classes'), 'school') ?>
  <?= $item('subjects', 'Subjects', route_to('subjects'), 'book') ?>
  <?= $item('teachers', 'Teachers', route_to('teachers'), 'teacher') ?>
  <?= $item('students', 'Students', route_to('students'), 'students') ?>
<?php endif; ?>

<div class="sidebar__section">Account</div>
<?= $item('profile', 'My profile', route_to('profile'), 'user') ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <title><?= esc($title ?? 'Sign in') ?> · Classroom</title>
  <link rel="icon" href="<?= base_url('favicon.ico') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <script>
    (function () {
      try {
        var saved = localStorage.getItem('classroom.theme');
        var dark = saved ? saved === 'dark'
          : window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', dark ? 'dark' : 'light');
      } catch (e) {
        document.documentElement.setAttribute('data-theme', 'light');
      }
    })();
  </script>
</head>
<body>
<main class="auth">
  <div class="auth__card">
    <span class="auth__brand">
      <span class="sidebar__mark"><?= view('partials/icon', ['name' => 'school']) ?></span>
      Classroom
    </span>

    <?= view('partials/alerts') ?>

    <div class="card">
      <div class="card__body">
        <?= $this->renderSection('content') ?>
      </div>
    </div>
  </div>
</main>

<script src="<?= base_url('assets/js/app.js') ?>" defer></script>
</body>
</html>

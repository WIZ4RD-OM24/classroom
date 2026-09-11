<?php

/**
 * The authenticated application shell.
 *
 * Replaces the old header.php / footer.php pair, which was included with
 * `view('header')` at the top of every page and `view('footer')` at the bottom.
 * Several pages closed tags the other one had never opened, and the header read
 * `$_SESSION['admin']['admin_name']` unconditionally, so every page fatally
 * errored for a teacher or student session.
 */
$auth = service('auth');
$user = $auth->user() ?? [];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="color-scheme" content="light dark">
  <title><?= esc($title ?? 'Classroom') ?> · Classroom</title>
  <link rel="icon" href="<?= base_url('favicon.ico') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
  <script>
    // Applied before first paint so the page never flashes the wrong theme.
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
<body data-nav="closed">
<a class="skip-link" href="#main">Skip to content</a>

<div class="layout">
  <div class="sidebar__scrim" data-nav-close></div>

  <aside class="sidebar">
    <a class="sidebar__brand" href="<?= route_to('dashboard') ?>">
      <span class="sidebar__mark"><?= view('partials/icon', ['name' => 'school']) ?></span>
      <span>Classroom</span>
    </a>

    <nav class="sidebar__nav" aria-label="Main">
      <?= view('partials/nav', ['active' => $active ?? '']) ?>
    </nav>

    <div class="sidebar__footer">
      <div class="identity">
        <span class="avatar"><?= view('partials/avatar', ['user' => $user]) ?></span>
        <span class="identity__body">
          <span class="identity__name"><?= esc($user['name'] ?? 'Guest') ?></span>
          <span class="identity__meta"><?= esc(ucfirst((string) ($user['role'] ?? ''))) ?></span>
        </span>
      </div>
    </div>
  </aside>

  <div class="main">
    <header class="topbar">
      <button type="button" class="btn btn--icon nav-toggle" data-nav-toggle
              aria-expanded="false" aria-label="Toggle navigation">
        <?= view('partials/icon', ['name' => 'menu']) ?>
      </button>

      <span class="topbar__title"><?= esc($title ?? 'Classroom') ?></span>

      <div class="topbar__spacer"></div>

      <button type="button" class="btn btn--icon" data-theme-toggle aria-label="Switch theme">
        <?= view('partials/icon', ['name' => 'theme']) ?>
      </button>

      <div class="menu">
        <button type="button" class="btn btn--icon" data-menu-toggle aria-expanded="false"
                aria-haspopup="true" aria-label="Account menu">
          <span class="avatar" style="width:28px;height:28px;font-size:12px">
            <?= view('partials/avatar', ['user' => $user]) ?>
          </span>
        </button>
        <div class="menu__panel" hidden>
          <div class="menu__header">
            <div class="strong"><?= esc($user['name'] ?? 'Guest') ?></div>
            <div class="muted text-sm"><?= esc($user['email'] ?? '') ?></div>
          </div>
          <a class="menu__item" href="<?= route_to('profile') ?>">
            <?= view('partials/icon', ['name' => 'user']) ?> My profile
          </a>
          <form method="post" action="<?= route_to('logout') ?>">
            <?= csrf_field() ?>
            <button type="submit" class="menu__item">
              <?= view('partials/icon', ['name' => 'logout']) ?> Sign out
            </button>
          </form>
        </div>
      </div>
    </header>

    <main class="content" id="main">
      <?= view('partials/alerts') ?>
      <?= $this->renderSection('content') ?>
    </main>
  </div>
</div>

<script src="<?= base_url('assets/js/app.js') ?>" defer></script>
</body>
</html>

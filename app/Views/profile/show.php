<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<?php
$auth = service('auth');
$name = $profile[$prefix . '_name'] ?? '';
?>

<div class="page-head">
  <div class="page-head__text">
    <div class="identity">
      <span class="avatar avatar--lg">
        <?= view('partials/avatar', [
            'user' => ['name' => $name, 'image' => $profile[$prefix . '_image'] ?? null],
        ]) ?>
      </span>
      <span>
        <h1><?= esc($name) ?></h1>
        <p><?= esc($profile[$prefix . '_email'] ?? '') ?></p>
      </span>
    </div>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--primary" href="<?= route_to('profile.edit') ?>">
      <?= view('partials/icon', ['name' => 'edit']) ?> Edit profile
    </a>
  </div>
</div>

<section class="card">
  <div class="card__head"><span class="card__title">Account</span></div>
  <div class="card__body">
    <dl class="details">
      <div>
        <dt>Role</dt>
        <dd><?= esc(ucfirst((string) $auth->role())) ?></dd>
      </div>
      <div>
        <dt>Mobile</dt>
        <dd><?= esc($profile[$prefix . '_mobile'] ?: '—') ?></dd>
      </div>
      <?php if ($prefix === 'admin'): ?>
        <div>
          <dt>Designation</dt>
          <dd><?= esc($profile['admin_designation'] ?: '—') ?></dd>
        </div>
        <div>
          <dt>Organisation</dt>
          <dd><?= esc($profile['admin_organisation'] ?: '—') ?></dd>
        </div>
      <?php endif; ?>
      <?php if ($prefix === 'student'): ?>
        <div>
          <dt>Roll number</dt>
          <dd><?= esc($profile['student_roll_no']) ?></dd>
        </div>
      <?php endif; ?>
      <?php if ($prefix === 'teacher'): ?>
        <div>
          <dt>Organisation</dt>
          <dd><?= esc($profile['teacher_organisation'] ?: '—') ?></dd>
        </div>
      <?php endif; ?>
      <div>
        <dt>Member since</dt>
        <dd><?= esc(display_date($profile['created_at'] ?? null)) ?></dd>
      </div>
    </dl>
  </div>
</section>

<?= $this->endSection() ?>

<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <div class="identity">
      <span class="avatar avatar--lg">
        <?= view('partials/avatar', [
            'user' => ['name' => $teacher['teacher_name'], 'image' => $teacher['teacher_image']],
        ]) ?>
      </span>
      <span>
        <h1><?= esc($teacher['teacher_name']) ?></h1>
        <p><?= esc($teacher['teacher_email']) ?></p>
      </span>
    </div>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('teachers') ?>">Back</a>
    <a class="btn btn--primary" href="<?= route_to('teachers.edit', $teacher['teacher_id']) ?>">
      <?= view('partials/icon', ['name' => 'edit']) ?> Edit
    </a>
  </div>
</div>

<div class="grid grid--split">
  <section class="card">
    <div class="card__head"><span class="card__title">Details</span></div>
    <div class="card__body">
      <dl class="details">
        <div>
          <dt>Organisation</dt>
          <dd><?= esc($teacher['teacher_organisation'] ?: '—') ?></dd>
        </div>
        <div>
          <dt>Class teacher of</dt>
          <dd>
            <?php if ($class !== null): ?>
              <?= esc($class['class_name']) ?><?= $class['section_name'] ? ' · ' . esc($class['section_name']) : '' ?>
            <?php else: ?>
              <span class="subtle">Unassigned</span>
            <?php endif; ?>
          </dd>
        </div>
        <div>
          <dt>Mobile</dt>
          <dd><?= esc($teacher['teacher_mobile'] ?: '—') ?></dd>
        </div>
        <div>
          <dt>Added</dt>
          <dd><?= esc(display_date($teacher['created_at'])) ?></dd>
        </div>
      </dl>
    </div>
  </section>

  <section class="card">
    <div class="card__head"><span class="card__title">Subjects taught</span></div>
    <?php if ($subjects === []): ?>
      <?= view('partials/empty', [
          'icon'    => 'book',
          'heading' => 'No subjects',
          'message' => 'This teacher has not been assigned to any subject yet.',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach ($subjects as $subject): ?>
          <div class="list__item">
            <div class="list__body">
              <div class="list__title"><?= esc($subject['subject_name']) ?></div>
            </div>
            <a class="btn btn--sm btn--ghost" href="<?= route_to('subjects.edit', $subject['subject_id']) ?>">Edit</a>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<?= $this->endSection() ?>

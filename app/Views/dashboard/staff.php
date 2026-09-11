<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Good <?= esc(date('G') < 12 ? 'morning' : (date('G') < 17 ? 'afternoon' : 'evening')) ?>,
      <?= esc(service('auth')->name()) ?></h1>
    <p>Here is what is happening across your classroom today.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('notices.new') ?>">
      <?= view('partials/icon', ['name' => 'notice']) ?> Post notice
    </a>
    <a class="btn btn--primary" href="<?= route_to('assignments.new') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> New assignment
    </a>
  </div>
</div>

<div class="grid grid--stats">
  <?php foreach ($stats as $stat): ?>
    <a class="stat" href="<?= esc($stat['href'], 'attr') ?>">
      <span class="stat__icon stat__icon--<?= esc($stat['tone'], 'attr') ?>">
        <?= view('partials/icon', ['name' => $stat['icon']]) ?>
      </span>
      <div class="stat__value"><?= esc(number_format($stat['value'])) ?></div>
      <div class="stat__label"><?= esc($stat['label']) ?></div>
    </a>
  <?php endforeach; ?>
</div>

<div class="grid grid--split mt-2">
  <section class="card">
    <div class="card__head">
      <span class="card__title">Upcoming assignments</span>
      <a class="btn btn--sm btn--ghost" style="margin-left:auto"
         href="<?= route_to('assignments') ?>">View all</a>
    </div>
    <?php if ($upcoming === []): ?>
      <?= view('partials/empty', [
          'icon'        => 'assignment',
          'heading'     => 'Nothing due',
          'message'     => 'No assignments are currently open. Post one to get started.',
          'actionHref'  => route_to('assignments.new'),
          'actionLabel' => 'New assignment',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach ($upcoming as $assignment): ?>
          <?php $badge = due_badge($assignment['assignment_post_due_date']); ?>
          <div class="list__item">
            <div class="list__body">
              <a class="list__title" href="<?= route_to('assignments.show', $assignment['assignment_post_id']) ?>">
                <?= esc($assignment['assignment_post_title']) ?>
              </a>
              <div class="list__meta">
                <span><?= esc($assignment['subject_name'] ?? 'No subject') ?></span>
                <span class="dot">·</span>
                <span><?= esc($assignment['class_name'] ?? 'All classes') ?></span>
              </div>
            </div>
            <span class="<?= esc($badge['class'], 'attr') ?>"><?= esc($badge['label']) ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <section class="card">
    <div class="card__head">
      <span class="card__title">Recent notices</span>
      <a class="btn btn--sm btn--ghost" style="margin-left:auto"
         href="<?= route_to('notices') ?>">View all</a>
    </div>
    <?php if ($notices === []): ?>
      <?= view('partials/empty', [
          'icon'        => 'notice',
          'heading'     => 'No notices yet',
          'message'     => 'Notices you post appear here and on every student dashboard.',
          'actionHref'  => route_to('notices.new'),
          'actionLabel' => 'Post notice',
      ]) ?>
    <?php else: ?>
      <div class="list">
        <?php foreach ($notices as $notice): ?>
          <div class="list__item">
            <div class="list__body">
              <div class="list__title"><?= esc($notice['notice_title']) ?></div>
              <div class="list__meta">
                <span><?= esc($notice['class_name'] ?? 'Everyone') ?></span>
                <span class="dot">·</span>
                <span><?= esc(display_date($notice['created_at'])) ?></span>
              </div>
              <?php if ($excerpt = excerpt_text($notice['notice_content'])): ?>
                <p class="list__excerpt"><?= esc($excerpt) ?></p>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>
</div>

<section class="card mt-2">
  <div class="card__head">
    <span class="card__title">Classes</span>
    <a class="btn btn--sm btn--ghost" style="margin-left:auto"
       href="<?= route_to('classes') ?>">Manage</a>
  </div>
  <?php if ($classes === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'school',
        'heading'     => 'No classes yet',
        'message'     => 'Create a class before adding students, subjects or assignments.',
        'actionHref'  => route_to('classes.new'),
        'actionLabel' => 'Add class',
    ]) ?>
  <?php else: ?>
    <div class="card__body--flush table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Class</th>
            <th scope="col">Section</th>
            <th scope="col">Students</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($classes as $class): ?>
            <tr>
              <td class="strong"><?= esc($class['class_name']) ?></td>
              <td class="muted"><?= esc($class['section_name'] ?: '—') ?></td>
              <td class="num"><?= esc((string) $class['student_count']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

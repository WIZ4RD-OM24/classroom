<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Teachers</h1>
    <p><?= esc((string) count($teachers)) ?> on staff</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--primary" href="<?= route_to('teachers.new') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> Add teacher
    </a>
  </div>
</div>

<section class="card">
  <div class="card__head">
    <form class="search" method="get" action="<?= route_to('teachers') ?>" role="search">
      <?= view('partials/icon', ['name' => 'search']) ?>
      <input class="input" type="search" name="q" placeholder="Search name or email"
             value="<?= esc($search, 'attr') ?>" aria-label="Search teachers">
    </form>
    <?php if ($search !== ''): ?>
      <a class="btn btn--sm btn--ghost" href="<?= route_to('teachers') ?>">Clear</a>
    <?php endif; ?>
  </div>

  <?php if ($teachers === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'teacher',
        'heading'     => $search !== '' ? 'No matching teachers' : 'No teachers yet',
        'message'     => $search !== ''
            ? 'No teacher matches "' . $search . '".'
            : 'Add the teaching staff who will post assignments and classwork.',
        'actionHref'  => $search !== '' ? null : route_to('teachers.new'),
        'actionLabel' => $search !== '' ? null : 'Add teacher',
    ]) ?>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Teacher</th>
            <th scope="col">Class</th>
            <th scope="col">Mobile</th>
            <th scope="col"><span class="visually-hidden">Actions</span></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($teachers as $teacher): ?>
            <tr>
              <td>
                <div class="identity">
                  <span class="avatar">
                    <?= view('partials/avatar', [
                        'user' => ['name' => $teacher['teacher_name'], 'image' => $teacher['teacher_image']],
                    ]) ?>
                  </span>
                  <span class="identity__body">
                    <span class="identity__name"><?= esc($teacher['teacher_name']) ?></span>
                    <span class="identity__meta"><?= esc($teacher['teacher_email']) ?></span>
                  </span>
                </div>
              </td>
              <td>
                <?php if ($teacher['class_name']): ?>
                  <span class="badge badge--brand"><?= esc($teacher['class_name']) ?></span>
                <?php else: ?>
                  <span class="subtle">Unassigned</span>
                <?php endif; ?>
              </td>
              <td class="muted"><?= esc($teacher['teacher_mobile'] ?: '—') ?></td>
              <td>
                <div class="table__actions">
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('teachers.show', $teacher['teacher_id']) ?>"
                     aria-label="View <?= esc($teacher['teacher_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'eye']) ?>
                  </a>
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('teachers.edit', $teacher['teacher_id']) ?>"
                     aria-label="Edit <?= esc($teacher['teacher_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'edit']) ?>
                  </a>
                  <?= view('partials/delete_form', [
                      'action'  => route_to('teachers.delete', $teacher['teacher_id']),
                      'confirm' => 'Delete ' . $teacher['teacher_name'] . '? This cannot be undone.',
                  ]) ?>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</section>

<?= $this->endSection() ?>

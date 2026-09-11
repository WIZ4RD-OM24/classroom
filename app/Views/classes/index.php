<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Classes</h1>
    <p><?= esc((string) count($classes)) ?> <?= count($classes) === 1 ? 'class' : 'classes' ?></p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--primary" href="<?= route_to('classes.new') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> Add class
    </a>
  </div>
</div>

<section class="card">
  <?php if ($classes === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'school',
        'heading'     => 'No classes yet',
        'message'     => 'Classes are the backbone of everything else — students, subjects, assignments and notices all attach to one.',
        'actionHref'  => route_to('classes.new'),
        'actionLabel' => 'Add class',
    ]) ?>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Class</th>
            <th scope="col">Section</th>
            <th scope="col">Students</th>
            <th scope="col">Created</th>
            <th scope="col"><span class="visually-hidden">Actions</span></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($classes as $class): ?>
            <tr>
              <td class="strong"><?= esc($class['class_name']) ?></td>
              <td class="muted"><?= esc($class['section_name'] ?: '—') ?></td>
              <td class="num"><?= esc((string) $class['student_count']) ?></td>
              <td class="muted nowrap"><?= esc(display_date($class['created_at'])) ?></td>
              <td>
                <div class="table__actions">
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('classes.edit', $class['class_id']) ?>"
                     aria-label="Edit <?= esc($class['class_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'edit']) ?>
                  </a>
                  <?= view('partials/delete_form', [
                      'action'  => route_to('classes.delete', $class['class_id']),
                      'confirm' => 'Delete ' . $class['class_name'] . '? Students in it will become unassigned.',
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

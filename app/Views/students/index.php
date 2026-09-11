<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Students</h1>
    <p><?= esc((string) count($students)) ?> enrolled</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('students.import') ?>">
      <?= view('partials/icon', ['name' => 'upload']) ?> Import CSV
    </a>
    <a class="btn btn--primary" href="<?= route_to('students.new') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> Add student
    </a>
  </div>
</div>

<section class="card">
  <div class="card__head">
    <form class="search" method="get" action="<?= route_to('students') ?>" role="search">
      <?= view('partials/icon', ['name' => 'search']) ?>
      <input class="input" type="search" name="q" placeholder="Search name, roll number or email"
             value="<?= esc($search, 'attr') ?>" aria-label="Search students">
    </form>
    <?php if ($search !== ''): ?>
      <a class="btn btn--sm btn--ghost" href="<?= route_to('students') ?>">Clear</a>
    <?php endif; ?>
  </div>

  <?php if ($students === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'students',
        'heading'     => $search !== '' ? 'No matching students' : 'No students yet',
        'message'     => $search !== ''
            ? 'No student matches "' . $search . '". Try a different search.'
            : 'Add students one at a time, or import a CSV of roll number, name and email.',
        'actionHref'  => $search !== '' ? null : route_to('students.new'),
        'actionLabel' => $search !== '' ? null : 'Add student',
    ]) ?>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Roll no.</th>
            <th scope="col">Student</th>
            <th scope="col">Class</th>
            <th scope="col"><span class="visually-hidden">Actions</span></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $student): ?>
            <tr>
              <td class="num nowrap"><?= esc($student['student_roll_no']) ?></td>
              <td>
                <div class="identity">
                  <span class="avatar">
                    <?= view('partials/avatar', [
                        'user' => ['name' => $student['student_name'], 'image' => $student['student_image']],
                    ]) ?>
                  </span>
                  <span class="identity__body">
                    <span class="identity__name"><?= esc($student['student_name']) ?></span>
                    <span class="identity__meta"><?= esc($student['student_email']) ?></span>
                  </span>
                </div>
              </td>
              <td>
                <?php if ($student['class_name']): ?>
                  <span class="badge badge--brand">
                    <?= esc($student['class_name']) ?><?= $student['section_name'] ? ' · ' . esc($student['section_name']) : '' ?>
                  </span>
                <?php else: ?>
                  <span class="subtle">Unassigned</span>
                <?php endif; ?>
              </td>
              <td>
                <div class="table__actions">
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('students.show', $student['student_id']) ?>"
                     aria-label="View <?= esc($student['student_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'eye']) ?>
                  </a>
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('students.edit', $student['student_id']) ?>"
                     aria-label="Edit <?= esc($student['student_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'edit']) ?>
                  </a>
                  <?= view('partials/delete_form', [
                      'action'  => route_to('students.delete', $student['student_id']),
                      'confirm' => 'Delete ' . $student['student_name'] . '? This cannot be undone.',
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

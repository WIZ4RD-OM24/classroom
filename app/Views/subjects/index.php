<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Subjects</h1>
    <p><?= esc((string) count($subjects)) ?> <?= count($subjects) === 1 ? 'subject' : 'subjects' ?></p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--primary" href="<?= route_to('subjects.new') ?>">
      <?= view('partials/icon', ['name' => 'plus']) ?> Add subject
    </a>
  </div>
</div>

<section class="card">
  <?php if ($subjects === []): ?>
    <?= view('partials/empty', [
        'icon'        => 'book',
        'heading'     => 'No subjects yet',
        'message'     => 'Add the subjects taught in each class, then assign a teacher to each one.',
        'actionHref'  => route_to('subjects.new'),
        'actionLabel' => 'Add subject',
    ]) ?>
  <?php else: ?>
    <div class="table-wrap">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Subject</th>
            <th scope="col">Class</th>
            <th scope="col">Teacher</th>
            <th scope="col"><span class="visually-hidden">Actions</span></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($subjects as $subject): ?>
            <tr>
              <td class="strong"><?= esc($subject['subject_name']) ?></td>
              <td>
                <?php if ($subject['class_name']): ?>
                  <span class="badge badge--brand">
                    <?= esc($subject['class_name']) ?><?= $subject['section_name'] ? ' · ' . esc($subject['section_name']) : '' ?>
                  </span>
                <?php else: ?>
                  <span class="subtle">All classes</span>
                <?php endif; ?>
              </td>
              <td class="muted"><?= esc($subject['teacher_name'] ?: 'Unassigned') ?></td>
              <td>
                <div class="table__actions">
                  <a class="btn btn--sm btn--ghost" href="<?= route_to('subjects.edit', $subject['subject_id']) ?>"
                     aria-label="Edit <?= esc($subject['subject_name'], 'attr') ?>">
                    <?= view('partials/icon', ['name' => 'edit']) ?>
                  </a>
                  <?= view('partials/delete_form', [
                      'action'  => route_to('subjects.delete', $subject['subject_id']),
                      'confirm' => 'Delete ' . $subject['subject_name'] . '?',
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

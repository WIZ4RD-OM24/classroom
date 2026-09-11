<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Import students</h1>
    <p>Add a whole class at once from a CSV file.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('students') ?>">Back to students</a>
  </div>
</div>

<div class="grid grid--split">
  <section class="card">
    <div class="card__head"><span class="card__title">Upload file</span></div>
    <div class="card__body">
      <form method="post" action="<?= route_to('students.import.run') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="field">
          <label for="file">CSV file</label>
          <input class="file-input" type="file" id="file" name="file" accept=".csv,text/csv" required>
          <span class="hint">Up to 2 MB. The first row is treated as a header and skipped.</span>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn--primary">
            <?= view('partials/icon', ['name' => 'upload']) ?> Import
          </button>
        </div>
      </form>
    </div>
  </section>

  <section class="card">
    <div class="card__head"><span class="card__title">Expected format</span></div>
    <div class="card__body">
      <p class="muted">Three columns, in this order:</p>
      <div class="table-wrap mt-1">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">Roll number</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
            </tr>
          </thead>
          <tbody>
            <tr><td>MCA001</td><td>Rahul Verma</td><td>rahul@example.com</td></tr>
            <tr><td>MCA002</td><td>Sneha Patil</td><td>sneha@example.com</td></tr>
          </tbody>
        </table>
      </div>
      <p class="muted mt-2">
        Rows whose roll number or email already exists in your school are skipped and
        reported back, rather than being imported twice. Every imported student is
        given the temporary password <code>student@123</code>.
      </p>
    </div>
  </section>
</div>

<?= $this->endSection() ?>

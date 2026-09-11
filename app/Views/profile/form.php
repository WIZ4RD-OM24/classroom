<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>

<div class="page-head">
  <div class="page-head__text">
    <h1>Edit profile</h1>
    <p>Change how your name and photo appear across the classroom.</p>
  </div>
  <div class="page-head__actions">
    <a class="btn btn--ghost" href="<?= route_to('profile') ?>">Back to profile</a>
  </div>
</div>

<section class="card">
  <div class="card__body">
    <form method="post" action="<?= route_to('profile') ?>" enctype="multipart/form-data">
      <?= csrf_field() ?>

      <div class="form-grid">
        <div class="field">
          <label for="name">Full name</label>
          <input class="input" type="text" id="name" name="name" required
                 value="<?= esc(field_value('name', $profile[$prefix . '_name'] ?? ''), 'attr') ?>">
        </div>

        <div class="field">
          <label for="mobile">Mobile number</label>
          <input class="input" type="tel" id="mobile" name="mobile"
                 value="<?= esc(field_value('mobile', $profile[$prefix . '_mobile'] ?? ''), 'attr') ?>">
        </div>

        <?php if ($prefix === 'admin'): ?>
          <div class="field">
            <label for="designation">Designation</label>
            <input class="input" type="text" id="designation" name="designation"
                   value="<?= esc(field_value('designation', $profile['admin_designation'] ?? ''), 'attr') ?>">
          </div>

          <div class="field">
            <label for="organisation">Organisation</label>
            <input class="input" type="text" id="organisation" name="organisation"
                   value="<?= esc(field_value('organisation', $profile['admin_organisation'] ?? ''), 'attr') ?>">
          </div>
        <?php endif; ?>

        <div class="field field--full">
          <label for="image">Profile photo</label>
          <input class="file-input" type="file" id="image" name="image" accept="image/*">
          <span class="hint">PNG, JPEG or WebP, up to 8 MB. Replacing it removes the old one.</span>
        </div>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn btn--primary">Save changes</button>
        <a class="btn btn--ghost" href="<?= route_to('profile') ?>">Cancel</a>
      </div>
    </form>
  </div>
</section>

<?= $this->endSection() ?>

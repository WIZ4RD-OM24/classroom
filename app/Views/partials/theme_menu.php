<?php

/**
 * Theme picker: surface mode on one axis, accent hue on the other.
 *
 * Entirely client-side — the choice is the reader's, not the account's, so it
 * lives in localStorage and never round-trips to the server. The markup is
 * rendered for everyone; assets/js/app.js marks the active options once it
 * knows what is stored.
 */
$accents = [
    'indigo'  => 'Indigo',
    'violet'  => 'Violet',
    'teal'    => 'Teal',
    'emerald' => 'Emerald',
    'amber'   => 'Amber',
    'rose'    => 'Rose',
    'slate'   => 'Slate',
];
?>
<div class="menu">
  <button type="button" class="btn btn--icon" data-menu-toggle aria-expanded="false"
          aria-haspopup="true" aria-label="Appearance">
    <?= view('partials/icon', ['name' => 'palette']) ?>
  </button>

  <div class="menu__panel theme-menu" hidden>
    <div class="theme-menu__label" id="theme-mode-label">Appearance</div>
    <div class="segmented" role="group" aria-labelledby="theme-mode-label">
      <button type="button" data-set-mode="light" aria-pressed="false">
        <?= view('partials/icon', ['name' => 'sun']) ?> Light
      </button>
      <button type="button" data-set-mode="dark" aria-pressed="false">
        <?= view('partials/icon', ['name' => 'moon']) ?> Dark
      </button>
      <button type="button" data-set-mode="system" aria-pressed="false">
        <?= view('partials/icon', ['name' => 'monitor']) ?> Auto
      </button>
    </div>

    <div class="theme-menu__label" id="theme-accent-label" style="margin-top:14px">Accent</div>
    <div class="swatches" role="group" aria-labelledby="theme-accent-label">
      <?php foreach ($accents as $key => $name): ?>
        <button type="button"
                class="swatch swatch--<?= esc($key, 'attr') ?>"
                data-set-accent="<?= esc($key, 'attr') ?>"
                aria-pressed="false"
                title="<?= esc($name, 'attr') ?>">
          <span class="visually-hidden"><?= esc($name) ?></span>
        </button>
      <?php endforeach; ?>
    </div>
  </div>
</div>

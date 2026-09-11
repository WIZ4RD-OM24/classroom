/**
 * Classroom — progressive enhancement.
 *
 * Replaces jQuery, Bootstrap's JS bundle, AdminLTE's JS and several plugins.
 * Everything here is optional: the pages work without it.
 */
(function () {
  'use strict';

  /* ---------------------------------------------------------------------
   * Theme
   *
   * Two independent axes: the surface mode (light / dark / follow the OS)
   * and the accent hue. Both are the reader's preference rather than the
   * account's, so they live in localStorage and never reach the server.
   *
   * The stored values are applied by an inline script in the <head>, before
   * first paint, so the page never flashes the wrong theme. Everything here
   * only handles changes made after load.
   * ------------------------------------------------------------------- */
  var MODE_KEY = 'classroom.theme';
  var ACCENT_KEY = 'classroom.accent';
  var DEFAULT_ACCENT = 'indigo';
  var ACCENTS = ['indigo', 'violet', 'teal', 'emerald', 'amber', 'rose', 'slate'];

  var darkQuery = window.matchMedia('(prefers-color-scheme: dark)');

  function read(key, fallback) {
    try {
      return localStorage.getItem(key) || fallback;
    } catch (e) {
      // Private browsing, or site data blocked.
      return fallback;
    }
  }

  function write(key, value) {
    try {
      localStorage.setItem(key, value);
    } catch (e) {
      // Not persisting is survivable; the choice still applies to this page.
    }
  }

  /** 'light' | 'dark' | 'system' */
  function storedMode() {
    var mode = read(MODE_KEY, 'system');
    return mode === 'light' || mode === 'dark' ? mode : 'system';
  }

  function resolveMode(mode) {
    return mode === 'system' ? (darkQuery.matches ? 'dark' : 'light') : mode;
  }

  function applyMode(mode) {
    document.documentElement.setAttribute('data-theme', resolveMode(mode));

    document.querySelectorAll('[data-set-mode]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', String(btn.getAttribute('data-set-mode') === mode));
    });
  }

  function applyAccent(accent) {
    if (ACCENTS.indexOf(accent) === -1) {
      accent = DEFAULT_ACCENT;
    }
    document.documentElement.setAttribute('data-accent', accent);

    document.querySelectorAll('[data-set-accent]').forEach(function (btn) {
      btn.setAttribute('aria-pressed', String(btn.getAttribute('data-set-accent') === accent));
    });
  }

  document.addEventListener('click', function (event) {
    var modeBtn = event.target.closest('[data-set-mode]');
    if (modeBtn) {
      var mode = modeBtn.getAttribute('data-set-mode');
      write(MODE_KEY, mode);
      applyMode(mode);
      return;
    }

    var accentBtn = event.target.closest('[data-set-accent]');
    if (accentBtn) {
      var accent = accentBtn.getAttribute('data-set-accent');
      write(ACCENT_KEY, accent);
      applyAccent(accent);
    }
  });

  // While following the OS, track it live rather than only at page load.
  var onSystemChange = function () {
    if (storedMode() === 'system') {
      applyMode('system');
    }
  };

  if (typeof darkQuery.addEventListener === 'function') {
    darkQuery.addEventListener('change', onSystemChange);
  } else if (typeof darkQuery.addListener === 'function') {
    darkQuery.addListener(onSystemChange); // Safari < 14
  }

  applyMode(storedMode());
  applyAccent(read(ACCENT_KEY, DEFAULT_ACCENT));

  /* ---------------------------------------------------------------------
   * Mobile navigation
   * ------------------------------------------------------------------- */
  function setNav(open) {
    document.body.setAttribute('data-nav', open ? 'open' : 'closed');
    var toggle = document.querySelector('[data-nav-toggle]');
    if (toggle) {
      toggle.setAttribute('aria-expanded', String(open));
    }
  }

  document.addEventListener('click', function (event) {
    if (event.target.closest('[data-nav-toggle]')) {
      setNav(document.body.getAttribute('data-nav') !== 'open');
      return;
    }
    if (event.target.closest('[data-nav-close]')) {
      setNav(false);
    }
  });

  /* ---------------------------------------------------------------------
   * Dropdown menus
   * ------------------------------------------------------------------- */
  function closeMenus(except) {
    document.querySelectorAll('.menu__panel').forEach(function (panel) {
      if (panel !== except) {
        panel.hidden = true;
        var trigger = panel.parentElement.querySelector('[data-menu-toggle]');
        if (trigger) {
          trigger.setAttribute('aria-expanded', 'false');
        }
      }
    });
  }

  document.addEventListener('click', function (event) {
    var trigger = event.target.closest('[data-menu-toggle]');

    if (!trigger) {
      if (!event.target.closest('.menu__panel')) {
        closeMenus(null);
      }
      return;
    }

    var panel = trigger.parentElement.querySelector('.menu__panel');
    if (!panel) {
      return;
    }

    var willOpen = panel.hidden;
    closeMenus(panel);
    panel.hidden = !willOpen;
    trigger.setAttribute('aria-expanded', String(willOpen));
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape') {
      closeMenus(null);
      setNav(false);
    }
  });

  /* ---------------------------------------------------------------------
   * Confirmation for destructive forms.
   *
   * Deletion used to be a plain GET link, so a crawler or a prefetch could
   * trigger it. Deletes are now POST forms; this only adds the prompt.
   * ------------------------------------------------------------------- */
  document.addEventListener('submit', function (event) {
    var form = event.target;
    var message = form.getAttribute('data-confirm');

    if (message && !window.confirm(message)) {
      event.preventDefault();
      return;
    }

    // Guard against double submission on slow connections.
    var submit = form.querySelector('button[type="submit"], input[type="submit"]');
    if (submit && !form.hasAttribute('data-no-lock')) {
      window.setTimeout(function () {
        submit.disabled = true;
      }, 0);
    }
  });

  /* ---------------------------------------------------------------------
   * Table search: filters rows in place, no page reload.
   * ------------------------------------------------------------------- */
  document.querySelectorAll('[data-filter-table]').forEach(function (input) {
    var table = document.querySelector(input.getAttribute('data-filter-table'));
    if (!table) {
      return;
    }
    var status = document.querySelector('[data-filter-status]');

    input.addEventListener('input', function () {
      var term = input.value.trim().toLowerCase();
      var shown = 0;

      table.querySelectorAll('tbody tr').forEach(function (row) {
        var match = term === '' || row.textContent.toLowerCase().indexOf(term) !== -1;
        row.hidden = !match;
        if (match) {
          shown++;
        }
      });

      if (status) {
        status.textContent = term === '' ? '' : shown + ' matching';
      }
    });
  });
})();

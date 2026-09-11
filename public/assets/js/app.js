/**
 * Classroom — progressive enhancement.
 *
 * Replaces jQuery, Bootstrap's JS bundle, AdminLTE's JS and several plugins.
 * Everything here is optional: the pages work without it.
 */
(function () {
  'use strict';

  /* ---------------------------------------------------------------------
   * Theme: remembers the reader's choice, otherwise follows the OS.
   * The initial value is applied by an inline script in the <head> so the
   * page never flashes the wrong theme.
   * ------------------------------------------------------------------- */
  var THEME_KEY = 'classroom.theme';

  function currentTheme() {
    return document.documentElement.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
  }

  function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    try {
      localStorage.setItem(THEME_KEY, theme);
    } catch (e) {
      /* Private browsing, or site data blocked — the theme just won't persist. */
    }
    document.querySelectorAll('[data-theme-toggle]').forEach(function (btn) {
      btn.setAttribute('aria-label', theme === 'dark' ? 'Switch to light theme' : 'Switch to dark theme');
      btn.setAttribute('aria-pressed', String(theme === 'dark'));
    });
  }

  document.addEventListener('click', function (event) {
    var toggle = event.target.closest('[data-theme-toggle]');
    if (toggle) {
      applyTheme(currentTheme() === 'dark' ? 'light' : 'dark');
    }
  });

  applyTheme(currentTheme());

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

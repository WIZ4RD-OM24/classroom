<?php

/**
 * Applies the stored theme before first paint.
 *
 * This has to run synchronously in the <head>, ahead of the stylesheet
 * painting anything: deferring it to assets/js/app.js would show a flash of
 * the default theme on every page load. Kept in one partial so the app and
 * auth layouts cannot drift apart.
 */
?>
<script>
  (function () {
    var root = document.documentElement;
    var mode = 'system';
    var accent = 'indigo';

    try {
      mode = localStorage.getItem('classroom.theme') || 'system';
      accent = localStorage.getItem('classroom.accent') || 'indigo';
    } catch (e) {
      // Site data blocked; fall through to the defaults.
    }

    if (mode !== 'light' && mode !== 'dark') {
      mode = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    root.setAttribute('data-theme', mode);
    root.setAttribute('data-accent', /^[a-z]+$/.test(accent) ? accent : 'indigo');
  })();
</script>

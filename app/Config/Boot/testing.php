<?php

/*
 |--------------------------------------------------------------------------
 | ERROR DISPLAY
 |--------------------------------------------------------------------------
 | In development, we want to show as many errors as possible to help
 | make sure they don't make it to production. And save us hours of
 | painful debugging.
 */
/*
 | Everything except deprecations. CodeIgniter 4.1.9 predates PHP 8.2 and its
 | own test harness (system/Test/DOMParser.php) calls mb_convert_encoding()
 | with 'HTML-ENTITIES', which 8.2 deprecates; CodeIgniter's error handler
 | promotes that notice to an ErrorException and fails every test that
 | receives an HTML 200 response. Excluding E_DEPRECATED keeps the suite
 | reporting application failures rather than framework ones. See "Known gaps"
 | in the README for the underlying framework upgrade.
 */
error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
ini_set('display_errors', '1');

/*
 |--------------------------------------------------------------------------
 | DEBUG BACKTRACES
 |--------------------------------------------------------------------------
 | If true, this constant will tell the error screens to display debug
 | backtraces along with the other error information. If you would
 | prefer to not see this, set this value to false.
 */
defined('SHOW_DEBUG_BACKTRACE') || define('SHOW_DEBUG_BACKTRACE', true);

/*
 |--------------------------------------------------------------------------
 | DEBUG MODE
 |--------------------------------------------------------------------------
 | Debug mode is an experimental flag that can allow changes throughout
 | the system. It's not widely used currently, and may not survive
 | release of the framework.
 */
defined('CI_DEBUG') || define('CI_DEBUG', true);

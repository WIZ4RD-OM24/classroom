<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class App extends BaseConfig
{
    /**
     * Sentinel meaning "work it out from the request".
     */
    public const BASE_URL_AUTO = 'auto';

    /**
     * --------------------------------------------------------------------------
     * Base Site URL
     * --------------------------------------------------------------------------
     *
     * URL to your CodeIgniter root, WITH a trailing slash:
     *
     *    http://example.com/
     *
     * Every asset, link and redirect the application emits is built from this,
     * so a value that does not match how the site is actually reached leaves
     * the page loading its stylesheet and scripts from somewhere that does not
     * exist — the site renders as unstyled HTML. This used to be hard-coded to
     * one developer's path, which broke the site for everybody else.
     *
     * Left as 'auto' it is derived from the incoming request, so the app works
     * on whatever host and port it is served from with no configuration.
     * Set `app.baseURL` in `.env` to pin it explicitly; that always wins.
     *
     * @var string
     */
    public $baseURL = self::BASE_URL_AUTO;

    /**
     * --------------------------------------------------------------------------
     * Index File
     * --------------------------------------------------------------------------
     *
     * Typically this will be your index.php file, unless you've renamed it to
     * something else. If you are using mod_rewrite to remove the page set this
     * variable so that it is blank.
     *
     * @var string
     */
    public $indexPage = 'index.php';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     *
     * This item determines which getServer global should be used to retrieve the
     * URI string.  The default setting of 'REQUEST_URI' works for most servers.
     * If your links do not seem to work, try one of the other delicious flavors:
     *
     * 'REQUEST_URI'    Uses $_SERVER['REQUEST_URI']
     * 'QUERY_STRING'   Uses $_SERVER['QUERY_STRING']
     * 'PATH_INFO'      Uses $_SERVER['PATH_INFO']
     *
     * WARNING: If you set this to 'PATH_INFO', URIs will always be URL-decoded!
     *
     * @var string
     */
    public $uriProtocol = 'REQUEST_URI';

    /**
     * --------------------------------------------------------------------------
     * Default Locale
     * --------------------------------------------------------------------------
     *
     * The Locale roughly represents the language and location that your visitor
     * is viewing the site from. It affects the language strings and other
     * strings (like currency markers, numbers, etc), that your program
     * should run under for this request.
     *
     * @var string
     */
    public $defaultLocale = 'en';

    /**
     * --------------------------------------------------------------------------
     * Negotiate Locale
     * --------------------------------------------------------------------------
     *
     * If true, the current Request object will automatically determine the
     * language to use based on the value of the Accept-Language header.
     *
     * If false, no automatic detection will be performed.
     *
     * @var bool
     */
    public $negotiateLocale = false;

    /**
     * --------------------------------------------------------------------------
     * Supported Locales
     * --------------------------------------------------------------------------
     *
     * If $negotiateLocale is true, this array lists the locales supported
     * by the application in descending order of priority. If no match is
     * found, the first locale will be used.
     *
     * @var string[]
     */
    public $supportedLocales = ['en'];

    /**
     * --------------------------------------------------------------------------
     * Application Timezone
     * --------------------------------------------------------------------------
     *
     * The default timezone that will be used in your application to display
     * dates with the date helper, and can be retrieved through app_timezone()
     *
     * @var string
     */
    public $appTimezone = 'Asia/Kolkata';

    /**
     * --------------------------------------------------------------------------
     * Default Character Set
     * --------------------------------------------------------------------------
     *
     * This determines which character set is used by default in various methods
     * that require a character set to be provided.
     *
     * @see http://php.net/htmlspecialchars for a list of supported charsets.
     *
     * @var string
     */
    public $charset = 'UTF-8';

    /**
     * --------------------------------------------------------------------------
     * URI PROTOCOL
     * --------------------------------------------------------------------------
     *
     * If true, this will force every request made to this application to be
     * made via a secure connection (HTTPS). If the incoming request is not
     * secure, the user will be redirected to a secure version of the page
     * and the HTTP Strict Transport Security header will be set.
     *
     * @var bool
     */
    public $forceGlobalSecureRequests = false;

    /**
     * --------------------------------------------------------------------------
     * Session Driver
     * --------------------------------------------------------------------------
     *
     * The session storage driver to use:
     * - `CodeIgniter\Session\Handlers\FileHandler`
     * - `CodeIgniter\Session\Handlers\DatabaseHandler`
     * - `CodeIgniter\Session\Handlers\MemcachedHandler`
     * - `CodeIgniter\Session\Handlers\RedisHandler`
     *
     * @var string
     */
    public $sessionDriver = 'CodeIgniter\Session\Handlers\FileHandler';

    /**
     * --------------------------------------------------------------------------
     * Session Cookie Name
     * --------------------------------------------------------------------------
     *
     * The session cookie name, must contain only [0-9a-z_-] characters
     *
     * @var string
     */
    public $sessionCookieName = 'ci_session';

    /**
     * --------------------------------------------------------------------------
     * Session Expiration
     * --------------------------------------------------------------------------
     *
     * The number of SECONDS you want the session to last.
     * Setting to 0 (zero) means expire when the browser is closed.
     *
     * @var int
     */
    public $sessionExpiration = 7200;

    /**
     * --------------------------------------------------------------------------
     * Session Save Path
     * --------------------------------------------------------------------------
     *
     * The location to save sessions to and is driver dependent.
     *
     * For the 'files' driver, it's a path to a writable directory.
     * WARNING: Only absolute paths are supported!
     *
     * For the 'database' driver, it's a table name.
     * Please read up the manual for the format with other session drivers.
     *
     * IMPORTANT: You are REQUIRED to set a valid save path!
     *
     * @var string
     */
    public $sessionSavePath = WRITEPATH . 'session';

    /**
     * --------------------------------------------------------------------------
     * Session Match IP
     * --------------------------------------------------------------------------
     *
     * Whether to match the user's IP address when reading the session data.
     *
     * WARNING: If you're using the database driver, don't forget to update
     *          your session table's PRIMARY KEY when changing this setting.
     *
     * @var bool
     */
    public $sessionMatchIP = false;

    /**
     * --------------------------------------------------------------------------
     * Session Time to Update
     * --------------------------------------------------------------------------
     *
     * How many seconds between CI regenerating the session ID.
     *
     * @var int
     */
    public $sessionTimeToUpdate = 300;

    /**
     * --------------------------------------------------------------------------
     * Session Regenerate Destroy
     * --------------------------------------------------------------------------
     *
     * Whether to destroy session data associated with the old session ID
     * when auto-regenerating the session ID. When set to FALSE, the data
     * will be later deleted by the garbage collector.
     *
     * @var bool
     */
    public $sessionRegenerateDestroy = false;

    /**
     * --------------------------------------------------------------------------
     * Cookie Prefix
     * --------------------------------------------------------------------------
     *
     * Set a cookie name prefix if you need to avoid collisions.
     *
     * @var string
     *
     * @deprecated use Config\Cookie::$prefix property instead.
     */
    public $cookiePrefix = '';

    /**
     * --------------------------------------------------------------------------
     * Cookie Domain
     * --------------------------------------------------------------------------
     *
     * Set to `.your-domain.com` for site-wide cookies.
     *
     * @var string
     *
     * @deprecated use Config\Cookie::$domain property instead.
     */
    public $cookieDomain = '';

    /**
     * --------------------------------------------------------------------------
     * Cookie Path
     * --------------------------------------------------------------------------
     *
     * Typically will be a forward slash.
     *
     * @var string
     *
     * @deprecated use Config\Cookie::$path property instead.
     */
    public $cookiePath = '/';

    /**
     * --------------------------------------------------------------------------
     * Cookie Secure
     * --------------------------------------------------------------------------
     *
     * Cookie will only be set if a secure HTTPS connection exists.
     *
     * @var bool
     *
     * @deprecated use Config\Cookie::$secure property instead.
     */
    public $cookieSecure = false;

    /**
     * --------------------------------------------------------------------------
     * Cookie HttpOnly
     * --------------------------------------------------------------------------
     *
     * Cookie will only be accessible via HTTP(S) (no JavaScript).
     *
     * @var bool
     *
     * @deprecated use Config\Cookie::$httponly property instead.
     */
    public $cookieHTTPOnly = true;

    /**
     * --------------------------------------------------------------------------
     * Cookie SameSite
     * --------------------------------------------------------------------------
     *
     * Configure cookie SameSite setting. Allowed values are:
     * - None
     * - Lax
     * - Strict
     * - ''
     *
     * Alternatively, you can use the constant names:
     * - `Cookie::SAMESITE_NONE`
     * - `Cookie::SAMESITE_LAX`
     * - `Cookie::SAMESITE_STRICT`
     *
     * Defaults to `Lax` for compatibility with modern browsers. Setting `''`
     * (empty string) means default SameSite attribute set by browsers (`Lax`)
     * will be set on cookies. If set to `None`, `$cookieSecure` must also be set.
     *
     * @var string
     *
     * @deprecated use Config\Cookie::$samesite property instead.
     */
    public $cookieSameSite = 'Lax';

    /**
     * --------------------------------------------------------------------------
     * Reverse Proxy IPs
     * --------------------------------------------------------------------------
     *
     * If your server is behind a reverse proxy, you must whitelist the proxy
     * IP addresses from which CodeIgniter should trust headers such as
     * HTTP_X_FORWARDED_FOR and HTTP_CLIENT_IP in order to properly identify
     * the visitor's IP address.
     *
     * You can use both an array or a comma-separated list of proxy addresses,
     * as well as specifying whole subnets. Here are a few examples:
     *
     * Comma-separated:	'10.0.1.200,192.168.5.0/24'
     * Array: ['10.0.1.200', '192.168.5.0/24']
     *
     * @var string|string[]
     */
    public $proxyIPs = '';

    /**
     * --------------------------------------------------------------------------
     * CSRF Token Name
     * --------------------------------------------------------------------------
     *
     * The token name.
     *
     * @deprecated Use `Config\Security` $tokenName property instead of using this property.
     *
     * @var string
     */
    public $CSRFTokenName = 'csrf_test_name';

    /**
     * --------------------------------------------------------------------------
     * CSRF Header Name
     * --------------------------------------------------------------------------
     *
     * The header name.
     *
     * @deprecated Use `Config\Security` $headerName property instead of using this property.
     *
     * @var string
     */
    public $CSRFHeaderName = 'X-CSRF-TOKEN';

    /**
     * --------------------------------------------------------------------------
     * CSRF Cookie Name
     * --------------------------------------------------------------------------
     *
     * The cookie name.
     *
     * @deprecated Use `Config\Security` $cookieName property instead of using this property.
     *
     * @var string
     */
    public $CSRFCookieName = 'csrf_cookie_name';

    /**
     * --------------------------------------------------------------------------
     * CSRF Expire
     * --------------------------------------------------------------------------
     *
     * The number in seconds the token should expire.
     *
     * @deprecated Use `Config\Security` $expire property instead of using this property.
     *
     * @var int
     */
    public $CSRFExpire = 7200;

    /**
     * --------------------------------------------------------------------------
     * CSRF Regenerate
     * --------------------------------------------------------------------------
     *
     * Regenerate token on every submission?
     *
     * @deprecated Use `Config\Security` $regenerate property instead of using this property.
     *
     * @var bool
     */
    public $CSRFRegenerate = true;

    /**
     * --------------------------------------------------------------------------
     * CSRF Redirect
     * --------------------------------------------------------------------------
     *
     * Redirect to previous page with error on failure?
     *
     * @deprecated Use `Config\Security` $redirect property instead of using this property.
     *
     * @var bool
     */
    public $CSRFRedirect = true;

    /**
     * --------------------------------------------------------------------------
     * CSRF SameSite
     * --------------------------------------------------------------------------
     *
     * Setting for CSRF SameSite cookie token. Allowed values are:
     * - None
     * - Lax
     * - Strict
     * - ''
     *
     * Defaults to `Lax` as recommended in this link:
     *
     * @see https://portswigger.net/web-security/csrf/samesite-cookies
     * @deprecated Use `Config\Security` $samesite property instead of using this property.
     *
     * @var string
     */
    public $CSRFSameSite = 'Lax';

    /**
     * --------------------------------------------------------------------------
     * Content Security Policy
     * --------------------------------------------------------------------------
     *
     * Enables the Response's Content Secure Policy to restrict the sources that
     * can be used for images, scripts, CSS files, audio, video, etc. If enabled,
     * the Response object will populate default values for the policy from the
     * `ContentSecurityPolicy.php` file. Controllers can always add to those
     * restrictions at run time.
     *
     * For a better understanding of CSP, see these documents:
     *
     * @see http://www.html5rocks.com/en/tutorials/security/content-security-policy/
     * @see http://www.w3.org/TR/CSP/
     *
     * @var bool
     */
    public $CSPEnabled = false;

    public function __construct()
    {
        // Runs first, so an explicit app.baseURL in .env or the environment
        // overrides the property default and the check below leaves it alone.
        parent::__construct();

        if ($this->baseURL === self::BASE_URL_AUTO) {
            // Commands, migrations and tests have no request to infer from.
            $this->baseURL = (PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg')
                ? 'http://localhost/'
                : $this->detectBaseURL();
        }

        // CodeIgniter builds every URL as rtrim($baseURL, '/') . '/', but a
        // missing slash still trips people up when reading the value back.
        $this->baseURL = rtrim($this->baseURL, '/') . '/';
    }

    /**
     * Work out the base URL from the current request.
     *
     * The Host header is attacker-controlled, and whatever lands here ends up
     * in every generated link and asset URL. Guessing is therefore allowed
     * only when the request came in on a loopback or private address, which is
     * a developer running the site locally; a real hostname has to be stated
     * in `.env`, and this says so instead of silently emitting URLs built from
     * a forged header.
     *
     * This deliberately does not key off ENVIRONMENT: that constant is defined
     * later in the boot sequence than the first config('App') call, and it
     * defaults to 'production' whenever CI_ENVIRONMENT is unset — which would
     * make a fresh checkout with no .env fail outright rather than just work.
     */
    private function detectBaseURL(): string
    {
        $host = (string) ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? '');

        // Strip anything that cannot appear in a host:port pair, so a hostile
        // header cannot smuggle in a path, a second URL, or a CR/LF.
        $host = preg_replace('/[^A-Za-z0-9.\-:\[\]]/', '', $host) ?? '';

        if (! self::isLocalHost($host)) {
            throw new \RuntimeException(sprintf(
                'app.baseURL is not configured, and it will only be guessed for local requests '
                . '(this one arrived for host "%s"). Set it in your .env file, for example: '
                . "app.baseURL = 'https://classroom.example.edu/'",
                $host === '' ? 'unknown' : $host
            ));
        }

        $https = (! empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off')
            || (int) ($_SERVER['SERVER_PORT'] ?? 0) === 443;

        // The directory index.php is served from, so this works whether the
        // app sits at the document root or under a folder such as
        // /classroom/public.
        $path = str_replace('\\', '/', dirname((string) ($_SERVER['SCRIPT_NAME'] ?? '/index.php')));
        $path = ($path === '/' || $path === '.' || $path === '') ? '' : '/' . trim($path, '/');

        return ($https ? 'https' : 'http') . '://' . $host . $path . '/';
    }

    /**
     * Is this host the machine the developer is sitting at?
     *
     * Proxy headers are ignored on purpose: X-Forwarded-Host would let a
     * remote caller pretend to be local.
     */
    private static function isLocalHost(string $host): bool
    {
        if ($host === '') {
            return false;
        }

        // Drop the port, and unwrap a bracketed IPv6 literal.
        $name = preg_replace('/:\d+$/', '', $host) ?? $host;
        $name = trim($name, '[]');
        $name = strtolower($name);

        if (in_array($name, ['localhost', '127.0.0.1', '::1', 'host.docker.internal'], true)) {
            return true;
        }

        // Hostnames reserved for local development.
        if (preg_match('/\.(test|localhost|local|internal)$/', $name) === 1) {
            return true;
        }

        // RFC 1918 / loopback / link-local addresses.
        if (filter_var($name, FILTER_VALIDATE_IP) !== false) {
            return filter_var(
                $name,
                FILTER_VALIDATE_IP,
                FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
            ) === false;
        }

        return false;
    }
}

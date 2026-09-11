<?php

namespace Config;

use App\Filters\AuthGuard;
use App\Filters\GuestOnly;
use App\Filters\RoleGuard;
use CodeIgniter\Config\BaseConfig;
use CodeIgniter\Filters\CSRF;
use CodeIgniter\Filters\DebugToolbar;
use CodeIgniter\Filters\Honeypot;
use CodeIgniter\Filters\InvalidChars;
use CodeIgniter\Filters\SecureHeaders;

class Filters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'csrf'          => CSRF::class,
        'toolbar'       => DebugToolbar::class,
        'honeypot'      => Honeypot::class,
        'invalidchars'  => InvalidChars::class,
        'secureheaders' => SecureHeaders::class,
        'auth'          => AuthGuard::class,
        'role'          => RoleGuard::class,
        'guest'         => GuestOnly::class,
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
            'invalidchars',
        ],
        'after' => [
            'toolbar',
            'secureheaders',
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * CSRF protection is enforced on every state-changing request. It was
     * commented out entirely before, which left every form forgeable.
     *
     * @var array
     */
    public $methods = [
        'post'   => ['csrf'],
        'put'    => ['csrf'],
        'patch'  => ['csrf'],
        'delete' => ['csrf'],
    ];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * @var array
     */
    public $filters = [];
}

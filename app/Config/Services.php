<?php

namespace Config;

use App\Libraries\Auth;
use CodeIgniter\Config\BaseService;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 */
class Services extends BaseService
{
    /**
     * Authentication and the current identity.
     *
     * Usage: service('auth')->user(), service('auth')->tenantId(), ...
     */
    public static function auth($getShared = true): Auth
    {
        if ($getShared) {
            return static::getSharedInstance('auth');
        }

        return new Auth(static::session());
    }
}

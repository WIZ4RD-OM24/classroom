<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Restricts a route to specific roles, e.g. ['filter' => 'role:admin,teacher'].
 *
 * Without this, a signed-in student could reach every administrative route
 * simply by typing the URL.
 */
class RoleGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $auth = service('auth');

        if (! $auth->check()) {
            return redirect()->to(route_to('login'));
        }

        $allowed = $arguments ?? [];

        if ($allowed !== [] && ! $auth->is(...$allowed)) {
            session()->setFlashdata('error', 'You do not have permission to open that page.');

            return redirect()->to(base_url('/'));
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

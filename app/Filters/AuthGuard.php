<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Requires a signed-in user.
 *
 * Applied to the whole authenticated route group rather than to a handful of
 * listing routes as before, when every create/update/delete route was reachable
 * without a session.
 */
class AuthGuard implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (service('auth')->check()) {
            return null;
        }

        // Remember where the user was heading so login can return them there.
        if ($request->getMethod() === 'get') {
            session()->setFlashdata('redirect_url', current_url());
        }

        session()->setFlashdata('error', 'Please sign in to continue.');

        return redirect()->to(route_to('login'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

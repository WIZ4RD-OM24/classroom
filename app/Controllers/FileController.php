<?php

namespace App\Controllers;

use App\Libraries\FileStore;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Streams uploaded files to members of the owning organisation.
 *
 * Uploads are stored outside the document root, so this is the only way to
 * reach them, and the path is always rebuilt from the session's organisation
 * id rather than from anything in the URL.
 */
class FileController extends BaseController
{
    public function show(string $category = '', string $name = '')
    {
        $path = (new FileStore())->path($category, $name, $this->tenantId());

        if ($path === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->response
            // Never let the browser second-guess the type of a user upload.
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setHeader('Content-Security-Policy', "default-src 'none'; sandbox")
            ->download($path, null)
            ->setFileName($name);
    }
}

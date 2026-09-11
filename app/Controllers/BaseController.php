<?php

namespace App\Controllers;

use App\Libraries\Auth;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Shared behaviour for every controller.
 */
class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * @var array
     */
    protected $helpers = ['form', 'url', 'view'];

    protected Auth $auth;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->auth = service('auth');
    }

    /**
     * The organisation the current request is scoped to.
     *
     * Every query in the application is filtered by this. Previously the
     * organisation id was read straight out of `$_SESSION['admin']['admin_id']`,
     * which raised an undefined-index error for teacher and student sessions.
     */
    protected function tenantId(): int
    {
        $id = $this->auth->tenantId();

        if ($id === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $id;
    }

    /**
     * Fetch a record owned by the current organisation, or 404.
     *
     * Returning 404 rather than 403 avoids confirming that a record with that
     * id exists in another organisation.
     */
    protected function findOwnedOr404(\App\Models\BaseModel $model, int $id): array
    {
        $row = $model->findOwned($id, $this->tenantId());

        if ($row === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $row;
    }

    /**
     * Accept a submitted foreign key only if it points at a record this
     * organisation owns; otherwise treat it as absent.
     *
     * Populating a <select> from a scoped query is not by itself a control —
     * the value still arrives from the client and can be edited freely — so
     * every relation id is re-checked here before it reaches the database.
     *
     * @param mixed $id
     */
    protected function ownedIdOrNull(\App\Models\BaseModel $model, $id): ?int
    {
        if ($id === null || $id === '' || ! ctype_digit((string) $id)) {
            return null;
        }

        return $model->findOwned((int) $id, $this->tenantId()) === null ? null : (int) $id;
    }

    /**
     * Redirect back to the form with the submitted values and the errors.
     */
    protected function failValidation(\App\Models\BaseModel $model)
    {
        return redirect()->back()
            ->withInput()
            ->with('errors', $model->errors())
            ->with('error', 'Please correct the highlighted fields.');
    }
}

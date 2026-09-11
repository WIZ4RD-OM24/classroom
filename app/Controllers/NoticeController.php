<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Libraries\FileStore;
use App\Models\ClassModel;
use App\Models\NoticeModel;

class NoticeController extends BaseController
{
    private NoticeModel $notices;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->notices = new NoticeModel();
    }

    public function index()
    {
        $isStudent = $this->auth->is(Auth::ROLE_STUDENT);

        return view('notices/index', [
            'title'   => 'Notices',
            'active'  => 'notices',
            'notices' => $this->notices->withClass(
                $this->tenantId(),
                $this->auth->classId(),
                $isStudent
            ),
        ]);
    }

    public function create()
    {
        return view('notices/form', [
            'title'   => 'Post notice',
            'active'  => 'notices',
            'classes' => (new ClassModel())->forTenant($this->tenantId())->orderBy('class_name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $tenantId = $this->tenantId();
        $fileName = null;

        $upload = $this->request->getFile('notice_file');

        if ($upload !== null && $upload->isValid()) {
            try {
                $fileName = (new FileStore())->store($upload, 'notices', $tenantId);
            } catch (\RuntimeException $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        $data = [
            'notice_title'   => $this->request->getPost('notice_title'),
            'notice_content' => $this->request->getPost('notice_content'),
            'notice_file'    => $fileName,
            // An empty class means the notice goes to the whole organisation.
            'class_id' => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
            'admin_id' => $tenantId,
        ];

        if ($this->auth->is(Auth::ROLE_TEACHER)) {
            $data['teacher_id'] = $this->auth->id();
        }

        if (! $this->notices->insert($data)) {
            (new FileStore())->delete($fileName, 'notices', $tenantId);

            return $this->failValidation($this->notices);
        }

        return redirect()->to(route_to('notices'))->with('success', 'Notice posted.');
    }

    public function delete(int $id)
    {
        $tenantId = $this->tenantId();
        $notice   = $this->notices->findOwned($id, $tenantId);

        if ($notice === null) {
            return redirect()->to(route_to('notices'))->with('error', 'That notice could not be found.');
        }

        (new FileStore())->delete($notice['notice_file'], 'notices', $tenantId);
        $this->notices->delete($id);

        return redirect()->to(route_to('notices'))->with('success', 'Notice deleted.');
    }
}

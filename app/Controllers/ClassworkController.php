<?php

namespace App\Controllers;

use App\Libraries\Auth;
use App\Libraries\FileStore;
use App\Models\ClassModel;
use App\Models\ClassworkModel;
use App\Models\SubjectModel;

class ClassworkController extends BaseController
{
    private ClassworkModel $classworks;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->classworks = new ClassworkModel();
    }

    public function index()
    {
        $isStudent = $this->auth->is(Auth::ROLE_STUDENT);

        return view('classworks/index', [
            'title'      => 'Classwork',
            'active'     => 'classworks',
            'classworks' => $this->classworks->withRelations(
                $this->tenantId(),
                $this->auth->classId(),
                $isStudent
            ),
        ]);
    }

    public function create()
    {
        $tenantId = $this->tenantId();

        return view('classworks/form', [
            'title'    => 'Add classwork',
            'active'   => 'classworks',
            'classes'  => (new ClassModel())->forTenant($tenantId)->orderBy('class_name', 'ASC')->findAll(),
            'subjects' => (new SubjectModel())->forTenant($tenantId)->orderBy('subject_name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $tenantId = $this->tenantId();
        $fileName = null;

        $upload = $this->request->getFile('classwork_file');

        if ($upload !== null && $upload->isValid()) {
            try {
                $fileName = (new FileStore())->store($upload, 'classworks', $tenantId);
            } catch (\RuntimeException $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        $data = [
            'classwork_title' => $this->request->getPost('classwork_title'),
            'classwork_file'  => $fileName,
            // The old form posted a class *name* into a column expecting an id,
            // then looked the class up again and dereferenced the result without
            // checking it — a fatal error whenever the name did not match.
            'class_id'   => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
            'subject_id' => $this->ownedIdOrNull(new SubjectModel(), $this->request->getPost('subject_id')),
            'admin_id'   => $tenantId,
        ];

        if ($this->auth->is(Auth::ROLE_TEACHER)) {
            $data['teacher_id'] = $this->auth->id();
        }

        if (! $this->classworks->insert($data)) {
            (new FileStore())->delete($fileName, 'classworks', $tenantId);

            return $this->failValidation($this->classworks);
        }

        return redirect()->to(route_to('classworks'))->with('success', 'Classwork added.');
    }

    public function delete(int $id)
    {
        $tenantId  = $this->tenantId();
        $classwork = $this->classworks->findOwned($id, $tenantId);

        if ($classwork === null) {
            return redirect()->to(route_to('classworks'))->with('error', 'That classwork could not be found.');
        }

        (new FileStore())->delete($classwork['classwork_file'], 'classworks', $tenantId);
        $this->classworks->delete($id);

        return redirect()->to(route_to('classworks'))->with('success', 'Classwork deleted.');
    }
}

<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;

class SubjectController extends BaseController
{
    private SubjectModel $subjects;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->subjects = new SubjectModel();
    }

    public function index()
    {
        return view('subjects/index', [
            'title'    => 'Subjects',
            'active'   => 'subjects',
            'subjects' => $this->subjects->withRelations($this->tenantId()),
        ]);
    }

    public function create()
    {
        return view('subjects/form', $this->formData(null, 'Add subject'));
    }

    public function store()
    {
        $data = $this->payload() + ['admin_id' => $this->tenantId()];

        if (! $this->subjects->insert($data)) {
            return $this->failValidation($this->subjects);
        }

        return redirect()->to(route_to('subjects'))->with('success', 'Subject added.');
    }

    public function edit(int $id)
    {
        $subject = $this->findOwnedOr404($this->subjects, $id);

        return view('subjects/form', $this->formData($subject, 'Edit subject'));
    }

    public function update(int $id)
    {
        $this->findOwnedOr404($this->subjects, $id);

        if (! $this->subjects->update($id, $this->payload())) {
            return $this->failValidation($this->subjects);
        }

        return redirect()->to(route_to('subjects'))->with('success', 'Subject updated.');
    }

    public function delete(int $id)
    {
        if (! $this->subjects->deleteOwned($id, $this->tenantId())) {
            return redirect()->to(route_to('subjects'))->with('error', 'That subject could not be found.');
        }

        return redirect()->to(route_to('subjects'))->with('success', 'Subject deleted.');
    }

    private function payload(): array
    {
        return [
            'subject_name' => $this->request->getPost('subject_name'),
            'teacher_id'   => $this->ownedIdOrNull(new TeacherModel(), $this->request->getPost('teacher_id')),
            'class_id'     => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
        ];
    }

    private function formData(?array $subject, string $title): array
    {
        $tenantId = $this->tenantId();

        return [
            'title'    => $title,
            'active'   => 'subjects',
            'subject'  => $subject,
            'teachers' => (new TeacherModel())->forTenant($tenantId)->orderBy('teacher_name', 'ASC')->findAll(),
            'classes'  => (new ClassModel())->forTenant($tenantId)->orderBy('class_name', 'ASC')->findAll(),
        ];
    }
}

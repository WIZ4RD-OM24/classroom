<?php

namespace App\Controllers;

use App\Models\ClassModel;
use App\Models\SubjectModel;
use App\Models\TeacherModel;

class TeacherController extends BaseController
{
    private TeacherModel $teachers;

    private const DEFAULT_PASSWORD = 'teacher@123';

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->teachers = new TeacherModel();
    }

    public function index()
    {
        $search = trim((string) $this->request->getGet('q'));

        return view('teachers/index', [
            'title'    => 'Teachers',
            'active'   => 'teachers',
            'teachers' => $this->teachers->withClass($this->tenantId(), $search),
            'search'   => $search,
        ]);
    }

    public function create()
    {
        return view('teachers/form', $this->formData(null, 'Add teacher'));
    }

    public function store()
    {
        $admin = (new \App\Models\AdminModel())->find($this->tenantId());

        $data = $this->payload() + [
            'admin_id'             => $this->tenantId(),
            'teacher_password'     => password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT),
            'teacher_organisation' => $admin['admin_organisation'] ?? null,
        ];

        if (! $this->teachers->insert($data)) {
            return $this->failValidation($this->teachers);
        }

        return redirect()->to(route_to('teachers'))
            ->with('success', 'Teacher added. Their temporary password is ' . self::DEFAULT_PASSWORD);
    }

    public function show(int $id)
    {
        $teacher = $this->findOwnedOr404($this->teachers, $id);

        $class = $teacher['class_id'] !== null
            ? (new ClassModel())->findOwned((int) $teacher['class_id'], $this->tenantId())
            : null;

        return view('teachers/show', [
            'title'    => $teacher['teacher_name'],
            'active'   => 'teachers',
            'teacher'  => $teacher,
            'class'    => $class,
            'subjects' => (new SubjectModel())
                ->forTenant($this->tenantId())
                ->where('teacher_id', $id)
                ->orderBy('subject_name', 'ASC')
                ->findAll(),
        ]);
    }

    public function edit(int $id)
    {
        $teacher = $this->findOwnedOr404($this->teachers, $id);

        return view('teachers/form', $this->formData($teacher, 'Edit teacher'));
    }

    public function update(int $id)
    {
        $this->findOwnedOr404($this->teachers, $id);

        if (! $this->teachers->update($id, $this->payload())) {
            return $this->failValidation($this->teachers);
        }

        return redirect()->to(route_to('teachers.show', $id))
            ->with('success', 'Teacher updated.');
    }

    /**
     * The old version echoed the delete result and never redirected, so the
     * browser was left on a blank page after every deletion.
     */
    public function delete(int $id)
    {
        if (! $this->teachers->deleteOwned($id, $this->tenantId())) {
            return redirect()->to(route_to('teachers'))->with('error', 'That teacher could not be found.');
        }

        return redirect()->to(route_to('teachers'))->with('success', 'Teacher deleted.');
    }

    private function payload(): array
    {
        return [
            'teacher_name'   => $this->request->getPost('teacher_name'),
            'teacher_email'  => $this->request->getPost('teacher_email'),
            'teacher_mobile' => $this->request->getPost('teacher_mobile'),
            'class_id'       => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
        ];
    }

    private function formData(?array $teacher, string $title): array
    {
        return [
            'title'   => $title,
            'active'  => 'teachers',
            'teacher' => $teacher,
            'classes' => (new ClassModel())->forTenant($this->tenantId())->orderBy('class_name', 'ASC')->findAll(),
        ];
    }
}

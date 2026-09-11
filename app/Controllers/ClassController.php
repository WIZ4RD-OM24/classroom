<?php

namespace App\Controllers;

use App\Models\ClassModel;

class ClassController extends BaseController
{
    private ClassModel $classes;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->classes = new ClassModel();
    }

    public function index()
    {
        return view('classes/index', [
            'title'   => 'Classes',
            'active'  => 'classes',
            'classes' => $this->classes->withStudentCounts($this->tenantId()),
        ]);
    }

    public function create()
    {
        return view('classes/form', [
            'title'  => 'Add class',
            'active' => 'classes',
            'class'  => null,
        ]);
    }

    public function store()
    {
        $data = [
            'class_name'   => $this->request->getPost('class_name'),
            'section_name' => $this->request->getPost('section_name'),
            'admin_id'     => $this->tenantId(),
        ];

        if (! $this->classes->insert($data)) {
            return $this->failValidation($this->classes);
        }

        return redirect()->to(route_to('classes'))
            ->with('success', 'Class added.');
    }

    public function edit(int $id)
    {
        return view('classes/form', [
            'title'  => 'Edit class',
            'active' => 'classes',
            'class'  => $this->findOwnedOr404($this->classes, $id),
        ]);
    }

    public function update(int $id)
    {
        $this->findOwnedOr404($this->classes, $id);

        $data = [
            'class_name'   => $this->request->getPost('class_name'),
            'section_name' => $this->request->getPost('section_name'),
        ];

        if (! $this->classes->update($id, $data)) {
            return $this->failValidation($this->classes);
        }

        return redirect()->to(route_to('classes'))
            ->with('success', 'Class updated.');
    }

    /**
     * Deletion is POST-only and ownership-checked. It used to be a GET link
     * that deleted any id, from any organisation, without a session.
     */
    public function delete(int $id)
    {
        if (! $this->classes->deleteOwned($id, $this->tenantId())) {
            return redirect()->to(route_to('classes'))
                ->with('error', 'That class could not be found.');
        }

        return redirect()->to(route_to('classes'))
            ->with('success', 'Class deleted.');
    }
}

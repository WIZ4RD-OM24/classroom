<?php

namespace App\Controllers;

use App\Libraries\FileStore;
use App\Models\AssignmentPostModel;
use App\Models\AssignmentUploadModel;
use App\Models\ClassModel;
use App\Models\SubjectModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class AssignmentController extends BaseController
{
    private AssignmentPostModel $assignments;

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->assignments = new AssignmentPostModel();
    }

    /**
     * Students see only their own class's assignments; staff see all of them.
     */
    public function index()
    {
        $tenantId = $this->tenantId();
        $isStudent = $this->auth->is(\App\Libraries\Auth::ROLE_STUDENT);

        return view('assignments/index', [
            'title'       => 'Assignments',
            'active'      => 'assignments',
            'assignments' => $this->assignments->withRelations(
                $tenantId,
                $this->auth->classId(),
                false,
                $isStudent
            ),
            'today' => date('Y-m-d'),
        ]);
    }

    public function show(int $id)
    {
        $assignment = $this->findOwnedOr404($this->assignments, $id);
        $uploads    = new AssignmentUploadModel();

        $isStudent = $this->auth->is(\App\Libraries\Auth::ROLE_STUDENT);

        if ($isStudent && ! $this->visibleToStudent($assignment)) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('assignments/show', [
            'title'       => $assignment['assignment_post_title'],
            'active'      => 'assignments',
            'assignment'  => $assignment,
            'class'       => $assignment['class_id'] ? (new ClassModel())->find($assignment['class_id']) : null,
            'subject'     => $assignment['subject_id'] ? (new SubjectModel())->find($assignment['subject_id']) : null,
            'submissions' => $isStudent ? [] : $uploads->forAssignment($id, $this->tenantId()),
            'mine'        => $isStudent ? $uploads->forStudent($id, (int) $this->auth->id()) : null,
            'today'       => date('Y-m-d'),
        ]);
    }

    public function create()
    {
        $tenantId = $this->tenantId();

        return view('assignments/form', [
            'title'    => 'Post assignment',
            'active'   => 'assignments',
            'classes'  => (new ClassModel())->forTenant($tenantId)->orderBy('class_name', 'ASC')->findAll(),
            'subjects' => (new SubjectModel())->forTenant($tenantId)->orderBy('subject_name', 'ASC')->findAll(),
        ]);
    }

    public function store()
    {
        $tenantId = $this->tenantId();
        $fileName = null;

        $upload = $this->request->getFile('file');

        if ($upload !== null && $upload->isValid()) {
            try {
                $fileName = (new FileStore())->store($upload, 'assignments', $tenantId);
            } catch (\RuntimeException $e) {
                return redirect()->back()->withInput()->with('error', $e->getMessage());
            }
        }

        $data = [
            'assignment_post_title'       => $this->request->getPost('assignment_post_title'),
            'assignment_post_description' => $this->request->getPost('assignment_post_description'),
            'assignment_post_file'        => $fileName,
            // Stored as a real date. The old code wrote `date('d-m-y h:i:s')`
            // into DATETIME columns and compared due dates as 'd-m-y' strings,
            // which sorts them wrongly.
            'assignment_post_due_date' => $this->request->getPost('assignment_post_due_date') ?: null,
            'class_id'                 => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
            'subject_id'               => $this->ownedIdOrNull(new SubjectModel(), $this->request->getPost('subject_id')),
            'admin_id'                 => $tenantId,
        ];

        if ($this->auth->is(\App\Libraries\Auth::ROLE_TEACHER)) {
            $data['teacher_id'] = $this->auth->id();
        }

        if (! $this->assignments->insert($data)) {
            (new FileStore())->delete($fileName, 'assignments', $tenantId);

            return $this->failValidation($this->assignments);
        }

        return redirect()->to(route_to('assignments'))->with('success', 'Assignment posted.');
    }

    /**
     * A student uploads their work for one assignment.
     */
    public function submit(int $id)
    {
        $assignment = $this->findOwnedOr404($this->assignments, $id);
        $tenantId   = $this->tenantId();

        if (! $this->visibleToStudent($assignment)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if ($assignment['assignment_post_due_date'] !== null
            && $assignment['assignment_post_due_date'] < date('Y-m-d')) {
            return redirect()->back()->with('error', 'The due date for this assignment has passed.');
        }

        $upload = $this->request->getFile('file');

        if ($upload === null || ! $upload->isValid()) {
            return redirect()->back()->with('error', 'Choose a file to submit.');
        }

        try {
            $fileName = (new FileStore())->store($upload, 'submissions', $tenantId);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $uploads  = new AssignmentUploadModel();
        $studentId = (int) $this->auth->id();
        $existing = $uploads->forStudent($id, $studentId);

        $payload = [
            'assignment_post_id'        => $id,
            'student_id'                => $studentId,
            'assignment_upload_file'    => $fileName,
            'assignment_upload_remarks' => $this->request->getPost('remarks'),
            'admin_id'                  => $tenantId,
        ];

        if ($existing !== null) {
            // Resubmitting replaces the previous file rather than orphaning it.
            (new FileStore())->delete($existing['assignment_upload_file'], 'submissions', $tenantId);
            $uploads->update($existing['assignment_upload_id'], $payload);
        } else {
            $uploads->insert($payload);
        }

        return redirect()->to(route_to('assignments.show', $id))
            ->with('success', 'Your work has been submitted.');
    }

    /**
     * Whether the signed-in student may see this assignment.
     *
     * Mirrors the list query in AssignmentPostModel::withRelations(): an
     * assignment with no class is addressed to the whole organisation;
     * otherwise it must be the student's own class.
     */
    private function visibleToStudent(array $assignment): bool
    {
        if ($assignment['class_id'] === null) {
            return true;
        }

        return $this->auth->classId() !== null
            && (int) $assignment['class_id'] === (int) $this->auth->classId();
    }

    public function delete(int $id)
    {
        $tenantId   = $this->tenantId();
        $assignment = $this->assignments->findOwned($id, $tenantId);

        if ($assignment === null) {
            return redirect()->to(route_to('assignments'))->with('error', 'That assignment could not be found.');
        }

        (new FileStore())->delete($assignment['assignment_post_file'], 'assignments', $tenantId);
        $this->assignments->delete($id);

        return redirect()->to(route_to('assignments'))->with('success', 'Assignment deleted.');
    }
}

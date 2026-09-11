<?php

namespace App\Controllers;

use App\Models\AssignmentUploadModel;
use App\Models\ClassModel;
use App\Models\StudentModel;

class StudentController extends BaseController
{
    private StudentModel $students;

    /**
     * Password issued to a newly created student. They are expected to change
     * it; it is at least no longer bypassed at login as it used to be.
     */
    private const DEFAULT_PASSWORD = 'student@123';

    public function initController($request, $response, $logger)
    {
        parent::initController($request, $response, $logger);
        $this->students = new StudentModel();
    }

    public function index()
    {
        $search = trim((string) $this->request->getGet('q'));

        return view('students/index', [
            'title'    => 'Students',
            'active'   => 'students',
            'students' => $this->students->withClass($this->tenantId(), $search),
            'search'   => $search,
        ]);
    }

    public function create()
    {
        return view('students/form', $this->formData(null, 'Add student'));
    }

    public function store()
    {
        $data = $this->payload() + [
            'admin_id'         => $this->tenantId(),
            'student_password' => password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT),
        ];

        if (! $this->students->insert($data)) {
            return $this->failValidation($this->students);
        }

        return redirect()->to(route_to('students'))
            ->with('success', 'Student added. Their temporary password is ' . self::DEFAULT_PASSWORD);
    }

    public function show(int $id)
    {
        $student = $this->findOwnedOr404($this->students, $id);

        $class = $student['class_id'] !== null
            ? (new ClassModel())->findOwned((int) $student['class_id'], $this->tenantId())
            : null;

        $submissions = (new AssignmentUploadModel())
            ->select('assignment_upload.*, assignment_post.assignment_post_title')
            ->join('assignment_post', 'assignment_post.assignment_post_id = assignment_upload.assignment_post_id')
            ->where('assignment_upload.student_id', $id)
            ->where('assignment_upload.admin_id', $this->tenantId())
            ->orderBy('assignment_upload.created_at', 'DESC')
            ->findAll();

        return view('students/show', [
            'title'       => $student['student_name'],
            'active'      => 'students',
            'student'     => $student,
            'class'       => $class,
            'submissions' => $submissions,
        ]);
    }

    public function edit(int $id)
    {
        $student = $this->findOwnedOr404($this->students, $id);

        return view('students/form', $this->formData($student, 'Edit student'));
    }

    /**
     * The previous version of this method referenced an undefined `$date` and
     * wrote to columns (`roll_no`, `first_name`, `last_name`) that do not exist
     * on the table, so it could never have worked.
     */
    public function update(int $id)
    {
        $this->findOwnedOr404($this->students, $id);

        if (! $this->students->update($id, $this->payload())) {
            return $this->failValidation($this->students);
        }

        return redirect()->to(route_to('students.show', $id))
            ->with('success', 'Student updated.');
    }

    public function delete(int $id)
    {
        if (! $this->students->deleteOwned($id, $this->tenantId())) {
            return redirect()->to(route_to('students'))->with('error', 'That student could not be found.');
        }

        return redirect()->to(route_to('students'))->with('success', 'Student deleted.');
    }

    public function importForm()
    {
        return view('students/import', [
            'title'  => 'Import students',
            'active' => 'students',
        ]);
    }

    /**
     * Bulk import from a CSV of: roll number, name, email.
     *
     * Rewritten from a version that echoed every parsed row to the browser,
     * wrote the file into the web root, checked for duplicates by roll number
     * across all organisations, and inserted rows with no password at all.
     */
    public function import()
    {
        if (! $this->validate(['file' => 'uploaded[file]|max_size[file,2048]|ext_in[file,csv]'])) {
            return redirect()->back()
                ->with('error', 'Upload a CSV file of 2 MB or less.')
                ->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file');

        if ($file === null || ! $file->isValid()) {
            return redirect()->back()->with('error', 'That file could not be read.');
        }

        $tenantId = $this->tenantId();
        $handle   = fopen($file->getTempName(), 'r');

        if ($handle === false) {
            return redirect()->back()->with('error', 'That file could not be read.');
        }

        $hash     = password_hash(self::DEFAULT_PASSWORD, PASSWORD_DEFAULT);
        $imported = 0;
        $skipped  = [];
        $row      = 0;

        while (($fields = fgetcsv($handle, 4096, ',')) !== false) {
            $row++;

            // Header row.
            if ($row === 1) {
                continue;
            }

            if (count($fields) < 3) {
                $skipped[] = "Row {$row}: expected 3 columns (roll number, name, email).";

                continue;
            }

            [$rollNo, $name, $email] = array_map(static fn ($v) => trim((string) $v), $fields);

            if ($rollNo === '' || $name === '' || $email === '') {
                $skipped[] = "Row {$row}: blank roll number, name or email.";

                continue;
            }

            // Duplicates are judged within this organisation, not globally, so
            // two colleges can both have a student numbered MCA001.
            $exists = $this->students
                ->where('admin_id', $tenantId)
                ->groupStart()
                ->where('student_roll_no', $rollNo)
                ->orWhere('student_email', $email)
                ->groupEnd()
                ->countAllResults();

            if ($exists > 0) {
                $skipped[] = "Row {$row}: {$rollNo} / {$email} already exists.";

                continue;
            }

            $inserted = $this->students->insert([
                'student_roll_no'  => $rollNo,
                'student_name'     => $name,
                'student_email'    => $email,
                'student_password' => $hash,
                'admin_id'         => $tenantId,
            ]);

            if ($inserted) {
                $imported++;
            } else {
                $skipped[] = "Row {$row}: " . implode(' ', $this->students->errors());
            }
        }

        fclose($handle);

        $redirect = redirect()->to(route_to('students'))
            ->with('success', $imported . ' ' . ($imported === 1 ? 'student' : 'students') . ' imported.');

        if ($skipped !== []) {
            $redirect->with('warning', 'Skipped ' . count($skipped) . ' row(s).')
                ->with('errors', array_slice($skipped, 0, 15));
        }

        return $redirect;
    }

    private function payload(): array
    {
        return [
            'student_roll_no' => $this->request->getPost('student_roll_no'),
            'student_name'    => $this->request->getPost('student_name'),
            'student_email'   => $this->request->getPost('student_email'),
            'student_mobile'  => $this->request->getPost('student_mobile'),
            'class_id'        => $this->ownedIdOrNull(new ClassModel(), $this->request->getPost('class_id')),
        ];
    }

    private function formData(?array $student, string $title): array
    {
        return [
            'title'   => $title,
            'active'  => 'students',
            'student' => $student,
            'classes' => (new ClassModel())->forTenant($this->tenantId())->orderBy('class_name', 'ASC')->findAll(),
        ];
    }
}

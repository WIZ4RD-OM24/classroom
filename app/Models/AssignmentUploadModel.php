<?php

namespace App\Models;

class AssignmentUploadModel extends BaseModel
{
    protected $table      = 'assignment_upload';
    protected $primaryKey = 'assignment_upload_id';

    protected $allowedFields = [
        'assignment_post_id',
        'student_id',
        'assignment_upload_file',
        'assignment_upload_remarks',
        'assignment_upload_grades',
        'assignment_upload_received_grades',
        'admin_id',
    ];

    protected $validationRules = [
        'assignment_post_id' => 'required|is_natural_no_zero',
        'student_id'         => 'required|is_natural_no_zero',
    ];

    /**
     * Every submission for one assignment, joined to the submitting student.
     */
    public function forAssignment(int $assignmentPostId, int $adminId): array
    {
        return $this->select('assignment_upload.*, student.student_name, student.student_roll_no')
            ->join('student', 'student.student_id = assignment_upload.student_id')
            ->where('assignment_upload.assignment_post_id', $assignmentPostId)
            ->where('assignment_upload.admin_id', $adminId)
            ->orderBy('student.student_roll_no', 'ASC')
            ->findAll();
    }

    /**
     * One student's submission for one assignment, if any.
     *
     * @return array|null
     */
    public function forStudent(int $assignmentPostId, int $studentId)
    {
        return $this->where('assignment_post_id', $assignmentPostId)
            ->where('student_id', $studentId)
            ->first();
    }
}

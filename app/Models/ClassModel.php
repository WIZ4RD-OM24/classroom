<?php

namespace App\Models;

class ClassModel extends BaseModel
{
    protected $table      = 'class';
    protected $primaryKey = 'class_id';

    protected $allowedFields = [
        'class_name',
        'section_name',
        'admin_id',
    ];

    protected $validationRules = [
        'class_name'   => 'required|min_length[2]|max_length[100]',
        'section_name' => 'permit_empty|max_length[50]',
    ];

    /**
     * Classes for one organisation, with the number of students in each.
     */
    public function withStudentCounts(int $adminId): array
    {
        return $this->select('class.*, COUNT(student.student_id) AS student_count')
            ->join('student', 'student.class_id = class.class_id', 'left')
            ->where('class.admin_id', $adminId)
            ->groupBy('class.class_id')
            ->orderBy('class.class_name', 'ASC')
            ->findAll();
    }
}

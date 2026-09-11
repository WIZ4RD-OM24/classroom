<?php

namespace App\Models;

class SubjectModel extends BaseModel
{
    protected $table      = 'subject';
    protected $primaryKey = 'subject_id';

    protected $allowedFields = [
        'subject_name',
        'class_id',
        'teacher_id',
        'admin_id',
    ];

    protected $validationRules = [
        'subject_name' => 'required|min_length[2]|max_length[100]',
        'teacher_id'   => 'permit_empty|is_natural_no_zero',
        'class_id'     => 'permit_empty|is_natural_no_zero',
    ];

    /**
     * Subjects for one organisation joined to their teacher and class names.
     */
    public function withRelations(int $adminId): array
    {
        return $this->select('subject.*, teacher.teacher_name, class.class_name, class.section_name')
            ->join('teacher', 'teacher.teacher_id = subject.teacher_id', 'left')
            ->join('class', 'class.class_id = subject.class_id', 'left')
            ->where('subject.admin_id', $adminId)
            ->orderBy('subject.subject_name', 'ASC')
            ->findAll();
    }
}

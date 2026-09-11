<?php

namespace App\Models;

class ClassworkModel extends BaseModel
{
    protected $table      = 'classwork';
    protected $primaryKey = 'classwork_id';

    protected $allowedFields = [
        'classwork_title',
        'classwork_file',
        'class_id',
        'subject_id',
        'teacher_id',
        'admin_id',
    ];

    protected $validationRules = [
        'classwork_title' => 'required|min_length[3]|max_length[200]',
        'class_id'        => 'permit_empty|is_natural_no_zero',
        'subject_id'      => 'permit_empty|is_natural_no_zero',
    ];

    /**
     * Classwork for one organisation joined to class and subject names.
     */
    public function withRelations(int $adminId, ?int $classId = null, bool $restrictToClass = false): array
    {
        $builder = $this->select('classwork.*, class.class_name, class.section_name, subject.subject_name')
            ->join('class', 'class.class_id = classwork.class_id', 'left')
            ->join('subject', 'subject.subject_id = classwork.subject_id', 'left')
            ->where('classwork.admin_id', $adminId);

        if ($restrictToClass) {
            $builder = $this->scopeToClass($builder, $classId);
        }

        return $builder->orderBy('classwork.created_at', 'DESC')->findAll();
    }
}
